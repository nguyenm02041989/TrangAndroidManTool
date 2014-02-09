<?php
require_once 'table/GroupsTable.php';
require_once 'AccessControllersModel.php';
require_once 'AccessModel.php';

/**
 * The SystemUsersModel manages all data for the users in the system.
 * 
 * @author trang
 *
 */
class GroupsModel 	extends BaseModel
					implements IModel
{
	
	/**
	 * Constructor. Fill the name of the table here
	 */
	public function __construct() {
		parent::__construct("groups");
	}
	
	/**
	 * Add new user to the system
	 * @param GroupsTable $obj
	 */
	function AddObject(GroupsTable $obj) {
	
		if(! is_a($obj, "GroupsTable")) {
			return -1;
		}
	
		$objCp = new GroupsTable();
		$this->CopyObjects($obj, $objCp);
		
		$now = date('Y-m-d H:i:s');
		$objCp->date_create = $now;
		$objCp->date_edit = $now;
		$objCp->create_by = $_SESSION["USER_ID"];
		$objCp->modified_by = $_SESSION["USER_ID"];
		$lastInsId = $this->Insert($objCp);
		
		$modA = new AccessModel();
		$modACtrl = new AccessControllersModel();
		$objsACtrl = $modACtrl->GetListAll();
		
		$rwxPrm = 0;
		if($obj->security_level == 0) {
			$rwxPrm = 1;
		}
		
		foreach($objsACtrl as $objAct) {
			
			$objAT = new AccessTable();
			$objAT->controller_id = $objAct->id;
			$objAT->group_id = $lastInsId;
			$objAT->write = $rwxPrm;
			$objAT->remove = $rwxPrm;
			$objAT->read = $rwxPrm;
			$modA->AddObject($objAT);
		}
		
		return $lastInsId;
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
	
		// Check if we can delete this group
		require_once 'SystemUsersModel.php';
		$modUsr = new SystemUsersModel();
		
		if(! $modUsr->RowExists(Array("group_id" => $id))) {

			$obj = $this->GetObject($id);
			$this->WriteLog("GroupsModel.DeleteUserById", $obj);
				
			$where = array("group_id" => $id, );
			$this->Delete($where);
			return $this;
		}
		else {
			
			FrontIndexController::RedirectTo("/groups/error");
			exit();
		}
	}
	
	
	/**
	 * Fetch the total amount of records in a table
	 * @param string $tableName
	 * @return	Object 	Example: $object->total
	 */
	public function FetchTotalRowsSearch($qSearch) {
	
		$encTxt = Encryption::Encrypt($qSearch);
	
		$query = "SELECT Count(*) AS total FROM " . $this->mTableName .
		" WHERE name LIKE '%$qSearch%' OR date_create LIKE '%$qSearch%' ;";
		$objects = $this->SelectWithQuery($query);
		return $objects[0]->total;
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
		t2.lastname AS name_creator, t3.lastname AS name_modifier
		FROM $table AS t1
		LEFT JOIN system_users AS t2 ON t1.create_by = t2.user_id
		LEFT JOIN system_users AS t3 ON t1.modified_by = t3.user_id
		WHERE t1.group_id = '$id'
		LIMIT 1
		";
		
		$objects = $this->SelectWithQuery($query);
		
		if(sizeof($objects) > 0) {
		return $objects[0];
		}
		return new GroupsTable();
	}	
	
	/**
	* Just simple calc how many pages you need for the list
	 * @param int $totalRows
	 * @param number $maxPerPage
	 * @return number
	 */
	 public function GetTotalPagesSearch($qSearch, &$totalFound, $maxPerPage = 10) {

		$encTxt = Encryption::Encrypt($qSearch);

		$query = "SELECT Count(*) AS total FROM " . $this->mTableName .
		" WHERE name LIKE '%$qSearch%' OR date_create LIKE '%$qSearch%' ;";
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
	
		$objects = $this->FetchObjects(
				Array("group_id", "name", "security_level",	"create_by","modified_by", "date_create", "date_edit"),
				Array(),
				"name",
				"ASC",
				$limit,
				$index
		);
		return $objects;
	}
	
	/**
	 * Get list all
	 * @return array
	 */
	public function GetListAll() {

		$objects = $this->FetchObjects(
				Array("group_id", "name", "security_level",	"create_by","modified_by", "date_create", "date_edit"),
				Array(),
				"name",
				"ASC",
				"",
				""
		);
		return $objects;
	}

	/**
	 * Get the list with a search keyword
	 *
	 * @param string $qSearch
	 * @param number $index
	 * @param number $limit
	 * @return array
	 */
	public function GetListSearch($qSearch, $index = 0, $limit = 10) {
	
		$index = (int) $index;
		$limit = (int) $limit;
	
		$encTxt = Encryption::Encrypt($qSearch);
	
		$query = "SELECT * FROM " . $this->mTableName .
		" WHERE name LIKE '%$qSearch%' OR date_create LIKE '%$qSearch%' 
		ORDER BY group_id ASC LIMIT $index, $limit;";
	
		$objects = $this->SelectWithQuery($query);
		return $objects;
	}

	
	/**
	 * Check if a value is unique in the table
	 * 
	 * @param string $column
	 * @param string $value
	 * @return boolean
	 */
	public function IsValueUnique ($column, $value, $recordCol = "", $recordId = "") {
		
		// Check if the column is in the table.
		$objTbl = new GroupsTable();
		return $this->IsValueUniqueWithTableObj($objTbl, $column, $value, $recordCol, $recordId);
	}

	
	/**
	 * Update an existing record
	 *
	 * @param GroupsTable $obj
	 * @param array $where
	 * @return number	-1 On error
	 */
	public function UpdateObject(GroupsTable $obj, $where) {
	
		if(! is_a($obj, "GroupsTable")) {
			return -1;
		}
	
		$objCp = new GroupsTable();
		
		$this->CopyObjects($obj, $objCp);
		
		$now = date('Y-m-d H:i:s');
		$objCp->date_edit = $now;
		$objCp->modified_by = $_SESSION["USER_ID"];
		
		return $this->Update($objCp, $where);
	}
		

		
}