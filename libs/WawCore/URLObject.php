<?php
/**
 * This is the class which holds the URI values.
 * These values we need to tell the system what to do!
 * 
 * @author trang
 *
 */
class URLObject extends Object
{
	/**
	 * This var tells which controller we need to use
	 * @var object
	 */
	private $mController = "";
	
	/**
	 * This var tell which function to execute
	 * @var object
	 */
	private $mTask = "";
	
	/**
	 * If the url contains a list, we also need a index
	 * @var int
	 */
	private $mIndex = 0;
	
	/**
	 * If the url contains a list, we als need a limit
	 * @var int
	 */
	private $mLimit = 10;
	
	/**
	 * If we want to retrieve a record we need the unique ID
	 * @var int
	 */
	private $mId = -1;
		
	/**
	 * Constructor
	 */
	function __construct() {
		
	}
	
	/**
	 * Get the controller
	 * @return object
	 */
	public function GetController() {
		return $this->mController;
	}

	/**
	 * Get the task
	 * @return object
	 */
	public function GetTask() {
		return $this->mTask;
	}
	
	/**
	 * Get the list index
	 * @return number
	 */
	public function GetIndex() {
		return $this->mIndex;
	}
	
	/**
	 * Get the list limit
	 * @return number
	 */
	public function GetLimit() {
		return $this->mLimit;
	}
	
	/**
	 * Get the unique ID
	 * @return number
	 */
	public function GetId() {
		return $this->mId;
	}
	
	/**
	 * Set the controller
	 * @param string $value
	 */
	public function SetController($value) {
		
		if($this->mController != $value) {
			$this->mController = ucfirst(strtolower($value));
		}
	}
	
	/**
	 * Set the controller task
	 * @param string $value
	 */
	public function SetTask($value) {
	
		if($this->mTask != $value) {
			$this->mTask = ucfirst(strtolower($value));
		}
	}

	/**
	 * Set the list index
	 * @param int $value
	 */
	public function SetIndex($value) {
	
		if($this->mIndex != $value) {
			$this->mIndex = (int) $value;
		}
	}

	/**
	 * Set the list limit
	 * @param int $value
	 */
	public function SetLimit($value) {
	
		if($this->mLimit != $value) {
			$this->mLimit = (int) $value;
		}
	}

	/**
	 * Set the record ID
	 * @param int $value
	 */
	public function SetId($value) {
	
		if($this->mId != $value) {
			$this->mId = (int) $value;
		}
	}	
}