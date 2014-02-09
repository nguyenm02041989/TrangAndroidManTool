<?php
/**
 * This class manages the links in the system. It
 * checks a link and when it doesn't find the controller and task method
 * then an error will be displayed.
 *
 * @author trang
 *
 */
class URI
{
	/**
	 * Pass into this method an url and it checks
	 * if it's correct.
	 * 
	 * @param string $url
	 * @return boolean
	 */
	public static function Get($url) {
		
		$urlSplit = explode("/", $url);
		
		$className = ucfirst(strtolower($urlSplit[1])) . "Controller";
		$fileName = $className . ".php";
		$filePath = "controller/" . $fileName;		
		
		$isError = false;
		
		if(file_exists($filePath)) {

			if(! class_exists($className)) {
				require_once $filePath;
			}
			
			$task = ucfirst(strtolower($urlSplit[2])) . "Task";
			if(method_exists($className, $task)) {
				
				return $url;
			}
			else {

				$isError = true;
			}
		}
		else {
			$isError = true;
		}
		
		if($isError) {
			echo "Class:URI f:Get ERROR URL ($url) DOESN'T EXISTS!";
			exit();
		}
		return $url;
	}
	
}