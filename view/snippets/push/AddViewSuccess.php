<h2>{PUSH_MESSAGES}</h2>
<div class="formseperator"></div>
<p><span class="title-sep">{MESSAGE}</span></p>
<div class="col-label">
	{ID}
</div>
<div class="col-input">
	{OBJECT_MSG_ID}
</div>
<div class="clear"></div>
<div class="col-label">
	{MESSAGE}
</div>
<div class="col-input">
	{OBJECT_MESSAGE}
</div>
<div class="clear"></div>
<div class="formseperator"></div>
<p><span class="title-sep">{GOOGLE}</span></p>
<div class="col-label">
	{SENT_GOOGLE}
</div>
<div class="col-input">
	<input type="checkbox" disabled="disabled" 
	<?php echo (ViewController::$TemplateVars["object_sento_google"] == 1 ? 'checked="checked"' : ""); ?>/>
</div>
<div class="clear"></div>
<div class="col-label">{SUCCESSFULLY}</div>
<div class="col-input">
	{OBJECT_SUCCESSFULL}
</div>
<div class="clear"></div>
<div class="col-label">{FAILED}</div>
<div class="col-input">
	{OBJECT_FAILED}
</div>
<div class="clear"></div>
<div class="formseperator"></div>
<p><span class="title-sep">{DATE}</span></p>
<div class="col-label">
	{DATE_CREATE}
</div>
<div class="col-input">
	{OBJECT_DATE_CREATE}
</div>
<div class="clear"></div>
<div class="col-label">
	{DATE_EDIT}
</div>
<div class="col-input">
	{OBJECT_DATE_EDIT}
</div>
<div class="clear"></div>
<div class="formseperator"></div>
<div class="col-input">
	<input type="button" value="{LIST}" class="typebtn" id="btnList" />
	<?if(Acl::GetInstance()->AllowRemoveAccess()) {?>
	<input type="button" value="{DELETE}" class="typebtn" id="btnDelete" />
	<?}?>
</div>
<div class="clear"></div>
<script type="text/javascript">
<!--
$("#btnList").bind("click", function(){
	window.location = "<?= ViewController::GetUrl("List") ?>";
});
<?if(Acl::GetInstance()->AllowRemoveAccess()) {?>
$("#btnDelete").bind("click", function(){
	var r = confirm("{CONFIRM_DELETE}");
	if(r) {
		window.location = "<?= ViewController::GetUrl("Delete") ?>/{OBJECT_MSG_ID}";
	}
});
<?}?>
//-->
</script>
