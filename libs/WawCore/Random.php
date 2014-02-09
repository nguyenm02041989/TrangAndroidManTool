<?php 


class Random extends Object
{
	/**
	 * Generate an random string code
	 * 
	 * @param string $length
	 * @param Array $numb
	 * @return string
	 */
	public static function MakeString($length, $numb = Array()) {
	
		$low_alpha = range ( 'a', 'z' );
		$upp_alpha = range ( 'A', 'Z' );
		$nums = array ();
		$placenums = array ();
		$newrand = 0;
		$prevrand = - 1;
		$randStr = '';
	
		for($i = 0; $i < $length; $i ++) {
			$rnd = mt_rand ( 0, 2 );
			if ($rnd == 0) {
				$randStr .= mt_rand ( 0, 9 );
			} else
			if ($rnd == 1) {
				$randStr .= $low_alpha[mt_rand ( 0, 25 )];
			} else {
				$randStr .= $upp_alpha[mt_rand ( 0, 25 )];
			}
		}
	
		return $randStr;
	}	
}