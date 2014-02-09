<?php
require_once './model/GroupsModel.php';

/**
 * The groups controller manages the system groups
 *
 * @author trang
 *
 */
class GroupsController extends BaseController
						implements IController
{
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->mModel = new GroupsModel();
		
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
				"PathSnippets" => "snippets/groups/",
				"PaginationView" => "PaginationView",
				);
		
		$this->mUrls = Array(
					"Add" => "/groups/add",
					"AddSave" => "/groups/addsave",
					"AddSuccess" => "/groups/addsuccess",
					"EditSave" => "/groups/editsave",
					"Edit" => "/groups/edit",
					"EditSuccess" => "/groups/editsuccess",
					"View" => "/groups/view",
					"Delete" => "/groups/delete",
					"Error" => "/groups/error",
					"List" => "/groups/list",
					"Search" => "/groups/search/",
					"Validate" => "/groups/validate",
					"ValidateChange" => "/groups/validatechange",
					"ValidateForm" => "/groups/validateform",
					"ValidateFormEdit" => "/groups/validateformedit",
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
	
		$obj = new GroupsTable();
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
	 * Task to do when succesfully added
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
	
		$name = FrontIndexController::GetRequest("name");
		if(strlen($name) >= 50 || strlen($name) == 0) {
			return false;
		}
		return true;
	}
	
	/**
	 * Check the Add form
	 *
	 * @return boolean
	 */
	public function CheckFormAdd () {
	
		if(! $this->CheckForm()) {
			return false;
		}
	
		$name = FrontIndexController::GetRequest("name");
		if(!$this->IsValueUnique($this->mModel, "name", $name)) {
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
	
		if(! $this->CheckForm()) {
			return false;
		}
		
		$id = FrontIndexController::GetRequest("group_id");
		
		$name = FrontIndexController::GetRequest("name");
		if(!$this->IsValueUnique($this->mModel, "name", $name, "group_id", $id)) {
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
		$this->mModel->DeleteById($id);
		
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
	
		$id = (int) FrontIndexController::GetRequest("group_id");

		if($this->CheckFormEdit ()) {
			
			$this->mModel->UpdateObject($obj, Array("group_id" => $id));
			parent::RedirectTo($this->mUrls["EditSuccess"] . "/" . $id);
 		}
		else {
	
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
	 * Handles errors
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

		$name = FrontIndexController::GetRequest("name");
		$secLevel = FrontIndexController::GetRequest("security_level");
		
		$obj = new GroupsTable();
		$obj->name = $name;
		$obj->security_level = (int) $secLevel;
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