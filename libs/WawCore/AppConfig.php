<?php

class AppConfig extends Object
				implements ISingleton
{
	/**
	 * The static instance
	 * @var Object
	 */
	protected static $mInstance;
	
	// Database members
	protected $m_DBHost;
	protected $m_DBUser;
	protected $m_DBPassword;
	protected $m_DBDatabase;
	protected $m_GoogleApiKey;
	
	// Encryption
	protected $m_EncryptionKey;

	protected function __construct(){


	}
	
	/**
	 * Get the static instance
	 * @return Singleton instance
	 */
	public static function GetInstance(){
	
		if(static::$mInstance == NULL) {
			$className = get_called_class();
			static::$mInstance = new $className();
		}
		return static::$mInstance;
	}	

}