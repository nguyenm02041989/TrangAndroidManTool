<?php

class Check extends Object
{

	public static function Email($email) {
		
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
		if (preg_match($regex, $email)) {
			return true;	
		}
		else {
			return false;
		}
	}
}