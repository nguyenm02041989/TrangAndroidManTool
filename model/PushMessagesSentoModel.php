<?php
require_once 'table/PushMessagesSentoTable.php';

class PushMessagesSentoModel extends BaseModel
{

	public function __construct() {
		parent::__construct("push_messages_sento");
	}
	
	
	public function AddLog($gcmId, $msgId) {
	
		$msgId = (int) $msgId;
		
		$objMsg = new PushMessagesSentoTable();
		$objMsg->msg_id = $msgId;	
		$objMsg->gcm_id = $gcmId;	
		return $this->Insert($objMsg);
	}	
	
	/**
	 * Delete the record
	 * @param int $id
	 */
	public function DeleteById($id) {
	
		$this->Delete(Array("msg_id" => $id));
	}	
	
	

		
}