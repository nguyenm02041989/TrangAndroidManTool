<?php
require_once './model/SystemUsersModel.php';
require_once './model/GroupsModel.php';
require_once './model/LanguagesModel.php';

/**
 * The FrontIndexController handles all client request
 *
 * @author trang
 *
 */
class UsersController 	extends BaseController
						implements IController
{

	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->mModel = new SystemUsersModel();
		
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
				"PathSnippets" => "snippets/users/",
				"PaginationView" => "PaginationView",
				);
		
		$this->mUrls = Array(
					"Add" => "/users/add",
					"AddSave" => "/users/addsave",
					"AddSuccess" => "/users/addsuccess",
					"Edit" => "/users/edit",
					"EditSave" => "/users/editsave",
					"EditSuccess" => "/users/editsuccess",
					"View" => "/users/view",
					"Delete" => "/users/delete",
					"Error" => "/users/error",
					"List" => "/users/list",
					"Search" => "/users/search/",
					"Error" => "/users/error",
					"Validate" => "/users/validate",
					"ValidatePass" => "/users/validatepass",
					"ValidateChange" => "/users/validatechange",				
					"ValidateForm" => "/users/validateform",
					"ValidateFormEdit" => "/users/validateformedit",
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
		
		$obj = new SystemUsersTable();
		$arrObj = $this->ConvertObjectToArray($obj);
	
		$modelGroups = new GroupsModel();
		$objsGroups = $modelGroups->GetListAll();
		$arrObj["list_groups"] = $objsGroups;
		
		$modLang = new LanguagesModel();
		$objsLang = $modLang->GetListAll();
		$arrObj["list_languages"] = $objsLang;
		
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
		$objVw->email = Encryption::Decrypt($objVw->email);
		
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
	
		$userName = FrontIndexController::GetRequest("username");
		if(strlen($userName) >= 50 || strlen($userName) == 0) {
			return false;
		}
	
		$email = FrontIndexController::GetRequest("email");
		if(strlen($email) >= 150 || strlen($email) == 0) {
			return false;
		}
	
		$usrId = FrontIndexController::GetRequest("user_id");
		$pass1 = FrontIndexController::GetRequest("password");
		$pass2 = FrontIndexController::GetRequest("passwordrepeat");
	
		if(strlen($usrId) == 0) {
			if(strlen($pass1) >= 100 || strlen($pass1) == 0 || $pass1 != $pass2) {
				return false;
			}
		}
	
		$firstName = FrontIndexController::GetRequest("firstname");
		if(strlen($firstName) >= 40 || strlen($firstName) == 0) {
			return false;
		}
	
		$middleName = FrontIndexController::GetRequest("middlename");
		if(strlen($middleName) >= 20) {
			return false;
		}
	
		$lastName = FrontIndexController::GetRequest("lastname");
		if(strlen($lastName) >= 50 || strlen($lastName) == 0) {
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
	
		$userName = FrontIndexController::GetRequest("username");
		if(!$this->IsValueUnique($this->mModel, "username", $userName)) {
			return false;
		}
	
		$email = FrontIndexController::GetRequest("email");
		if(!$this->IsEmailUnique($this->mModel, "email", $email)) {
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
	
		$recordId = FrontIndexController::GetRequest("recordid");
		if(strlen($recordId) > 0) {
			$recordId = (int) $recordId;
		}
		else {
			$recordId = (int) FrontIndexController::GetRequest("user_id");
		}
	
		$userName = FrontIndexController::GetRequest("username");
		if(!$this->mModel->IsUserUniqueForSession($userName, $recordId)) {
			return false;
		}
	
		$email = FrontIndexController::GetRequest("email");
	
		if(!Check::Email($email)) {
			return false;
		}
	
		$valEnc = Encryption::Encrypt($email);
		if(! $this->mModel->IsEmailUniqueForSessionUser($valEnc, $recordId)) {
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
		$objVw->email = Encryption::Decrypt($objVw->email);
		
		$arrObj = $this->ConvertObjectToArray($objVw);
		$modelGroups = new GroupsModel();
		$objsGroups = $modelGroups->GetListAll();
		$arrObj["list_groups"] = $objsGroups;
		
		$modLang = new LanguagesModel();
		$objsLang = $modLang->GetListAll();
		$arrObj["list_languages"] = $objsLang;
		
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
	
		$userId = (int) FrontIndexController::GetRequest("user_id");
	
		if($this->CheckFormEdit ()) {
				
			$this->mModel->UpdateUser($obj, Array("user_id" => $userId));
			parent::RedirectTo($this->mUrls["EditSuccess"] . "/" . $userId);
		}
		else {
	
			$viewCtrl = ViewController::GetInstance();
			$arrObj = $this->ConvertObjectToArray($obj);
			$arrObj["object_user_id"] = $user_id;
				
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
		$objVw->email = Encryption::Decrypt($objVw->email);
		
		// Load data
		$arrObj = $this->ConvertObjectToArray($objVw);
		
		$snippet = $this->GetSnippet($this->mTemplateCfg["EditSuccess"], $arrObj);
		return $this->GetParsedContent($snippet);
	}
		
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
	 * @return SystemUsersTable
	 */
	public function MapRequestDataToObject() {

		$userName = FrontIndexController::GetRequest("username");
		$email = FrontIndexController::GetRequest("email");
		$pass1 = FrontIndexController::GetRequest("password");
		$firstName = FrontIndexController::GetRequest("firstname");
		$middleName = FrontIndexController::GetRequest("middlename");
		$lastName = FrontIndexController::GetRequest("lastname");
		$groupId = (int) FrontIndexController::GetRequest("group_id");
		$langId = (int) FrontIndexController::GetRequest("lang_id");
		
		$obj = new SystemUsersTable();
		$obj->username = $userName;
		$obj->firstname = $firstName;
		$obj->middlename = $middleName;
		$obj->lastname = $lastName;		
		$obj->password = $pass1;
		$obj->email = $email;
		$obj->group_id = $groupId;
		$obj->lang_id = $langId;
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

		$objVw->email = Encryption::Decrypt($objVw->email);
		
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
			case "email":
				return $this->ValidateEmailValue($this->mModel, $column, $columnType, $value);
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
	
		switch($columnType) {
			case "required":
				return $this->ValidateRequiredValue($column, $columnType, $value);
			case "unique":
				if($column == "username") {
					return $this->ValidateUsername($column, $columnType, $value);
				}
				else {
					return $this->ValidateUniqueValue($this->mModel, $column, $columnType, $value);
				}
			case "email":
				return $this->ValidateEmailForSessionUser($column, $columnType, $value);
		}
		return $this->GetResponseJsonValidation($column, $columnType, STATUS_CODE_OK, "");
	}	
	
	/**
	 * Check if the username is unique. The user kan save his own username!
	 * @param string $column
	 * @param string $columnType
	 * @param string $value
	 * @return string
	 */
	public function ValidateUsername($column, $columnType, $value) {

		$recordId = (int) FrontIndexController::GetRequest("recordid");
				
		if($this->mModel->IsUserUniqueForSession($value, $recordId)) {
			return $this->GetResponseJsonValidation($column, $columnType, STATUS_CODE_OK, "");
		}
		else {
			return $this->GetResponseJsonValidation($column, $columnType, STATUS_CODE_ERROR, "{MSG_FIELD_EXISTS}");
		}
	}
	
	public function ValidateEmailForSessionUser($column, $columnType, $value) {

		$statusCode = STATUS_CODE_OK;
		$retMsg = "";
		
		if(!Check::Email($value)) {
			
			$statusCode = STATUS_CODE_ERROR;
			$retMsg = "{MSG_EMAIL_INVALID}";
			return $this->GetResponseJsonValidation($column, $columnType, $statusCode, $retMsg);
		}
		
		$recordId = (int) FrontIndexController::GetRequest("recordid");
		$valEnc = Encryption::Encrypt($value);
		if(! $this->mModel->IsEmailUniqueForSessionUser($valEnc, $recordId)) {
		
			$statusCode = STATUS_CODE_ERROR;
			$retMsg = "{MSG_EMAIL_EXISTS}";
		}
		return $this->GetResponseJsonValidation($column, $columnType, $statusCode, $retMsg);
	}

	/**
	 * Validate two passwords
	 *
	 * @return string
	 */
	public function ValidatePassTask() {

		$this->CheckSecurity(false); // No redirect if not logged in

		$p1 = FrontIndexController::GetRequest("p1");
		$p2 = FrontIndexController::GetRequest("p2");

		$statusCode = STATUS_CODE_OK;
		$retMsg = "";

		if($p1 != $p2) {

			$statusCode = STATUS_CODE_ERROR;
			$retMsg = "{MSG_PASSWORD_NOTSAME}";
		}

		return $this->GetResponseJsonValidation("", "", $statusCode, $retMsg);
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