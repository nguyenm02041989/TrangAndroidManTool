<?php

class GcmUsersTable extends Object
{
	private $gcm_id;
	
	public $gcm_regid;
	public $unique_device_id;
	public $app_id;
	public $name;
	public $email;
	public $date_create;
	public $date_edit;
	
	
	public function __construct() {
		
		
	}
	
	public function SetId($id) {
		$this->gcm_id = $id;
	}
	
	public function GetId() {
		return $this->gcm_id;
	}

}