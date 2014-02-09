<?php 

/**
 * The interface IModel tells what the Data Model must implement.
 *
 * @author trang
 *
 */
interface IModel
{
	
	public function DeleteById($id);
	public function GetObject($id);
	public function GetList($index = 0, $limit = 10);
	public function GetListSearch($qSearch, $index = 0, $limit = 10);
}