<?php
require_once 'table/AccessTable.php';
require_once 'AccessControllersModel.php';
require_once './libs/WawCore/Dir.php';

/**
 * The AccessModel manages user permissions.
 * 
 * @author trang
 *
 */
class AccessModel 	extends BaseModel
					implements IModel
{
	
	/**
	 * Constructor. Fill the name of the table here
	 */
	public function __construct() {
		parent::__construct("access");
	}
	
	/**
	 * Add new user to the system
	 * @param AccessTable $obj
	 */
	public function AddObject(AccessTable $obj) {
	
		if(! is_a($obj, "AccessTable")) {
			return -1;
		}
	
		$objCp = new AccessTable();
		$this->CopyObjects($obj, $objCp);
		
		$now = date('Y-m-d H:i:s');
		$objCp->date_create = $now;
		$objCp->date_edit = $now;
		$objCp->create_by = $_SESSION["USER_ID"];
		$objCp->modified_by = $_SESSION["USER_ID"];
				
		return $this->Insert($objCp);
	}	
	
	/**
	 * Delete an user
	 *
	 * @param int $userId
	 * @return Object
	 */
	public function DeleteById($id) {
	
		if(! is_numeric($id)) {
			return - 1;
		}
	
		$id = (int) $id;
	
		$obj = $this->GetObject($id);
		$this->WriteLog("AccessModel.DeleteById", $obj);
		
		$where = array("id" => $id, );
		$this->Delete($where);
		return $this;
	}
	
	
	/**
	 * Get the User object
	 * @param int $id
	 * @return Object
	 */
	public function GetObject($id) {
	
		$id = (int) $id;
		$table = $this->mTableName;
		$query = 	"SELECT t1.*,
		t2.lastname AS name_creator, t3.lastname AS name_modifier,
		t4.alias AS module, t5.name AS group_name
		FROM $table AS t1
		LEFT JOIN system_users AS t2 ON t1.create_by = t2.user_id
		LEFT JOIN system_users AS t3 ON t1.modified_by = t3.user_id
		LEFT JOIN access_controllers AS t4 ON t1.controller_id = t4.id 
		LEFT JOIN groups AS t5 ON t5.group_id = t1.group_id 
		
		WHERE t1.id = '$id'
		LIMIT 1
		";
		
		$objects = $this->SelectWithQuery($query);
		
		if(sizeof($objects) > 0) {
		return $objects[0];
		}
		return new SystemUsersTable();
	}	
	
	protected function GetQuerySearchTotal($qSearch) {
		
		$query = sprintf("SELECT Count(t1.id) AS total FROM %s AS t1
							LEFT JOIN access_controllers AS t2 ON t1.controller_id = t2.id
							LEFT JOIN groups AS t3 ON t3.group_id = t1.group_id
							WHERE t2.controller LIKE '%s' OR t3.name LIKE '%s'; ",
				$this->mTableName,
				"%$qSearch%",
				"%$qSearch%"
		);
		return $query;
	}
	
	/**
	 * Fetch the total amount of records in a table
	 * @param string $tableName
	 * @return	Object 	Example: $object->total
	 */
	public function FetchTotalRowsSearch($qSearch) {
	
		$query = $this->GetQuerySearchTotal($qSearch);
		$objects = $this->SelectWithQuery($query);
		return $objects[0]->total;
	}

	/**
	 * Just simple calc how many pages you need for the list
	 * @param int $totalRows
	 * @param number $maxPerPage
	 * @return number
	 */
	public function GetTotalPagesSearch($qSearch, &$totalFound, $maxPerPage = 10) {
	
		$query = $this->GetQuerySearchTotal($qSearch);
		$objects = $this->SelectWithQuery($query);
		$totalRows = $objects[0]->total;
		$totalFound = $totalRows;
		$total = floor($totalRows / $maxPerPage) + ($totalRows % $maxPerPage < $maxPerPage ? 1 : 0);
		return $total;
	}
		
	/**
	 * Get the list
	 *
	 * @param number $index
	 * @param number $limit
	 * @return array
	 */
	public function GetList($index = 0, $limit = 10) {
	
		$index = (int) $index;
		$limit = (int) $limit;
	
		$query = sprintf("SELECT t1.id, t2.alias AS module, t3.name AS group_name, t1.read, t1.write, t1.remove 
						FROM %s AS t1 
						LEFT JOIN access_controllers AS t2 ON t1.controller_id = t2.id 
						LEFT JOIN groups AS t3 ON t3.group_id = t1.group_id 
						ORDER BY t2.alias ASC 
						LIMIT %s, %s
						", 
						$this->mTableName,
						$index,
						$limit
						);
		$objects = $this->SelectWithQuery($query);
		return $objects;
	}

	/**
	 * Get the search list
	 * 
	 * @param string $qSearch
	 * @param int $index
	 * @param int $limit
	 * @return NULL
	 */
	public function GetListSearch($qSearch, $index = 0, $limit = 10) {

		$query = sprintf("SELECT t1.id, t2.alias AS module, t3.name AS group_name, t1.read, t1.write, t1.remove 
							FROM %s AS t1
							LEFT JOIN access_controllers AS t2 ON t1.controller_id = t2.id
							LEFT JOIN groups AS t3 ON t3.group_id = t1.group_id
							WHERE t2.controller LIKE '%s' OR t3.name LIKE '%s'; ",
				$this->mTableName,
				"%$qSearch%",
				"%$qSearch%"
		);
		$objects = $this->SelectWithQuery($query);
		return $objects;
	}
	
	/**
	 * Get the permissions from a specific module
	 * 
	 * @param Object $className
	 * @return Array
	 */
	public function GetPermissions($className) {
		
		$query = sprintf("SELECT t1.id, t1.group_id, t1.write, t1.read, 
							t1.remove FROM %s AS t1 
							LEFT JOIN access_controllers AS t2 ON t1.controller_id = t2.id 
							LEFT JOIN groups AS t3 ON t3.group_id = t1.group_id 
							WHERE t2.controller = '%s'; ",
							$this->mTableName, 
							$className
				);
		$objects = $this->SelectWithQuery($query);
		
		return $objects;
	}
	
	/**
	 * Get the permission with a group id
	 * @param int $groupId
	 * @return Array
	 */
	public function GetPermissionsByGroup($groupId) {

		$groupId = (int) $groupId;
		$query = sprintf("SELECT t1.id, t1.group_id, t1.write, t1.read,
							t1.remove, Lower(t2.controller) AS controller  
							FROM %s AS t1
							LEFT JOIN access_controllers AS t2 ON t1.controller_id = t2.id
							LEFT JOIN groups AS t3 ON t3.group_id = t1.group_id
							WHERE t1.group_id = '%s'; ",
				$this->mTableName,
				$groupId
		);
		$objects = $this->SelectWithQuery($query);
		return $objects;
	}
	
	/**
	 * This function scans controller files.
	 * And add them to the table access_controllers.
	 * You could put this function in a cron job.
	 * 
	 */
	public function ScanForControllers() {
		
		$modACtrl = new AccessControllersModel();
		
		$fileNames = Dir::GetFileNames("./controller/");
		
		foreach($fileNames as $fileName) {

			$canRead = true;
			
			switch($fileName) {
				case "FrontIndexController.php":
				case "BaseController.php":
				case "LoginController.php":
				case "ViewController.php":
					$canRead = false;
					break;
			}
			
			if($canRead) {

				require_once './controller/' . $fileName;
					
				$className = str_replace(".php", "", $fileName);
				
				if(class_exists($className)) {
					
					$objInst = new $className();
					if($objInst instanceof IController) {
						
						if(!$modACtrl->RowExists(Array("controller" => $className))) {
							
							$nwObj = new AccessControllersTable();
							$nwObj->controller = $className;
							$nwObj->alias = str_replace("controller", "", strtolower($className));
							$nwObj->alias = "{" . strtoupper($nwObj->alias) . "}";
							
							$modACtrl->AddObject($nwObj);
						}
					}
				}
			}	
		}
		return $this;
	}
	
	/**
	 * Update an existing record
	 *
	 * @param AccessTable $obj
	 * @param array $where
	 * @return number	-1 On error
	 */
	public function UpdateObject(AccessTable $obj, $where) {
	
		if(! is_a($obj, "AccessTable")) {
			return -1;
		}
	
		$objCp = new AccessTable();
		
		$this->CopyObjects($obj, $objCp);
		
		$now = date('Y-m-d H:i:s');
		$objCp->date_edit = $now;
		$objCp->modified_by = $_SESSION["USER_ID"];
		
		return $this->Update($objCp, $where);
	}
		

		
}