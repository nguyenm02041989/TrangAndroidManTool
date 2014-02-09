<?php
class LanguagesTable extends Object
{
	private $lang_id;
	public $lang_code;

	function __construct() {
		
	}
	
	public function SetId($id) {
		$this->lang_id = $id;
	}
	
	public function GetId() {
		return $this->lang_id;
	}
}