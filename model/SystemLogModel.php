<?php
require_once 'table/SystemLogTable.php';

class SystemLogModel extends BaseModel
{

	public function __construct() {
		parent::__construct("system_log");
	}
	
	public function AddLog($userId, $ipAddr, $msg)
	{
		
		$obj = new SystemLogTable();
		$obj->user_id = $userId;
		$obj->ip_addr = $ipAddr;
		$obj->log_msg = $msg;
		$this->Insert($obj);
		return $this;
	}
}