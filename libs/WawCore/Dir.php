<?php 

class Dir extends Object
{
	
	public static function GetFileNames($root) {
	
		$files = array ()
	
		;
	
		if (is_dir ( $root )) {
				
			$d = @dir ( $root );
				
			while ( false !== ($entry = $d->read ()) ) {
				if ($entry != '.' && $entry != '..') {
					$node = $root . '/' . $entry;
					if (is_file ( $node )) {
	
						$baseName = basename ( $entry );
						$files [ ] = $baseName;
					}
				}
			}
			$d->close ();
		}
		sort ( $files );
		return $files;
	}
	
}
?>