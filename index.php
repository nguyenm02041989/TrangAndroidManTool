<?php
session_start();
require_once 'require.php';

$_ViewCtrl = ViewController::GetInstance();
$_LoginCtrl = new LoginController();

$_FrontCtrl = FrontIndexController::GetInstance();
$_URLObject = $_FrontCtrl->ReadRequestUri();

$_Output = "";

if(!isset($_SESSION["LOGIN_STATUS"])) {
	$_SESSION["LOGIN_STATUS"] = 0;
}

if(strlen($_URLObject->GetController()) == 0) {
	
	if($_LoginCtrl->CheckLoginStatus()) {
		
		FrontIndexController::RedirectTo("/apps/list");
	}
	else {
		$_Output = $_LoginCtrl->GetViewLogin($_ViewCtrl);
	}
}
else {

	$className = $_URLObject->GetController() . "Controller";
	$fileName = $className . ".php";
	$filePath = "controller/" . $fileName;
	
	if(file_exists($filePath)) {
		
		require_once $filePath;

		$task = $_URLObject->GetTask(). "Task";
		
		if(method_exists($className, $task)) {
			
			$reflectionMethod = new ReflectionMethod($className, $task);
			$_Output = $reflectionMethod->invoke(new $className());
		}
		else {
			
			// Get Not found page
			$_Output = $_FrontCtrl->GetViewNotFound($_ViewCtrl);
		}
	}
	else {
		
		// Get Not found page
		$_Output = $_FrontCtrl->GetViewNotFound($_ViewCtrl);
	}
}

echo $_Output;