<?php

/**
 * Base class Database to connect with the MySQL database
 * @author trang
 *
 */
class Database extends Object						
{
	/**
	 * The static instance
	 * @var Object
	 */
	protected static $mInstance;
	
	private $mAppConfig; // Config settings
	
	protected function __construct(AppConfig $AppConfig)
	{
		if(is_a($AppConfig, "AppConfig")) {
			$this->mAppConfig = $AppConfig;
		}
	}
	
	/**
	 * Get a singleton instance
	 * 
	 * @param AppConfig $AppConfig
	 */
	public static function GetInstance(AppConfig $AppConfig) {

		if(is_a($AppConfig, "AppConfig")) {
			if(static::$mInstance == NULL) {
				static::$mInstance = new Database($AppConfig);
			}
			return static::$mInstance;
		}
	}
	
	/**
	 * Get the database instance
	 */
	public static function GetDB(){
		return static::$mInstance;
	}
	
	/**
	 * Call this to connect to database
	 * 
	 * @return PDO
	 */
	public function Connect() {
		
		$dbh = NULL;
		
		try {
			
			$sqlStr = sprintf("mysql:host=%s;dbname=%s", 
					$this->mAppConfig->get("m_DBHost"), 
					$this->mAppConfig->get("m_DBDatabase"));
			
			$dbh = new PDO(	$sqlStr, 
							$this->mAppConfig->get("m_DBUser"), 
							$this->mAppConfig->get("m_DBPassword"));
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			exit();
		}
		return $dbh;
	}
		
	/**
	 * Fetch objects
	 * 
	 * @param string $query
	 * @return multitype:|NULL
	 */
	public function FetchObjects($query) {
		
		try {
			
			$dbh = $this->Connect();
			$stmt = $dbh->query($query);
			if($stmt != NULL) {
				$arrObj = $stmt->fetchAll(PDO::FETCH_OBJ);			
				$dbh = NULL;
				return $arrObj;
			}
			return NULL;
		}
		catch(PDOException $e)
		{
			
		}		
		return NULL;
	}
	
	/**
	 * Fetch one single object
	 * @param string $query
	 * @return mixed|NULL
	 */
	public function FetchObject($query) {
	
		try {
			
			$dbh = $this->Connect();
			$stmt = $dbh->query($query);
			$obj = $stmt->fetch(PDO::FETCH_OBJ);			
			$dbh = NULL;
			return $obj;
		}
		catch(PDOException $e)
		{
			
		}		
		return NULL;	
	}
	
	/**
	 * Return the total amount of records for a table.
	 * @param string $tableName
	 * @return Object $object->total
	 */
	public function FetchTotalRows($tableName) {
		
		$query = sprintf("SELECT Count(*) AS total FROM %s LIMIT 1", $tableName);
		return $this->FetchObject($query);
	}
		
	/**
	 * Fetch an associative array
	 * @param string $query
	 * @return multitype:|NULL
	 */
	public function FetchAssoc($query)
	{
		try {
				
			$dbh = $this->Connect();
			$stmt = $dbh->query($query);
			$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$dbh = NULL;
			return $arr;
		}
		catch(PDOException $e)
		{
				
		}
		return Array();		
	}
	
	/**
	 * Fetch an associative array
	 * @param string $query
	 * @return multitype:|NULL
	 */
	public function FetchAssocOne($query)
	{
		try {
	
			$dbh = $this->Connect();
			$stmt = $dbh->query($query);
			$arr = $stmt->fetch(PDO::FETCH_ASSOC);
			$dbh = NULL;
			return $arr;
		}
		catch(PDOException $e)
		{
	
		}
		return Array();
	}	
	
	/**
	 * Update database table
	 * 
	 * @param string $query
	 * @return number
	 */
	public function Update($query) {
		
		return $this->Exec($query);
	}
	
	/**
	 * Insert new record
	 * @param string $query
	 * @return number
	 */
	public function Insert($query)
	{
		
		try {
		
			$dbh = $this->Connect();
			$dbh->exec($query);
			$lastId = $dbh->lastInsertId();
			$dbh = NULL;
			return $lastId;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			exit();
		}
		return -1;
	}

	/**
	 * Delete record
	 * @param string $query
	 * @return number
	 */
	public function Delete($query)
	{
		return $this->Exec($query);
	}
	
	/**
	 * Execute. Only use internal
	 * 
	 * @param string $query
	 * @return number	Total rows affected
	 */
	private function Exec($query){
		
		try {
		
			$dbh = $this->Connect();
			$count = $dbh->exec($query);
			$dbh = NULL;
			return $count;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			exit();
		}
		return 0;		
	}
	
}