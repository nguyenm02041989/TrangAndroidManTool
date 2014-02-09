<?php
class PushMessagesSentoTable extends Object
{
	private $id;
	public $msg_id;
	public $gcm_id;

	function __construct() {
		
	}
	
	public function SetId($id) {
		$this->id = $id;
	}
	
	public function GetId() {
		return $this->id;
	}
		
}