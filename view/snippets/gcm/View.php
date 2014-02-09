<h2>{VIEW} {DEVICE_REGISTRATIONS}</h2>
<div class="formseperator"></div>
<div class="col-label">{GCM_REGID}</div>
<div class="col-input">
	{OBJECT_GCM_REGID}
</div>
<div class="clear"></div>
<div class="col-label">{APP_ID}</div>
<div class="col-input">
	{OBJECT_APP_ID}
</div>
<div class="clear"></div>
<div class="col-label">{SIGNED_OUT}</div>
<div class="col-input">
	<input type="checkbox" disabled="disabled" 
	<?php echo (ViewController::$TemplateVars["object_is_deleted"] == 1 ? 'checked="checked"' : ""); ?>/>
</div>
<div class="clear"></div>
<div class="formseperator"></div>
<div class="col-label">{DATE_CREATE}</div>
<div class="col-input">
	{OBJECT_DATE_CREATE}
</div>
<div class="clear"></div>
<div class="col-label">{DATE_EDIT}</div>
<div class="col-input">
	{OBJECT_DATE_EDIT}
</div>
<div class="clear"></div>
<div class="formseperator"></div>
<div class="col-input">
	<input type="button" value="{LIST}" class="typebtn" id="btnList" />
	<?if(Acl::GetInstance()->AllowWriteAccess()) {?>
	<?if(ViewController::$TemplateVars["object_is_deleted"] != 1) {?>
	<input type="button" value="{SIGN_OUT}" class="typebtn" id="btnDelete" />	
	<?}?>
	<?}?>
</div>
<div class="clear"></div>

<script type="text/javascript">
<!--
$("#btnList").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("List") ?>";
});
<?if(Acl::GetInstance()->AllowWriteAccess()) {?>
$("#btnDelete").bind("click", function(){

	var r = confirm("{CONFIRM_DELETE}");
	if(r){
		window.location.href = "<?= ViewController::GetUrl("Delete") ?>/{OBJECT_GCM_ID}";
	}
});
<?}?>
//-->
</script>