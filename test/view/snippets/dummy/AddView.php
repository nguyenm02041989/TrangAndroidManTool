<h2>{ADD} {DUMMY}</h2>
<form action="<?= ViewController::GetUrl("AddSave") ?>" method="post" id="frmadd">
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
//var sR = "<?= ViewController::GetUrl("Validate") ?>";
//BindCheckTextInput("app_id", "app_id", "unique", sR);
BindForm("frmadd", "btnSend", "<?= ViewController::GetUrl("ValidateForm") ?>", "errformcheck");
//-->
</script>