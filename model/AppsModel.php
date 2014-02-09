<?php
require_once 'table/AppsTable.php';
require_once 'GcmUsersModel.php';

/**
 * The AppsModel manages the mobile apps created by the developer.
 * 
 * @author trang
 *
 */
class AppsModel 	extends BaseModel
					implements IModel
{
	
	/**
	 * Constructor. Fill the name of the table here
	 */
	public function __construct() {
		parent::__construct("mobile_apps");
	}
	
	/**
	 * Add new user to the system
	 * @param AppsTable $obj
	 */
	public function AddObject(AppsTable $obj) {
	
		if(! is_a($obj, "AppsTable")) {
			return -1;
		}
	
		$objCp = new AppsTable();
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
		
		// Check if there any devices which are using this App ID
		$appId = $obj->app_id;
		
		$modelCgm = new GcmUsersModel();
		if($modelCgm->RowExists(Array("app_id" => $appId))) {
			return -1;
		}

		$this->WriteLog("AppsModel.DeleteById", $obj);
		
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
		$query = 	"SELECT t1.*
		, t2.lastname AS name_creator, t3.lastname AS name_modifier
		FROM $table AS t1
		LEFT JOIN system_users AS t2 ON t1.create_by = t2.user_id
		LEFT JOIN system_users AS t3 ON t1.modified_by = t3.user_id
		WHERE t1.id = '$id'
		LIMIT 1
		";
		$objects = $this->SelectWithQuery($query);
		
		if(sizeof($objects) > 0) {
		return $objects[0];
		}
		return new AppsTable();
	}	
	
	/**
	 * Get the User object
	 * @param int $id
	 * @return Object
	 */
	public function GetObjectWithAppId($appId) {
	
		$table = $this->mTableName;
		$query = 	"SELECT t1.*
		, t2.lastname AS name_creator, t3.lastname AS name_modifier
		FROM $table AS t1
		LEFT JOIN system_users AS t2 ON t1.create_by = t2.user_id
		LEFT JOIN system_users AS t3 ON t1.modified_by = t3.user_id
		WHERE t1.app_id = '$appId'
		LIMIT 1
		";
		$objects = $this->SelectWithQuery($query);
	
		if(sizeof($objects) > 0) {
		return $objects[0];
		}
		return new AppsTable();
	}	
	
	protected function GetQuerySearchTotal($qSearch) {
		
		$query = sprintf("SELECT Count(t1.id) AS total FROM %s AS t1
							WHERE t1.description LIKE '%s' ; ",
				$this->mTableName,
				"%$qSearch%",
				"%$qSearch%"
		);
		return $query;
	}
	
	public function GetTotalPushedMessages($appId) {
		
		$query = "SELECT Count(msg_id) AS total FROM push_messages WHERE app_id = '$appId'; ";
		$objects = $this->SelectWithQuery($query);
		return $objects[0]->total;
	}
	
	public function GetDeviceSubscriptions($appId) {

		$query = "SELECT Count(gcm_id) AS total FROM gcm_users WHERE app_id = '$appId'; ";
		$objects = $this->SelectWithQuery($query);
		return $objects[0]->total;
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
	 * Get list all
	 * @return array
	 */
	public function GetListAll() {
	
		$objects = $this->FetchObjects(
				Array("id", "description"),
				Array(),
				"description",
				"ASC",
				"",
				""
		);
		return $objects;
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
	
		$query = sprintf("SELECT t1.*
						FROM %s AS t1 
						ORDER BY t1.description ASC 
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

		$query = sprintf("SELECT t1.*
							FROM %s AS t1
							WHERE t1.description LIKE '%s' ORDER BY t1.description ASC ; ",
				$this->mTableName,
				"%$qSearch%"
		);
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
		$objTbl = new AppsTable();
		return $this->IsValueUniqueWithTableObj($objTbl, $column, $value, $recordCol, $recordId);
	}
	
	
	/**
	 * Update an existing record
	 *
	 * @param AppsTable $obj
	 * @param array $where
	 * @return number	-1 On error
	 */
	public function UpdateObject(AppsTable $obj, $where) {
	
		if(! is_a($obj, "AppsTable")) {
			return -1;
		}
	
		$objCp = new AppsTable();
		
		$this->CopyObjects($obj, $objCp);
		
		$now = date('Y-m-d H:i:s');
		$objCp->date_edit = $now;
		$objCp->modified_by = $_SESSION["USER_ID"];
		
		return $this->Update($objCp, $where);
	}
		

		
}