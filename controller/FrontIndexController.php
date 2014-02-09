<?php

/**
 * The FrontIndexController handles all client request
 * 
 * @author trang
 *
 */
class FrontIndexController 	extends BaseController
							implements ISingleton
{
	/**
	 * Container of the POST values
	 * @var Array
	 */
	protected $mPostVars = Array();
	
	/**
	 * Container of the GET values
	 * @var Array
	*/
	protected $mGetVars = Array();
	
	/**
	 * The static instance
	 * @var Object
	 */
	protected static $mInstance;
	
	/**
	 * Filter external input
	*/
	public function FilterInput() {
	
		$this->FilterPostVars();
		$this->FilterGetVars();
	}
	
	/**
	 * Just filter the external $_POST var
	 */
	protected function FilterPostVars() {
	
		if(sizeof($_POST) > 0 && is_array($_POST)) {
	
			foreach($_POST as $key => $value) {
	
				$fOut = addslashes($value);
				$fOut = htmlentities($fOut);
				$fOut = strip_tags($fOut);
				$this->mPostVars[$key] = $fOut;
			}
		}
	}
	
	/**
	 * Just filter the external $_GET var
	 */
	protected function FilterGetVars() {
	
		if(sizeof($_GET) > 0 && is_array($_GET)) {
	
			foreach($_GET as $key => $value) {
					
				$fOut = addslashes($value);
				$fOut = htmlentities($fOut);
				$fOut = strip_tags($fOut);
				$this->mGetVars[$key] = $fOut;
			}
		}
	}
	
	/**
	 * Get the Request var
	 *
	 * @param string $key
	 * @return multitype:|string
	 */
	public static function GetRequest($key) {
	
		$obj = FrontIndexController::GetInstance();
		
		if(isset($obj->mPostVars[$key]) && strlen($obj->mPostVars[$key]) > 0) {
			return $obj->mPostVars[$key];
		}
		else if(isset($obj->mGetVars[$key]) && strlen($obj->mGetVars[$key]) > 0) {
			return $obj->mGetVars[$key];
		}
		return "";
	}	
	
	/**
	 * The URLObject which holds the info what to do
	 * @var URLObject
	 */
	protected $mURLObject;
	
	private function __construct() {
		
		$this->mURLObject = new URLObject();
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
	 * Get the URL object
	 * 
	 * @return URLObject
	 */
	public function GetUrlObject()
	{
		return $this->mURLObject;
	}
	

	
	/**
	 * This function read the request URI and splits it up.
	 * 
	 * @return URLObject
	 */
	public function ReadRequestUri() {
		
		$urlSplit = Array();
		
		if(isset($_POST["task"]) && strlen($_POST["task"]) > 0) {
			$urlSplit = explode("/", $_POST["task"]);
		}
		else if(isset($_GET["task"]) && strlen($_GET["task"]) > 0) {
			$urlSplit = explode("/", $_GET["task"]);
		}
		else {
			
			$reqU = str_replace("/", "|", $_SERVER["REQUEST_URI"]);
			$urlSplit = explode("/", $_SERVER["REQUEST_URI"]);
		}
		
		// The controller
		if(isset($urlSplit[1]) && strlen($urlSplit[1]) > 0) {			
			
			$this->mURLObject->SetController($urlSplit[1]);
		}
		
		// The controller Task
		if(isset($urlSplit[2]) && strlen($urlSplit[2]) > 0) {			
			
			$this->mURLObject->SetTask($urlSplit[2]);
			
			$task = strtolower($urlSplit[2]);
			
			if(preg_match("/(list)/", $task) 
				|| preg_match("/(search)/", $task)) {

				// The list index
				if(isset($urlSplit[3]) && strlen($urlSplit[3]) > 0) {
					$this->mURLObject->SetIndex($urlSplit[3]);
				}
				// The list limit
				if(isset($urlSplit[4]) && strlen($urlSplit[4]) > 0) {
					$this->mURLObject->SetLimit($urlSplit[4]);
				}				
			}
			else if(preg_match("/(edit)/", $task)
					|| preg_match("/(add)/", $task)
					|| preg_match("/(view)/", $task)
					|| preg_match("/(delete)/", $task)) {
				
				// The record ID
				if(isset($urlSplit[3]) && strlen($task) > 0) {
					$this->mURLObject->SetID($urlSplit[3]);
				}
			}
		}
		
		return $this->mURLObject;
	}
	
	/**
	 * Get Page not found
	 *
	 * @param ViewController $viewCtrl
	 * @param string $errText
	 * @return string
	 */
	public function GetViewNotFound(ViewController $viewCtrl, $errText = "") {
	
		return $this->GetView($viewCtrl, "PageNotFound", Array("error_text" => $errText));
	}
	
	
}