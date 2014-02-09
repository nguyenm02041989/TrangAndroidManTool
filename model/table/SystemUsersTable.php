<?php

class SystemUsersTable extends Object
{
	private $user_id;
	public $group_id;
	public $username;
	public $password;
	public $email;
	public $firstname;
	public $middlename;
	public $lastname;
	public $lang_id;
	public $date_create;
	public $date_edit;
	public $create_by;
	public $modified_by;
		
	public function __construct() {
		
	}
	
	public function SetId($id) {
		$this->user_id = $id;
	}
	
	public function GetId() {
		return $this->user_id;
	}

}