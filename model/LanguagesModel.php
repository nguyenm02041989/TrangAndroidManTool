<?php
require_once 'table/LanguagesTable.php';

class LanguagesModel extends BaseModel
{

	public function __construct() {
		parent::__construct("languages");
	}
	
	/**
	 * Get list all
	 * @return array
	 */
	public function GetListAll() {
	
		$objects = $this->FetchObjects(
				Array("lang_id", "lang_code", "description"),
				Array(),
				"lang_code",
				"ASC",
				"",
				""
		);
		
		$arrRet = Array();
		
		foreach($objects as $obj) {
			
			if($obj->lang_code == "en") {
				$arrRet[] = $obj;
			}
			else {
				
				$fileName = "./res/string-" . $obj->lang_code . ".txt";
				if(file_exists($fileName)) {
					$arrRet[] = $obj;
				}
			}
		}
		
		return $arrRet;
	}	
	
	/**
	 * Get the language code by passing the ID
	 * @param int $langId
	 */
	public function GetLanguageById($langId) {
		
		$langId = (int) $langId;		
		$langObj = $this->FetchObject(	  Array("lang_id", "lang_code")
										, Array("lang_id" => $langId)
				);
		return $langObj->lang_code;	
	}
}