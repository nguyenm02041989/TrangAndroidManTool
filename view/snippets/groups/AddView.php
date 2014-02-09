<h2>{ADD} {GROUP}</h2>
<form action="<?= ViewController::GetUrl("AddSave") ?>" method="post" id="frmadd">
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
			<option value="0">{ACCESS_TO_ALL}</option>
			<option value="1">{ACCESS_CUSTOM}</option>
		</select>
	</div>
	<div class="col-error" id="err-security_level"></div>
	<div class="clear"></div>	
	<div class="col-label"></div>
	<div class="col-input">
		* {REQUIRED!} <span class="error" id="errformcheck"></span>
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
BindCheckTextInput("name", "name", "unique", sR);
BindForm("frmadd", "btnSend", "<?= ViewController::GetUrl("ValidateForm") ?>", "errformcheck");
//-->
</script>
