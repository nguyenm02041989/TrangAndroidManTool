<?php

/**
 * This is the base model to handle all simple database actions.
 * 
 * @author trang
 *
 */
class BaseModel extends Object
{
	/**
	 * The table which this model is assigned to.
	 * @var string
	 */
	protected $mTableName;
	
	public function __construct($tableName)
	{
		$this->mTableName = $tableName;
	}
	
	public function FetchObject($cols = array(), $where = array())
	{
		$res = $this->FetchObjects($cols, $where, '', '', 1, 0);
		if(sizeof($res) > 0) {
			return $res[0];
		}
		return NULL;
	}
	
	/**
	 * Function to build the where statement.
	 * @param string $where
	 * @return string
	 */
	protected function GetWhereQuery($where) {
		
		$qWhere = "";
		if(sizeof($where) > 0 && is_array($where)) {
				
			$arrWhere = Array();
			foreach($where as $key => $val) {
				if(strlen($val) > 0) {
					$arrWhere[] = $key . " = '" . addslashes($val) . "'";
				}
			}
			$qWhere = "WHERE " . implode(" AND ", $arrWhere);
		}
		return $qWhere;
	}
	
	/**
	 * Fetch the total amount of records in a table
	 * @param string $tableName
	 * @return	Object 	Example: $object->total
	 */
	public function FetchTotalRows() {
		
		$db = Database::GetDB();
		return $db->FetchTotalRows($this->mTableName)->total;
	}
	
	/**
	 * Just simple calc how many pages you need for the list
	 * @param int $totalRows
	 * @param number $maxPerPage
	 * @return number
	 */
	public function GetTotalPages($maxPerPage = 10) {
		
		$totalRows = $this->FetchTotalRows();
		$total = floor($totalRows / $maxPerPage) + ($totalRows % $maxPerPage < $maxPerPage ? 1 : 0);
		return $total;
	}

	/**
	 * Just count the amount of values in a specific table column.
	 * @param string $column
	 * @param string $value
	 * @return number
	 */
	public function CountValuesInColumn($column, $value, $recordCol = "", $recordId = "") {
		
		if(strlen($recordCol) == 0) {
			$query = "SELECT Count(*) AS total FROM " . $this->mTableName . " WHERE $column = '$value' LIMIT 1;";
		}
		else {
			$query = "SELECT Count(*) AS total FROM " . $this->mTableName . " WHERE $column = '$value' AND $recordCol <> '$recordId' LIMIT 1;";
		}
		
		$objects = $this->SelectWithQuery($query);
		if(sizeof($objects) > 0) {
			return $objects[0]->total;
		}
		return 0;
	}
	
	/**
	 * Check if a value is unique in the table
	 *
	 * @param string $column
	 * @param string $value
	 * @return boolean
	 */
	public function IsValueUniqueWithTableObj ($tableObj, $column, $value, $recordCol = "", $recordId = "") {
		
		if(!is_object($tableObj)) {
			
			echo "ERROR: IsValueUniqueWithTableObj tableObj is not een Object";
			exit();
		}
		
		$inTable = false;
		
		foreach($tableObj as $prop => $val) {
				
			if($prop == $column) {
				$inTable = true;
			}
		}
		
		if(!$inTable) {
				
			echo "ERROR: IsValueUniqueWithTableObj column does not exists";
			exit();
		}
		
		$count = $this->CountValuesInColumn($column, $value, $recordCol, $recordId);
		
		if($count > 0) {
			return false;
		}
		return true;
	}	
	
		
	/**
	 * Fetch a list of records
	 * @param array $cols	Array("col1", "col2")
	 * @param array $where	Array("col1" => "1", "col2" => 2)
	 * @param string $orderCol	col1
	 * @param string $orderBy	ASC or DESC
	 * @param number $limit		
	 * @param number $index
	 */
	public function FetchObjects($cols = array(), $where = array(), 
			$orderCol = '', $orderBy = "ASC", $limit = 10, $index = 0) {
		
		$db = Database::GetDB();
		
		$selCols = "*";
		$qOBy = "";
		$qLimit = "";
		
		if(sizeof($cols) > 0) {			
			$selCols = implode(",".$this->mTableName.".", $cols);
		}

		$qWhere = $this->GetWhereQuery($where);
		
		if(strlen($orderCol) > 0) {			
			$qOBy = sprintf("ORDER BY %s %s", $orderCol, $orderBy);
		}
		
		if($limit > 0) {
			$qLimit = sprintf("LIMIT %s, %s", $index, $limit);
		}
		
		$query = sprintf("SELECT %s FROM %s %s %s %s", 
														$selCols, 
														$this->mTableName, 
														$qWhere, 
														$qOBy, 
														$qLimit);
		
		return $db->FetchObjects($query);
	}
	
	/**
	 * Insert new record
	 * @param Object $obj
	 * @return number	Last inserted ID
	 */
	public function Insert($obj) {
		
		if(is_object($obj)) {
		
			$db = Database::GetDB();
			
			$cols = Array();
			$values = Array();
			$now = date("Y-m-d H:i:s");
			
			foreach($obj as $prop => $value) {
				
				if($prop == "date_create") {
					$cols[] = $prop;
					$values[] = "'" . $now . "'";
				}
				else if($prop == "date_edit") {
					$cols[] = $prop;
					$values[] = "'" . $now . "'";
				}
				else {
					$cols[] = $this->mTableName . "." . $prop;
					$values[] = "'" . $value . "'";
				}
			}
			
			$qCols = implode(",", $cols);
			$qVals = implode(",", $values);
			
			$query = sprintf("INSERT INTO %s (%s) VALUES (%s);", 
																$this->mTableName,
																$qCols,
																$qVals
																);
			return $db->Insert($query);
		}
		return -1;
	}
	
	/**
	 * Update a records directly by specifying the columns
	 * 
	 * @param Array $columns	Array("col1" => "1", "col2" => 2)
	 * @param Array $where		Array("col1" => "1", "col2" => 2)
	 * @return number			total affected
	 */
	public function UpdateColumns($columns, $where) {
		
		if(sizeof($where) > 0 && is_array($where)) {
			
			if(! $this->RowExists($where)) {
				return 0;
			}
				
			$db = Database::GetDB();
			$now = date("Y-m-d H:i:s");
			$values = Array();
			
			foreach($columns as $column => $value) {
				$values[] = $column . "='" . addslashes($value) . "'";
			}
			$values[] = "date_edit = '" . $now . "'";
			$qVals = implode(",", $values);
			$qWhere = $this->GetWhereQuery($where);
			
			$query = sprintf("UPDATE %s SET %s %s;",
					$this->mTableName,
					$qVals,
					$qWhere
			);
			return $db->Update($query);
		}
		return 0;
	}
	
	/**
	 * Update a record via an object
	 * 
	 * @param Object $obj	$object->col1
	 * @param Array $where	Array("col1" => "1", "col2" => 2)
	 * @return number		total affected
	 */
	public function Update($obj, $where) {
		
		if(sizeof($where) > 0 && is_array($where)) {
			
			if(! $this->RowExists($where)) {
				return 0;
			}
			
			$db = Database::GetDB();
			$now = date("Y-m-d H:i:s");
			$values = Array();
			
			foreach($obj as $prop => $value) {

				if($prop == "password") {
					
					if(strlen($value) > 0) {
						$values[] = $prop . "='" . addslashes($value) . "'";
					}
				}
				else if($prop == "date_create") {
				
				}
				else if($prop == "date_edit") {
					$values[] = $prop . "='" . $now . "'";
				}
				else {
					
					$values[] = $this->mTableName . "." . $prop . "='" . addslashes($value) . "'";
				}				
			}			
			$qVals = implode(",", $values);
			
			$qWhere = $this->GetWhereQuery($where);		
						
			$query = sprintf("UPDATE %s SET %s %s;", 
													$this->mTableName,
													$qVals,
													$qWhere
					);
			return $db->Update($query);
		}
		return 0;
	}
	
	/**
	 * Copy objects.
	 *
	 * @param object $sourceObj
	 * @param object $targetObj
	 */
	public function CopyObjects($sourceObj, &$targetObj) {
	
		foreach($sourceObj as $prop => $val) {
			
			$targetObj->set($prop, $val);
		}
	}	
	
	/**
	 * Delete a record
	 * @param Array $where	Array("col1" => "1", "col2" => 2)
	 * @return number		Total affected
	 */
	public function Delete($where = Array()) {
		
		if(sizeof($where) > 0 && is_array($where)) {
				
			if(! $this->RowExists($where)) {
				return 0;
			}

			$db = Database::GetDB();
			
			$qWhere = $this->GetWhereQuery($where);
			
			$query = sprintf("DELETE FROM %s %s;",
					$this->mTableName,
					$qWhere
			);
			return $db->Delete($query);
				
		}
		return 0;
	}
	
	/**
	 * Check if a row exists
	 * @param Array $where	Array("col1" => "1", "col2" => 2)
	 * @param Array $excludes	Tell which ids are not included
	 * @return boolean	Return true on success
	 */
	public function RowExists($where = Array(), $exludes = Array()) {
		
		
		if(sizeof($where) > 0 && is_array($where)) {
				
			$db = Database::GetDB();
				
			$qWhere = $this->GetWhereQuery($where);

			$qExl = "";
			if(sizeof($where) > 0 && is_array($where) 
				&& sizeof($exludes) > 0 && is_array($exludes)) {
			
				$arrExc = Array();
				foreach($exludes as $key => $val) {
					if(strlen($val) > 0) {
						$arrExc[] = $key . " <> '" . addslashes($val) . "'";
					}
				}
				$qExl = " AND " . implode(" AND ", $arrExc);
			}
			
			$query = sprintf("SELECT * FROM %s %s %s LIMIT 1;",
					$this->mTableName,
					$qWhere,
					$qExl
			);
				
			$rows = $db->FetchAssocOne($query);
			
			if(sizeof($rows) > 0 && is_array($rows)) {
				return true;
			}
			return false;
		}
		
	}
	
	/**
	 * Plain query statement.
	 * @param string $query
	 */
	public function SelectWithQuery($query) {
		
		$db = Database::GetDB();
		return $db->FetchObjects($query);
	}
	
	/**
	 * Get property
	 * 
	 * @param string $property
	 * @return string | Object
	 */
	public function get($property){
	
		if (property_exists($this, $property)) {
			return $this->$property;
		}
		return null;
	}
	
	/**
	 * Set a property
	 * @param string $property
	 * @param string $value
	 * @return BaseModel
	 */
	public function set($property, $value){
	
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
		return $this;
	}	
	
	/**
	 * Write a log
	 *
	 * @param string $msg
	 * @param object $object
	 */
	public function WriteLog($msg, $object) {
	
		$message = $msg . ". " . (is_object($object) ? serialize($object) : "");
	
		$logModel = new SystemLogModel();
		$logModel->AddLog($_SESSION["USER_ID"], $_SERVER["REMOTE_ADDR"], $message);
	}	
}