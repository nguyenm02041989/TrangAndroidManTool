<?php
require_once 'table/GcmUsersTable.php';



class GcmUsersModel extends BaseModel
{
	
	public function __construct() {
		parent::__construct("gcm_users");
	}

	/**
	 * Check if the registration exists.
	 * 
	 * @param string $regId
	 * @return boolean
	 */
	public function RegistrationExists($regId) {
		
		$exists = $this->RowExists(array("gcm_regid" => $regId));
		
		if($exists) {
			return true;
		}
		return false;
	}
	
	/**
	 * Store the device into the system
	 * 
	 * @param string $regId
	 * @return number
	 */
	public function RegisterUserDevice($regId, $appId = "", $devUniqueId = "") {
		
		if(strlen($regId) > 0) {

			$obj = new GcmUsersTable();
			$obj->gcm_regid = $regId;
			$obj->unique_device_id = $devUniqueId;
			$obj->app_id = $appId;
			$obj->is_deleted = 0;
			$obj->date_create = date("Y-m-d H:i:s");
			$obj->date_edit = $obj->date_create;
			return $this->Insert($obj);
		}
		return -1;
	}
	
	/**
	 * Remove the device from the system. Don not delete it, but flag it only. 
	 * @param string $regId
	 * @return GcmUsersModel
	 */
	public function RemoveUserDevice($regId) {
		
		$this->UpdateColumns(
							array('is_deleted' => 1), 
							array('gcm_regid' => $regId)
							);
		return $this;
	}
	
	/**
	 * Reactivate the device
	 * @param string $regId
	 * @return GcmUsersModel
	 */
	public function ReActivateUserDevice($regId) {
		
		$this->UpdateColumns(
				array('is_deleted' => 0),
				array('gcm_regid' => $regId)
		);
		return $this;
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
		
		$query = "SELECT * FROM " . $this->mTableName .
		" WHERE is_deleted <> 1 
		 ORDER BY gcm_id ASC
		 LIMIT $index, $limit";
		
		$objects = $this->SelectWithQuery($query);
		return $objects;
	}
	
	/**
	 * Get the list
	 *
	 * @param number $index
	 * @param number $limit
	 * @return array
	 */
	public function GetListWithAppId($index = 0, $limit = 10, $appId = "") {
	
		$index = (int) $index;
		$limit = (int) $limit;
	
		$query = "SELECT * FROM " . $this->mTableName .
				" WHERE is_deleted <> 1 AND app_id = '$appId'
				 ORDER BY gcm_id ASC
				 LIMIT $index, $limit";
		
		$objects = $this->SelectWithQuery($query);
		return $objects;
	}
		
	/**
	 * Get the list
	 *
	 * @param number $index
	 * @param number $limit
	 * @return array
	 */
	public function GetListDelete($index = 0, $limit = 10) {
	
		$index = (int) $index;
		$limit = (int) $limit;
	
		$query = sprintf("SELECT * FROM %s
				WHERE is_deleted = 1
				ORDER BY gcm_id ASC
				LIMIT %s, %s", $this->mTableName, $index, $limit);
	
		$objects = $this->SelectWithQuery($query);
		return $objects;
	}	
	
	/**
	 * Fetch total rows
	 * 
	 * @return number
	 */
	public function FetchTotalRows() {
		
		$query = sprintf("SELECT Count(gcm_id) AS total 
						FROM %s WHERE is_deleted <> 1 ", $this->mTableName);
		
		$objects = $this->SelectWithQuery($query);
		if(sizeof($objects) > 0) {
			return $objects[0]->total;
		}
		return 0;
	}
	
	/**
	 * Fetch total rows
	 *
	 * @return number
	 */
	public function FetchTotalRowsDeleted() {
	
		$query = sprintf("SELECT Count(gcm_id) AS total
						FROM %s WHERE is_deleted = 1 ", $this->mTableName);
	
		$objects = $this->SelectWithQuery($query);
		if(sizeof($objects) > 0) {
			return $objects[0]->total;
		}
		return 0;
	}	
	
	/**
	 * Override function
	 * 
	 * @param number $maxPerPage
	 * @return number
	 */
	public function GetTotalPages($maxPerPage = 10) {
	
		$totalRows = $this->FetchTotalRows();
		$total = floor($totalRows / $maxPerPage) + ($totalRows % $maxPerPage < $maxPerPage ? 1 : 0);
		return $total;
	}	
	
	/**
	 * Override function
	 *
	 * @param number $maxPerPage
	 * @return number
	 */
	public function GetTotalPagesDeleted($maxPerPage = 10) {
	
		$totalRows = $this->FetchTotalRowsDeleted();
		$total = floor($totalRows / $maxPerPage) + ($totalRows % $maxPerPage < $maxPerPage ? 1 : 0);
		return $total;
	}
		
	/**
	 * Get the User object
	 * @param int $id
	 * @return Object
	 */
	public function GetObject($id) {
		
		$object = $this->FetchObject(
								Array("gcm_id", "gcm_regid", "app_id", "unique_device_id", "is_deleted", "date_create", "date_edit"),
								Array("gcm_id" => $id)
				);
		return $object;
	}
	
	/**
	 * Update an existing record
	 *
	 * @param AccessTable $obj
	 * @param array $where
	 * @return number	-1 On error
	 */
	public function UpdateObject(GcmUsersTable $obj, $where) {
	
		if(! is_a($obj, "GcmUsersTable")) {
			return -1;
		}
	
		$objCp = new GcmUsersTable();
	
		$this->CopyObjects($obj, $objCp);
	
		$now = date('Y-m-d H:i:s');
		$objCp->date_edit = $now;
	
		return $this->Update($objCp, $where);
	}	
	
}