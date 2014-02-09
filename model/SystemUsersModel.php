<?php
require_once 'table/SystemUsersTable.php';

/**
 * The SystemUsersModel manages all data for the users in the system.
 * 
 * @author trang
 *
 */
class SystemUsersModel 	extends BaseModel
						implements IModel
{
	/**
	 * Constructor. Fill the name of the table here
	 */
	public function __construct() {
		parent::__construct("system_users");
	}
	
	/**
	 * Add new user to the system
	 * @param SystemUserTable $obj
	 */
	function AddObject(SystemUsersTable $obj) {
	
		if(! is_a($obj, "SystemUsersTable")) {
			return -1;
		}
	
		$objCp = new SystemUsersTable();
		$this->CopyObjects($obj, $objCp);
	
		$now = date('Y-m-d H:i:s');
		$objCp->date_create = $now;
		$objCp->date_edit = $now;
		$objCp->create_by = $_SESSION["USER_ID"];
		$objCp->modified_by = $_SESSION["USER_ID"];
		
		return $this->Insert($objCp);
	}	
	
	/**
	 * Copy objects. Password and E-mails will be encrypted.
	 * 
	 * @param object $sourceObj
	 * @param object $targetObj
	 */
	public function CopyObjects($sourceObj, &$targetObj) {

		foreach($sourceObj as $prop => $val) {
				
			switch($prop) {
				default:
					$targetObj->set($prop, $val);
					break;
				case "password":
				case "email":
					if(strlen($val) > 0) {
						$targetObj->set($prop, Encryption::Encrypt($val));
					}
					break;
			}
		}
	}
	
	/**
	 * Delete an user
	 *
	 * @param int $userId
	 * @return Object
	 */
	public function DeleteById($userId) {
	
		if(! is_numeric($userId)) {
			return - 1;
		}
	
		$userId = (int) $userId;
	
		if($userId != $_SESSION["USER_ID"]) {
				
			$objUsr = $this->GetObject($userId);
			$this->WriteLog("SystemUsersModel.DeleteUserById", $objUsr);
				
			$where = array("user_id" => $userId, "system_user" => 0);
			$this->Delete($where);
		}
		return $this;
	}
	
	
	/**
	 * Fetch the total amount of records in a table
	 * @param string $tableName
	 * @return	Object 	Example: $object->total
	 */
	public function FetchTotalRowsSearch($qSearch) {
	
		$encTxt = Encryption::Encrypt($qSearch);
	
		$query = "SELECT Count(*) AS total FROM " . $this->mTableName .
		" WHERE username LIKE '%$qSearch%' OR date_create LIKE '%$qSearch%' OR email = '$encTxt' ;";
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
					t2.lastname AS name_creator, t3.lastname AS name_modifier,
					t4.name AS group_name, t5.description AS language 
					FROM $table AS t1 
					LEFT JOIN $table AS t2 ON t1.create_by = t2.user_id 
					LEFT JOIN $table AS t3 ON t1.modified_by = t3.user_id 
					LEFT JOIN groups AS t4 ON t1.group_id = t4.group_id 
					LEFT JOIN languages AS t5 ON t1.lang_id = t5.lang_id 
					WHERE t1.user_id = '$id' 
					LIMIT 1 
					";
		
		$objects = $this->SelectWithQuery($query);
		
		if(sizeof($objects) > 0) {
			return $objects[0];
		}
		return new SystemUsersTable();
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
		" WHERE username LIKE '%$qSearch%' OR date_create LIKE '%$qSearch%' OR email = '$encTxt' ;";
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
				Array("user_id", "username", "email",
						"firstname", "middlename", "lastname", "date_create", "date_edit"),
				Array(),
				"user_id",
				"ASC",
				$limit,
				$index
		);
		return $objects;
	}
	
	/**
	 * Retrieve the user the username and password
	 * 
	 * @param string $uName
	 * @param string $uPass
	 * @return Object
	 */
	public function GetUser($uName, $uPass) {
	
		$query = sprintf("SELECT t1.user_id, t1.group_id, t1.username, t1.password, 
				t1.lang_id, t1.firstname, t1.middlename, t1.lastname, t2.security_level  
				FROM %s AS t1 
				LEFT JOIN groups AS t2 ON t1.group_id = t2.group_id 
				WHERE username = '%s'
				",
				$this->mTableName,
				$uName
				);
		$objs = $this->SelectWithQuery($query);
		
		if(sizeof($objs) > 0) {
			$usrObj = $objs[0];
			
			// Decrypt password
			$dPass = Encryption::Decrypt($usrObj->password);
			$usrObj->password = $dPass;
			return $usrObj;
		}
		else {
			return new SystemUsersTable();
		}
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
		" WHERE username LIKE '%$qSearch%' 
		OR date_create LIKE '%$qSearch%' 
		OR firstname LIKE '%$qSearch%' 
		OR lastname LIKE '%$qSearch%' 
		OR email = '$encTxt'
		ORDER BY user_id ASC LIMIT $index, $limit;";
	
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
		$objTbl = new SystemUsersTable();
		return $this->IsValueUniqueWithTableObj($objTbl, $column, $value);
	}

	/**
	 * Check if a value is unique in the table for the session user
	 * 
	 * @param string $value
	 * @param string $column
	 * @return boolean
	 */
	protected function IsValueUniqueForSessionUser($value, $column, $recordId) {
		
		$query = "SELECT Count(*) AS total FROM " . $this->mTableName 
				. " WHERE user_id <> '$recordId' AND $column = '$value';";
		
		$objs = $this->SelectWithQuery($query);
		
		if(sizeof($objs) > 0) {
			if($objs[0]->total > 0) {		
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Check if a value is unique in the table for the session user
	 *
	 * @param string $column
	 * @param string $value
	 * @return boolean
	 */
	public function IsUserUniqueForSession ($value, $recordId) {
	
		return $this->IsValueUniqueForSessionUser($value, "username", $recordId);

	}

	/**
	 * Check if a e-mail is unique in the table for the session user
	 *
	 * @param string $column
	 * @param string $value
	 * @return boolean
	 */
	public function IsEmailUniqueForSessionUser ($value, $recordId) {

		return $this->IsValueUniqueForSessionUser($value, "email", $recordId);
	}
	
	
	/**
	 * Update an existing System user
	 *
	 * @param SystemUserTable $obj
	 * @param array $where
	 * @return number	-1 On error
	 */
	public function UpdateUser(SystemUsersTable $obj, $where) {
	
		if(! is_a($obj, "SystemUsersTable")) {
			return -1;
		}
	
		$objCp = new SystemUsersTable();
	
		$this->CopyObjects($obj, $objCp);
	
		$now = date('Y-m-d H:i:s');
		$objCp->date_edit = $now;
		$objCp->modified_by = $_SESSION["USER_ID"];
		
		return $this->Update($objCp, $where);
	}
		

		
}