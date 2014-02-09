<?php
require_once './model/GcmUsersModel.php';
require_once './model/PushMessagesModel.php';
require_once './model/PushMessagesSentoModel.php';

/**
 * The GcmController is controller which handles all tasks for
 * communicating with Google Cloud Messaging devices and servers.
 * 
 * @author trang
 * @version 1.0
 */
class GcmController 	extends BaseController
						implements IController
{

	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->mModel = new GcmUsersModel();

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
				"PathSnippets" => "snippets/gcm/",
				"PaginationView" => "PaginationView",
		);

		$this->mUrls = Array(
				"Add" => "/gcm/add",
				"AddSave" => "/gcm/addsave",
				"AddSuccess" => "/gcm/addsuccess",
				"Edit" => "/gcm/edit",
				"EditSave" => "/gcm/editsave",
				"EditSuccess" => "/gcm/editsuccess",
				"View" => "/gcm/view",
				"Delete" => "/gcm/delete",
				"Error" => "/gcm/error",
				"List" => "/gcm/list",
				"Search" => "/gcm/search/",
				"Validate" => "/gcm/validate",
				"ValidateChange" => "/gcm/validatechange",
				"ValidateForm" => "/gcm/validateform",
				"ValidateFormEdit" => "/gcm/validateformedit",
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

		$obj = new GcmUsersTable();
		$arrObj = $this->ConvertObjectToArray($obj);

		$snippet = $this->GetSnippet($this->mTemplateCfg["Add"], $arrObj);
		return $this->GetParsedContent($snippet);
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

		$obj = $this->MapRequestDataToObject();

		if($this->CheckFormAdd ()) {

			$id = $this->mModel->AddObject($obj);
			parent::RedirectTo($this->mUrls["AddSuccess"] . "/" . $id);
		}
		else {

			$arrObj = $this->ConvertObjectToArray($obj);
			$snippet = $this->GetSnippet($this->mTemplateCfg["Add"], $arrObj);
			return $this->GetParsedContent($snippet);
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


		return true;
	}

	/**
	 * Check the Edit form
	 *
	 * @return boolean
	 */
	public function CheckFormEdit () {

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
		$obj = $this->mModel->GetObject($id);

		$objSrc = new GcmUsersTable();
		$objSrc->is_deleted = 1;
		$objSrc->gcm_regid = $obj->gcm_regid;
		$objSrc->unique_device_id = $obj->unique_device_id;
		$objSrc->app_id = $obj->app_id;
		$objSrc->name = $obj->name;
		$objSrc->email = $obj->email;
		$objSrc->date_create = $obj->date_create;
		$objSrc->date_edit = $obj->date_edit;
		$this->mModel->UpdateObject($objSrc, Array("gcm_id" => $id));

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

		$obj = $this->MapRequestDataToObject();

		$id = (int) FrontIndexController::GetRequest("id");

		if($this->CheckFormEdit()) {

			$this->mModel->UpdateObject($obj, Array("id" => $id));
			parent::RedirectTo($this->mUrls["EditSuccess"] . "/" . $id);
		}
		else {

			$viewCtrl = ViewController::GetInstance();
			$arrObj = $this->ConvertObjectToArray($obj);
			$arrObj["object_group_id"] = $id;

			$snippet = $this->GetSnippet($this->mTemplateCfg["Edit"], $arrObj);
			return $this->GetParsedContent($snippet);
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

		$id = $this->GetUrlObjectId();
		$objVw = $this->mModel->GetObject($id);
		
		// Load data
		$arrObj = $this->ConvertObjectToArray($objVw);
		
		$snippet = $this->GetSnippet($this->mTemplateCfg["EditSuccess"], $arrObj);
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
	 * Check if the device is already registered
	 *
	 * @param string $regId
	 * @return boolean
	 */
	public function HasNewRegistration($regId) {
	
		return $this->mModel->RegistrationExists($regId);
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
	 * Get the list
	 *
	 * @see BaseController::ListTask()
	 */
	public function ListDeleteTask () {
	
		parent::CheckSecurity();
	
		if(! Acl::GetInstance()->AllowReadAccess()) {
			return "";
		}
		
		$this->mUrls["List"] = "/gcm/listdelete";
		$this->mTemplateCfg["List"] = "ListDeletedView";
	
		$objPag = new Pagination();
		$this->GetListIndexAndLimit($objPag->index, $objPag->limit);
		$objPag->listObjects = $this->mModel->GetListDelete($objPag->index, $objPag->limit);
		$objPag->totalPages = $this->mModel->GetTotalPagesDeleted($objPag->limit);
		$objPag->totalRecords = $this->mModel->FetchTotalRowsDeleted();
	
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
	 * Register the device, so it can be notified
	 *
	 * @param string $regId
	 * @return boolean
	 */
	protected function RegisterDevice($regId) {
	
		$newRegId = $this->mModel->RegisterUserDevice($regId);
	
		if($newRegId != -1) {
			return true;
		}
		return false;
	}
	
	/**
	 * Register the device into the system. This public API function.
	 *
	 *
	 * @return string
	 */
	public function RegisterDeviceTask() {
	
		// This is the registration ID from Google
		$gcmId = FrontIndexController::GetRequest("regid");
		if(strlen($gcmId) > 255) {
			$gcmId = substr($gcmId, 0, 255);
		}
	
		// This is the App ID. A developer can have multiple Apps. So with this code, we know
		// which App we have to call.
		$appId = FrontIndexController::GetRequest("appid");
		if(strlen($appId) > 50) {
			$appId = substr($appId, 0, 50);
		}
	
		// This is the unique ID from the device. It can be an IMEI code or something else.
		$appUnId = FrontIndexController::GetRequest("uid");
		if(strlen($appUnId) > 150) {
			$appUnId = substr($appUnId, 0, 150);
		}
	
		$out = "";
		
		if(strlen($gcmId) > 0 && ! $this->HasNewRegistration($gcmId)) {
				
			if($this->mModel->RegisterUserDevice($gcmId, $appId, $appUnId) != -1) {
	
				$this->GetResponseJson(RESPONSE_SUCCESS, $out);
			}
			else {
	
				$this->GetResponseJson(RESPONSE_FAILURE, $out);
			}
		}
		else {
				
			$this->GetResponseJson(RESPONSE_FAILURE, $out);
		}
		return $out;
	}
	
	
	/**
	 * Reactive the device
	 *
	 * @param string $regId
	 */
	public function ReActivateUserDevice($regId) {
	
		$this->mModel->ReActivateUserDevice($regId);
	}
	
	/**
	 * Unregister the device
	 *
	 * @param string $regId
	 */
	public function UnRegisterDevice($regId) {
	
		$this->mModel->RemoveUserDevice($regId);
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