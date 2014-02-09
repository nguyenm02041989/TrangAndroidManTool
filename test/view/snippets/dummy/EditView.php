<h2>{EDIT} {APPS}</h2>
<form action="<?= ViewController::GetUrl("EditSave") ?>" method="post" id="frmedit">



	<div class="formseperator"></div>
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
		<span class="error" id="errformcheck"></span>
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
	<input type="hidden" name="id" name="id" value="{OBJECT_ID}" />
</form>
<script type="text/javascript">
<!--
var sR = "<?= ViewController::GetUrl("ValidateChange") ?>";
//BindCheckTextInput("app_id", "app_id", "unique", sR, "{OBJECT_ID}");
BindForm("frmedit", "btnSend", "<?= ViewController::GetUrl("ValidateFormEdit") ?>", "errformcheck");
$("#btnDelete").bind("click", function(){
	var r = confirm("{CONFIRM_DELETE}");
	if(r){
		window.location.href = "<?= ViewController::GetUrl("Delete") ?>/{OBJECT_ID}";
	}
});
//-->
</script>
