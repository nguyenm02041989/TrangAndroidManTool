<?php
require_once './model/SystemUsersModel.php';

/**
 * The LoginController handles all Login tasks for the system
 * 
 * @author trang
 *
 */
class LoginController extends BaseController
{
	
	public function __construct()
	{
		$this->mModel = new SystemUsersModel();
	}
	
	/**
	 * Check the login status of the user
	 * 
	 * @return number
	 */
	public function CheckLoginStatus() {
		
		if(isset($_SESSION) && isset($_SESSION['LOGIN_STATUS'])) {
			
			if($_SESSION['LOGIN_STATUS'] == 1) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Authenticate the client
	 */
	public function AuthenticateTask() {
				
		// Check security form id first
		if(! $this->CheckSecurityFormId(FrontIndexController::GetRequest("security_form_id"))) {
			parent::RedirectTo(URI::Get("/login/failed"));
		}
		
		$uName = FrontIndexController::GetRequest("uname");
		$uPass = FrontIndexController::GetRequest("upass");
		
		if($this->Authorization($uName, $uPass)) {
			parent::RedirectTo("/");
		}
		else {
			parent::RedirectTo(URI::Get("/login/failed"));
		}
	}
	
	
	/**
	 * Authorize the user to login
	 * 
	 * @param string $userName
	 * @param string $passWord
	 * @return boolean
	 */
	public function Authorization($uName, $uPass) {
		
		if(strlen($uName) > 0 && strlen($uPass) > 0) {
			
			$usrObj = $this->mModel->GetUser($uName, $uPass);
			
			if($usrObj->username == $uName && $usrObj->password == $uPass) {
				
				// Login allowed
				$_SESSION["LOGIN_STATUS"] = 1;
				$_SESSION["USER_ID"] = $usrObj->user_id;
				$_SESSION["GROUP_ID"] = $usrObj->group_id;
				$_SESSION["SECURITY_LEVEL"] = $usrObj->security_level;
				
				if(strlen($usrObj->lang_id) > 0) {
					
					require_once './model/LanguagesModel.php';
					$langModel = new LanguagesModel();
					$langCode = $langModel->GetLanguageById($usrObj->lang_id);
					
					if(strlen($langCode) > 0) {
						$_SESSION["LANGUAGE"] = $langCode;
					}
				}
				
				if(strlen($usrObj->middlename) > 0) {
					$_SESSION["NAME_USER"] = $usrObj->firstname . ' ' . $usrObj->middlename . ' ' . $usrObj->lastname;
				}
				else {
					$_SESSION["NAME_USER"] = $usrObj->firstname . ' ' . $usrObj->lastname;
				}
				
				$logModel = new SystemLogModel();
				$logModel->AddLog($usrObj->user_id, $_SERVER["REMOTE_ADDR"], MSG_ACCESS_GRANTED);
				
				return true;
			}
			
		}		
		return false;
	}
	
	/**
	 * Get Login View if not logged in
	 * 
	 * @param ViewController $viewCtrl
	 * @param string $errText
	 * @return string
	 */
	public function GetViewLogin(ViewController $viewCtrl, $errText = "") {
		
		return $this->GetView($viewCtrl, "LoginView", Array("error_text" => $errText));
	}

	/**
	 * Get Control Panel View if logged in
	 *
	 * @param ViewController $viewCtrl
	 * @param string $errText
	 * @return string
	 */
	public function GetViewAdmin(ViewController $viewCtrl, $errText = "") {
	
		return $this->GetView($viewCtrl, "AdminView", Array(
																"error_text" => $errText, 
																)
															);
	}
	
	/**
	 * Logout of the system
	 * 
	 * @return boolean
	 */
	public function LogoutTask() {

		$logModel = new SystemLogModel();
		$logModel->AddLog($_SESSION["USER_ID"], $_SERVER["REMOTE_ADDR"], MSG_LOGOUT);
		
		$_SESSION["LOGIN_STATUS"] = 0;
		$_SESSION["LANGUAGE"] = "";
		$_SESSION["USER_ID"] = "";
		
		parent::RedirectTo("/");
	}
	
	/**
	 * Get the page login failed
	 * 
	 * @return string
	 */
	public function FailedTask() {
		
		$objVw = ViewController::GetInstance();
		return $this->GetViewLogin(	$objVw,
									$objVw->GetLangValueByKey("login_failed")
		);
	}
	
}