<?php
/**
 * Just a simple Access Control List class
 * 
 * @author trang
 *
 */
class Acl extends Object
{
	/**
	 * The static instance
	 * @var Object
	 */
	protected static $mInstance;
		
	/**
	 * Holds the permissions
	 * @var Array
	 */
	protected $mPermissions;
	
	/**
	 * Constructor
	 */
	private function __construct() {

		$this->mPermissions = Array();
	}
	
	/**
	 * Check if ACL has any permissions
	 * 
	 * @return boolean
	 */
	public function HasPermissions() {
		
		if(sizeof($this->mPermissions) == 0) {
			return false;
		} 
		return true;
	}

	/**
	 * Get the static instance
	 * @return Singleton instance
	 */
	public static function GetInstance(){
	
		if(static::$mInstance == NULL) {
			static::$mInstance = new Acl();
		}
		return static::$mInstance;
	}	
	
	/**
	 * Get the permissions
	 */
	public function GetPermissions() {
		
		return $this->mPermissions;
	}
	
	/**
	 * Set the permissions
	 * @param Array $permissions
	 */
	public function SetPermissions($permissions) {
		
		if(sizeof($permissions) > 0) {
		
			foreach($permissions as $permission) {
				
				$this->mPermissions[$permission->group_id] = $permission;
			}
		}
	}
	
	/**
	 * Check if you have read access
	 * @param int $groupId
	 * @return boolean
	 */
	public function AllowReadAccess($groupId = "") {
		
		if(isset($_SESSION["SECURITY_LEVEL"])
		&& $_SESSION["SECURITY_LEVEL"] == 0)
			return true;
		
		if(strlen($groupId) == 0){
			$groupId = $_SESSION["GROUP_ID"];
		} 
		
		if($this->mPermissions[$groupId]->read == 1) {
			return true;
		}
		return false;
	}

	/**
	 * Check if you have read / write access
	 * @param int $groupId
	 * @return boolean
	 */
	public function AllowReadWriteAccess($groupId = "") {

		if(isset($_SESSION["SECURITY_LEVEL"])
		&& $_SESSION["SECURITY_LEVEL"] == 0)
			return true;
		
		if(strlen($groupId) == 0){
			$groupId = $_SESSION["GROUP_ID"];
		}
		
		if($this->mPermissions[$groupId]->read == 1 
		&& $this->mPermissions[$groupId]->write == 1) {
			return true;
		}
		return false;
	}
	
	/**
	 * Check if you have write access
	 * @param int $groupId
	 * @return boolean
	 */
	public function AllowWriteAccess($groupId = "") {
		
		if(isset($_SESSION["SECURITY_LEVEL"])
		&& $_SESSION["SECURITY_LEVEL"] == 0)
			return true;
		
		if(strlen($groupId) == 0){
			$groupId = $_SESSION["GROUP_ID"];
		}
		
		if($this->mPermissions[$groupId]->write == 1) {
			return true;
		}
		return false;		
	}
	
	/**
	 * Check if you have removing access
	 * @param int $groupId
	 * @return boolean
	 */
	public function AllowRemoveAccess($groupId = "") {
		
		if(isset($_SESSION["SECURITY_LEVEL"])
		&& $_SESSION["SECURITY_LEVEL"] == 0)
			return true;
		
		if(strlen($groupId) == 0){
			$groupId = $_SESSION["GROUP_ID"];
		}
		
		if($this->mPermissions[$groupId]->remove == 1) {
			return true;
		}
		return false;		
	}
	
	/**
	 * Check if you have read / write / remove access
	 * @param int $groupId
	 * @return boolean
	 */
	public function AllowAllAccess($groupId = "") {
		
		if(isset($_SESSION["SECURITY_LEVEL"]) 
			&& $_SESSION["SECURITY_LEVEL"] == 0)
			return true;
		
		if(strlen($groupId) == 0){
			$groupId = $_SESSION["GROUP_ID"];
		}
		
		if($this->mPermissions[$groupId]->read == 1
		&& $this->mPermissions[$groupId]->write == 1
		&& $this->mPermissions[$groupId]->remove == 1
		) {
			return true;
		}
		return false;
	}
	
}