<?php 

function writeFile($file, $content) {

	if(!file_exists($file)) {
	
		$handle = fopen($file, "w+");
		fwrite($handle, $content);
		fclose($handle);
		echo "file created: " . $file . '<br/>';
	}
}

function GetFileNames($root) {

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

$task = isset($_POST["task"]) ? $_POST["task"] : "";
$controller = isset($_POST["controller"]) ? $_POST["controller"] : "";
$table = isset($_POST["table"]) ? $_POST["table"] : "";

if(strlen($task) > 0 && strlen($controller) > 0) {
	
	$controller = strtolower($controller);
	$className = ucfirst($controller);
	
	$cntController = file_get_contents("controller/DummyController.php");
	$newCntContr = str_replace("Dummy", $className, $cntController);
	$newCntContr = str_replace("/dummy/", "/$controller/", $newCntContr);
	
	$fileName = $className . "Controller.php";
	$pathFile = "controller/" . $fileName;
	writeFile($pathFile, $newCntContr);
	
	$cnt = file_get_contents("model/DummyModel.php");
	$newCnt = str_replace("Dummy", $className, $cnt);
	$fileName = $className . "Model.php";
	$pathFile = "model/" . $fileName;
	writeFile($pathFile, $newCnt);
	
	$cnt = file_get_contents("model/table/DummyTable.php");
	$newCnt = str_replace("Dummy", $className, $cnt);
	$newCnt = str_replace("dummy_table", $table, $newCnt);
	$fileName = $className . "Table.php";
	$pathFile = "model/table/" . $fileName;
	writeFile($pathFile, $newCnt);
	
	if(! file_exists("view/snippets/" . $controller)) {
		mkdir("view/snippets/" . $controller);
	}
	
	$files = GetFileNames("view/snippets/dummy/");
	
	foreach($files as $file) {
		
		$cnt = file_get_contents("view/snippets/dummy/" . $file);
		$newCnt = str_replace("{DUMMY}", "{" . strtoupper($className) . "}", $cnt);
		$pathFile = "view/snippets/" . $controller . "/" . $file;
		writeFile($pathFile, $newCnt);
	}
	
	exit();
}
else {

	?>
<p>Give a name for the controller and submit the form. It creates the files and places it in the directory.</p>	
<form action="make.php" method="post" id="frmadd">

	<table>
		<tr>
			<td>Controller:</td>
			<td><input type="text" name="controller" value="" maxlength="50" /></td>
		</tr>
		<tr>
			<td>Table name:</td>
			<td><input type="text" name="table" value="" maxlength="50" /></td>
		</tr>
	</table>

	<br/>
	<input type="hidden" name="task" value="1" />
	<input type="submit" value="Create files" />
</form>	
	
<?php 	
}
?>