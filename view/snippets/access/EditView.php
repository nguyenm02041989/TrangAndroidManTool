<h2>{EDIT} {ACCESS}</h2>
<form action="<?= ViewController::GetUrl("EditSave") ?>" method="post" id="frmedit">
	<div class="formseperator"></div>
	<div class="col-label">{MODULE}</div>
	<div class="col-input">
		<select name="controller_id">
			<? foreach(ViewController::$TemplateVars["list_controllers"] as $objCtrl) {?>
				<? if(ViewController::$TemplateVars["object_controller_id"] == $objCtrl->id) {?>
				<option value="<?= $objCtrl->id; ?>" selected="selected"><?= $objCtrl->alias; ?></option>
				<?}?>
			<?}?>
		</select>
	</div>
	<div class="col-error" id="err-controller_id"></div>
	<div class="clear"></div>	
	<div class="col-label">{GROUP}</div>
	<div class="col-input">
		<select name="group_id">
			<? foreach(ViewController::$TemplateVars["list_groups"] as $objGrp) {?>
				<? if(ViewController::$TemplateVars["object_group_id"] == $objGrp->group_id) {?>
				<option value="<?= $objGrp->group_id; ?>" selected="selected"><?= $objGrp->name; ?></option>
				<?}?>
			<?}?>
		</select>
	</div>
	<div class="col-error" id="err-group_id"></div>
	<div class="clear"></div>	
	<div class="col-label">{READ}</div>
	<div class="col-input">
		<input type="checkbox" name="read" id="read" value="1" 
		<?php echo ViewController::$TemplateVars["object_read"] == 1 ? 'checked="checked"' : "" ?>
		/>
	</div>
	<div class="col-error" id="err-read"></div>
	<div class="clear"></div>
	<div class="col-label">{WRITE}</div>
	<div class="col-input">
		<input type="checkbox" name="write" id="write" value="1" 
		<?php echo ViewController::$TemplateVars["object_write"] == 1 ? 'checked="checked"' : "" ?>
		/>
	</div>
	<div class="col-error" id="err-write"></div>
	<div class="clear"></div>
	<div class="col-label">{DELETE}</div>
	<div class="col-input">
		<input type="checkbox" name="remove" id="remove" value="1" 
		<?php echo ViewController::$TemplateVars["object_remove"] == 1 ? 'checked="checked"' : "" ?>
		/>
	</div>
	<div class="col-error" id="err-remove"></div>
	<div class="clear"></div>
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
BindForm("frmedit", "btnSend", "<?= ViewController::GetUrl("ValidateFormEdit") ?>", "errformcheck");
$("#btnDelete").bind("click", function(){
	var r = confirm("{CONFIRM_DELETE}");
	if(r){
		window.location.href = "<?= ViewController::GetUrl("Delete") ?>/{OBJECT_ID}";
	}
});
//-->
</script>
