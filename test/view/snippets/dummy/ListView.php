<div id="searchbox">
{SNIPPET_SEARCH}
</div>
<h2>{DUMMY}</h2>
{SNIPPET_PAGINATION}
<div class="rowheader">
	<div class="rowcol200px headerlabel">{ID}</div>
	<div class="rowcol200px headerlabel">{NAME}</div>
	<div class="rowcolcheckbox headerlabel"></div>
</div>
<div class="clear"></div>
<div class="line"></div>
<?php 
$allowWrite = Acl::GetInstance()->AllowWriteAccess();

foreach(ViewController::$TemplateVars["list_rows"] as $obj) {?>

<div class="row">
	<div class="rowcol200px"><?php echo $obj->id; ?></div>
	<div class="rowcol200px"><?php echo $obj->description; ?></div>
	<? if($allowWrite) {?>
	<div class="rowcolaction"><a href="<?= ViewController::GetUrl("Edit") . "/" . $obj->id ?>">{EDIT}</a></div>
	<?}?>
</div>
<div class="clear"></div>
<div class="line"></div>
<?}?>
<div class="col-input">
	<? if($allowWrite) {?>
		<input type="button" value="{ADD}" class="typebtn" id="btnAdd" />
	<?}?>
</div>
<div class="clear"></div>
<? if($allowWrite) {?>
<script type="text/javascript">
<!--
$("#btnAdd").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("Add") ?>";
});
//-->
</script>
<?}?>
