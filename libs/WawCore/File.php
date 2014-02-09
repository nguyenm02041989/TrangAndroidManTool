<?php 


class File extends Object
{
	
	public static function WriteFile($name, $content, $mode = 'w+') {
	
		$cp1252_map = array(
				"\xc2\x80" => "\xe2\x82\xac", /* EURO SIGN */
				"\xc2\x82" => "\xe2\x80\x9a", /* SINGLE LOW-9 QUOTATION MARK */
				"\xc2\x83" => "\xc6\x92",     /* LATIN SMALL LETTER F WITH HOOK */
				"\xc2\x84" => "\xe2\x80\x9e", /* DOUBLE LOW-9 QUOTATION MARK */
				"\xc2\x85" => "\xe2\x80\xa6", /* HORIZONTAL ELLIPSIS */
				"\xc2\x86" => "\xe2\x80\xa0", /* DAGGER */
				"\xc2\x87" => "\xe2\x80\xa1", /* DOUBLE DAGGER */
				"\xc2\x88" => "\xcb\x86",     /* MODIFIER LETTER CIRCUMFLEX ACCENT */
				"\xc2\x89" => "\xe2\x80\xb0", /* PER MILLE SIGN */
				"\xc2\x8a" => "\xc5\xa0",     /* LATIN CAPITAL LETTER S WITH CARON */
				"\xc2\x8b" => "\xe2\x80\xb9", /* SINGLE LEFT-POINTING ANGLE QUOTATION */
				"\xc2\x8c" => "\xc5\x92",     /* LATIN CAPITAL LIGATURE OE */
				"\xc2\x8e" => "\xc5\xbd",     /* LATIN CAPITAL LETTER Z WITH CARON */
				"\xc2\x91" => "\xe2\x80\x98", /* LEFT SINGLE QUOTATION MARK */
				"\xc2\x92" => "\xe2\x80\x99", /* RIGHT SINGLE QUOTATION MARK */
				"\xc2\x93" => "\xe2\x80\x9c", /* LEFT DOUBLE QUOTATION MARK */
				"\xc2\x94" => "\xe2\x80\x9d", /* RIGHT DOUBLE QUOTATION MARK */
				"\xc2\x95" => "\xe2\x80\xa2", /* BULLET */
				"\xc2\x96" => "\xe2\x80\x93", /* EN DASH */
				"\xc2\x97" => "\xe2\x80\x94", /* EM DASH */
	
				"\xc2\x98" => "\xcb\x9c",     /* SMALL TILDE */
				"\xc2\x99" => "\xe2\x84\xa2", /* TRADE MARK SIGN */
				"\xc2\x9a" => "\xc5\xa1",     /* LATIN SMALL LETTER S WITH CARON */
				"\xc2\x9b" => "\xe2\x80\xba", /* SINGLE RIGHT-POINTING ANGLE QUOTATION*/
				"\xc2\x9c" => "\xc5\x93",     /* LATIN SMALL LIGATURE OE */
				"\xc2\x9e" => "\xc5\xbe",     /* LATIN SMALL LETTER Z WITH CARON */
				"\xc2\x9f" => "\xc5\xb8"      /* LATIN CAPITAL LETTER Y WITH DIAERESIS*/
		);
		$content = strtr(utf8_encode( $content ), $cp1252_map);
		$handle = fopen($name, $mode);
		fwrite($handle, $content);
		fclose($handle);
		return true;
	}
	
}