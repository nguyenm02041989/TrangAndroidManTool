<?php
/**
 * This is the base object
 * @author trang
 *
 */
class Object
{
	/**
	 * Get the a property
	 * 
	 * @param various $property
	 * @return string | int | Array
	 */
	public function get($property){
	
		if(isset($this->$property)) {
			return $this->$property;
		}
		return "";
	}
	
	/**
	 * Set the property
	 * 
	 * @param string $property
	 * @param string | int | Array $value
	 * @return Object
	 */
	public function set($property, $value){
	
		$this->$property = $value;
		return $this;
	}
}