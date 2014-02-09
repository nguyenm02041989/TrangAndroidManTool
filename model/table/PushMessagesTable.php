<?php
class PushMessagesTable extends Object
{
	private $msg_id;
	public $app_id;
	public $message;
	public $sento_google;
	public $successfull;
	public $failed;
	public $google_response;
	public $create_by;
	public $date_create;
	public $date_edit;
	

	function __construct() {
		
	}
	
	public function SetId($id) {
		$this->msg_id = $id;
	}
	
	public function GetId() {
		return $this->msg_id;
	}
		
}