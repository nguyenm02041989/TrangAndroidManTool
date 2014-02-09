<?php
require_once 'table/AccessControllersTable.php';

/**
 * The SystemUsersModel manages all data for the users in the system.
 * 
 * @author trang
 *
 */
class AccessControllersModel extends BaseModel
{
	
	/**
	 * Constructor. Fill the name of the table here
	 */
	public function __construct() {
		parent::__construct("access_controllers");
	}
	
	/**
	 * Add new user to the system
	 * @param AccessTable $obj
	 */
	public function AddObject(AccessControllersTable $obj) {
	
		if(! is_a($obj, "AccessControllersTable")) {
			return -1;
		}
		return $this->Insert($obj);
	}
	
	/**
	 * Get list all
	 * @return array
	 */
	public function GetListAll() {

		$objects = $this->FetchObjects(
				Array("id", "controller", "alias"),
				Array(),
				"alias",
				"ASC",
				"",
				""
		);
		return $objects;
	}

		
}