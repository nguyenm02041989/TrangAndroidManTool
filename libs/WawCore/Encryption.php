<?php

/**
 * Encryption class. We use a RIJNDAEL synchronious encryption.
 * 
 * @author trang
 *
 */
class Encryption extends Object
{
	
	
	public static function Encrypt($plainText) {
		
		$ivSize = mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
		$iv = mcrypt_create_iv ( $ivSize, MCRYPT_RAND );
		$out = mcrypt_encrypt ( 
									MCRYPT_RIJNDAEL_256, 
									AppConfig::GetInstance()->get("m_EncryptionKey"), 
									$plainText, 
									MCRYPT_MODE_ECB,
									$iv 
								);
		return base64_encode($out);
	}

	public static function Decrypt($cipher) {
	
		$code = base64_decode($cipher);
		
		$ivSize = mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
		$iv = mcrypt_create_iv ( $ivSize, MCRYPT_RAND );
		$crypttext = mcrypt_decrypt(
										MCRYPT_RIJNDAEL_256, 
										AppConfig::GetInstance()->get("m_EncryptionKey"),
										$code, 
										MCRYPT_MODE_ECB, 
										$iv
									);
		return rtrim($crypttext, "\0");	
	}
	
}