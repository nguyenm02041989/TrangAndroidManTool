<h2>{EDIT} {GROUP}</h2>
<form action="<?= ViewController::GetUrl("EditSave") ?>" method="post" id="frmedit">
	<div class="formseperator"></div>
	<div class="col-label">{NAME} *</div>
	<div class="col-input">
		<input type="text" name="name" id="name" class="typeinp" value="{OBJECT_NAME}" maxlength="50" />
	</div>
	<div class="col-error" id="err-name"></div>
	<div class="clear"></div>
	<div class="col-label">{SECURITY_LEVEL}</div>
	<div class="col-input">
		<select name="security_level">
			<option value="0" <? echo (ViewController::$TemplateVars["object_security_level"] == 0 ? 'selected="selected"' : "") ?>>{ACCESS_TO_ALL}</option>
			<option value="1" <? echo (ViewController::$TemplateVars["object_security_level"] == 1 ? 'selected="selected"' : "") ?>>{ACCESS_CUSTOM}</option>
		</select>
	</div>
	<div class="col-error" id="err-security_level"></div>
	<div class="clear"></div>	
	<div class="formseperator"></div>
	<p><span class="title-sep">{DATE}</span></p>
	<div class="col-label">{CREATE_BY}</div>
	<div class="col-input">
		{OBJECT_NAME_CREATOR} | {OBJECT_DATE_CREATE}
	</div>
	<div class="clear"></div>
	<div class="col-label">{MODIFIED_BY}</div>
	<div class="col-input">
		{OBJECT_NAME_MODIFIER} | {OBJECT_DATE_EDIT}
	</div>
	<div class="clear"></div>
	<div class="col-label"></div>
	<div class="col-input">
		* {REQUIRED!} <span class="error" id="errformcheck"></span>
	</div>
	<div class="clear"></div>
	<div class="formseperator"></div>
	<div class="col-input">
		<input type="button" value="{CHANGE}" class="typebtn" id="btnSend" />
		<?if(Acl::GetInstance()->AllowRemoveAccess()) {?>
		<input type="button" value="{DELETE}" class="typebtn" id="btnDelete" />
		<?}?>
	</div>
	<div class="clear"></div>
	<input type="hidden" name="group_id" name="group_id" value="{OBJECT_GROUP_ID}" />
</form>
<script type="text/javascript">
<!--
var sR = "<?= ViewController::GetUrl("ValidateChange") ?>";
BindCheckTextInput("name", "name", "unique", sR, "{OBJECT_GROUP_ID}");
BindForm("frmedit", "btnSend", "<?= ViewController::GetUrl("ValidateFormEdit") ?>", "errformcheck");
<?if(Acl::GetInstance()->AllowRemoveAccess()) {?>
$("#btnDelete").bind("click", function(){
	var r = confirm("{CONFIRM_DELETE}");
	if(r){
		window.location.href = "<?= ViewController::GetUrl("Delete") ?>/{OBJECT_GROUP_ID}";
	}
});
<?}?>
//-->
</script>
