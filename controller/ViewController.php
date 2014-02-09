<?php

/**
 * The ViewerController class manages the output to the clients
 * 
 * @author trang
 *
 */
class ViewController 	extends BaseController
						implements ISingleton
{
	/**
	 * The static instance
	 * @var Object
	 */
	protected static $mInstance;	
	
	/**
	 * Template variables used in parsing the output
	 * @var Array
	 */
	public static $TemplateVars;
	
	/**
	 * Holds the language strings
	 * 
	 * @var Array
	 */
	private $mLangStr;
	
	/**
	 * Template urls
	 * @var Array
	 */
	protected $mTemplateUrls;
	
	private function __construct()
	{
		$langCode = (isset($_SESSION["LANGUAGE"]) &&
				strlen($_SESSION["LANGUAGE"]) > 0 ? $_SESSION["LANGUAGE"] : "");
		$this->mLangStr = $this->LoadLanguageFile($langCode);		
	}

	/**
	 * Set the template urls
	 * @param Array $urls
	 */
	public function SetTemplateUrls($urls) {
		
		$this->mTemplateUrls = $urls;
	}
	
	/**
	 * Get the template urls
	 * @return Array
	 */
	public function GetTemplateUrls() {
		
		return $this->mTemplateUrls;
	}
	
	/**
	 * Get a value from the template vars
	 * 
	 * @param string $key
	 */
	public static function Get($key) {
		
		return ViewController::$TemplateVars[$key];
	}

	/**
	 * Get the template url
	 * @param string $key
	 * @return string
	 */
	public static function GetUrl($key) {
	
		$obj = ViewController::GetInstance();
		$arrTmp = ViewController::GetInstance()->GetTemplateUrls();
		
		if(sizeof($arrTmp) && sizeof($arrTmp) > 0) {
			
			$url = $arrTmp[$key];
			return URI::Get($url);
		}
		return "";
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
	 * Get a language string with a key
	 * @param string $key
	 * @return string
	 */
	public function GetLangValueByKey($key) {
		
		if(isset($this->mLangStr[$key]) && 
			strlen($this->mLangStr[$key]) > 0) {
			return $this->mLangStr[$key];
		}
		return "";
	}

	/**
	 * Parse an string and replace with text values from /res/string.txt
	 * 
	 * @param string $text
	 * @param Array $data
	 * @return string
	 */
	public function ParseString($text, $data = array()) {
		
		if(sizeof($data) > 0) {
			ViewController::$TemplateVars = array_merge($data, $this->mLangStr);
		}
		else {
			ViewController::$TemplateVars = $this->mLangStr;
		}
		$out = "";
		
		$pVars = ViewController::$TemplateVars;
		
		if(sizeof($pVars) > 0) {
				
			$search = Array();
			$replace = Array();
		
			foreach($pVars as $key => $val) {
					
				if(is_string($key)) {
					$search[] = "{" . strtoupper($key) . "}";
					$replace[] = $val;
				}
			}
		
			$out = str_replace($search, $replace, $text);
			ViewController::$TemplateVars = NULL; // clean up here
		}		
		return $out;
	}
	
	/**
	 * Parse content
	 * @param string $template
	 * @param array $data
	 * @return mixed|string
	 */
	public function ParseContent($template, $data = array()) {
		
		$fileName = "view/" . $template . ".php";
		if(file_exists($fileName)) {
			
			if(sizeof($data) > 0) {				
				ViewController::$TemplateVars = array_merge($data, $this->mLangStr);
			}
			else {
				ViewController::$TemplateVars = $this->mLangStr;
			}		
				
			$frmSecId = $this->GetSecurityFormId();
			$_SESSION["SECURITY_FORM_ID"] = $frmSecId;
			ViewController::$TemplateVars['security_form_id'] = $frmSecId;
			
			ob_start();
			include_once ($fileName);
			$out = ob_get_contents();
			ob_end_clean();
			
			$pVars = ViewController::$TemplateVars;
			
			if(sizeof($pVars) > 0) {
			
				$search = Array();
				$replace = Array();
				
				foreach($pVars as $key => $val) {
					
					if(is_string($key)) {
						$search[] = "{" . strtoupper($key) . "}";
						$replace[] = $val;
					}
				}
				
				$out = str_replace($search, $replace, $out);
				
				ViewController::$TemplateVars = NULL; // clean up here
			}
			return $out;
		}
		return "";
	}
	
	/**
	 * Load language file
	 * @param string $langCode
	 * @return multitype:string
	 */
	protected function LoadLanguageFile($langCode = '') {
		
		$fileName = "./res/string.txt";
		if(strlen($langCode) > 0) {
			$fileName = "./res/string-" . $langCode . ".txt";
		}
		
		if(!file_exists($fileName)) {
			$fileName = "./res/string.txt";
		}
		
		$lines = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		
		$tmpVars = Array();
		
		foreach($lines as $line) {
			
			$arrSplit = explode("=", $line);
			
			if(sizeof($arrSplit) == 2) {
				$tmpVars[trim($arrSplit[0])] = trim($arrSplit[1]);
			}
		}
		return $tmpVars;
	}
	
}