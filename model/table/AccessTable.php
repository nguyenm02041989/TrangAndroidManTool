<?php

class AccessTable extends Object
{
	private $id;
	public $controller_id;
	public $group_id;
	public $write;
	public $remove;
	public $read;
	public $create_by;
	public $modified_by;
	public $date_create;
	public $date_edit;
	
	public function __construct() {
		
	}
	
}