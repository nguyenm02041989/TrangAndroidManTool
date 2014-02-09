<h2>{ADD} {ACCESS}</h2>
<form action="<?= ViewController::GetUrl("AddSave") ?>" method="post" id="frmadd">
	<div class="formseperator"></div>
	<div class="col-label">{MODULE}</div>
	<div class="col-input">
		<select name="controller_id">
			<? foreach(ViewController::$TemplateVars["list_controllers"] as $objCtrl) {?>
			<option value="<?= $objCtrl->id; ?>"><?= $objCtrl->alias; ?></option>
			<?}?>
		</select>
	</div>
	<div class="col-error" id="err-controller_id"></div>
	<div class="clear"></div>	
	<div class="col-label">{GROUP}</div>
	<div class="col-input">
		<select name="group_id">
			<? foreach(ViewController::$TemplateVars["list_groups"] as $objGrp) {?>
			<option value="<?= $objGrp->group_id; ?>"><?= $objGrp->name; ?></option>
			<?}?>
		</select>
	</div>
	<div class="col-error" id="err-group_id"></div>
	<div class="clear"></div>	
	<div class="col-label">{READ}</div>
	<div class="col-input">
		<input type="checkbox" name="read" id="read" value="1" />
	</div>
	<div class="col-error" id="err-read"></div>
	<div class="clear"></div>
	<div class="col-label">{WRITE}</div>
	<div class="col-input">
		<input type="checkbox" name="write" id="write" value="1" />
	</div>
	<div class="col-error" id="err-write"></div>
	<div class="clear"></div>
	<div class="col-label">{DELETE}</div>
	<div class="col-input">
		<input type="checkbox" name="remove" id="remove" value="1" />
	</div>
	<div class="col-error" id="err-remove"></div>
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
BindForm("frmadd", "btnSend", "<?= ViewController::GetUrl("ValidateForm") ?>", "errformcheck");
//-->
</script>