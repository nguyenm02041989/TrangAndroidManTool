<div id="searchbox">
{SNIPPET_SEARCH}
</div>
<h2>{ACCESS}</h2>
{SNIPPET_PAGINATION}
<div class="rowheader">
	<div class="rowcol200px headerlabel">{MODULE}</div>
	<div class="rowcol4 headerlabel">{GROUP}</div>
	<div class="rowcolcheckbox headerlabel">{READ}</div>
	<div class="rowcolcheckbox headerlabel">{WRITE}</div>
	<div class="rowcolcheckbox headerlabel">{DELETE}</div>
</div>
<div class="clear"></div>
<div class="line"></div>
<?php 
$allowWrite = Acl::GetInstance()->AllowWriteAccess();

foreach(ViewController::$TemplateVars["list_rows"] as $obj) {?>

<div class="row">
	<div class="rowcol200px"><?php echo $obj->module; ?></div>
	<div class="rowcol4"><?php echo $obj->group_name; ?></div>
	<div class="rowcolcheckbox"><input type="checkbox" <?= ($obj->read == 1 ? 'checked="checked"' : ""); ?> disabled="disabled" /></div>
	<div class="rowcolcheckbox"><input type="checkbox" <?= ($obj->write == 1 ? 'checked="checked"' : ""); ?> disabled="disabled" /></div>
	<div class="rowcolcheckbox"><input type="checkbox" <?= ($obj->remove == 1 ? 'checked="checked"' : ""); ?> disabled="disabled" /></div>
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
