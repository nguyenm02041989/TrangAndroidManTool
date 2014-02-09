<?php
require_once './model/PushMessagesModel.php';
require_once './model/PushMessagesSentoModel.php';
require_once './model/GcmUsersModel.php';
require_once './model/AppsModel.php';

/**
 * The class handles push messages to the devices
 *
 * @author trang
 *
 */
class PushController 	extends BaseController 
						implements IController
{

	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->mModel = new PushMessagesModel();

		$this->mTemplateCfg = Array(
				"AdminView" => "AdminView",
				"Add" => "AddView",
				"AddSuccess" => "AddViewSuccess",
				"Edit" => "EditView",
				"EditSuccess" => "EditViewSuccess",
				"View" => "View",
				"Delete" => "",
				"Error" => "ErrorView",
				"List" => "ListView",
				"Search" => "SearchView",
				"PathSnippets" => "snippets/push/",
				"PaginationView" => "PaginationView",
		);

		$this->mUrls = Array(
				"Add" => "/push/add",
				"AddSave" => "/push/addsave",
				"AddSuccess" => "/push/addsuccess",
				"Edit" => "/push/edit",
				"EditSave" => "/push/editsave",
				"EditSuccess" => "/push/editsuccess",
				"View" => "/push/view",
				"Delete" => "/push/delete",
				"Error" => "/push/error",
				"List" => "/push/list",
				"Search" => "/push/search/",
				"Validate" => "/push/validate",
				"ValidateChange" => "/push/validatechange",
				"ValidateForm" => "/push/validateform",
				"ValidateFormEdit" => "/push/validateformedit",
		);

		ViewController::GetInstance()->SetTemplateUrls($this->mUrls);
	}

	/**
	 * Add new record
	 * @see string
	 */
	public function AddTask() {

		parent::CheckSecurity();

		if(! Acl::GetInstance()->AllowWriteAccess()) {
			return "";	
		}
		
		$obj = new PushMessagesTable();
		$arrObj = $this->ConvertObjectToArray($obj);

		$id = $this->GetUrlObjectId();
		
		$modelApps = new AppsModel();
		$objApp = $modelApps->GetObject($id);
		
		if(strlen($objApp->app_id) > 0) {

			$arrObj["object_app_id"] = $objApp->app_id;
			
			$snippet = $this->GetSnippet($this->mTemplateCfg["Add"], $arrObj);
		return $this->GetParsedContent($snippet);
		}
		else {
			return "";
		}
	}

	/**
	 * Save the Add form
	 *
	 * @return string
	 */
	public function AddSaveTask() {

		parent::CheckSecurity();
		
		if(! Acl::GetInstance()->AllowWriteAccess()) {
			parent::RedirectTo($this->mUrls["Error"]);
			return;
		}

		$msg = FrontIndexController::GetRequest("message");
		
		$pmModel = new PushMessagesModel();
		$msgId = $pmModel->AddLog($msg);
		
		// Sento to Google
		if($this->SentPushMessageToGoogle($msg, $msgId)) {
		
			parent::RedirectTo($this->mUrls["AddSuccess"] . "/" . $msgId);
		}
		else {
		
			parent::RedirectTo($this->mUrls["Error"]);
		}
	}

	/**
	 * Task to do when record succesfully added
	 * @return string
	 */
	public function AddSuccessTask() {

		parent::CheckSecurity();

		if(! Acl::GetInstance()->AllowReadAccess()) {
			parent::RedirectTo($this->mUrls["Error"]);
			return;
		}
		
		$id = $this->GetUrlObjectId();
		$objVw = $this->mModel->GetObject($id);
		
		// Load data
		$arrObj = $this->ConvertObjectToArray($objVw);
		
		$snippet = $this->GetSnippet($this->mTemplateCfg["AddSuccess"], $arrObj);
		return $this->GetParsedContent($snippet);		
	}

	/**
	 * Check the form
	 *
	 * @return boolean
	 */
	public function CheckForm() {


		return true;
	}

	/**
	 * Check the Add form
	 *
	 * @return boolean
	 */
	public function CheckFormAdd () {

		$ctrId = (int) FrontIndexController::GetRequest("controller_id");
		$groupId = (int) FrontIndexController::GetRequest("group_id");

		if($this->mModel->RowExists(Array("controller_id" => $ctrId, "group_id" => $groupId))) {
			return false;
		}

		return true;
	}

	/**
	 * Check the Edit form
	 *
	 * @return boolean
	 */
	public function CheckFormEdit () {

		$id = (int) FrontIndexController::GetRequest("id");
		$ctrId = (int) FrontIndexController::GetRequest("controller_id");
		$groupId = (int) FrontIndexController::GetRequest("group_id");
			
		if($this->mModel->RowExists(Array("controller_id" => $ctrId, "group_id" => $groupId),
				Array("id" => $id))) {
			return false;
		}
		return true;
	}

	/**
	 * Remove the user from the system
	 *
	 * @see BaseController::DeleteTask()
	 */
	public function DeleteTask () {

		parent::CheckSecurity();
		if(! Acl::GetInstance()->AllowRemoveAccess()) {
			parent::RedirectTo($this->mUrls["Error"]);
			return;
		}

		$id = $this->GetUrlObjectId();
		
		$pmModel = new PushMessagesModel();
		$pmModel->DeleteById($id);
		
		$pmSentModel = new PushMessagesSentoModel();
		$pmSentModel->DeleteById($id);
		
		parent::RedirectTo($this->mUrls["List"]);
	}

	/**
	 * Get the Edit form
	 * @see string
	 */
	public function EditTask() {

		parent::CheckSecurity();

		if(! Acl::GetInstance()->AllowWriteAccess()) {
			return "";
		}
		
		$id = $this->GetUrlObjectId();
		$objVw = $this->mModel->GetObject($id);

		// Load data
		$arrObj = $this->ConvertObjectToArray($objVw);
		$obj = new AccessTable();

		$modAccCtr = new AccessControllersModel();
		$objCtrl = $modAccCtr->GetListAll();
		$arrObj["list_controllers"] = $objCtrl;

		$modGroups = new GroupsModel();
		$objGrps = $modGroups->GetListAll();
		$arrObj["list_groups"] = $objGrps;

		$snippet = $this->GetSnippet($this->mTemplateCfg["Edit"], $arrObj);
		return $this->GetParsedContent($snippet);
	}

	/**
	 * Update the form
	 *
	 * @return string
	 */
	public function EditSaveTask() {

		parent::CheckSecurity();
		if(! Acl::GetInstance()->AllowWriteAccess()) {
			parent::RedirectTo($this->mUrls["Error"]);
			return;
		}
	}

	/**
	 * The update task is succesfully completed.
	 *
	 * @return string
	 */
	public function EditSuccessTask() {

		parent::CheckSecurity();

		if(! Acl::GetInstance()->AllowReadAccess()) {
			parent::RedirectTo($this->mUrls["Error"]);
			return;
		}
		
		$snippet = $this->GetSnippet($this->mTemplateCfg["EditSuccess"], Array());
		return $this->GetParsedContent($snippet);
	}

	/**
	 * Handles all errors
	 */
	public function ErrorTask() {

		parent::CheckSecurity();

		$snippet = $this->GetSnippet($this->mTemplateCfg["Error"], Array());
		return $this->GetParsedContent($snippet);
	}

	/**
	 * Get the list
	 *
	 * @see BaseController::ListTask()
	 */
	public function ListTask () {

		parent::CheckSecurity();

		if(! Acl::GetInstance()->AllowReadAccess()) {
			return "";
		}
		
		$objPag = new Pagination();
		$this->GetListIndexAndLimit($objPag->index, $objPag->limit);
		$objPag->listObjects = $this->mModel->GetList($objPag->index, $objPag->limit);
		$objPag->totalPages = $this->mModel->GetTotalPages($objPag->limit);
		$objPag->totalRecords = $this->mModel->FetchTotalRows();

		$snippet = $this->GetListViewWithPagination($objPag);
		return $this->GetParsedContent($snippet);
	}



	/**
	 * Map the request to an object
	 *
	 * @return Object
	 */
	public function MapRequestDataToObject() {

		// Check each incoming value.
		return NULL;
	}

	/**
	 * Sent the push message to Google
	 * 
	 * @param string $message
	 * @return boolean
	 */
	public function SentPushMessageToGoogle($message, $msgId) {
	
		$url = 'https://android.googleapis.com/gcm/send';
		
		$appId = FrontIndexController::GetRequest("app_id");
		if(strlen($appId) == 0) {
			return false;
		}
		
		$max = 1000;
		$index = 0;
		$pmModel = new PushMessagesModel();
		$pmSentModel = new PushMessagesSentoModel();
		$modelUsrs = new GcmUsersModel();
		$totalPages = $modelUsrs->GetTotalPages($max);
		
		$modelApps = new AppsModel();
		$objApp = $modelApps->GetObjectWithAppId($appId);
		
		$hasSent = false;
		
		$arrSerialize = Array();
		$totalSucc = 0;
		$totalFailed = 0;
	
		for($i = 0; $i < $totalPages; $i++) {
	
			$index = $i * $max;
			$devices = $modelUsrs->GetListWithAppId($index, $max, $appId);
			$registrationIds = Array();
			
			foreach($devices as $device) {
				$registrationIds[] = $device->gcm_regid;
			}
				
			$fields = array(
					'registration_ids' => $registrationIds,
					'data' => array("message" => $message),
			);
				
			$headers = array(
					'Authorization: key=' . AppConfig::GetInstance()->get("m_GoogleApiKey"),
					'Content-Type: application/json'
			);
				
			$ch = curl_init();
				
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('Connection failed: ' . curl_error($ch));
			}
			curl_close($ch);
				
			$retRespJson = json_decode($result);
	
			print_r($retRespJson);
			
			$totalSucc += $retRespJson->success;
			$totalFailed += $retRespJson->failure;
			
			foreach($devices as $device) {
				$pmSentModel->AddLog($msgId, $device->gcm_id);
			}

			$arrSerialize[] = $retRespJson;
				
			$hasSent = true;
		}
		
		$objMsg = new PushMessagesTable();
		$objMsg->sento_google = 1;
		$objMsg->app_id = $objApp->id;
		$objMsg->successfull = $totalSucc;
		$objMsg->failed = $totalFailed;
		$objMsg->message = $message;
		$objMsg->create_by = $_SESSION["USER_ID"];
		$objMsg->google_response = serialize($arrSerialize);
		$pmModel->UpdateObject($objMsg, Array("msg_id" => $msgId));
		
		return $hasSent;
	}
	
	
	/**
	 * Search the data for specific records. Very simple.
	 *
	 * @return string
	 */
	public function SearchTask() {

		parent::CheckSecurity();

		if(! Acl::GetInstance()->AllowReadAccess()) {
			return "";
		}
		
		$qSearch = FrontIndexController::GetRequest("q");
		$objPag = new Pagination();
		$this->GetListIndexAndLimit($objPag->index, $objPag->limit);
		$objPag->listObjects = $this->mModel->GetListSearch($qSearch, $objPag->index, $objPag->limit);
		$objPag->totalPages = $this->mModel->GetTotalPagesSearch($qSearch, $objPag->totalRecords, $objPag->limit);
		$snippet = $this->GetListViewWithPagination($objPag);
		return $this->GetParsedContent($snippet);
	}


	/**
	 * Get the view of a specific record
	 *
	 * @return string
	 */
	public function ViewTask () {

		parent::CheckSecurity();

		if(! Acl::GetInstance()->AllowReadAccess()) {
			return "";
		}
		
		$id = $this->GetUrlObjectId();
		$objVw = $this->mModel->GetObject($id);
		$arrObj = $this->ConvertObjectToArray($objVw);
		$snippet = $this->GetSnippet($this->mTemplateCfg["View"], $arrObj);
		return $this->GetParsedContent($snippet);
	}

	/**
	 * Validate input text via Ajax request
	 *
	 * @return string	JSON format
	 */
	public function ValidateTask() {

		$this->CheckSecurity(false); // No redirect if not logged in

		$column = FrontIndexController::GetRequest("column");
		$columnType = FrontIndexController::GetRequest("columntype");
		$value = FrontIndexController::GetRequest("value");
		
		if(! Acl::GetInstance()->AllowReadAccess()) {
			return $this->GetResponseJsonValidation($column, $columnType, STATUS_CODE_ERROR, "");;
		}

		switch($columnType) {
			case "required":
				return $this->ValidateRequiredValue($column, $columnType, $value);
			case "unique":
				return $this->ValidateUniqueValue($this->mModel, $column, $columnType, $value);
		}
		return $this->GetResponseJsonValidation($column, $columnType, STATUS_CODE_OK, "");
	}

	/**
	 * Validate input text via Ajax request
	 *
	 * @return string	JSON format
	 */
	public function ValidateChangeTask() {

		$this->CheckSecurity(false); // No redirect if not logged in

		$column = FrontIndexController::GetRequest("column");
		$columnType = FrontIndexController::GetRequest("columntype");
		$value = FrontIndexController::GetRequest("value");
		$recordId = FrontIndexController::GetRequest("recordid");

		if(! Acl::GetInstance()->AllowReadAccess()) {
			return $this->GetResponseJsonValidation($column, $columnType, STATUS_CODE_ERROR, "");;
		}
		
		switch($columnType) {
			case "required":
				return $this->ValidateRequiredValue($column, $columnType, $value);
			case "unique":
				return $this->ValidateUniqueValue($this->mModel, $column, $columnType, $value, "group_id", $recordId);
				break;
		}
		return $this->GetResponseJsonValidation($column, $columnType, STATUS_CODE_OK, "");
	}


	/**
	 * Check the form for a last time
	 *
	 * @return string
	 */
	public function ValidateFormTask() {

		$this->CheckSecurity(false); // No redirect if not logged in

		if(! Acl::GetInstance()->AllowWriteAccess()) {
			return $this->GetResponseJsonValidation("", "", STATUS_CODE_ERROR, "");;
		}
		
		$statusCode = STATUS_CODE_OK;
		$retMsg = "";

		if(! $this->CheckFormAdd()) {

			$statusCode = STATUS_CODE_ERROR;
			$retMsg = "{MSG_FORM_CONTAINS_ERRORS}";
		}
		return $this->GetResponseJsonValidation("", "", $statusCode, $retMsg);
	}

	/**
	 * Check the form for a last time
	 *
	 * @return string
	 */
	public function ValidateFormEditTask() {

		$this->CheckSecurity(false); // No redirect if not logged in

		if(! Acl::GetInstance()->AllowWriteAccess()) {
			return $this->GetResponseJsonValidation("", "", STATUS_CODE_ERROR, "");;
		}
		
		$statusCode = STATUS_CODE_OK;
		$retMsg = "";
		if(! $this->CheckFormEdit()) {

			$statusCode = STATUS_CODE_ERROR;
			$retMsg = "{MSG_FORM_CONTAINS_ERRORS}";
		}
		return $this->GetResponseJsonValidation("", "", $statusCode, $retMsg);
	}


}