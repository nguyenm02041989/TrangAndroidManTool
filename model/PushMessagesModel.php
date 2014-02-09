<?php
require_once 'table/PushMessagesTable.php';

class PushMessagesModel extends BaseModel 
						implements IModel
{

	public function __construct() {
		parent::__construct("push_messages");
	}
	
	
	public function AddLog($message) {
	
		$objMsg = new PushMessagesTable();
		$objMsg->message = $message;
		$objMsg->create_by = $_SESSION["USER_ID"];
	
		return $this->Insert($objMsg);
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
	
		$query = "SELECT t1.*, t2.description AS app_name 
				FROM " . $this->mTableName . " AS t1 
				LEFT JOIN mobile_apps AS t2 ON t1.app_id = t2.id 
				ORDER BY t1.msg_id DESC 
				LIMIT $index, $limit 
				";
		$objects = $this->SelectWithQuery($query);
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
	
		$query = "SELECT t1.*, t2.description AS app_name  
		FROM push_messages AS t1 
		LEFT JOIN mobile_apps AS t2 ON t1.app_id = t2.id 
		WHERE t1.message LIKE '%$qSearch%' 
		OR t1.date_create LIKE '%$qSearch%' 
		OR t2.description LIKE '%$qSearch%'
		ORDER BY msg_id DESC LIMIT $index, $limit;";
		
		$objects = $this->SelectWithQuery($query);
		return $objects;
	}
	

	/**
	 * Fetch the total amount of records in a table
	 * @param string $tableName
	 * @return	Object 	Example: $object->total
	 */
	public function FetchTotalRowsSearch($qSearch) {

		$query = "SELECT Count(*) AS total FROM push_messages AS t1
		LEFT JOIN mobile_apps AS t2 ON t1.app_id = t2.id 
		WHERE t1.message LIKE '%$qSearch%' 
		OR t1.date_create LIKE '%$qSearch%'
		OR t2.description LIKE '%$qSearch%'
		;";
		$objects = $this->SelectWithQuery($query);
		return $objects[0]->total;
	}
		
	/**
	 * Just simple calc how many pages you need for the list
	 * @param int $totalRows
	 * @param number $maxPerPage
	 * @return number
	 */
	public function GetTotalPagesSearch($qSearch, $maxPerPage = 10) {

		$query = "SELECT Count(*) AS total FROM push_messages AS t1
		LEFT JOIN mobile_apps AS t2 ON t1.app_id = t2.id
		WHERE t1.message LIKE '%$qSearch%'
		OR t1.date_create LIKE '%$qSearch%'
		OR t2.description LIKE '%$qSearch%'
		;";		
		$objects = $this->SelectWithQuery($query);
		$totalRows = $objects[0]->total;		
		$total = floor($totalRows / $maxPerPage) + ($totalRows % $maxPerPage < $maxPerPage ? 1 : 0);
		return $total;
	}	
	
	/**
	 * Get the Object
	 * @param int $id
	 * @return Object
	 */
	public function GetObject($id) {
	
		$object = $this->FetchObject(
				Array("msg_id", "message", "sento_google", "successfull", "failed",
						"date_create", "date_edit", "create_by"),
				Array("msg_id" => $id)
		);
		return $object;
	}
	
	/**
	 * Delete the record
	 * @param int $id
	 */
	public function DeleteById($id) {
		
		$this->Delete(Array("msg_id" => $id));
	}
	

		
	/**
	 * Update an existing record
	 *
	 * @param AccessTable $obj
	 * @param array $where
	 * @return number	-1 On error
	 */
	public function UpdateObject(PushMessagesTable $obj, $where) {
	
		if(! is_a($obj, "PushMessagesTable")) {
			return -1;
		}
	
		$objCp = new PushMessagesTable();
	
		$this->CopyObjects($obj, $objCp);
	
		$now = date('Y-m-d H:i:s');
		$objCp->date_edit = $now;
		$objCp->modified_by = $_SESSION["USER_ID"];
	
		return $this->Update($objCp, $where);
	}	
		
}