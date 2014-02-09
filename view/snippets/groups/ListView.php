<div id="searchbox">
{SNIPPET_SEARCH}
</div>
<h2>{GROUPS}</h2>
{SNIPPET_PAGINATION}
<div class="rowheader">
	<div class="rowcol200px headerlabel">{NAME}</div>
	<div class="rowcoldate headerlabel">{DATE_EDIT}</div>
</div>
<div class="clear"></div>
<div class="line"></div>
<?php 
$allowWrite = Acl::GetInstance()->AllowWriteAccess();
foreach(ViewController::$TemplateVars["list_rows"] as $obj) {?>

<div class="row">
	<div class="rowcol200px"><?php echo $obj->name; ?></div>
	<div class="rowcoldate"><?php echo $obj->date_edit; ?></div>
	<div class="rowcolaction"><a href="<?= ViewController::GetUrl("View") . "/" . $obj->group_id ?>">{VIEW}</a></div>
	<? if($allowWrite) {?>
	<div class="rowcolaction"><a href="<?= ViewController::GetUrl("Edit") . "/" . $obj->group_id ?>">{EDIT}</a></div>
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
