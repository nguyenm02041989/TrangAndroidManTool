<?php
require_once './model/AppsModel.php';
require_once './libs/WawCore/Random.php';

/**
 * The class handles all security access issues
 *
 * @author trang
 *
 */
class AppsController 	extends BaseController 
						implements IController
{

	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->mModel = new AppsModel();

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
				"PathSnippets" => "snippets/apps/",
				"PaginationView" => "PaginationView",
		);

		$this->mUrls = Array(
				"Add" => "/apps/add",
				"AddSave" => "/apps/addsave",
				"AddSuccess" => "/apps/addsuccess",
				"Edit" => "/apps/edit",
				"EditSave" => "/apps/editsave",
				"EditSuccess" => "/apps/editsuccess",
				"View" => "/apps/view",
				"Delete" => "/apps/delete",
				"Error" => "/apps/error",
				"List" => "/apps/list",
				"Search" => "/apps/search/",
				"Validate" => "/apps/validate",
				"ValidateChange" => "/apps/validatechange",
				"ValidateForm" => "/apps/validateform",
				"ValidateFormEdit" => "/apps/validateformedit",
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
		
		$obj = new AppsTable();
		$obj->app_id = Random::MakeString(15, Array(0,1,2,3,4,5,6,7,8,9));
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

		$appId = FrontIndexController::GetRequest("app_id");
		if(!$this->IsValueUnique($this->mModel, "app_id", $appId)) {
			return false;
		}
		
		$name = FrontIndexController::GetRequest("description");
		
		if(strlen($name) == 0) {
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

		$id = FrontIndexController::GetRequest("id");
		$appId = FrontIndexController::GetRequest("app_id");
		if(!$this->IsValueUnique($this->mModel, "app_id", $appId, "id", $id)) {
			return false;
		}
		
		$name = FrontIndexController::GetRequest("description");
		if(strlen($name) == 0) {
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
		if($this->mModel->DeleteById($id) != -1) {

			parent::RedirectTo($this->mUrls["List"]);
		}
		else {

			parent::RedirectTo($this->mUrls["Error"]);
		}
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
		$out = $this->GetParsedContent($snippet);
		return $out;
	}



	/**
	 * Map the request to an object
	 *
	 * @return Object
	 */
	public function MapRequestDataToObject() {

		// Check each incoming value.
		
		$appId = FrontIndexController::GetRequest("app_id");
		$name = FrontIndexController::GetRequest("description");

		if(strlen($appId) > 50) {
			$appId = substr($appId, 0, 50);
		}
		if(strlen($name) > 150) {
			$name = substr($name, 0, 150);
		}
		
		$obj = new AppsTable();
		$obj->app_id = $appId;
		$obj->description = $name;
		return $obj;
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
		
		$arrObj["object_total_messages"] = $this->mModel->GetTotalPushedMessages($objVw->id);
		$arrObj["object_total_subscriptions"] = $this->mModel->GetDeviceSubscriptions($objVw->app_id);
		
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