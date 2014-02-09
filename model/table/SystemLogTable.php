<?php
class SystemLogTable extends Object
{
	private $log_id;
	public $log_msg;
	public $user_id;
	public $ip_addr;
	public $date_create;

	function __construct() {
		
	}
	
	public function SetId($id) {
		$this->log_id = $id;
	}
	
	public function GetId() {
		return $this->log_id;
	}
}