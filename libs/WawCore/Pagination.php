<?php
class Pagination extends Object
{
	public $listObjects;
	public $totalPages;
	public $urlPage;
	public $urlSearchPage;
	public $templatePagination = "snippets/PaginationView";
	public $templateList;
	public $templateSearch;
	public $index;
	public $limit;
	public $totalRecords;
	
	function __construct()
	{
		
	}
}