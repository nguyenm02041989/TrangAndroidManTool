<h2>{ADD} {APPS}</h2>
<form action="<?= ViewController::GetUrl("AddSave") ?>" method="post" id="frmadd">
	<div class="formseperator"></div>
	<div class="col-label">{APP_ID} *</div>
	<div class="col-input">
		<input type="text" name="app_id" id="app_id" class="typeinp" value="{OBJECT_APP_ID}" maxlength="50" />
	</div>
	<div class="col-error" id="err-name"></div>
	<div class="clear"></div>
	<div class="col-label">{NAME} *</div>
	<div class="col-input">
		<input type="text" name="description" id="description" class="typeinp" value="{OBJECT_DESCRIPTION}" maxlength="150" />
	</div>
	<div class="col-error" id="err-name"></div>
	<div class="clear"></div>
	<div class="col-label"></div>
	<div class="col-input">
		<span class="error" id="errformcheck"></span>
	</div>
	<div class="clear"></div>
	<div class="formseperator"></div>
	<div class="col-label"></div>
	<div class="col-input">
		<input type="button" value="{ADD}" class="typebtn" id="btnSend" />
	</div>
	<div class="clear"></div>
</form>
<script type="text/javascript">
<!--
var sR = "<?= ViewController::GetUrl("Validate") ?>";
BindCheckTextInput("app_id", "app_id", "unique", sR);
BindCheckTextInput("description", "description", "required", sR);
BindForm("frmadd", "btnSend", "<?= ViewController::GetUrl("ValidateForm") ?>", "errformcheck");
//-->
</script>