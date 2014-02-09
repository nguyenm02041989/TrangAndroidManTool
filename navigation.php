<?php 

class Navigation 
{
	
	/**
	 * The static instance
	 * @var Object
	 */	
	protected static $mInstance;
		
	/**
	 * Holds the nav for the system.
	 * 
	 * @var Array
	 */
	private $mNavigation;
	
	/**
	 * The data model
	 * @var object
	 */
	private $mModel;
	
	/**
	 * The permissions
	 * @var Array
	 */
	private $mPermissions = Array();
	
	
	public function __construct() {
		
		$this->mModel = new AccessModel();
		$retPrm = $this->mModel->GetPermissionsByGroup($_SESSION["GROUP_ID"]);
		
		foreach($retPrm as $perm) {
			$this->mPermissions[$perm->controller] = $perm;
		}
		
		$this->mNavigation = Array(
				
				"controllers" => Array(
						
						"gcm" => Array(
								
								Array("name" => "{APPS}", "url" => "/apps/list", "ctrl" => "apps"),
								Array("name" => "{DEVICE_REGISTRATIONS}", "url" => "/gcm/list", "ctrl" => "gcm"),
								Array("name" => "{PUSH_MESSAGES}", "url" => "/push/list", "ctrl" => "push"),
								
								),
						"settings" => Array(
								
								Array("name" => "{USERS}", "url" => "/users/list", "ctrl" => "users"),
								Array("name" => "{GROUPS}", "url" => "/groups/list", "ctrl" => "groups"),
								Array("name" => "{ACCESS}", "url" => "/access/list", "ctrl" => "access"),
								
								),

						
					)				
				);
		
	}
	
	/**
	 * Get the static instance
	 * @return Singleton instance
	 */
	public static function GetInstance(){
	
		if(static::$mInstance == NULL) {
			$className = get_called_class();
			static::$mInstance = new $className();
		}
		return static::$mInstance;
	}	
	
	/**
	 * Get the navigation
	 * 
	 * @return Array
	 */
	public function Get($controllerName) {
		
		$navArr = $this->mNavigation["controllers"][$controllerName];
		
		$arrRet = Array();
		
		$_URLObject = FrontIndexController::GetInstance()->GetUrlObject();
		
		$pattern = "";
		if(strlen($_URLObject->GetController()) > 0) {
			$urlPatt = "(" . $_URLObject->GetController() . ")";
			$urlPatt = strtolower($urlPatt);
			$pattern = "/$urlPatt/";
		}
		
		foreach($navArr as $row) {
			
			if(	sizeof($row) > 0 && 
				$this->mPermissions[$row["ctrl"]."controller"]->read == 1) {
				
				if(strlen($pattern) > 0 && preg_match($pattern, $row["url"])) {
					$row["active"] = "class=\"active\"";
				}
				$arrRet[] = $row;
			}
		}
		return $arrRet;
	}

	/**
	 * Get the navigation
	 *
	 * @return Array
	 */
	public function GetNavigation() {
		return $this->mNavigation;
	}
	
}

