<h2>{EDIT} {USER}</h2>
<form action="<?= ViewController::GetUrl("EditSave") ?>" method="post" id="frmedit">
	<div class="formseperator"></div>
	<p><span class="title-sep">{LOGIN}</span></p>
	<div class="col-label">{USERNAME} *</div>
	<div class="col-input">
		<input type="text" name="username" id="username" class="typeinp" value="{OBJECT_USERNAME}" maxlength="50" />
	</div>
	<div class="col-error" id="err-username"></div>
	<div class="clear"></div>
	<div class="col-label">{EMAIL} *</div>
	<div class="col-input">
		<input type="text" name="email" id="email" class="typeinp" value="{OBJECT_EMAIL}" maxlength="150" />
	</div>
	<div class="col-error" id="err-email"></div>
	<div class="clear"></div>
	<div class="col-label">{PASSWORD} *</div>
	<div class="col-input">
		<input type="password" name="password" id="password" class="typeinp" value="" maxlength="100" />
	</div>
	<div class="col-error" id="err-password"></div>
	<div class="clear"></div>
	<div class="col-label">{PASSWORD_REPEAT} *</div>
	<div class="col-input">
		<input type="password" name="passwordrepeat" id="passwordrepeat" class="typeinp" value="" maxlength="100" />
	</div>
	<div class="col-error" id="err-passwordrepeat"></div>
	<div class="clear"></div>
	<div class="formseperator"></div>
	<p><span class="title-sep">{PERMISSIONS}</span></p>
	<div class="col-label">{GROUP}</div>
	<div class="col-input">
		<select name="group_id">
			<? foreach(ViewController::$TemplateVars["list_groups"] as $objGrp) {?>
			<option value="<?= $objGrp->group_id; ?>"
			<?php echo ViewController::$TemplateVars["object_group_id"] == $objGrp->group_id ? 'selected="selected"' : "" ?>
			><?= $objGrp->name; ?></option>
			<?}?>
		</select>
	
	</div>
	<div class="col-error" id="err-group_id"></div>
	<div class="clear"></div>
	<div class="col-label">{LANGUAGE}</div>
	<div class="col-input">
		<select name="lang_id">
			<? foreach(ViewController::$TemplateVars["list_languages"] as $objLang) {?>
			<option value="<?= $objLang->lang_id; ?>"
			<?php echo ViewController::$TemplateVars["object_lang_id"] == $objLang->lang_id ? 'selected="selected"' : "" ?>
			><?= $objLang->description; ?></option>
			<?}?>
		</select>
	</div>
	<div class="col-error" id="err-lang_id"></div>
	<div class="clear"></div>
	
	<div class="formseperator"></div>
	<p><span class="title-sep">{PERSONAL_INFO}</span></p>
	<div class="col-label">{FIRSTNAME} *</div>
	<div class="col-input">
		<input type="text" name="firstname" id="firstname" class="typeinp" value="{OBJECT_FIRSTNAME}" maxlength="40" />
	</div>
	<div class="col-error" id="err-firstname"></div>
	<div class="clear"></div>
	<div class="col-label">{MIDDLENAME}</div>
	<div class="col-input">
		<input type="text" name="middlename" id="middlename" class="typeinp" value="{OBJECT_MIDDLENAME}" maxlength="20" />
	</div>
	<div class="col-error" id="err-middlename"></div>
	<div class="clear"></div>
	<div class="col-label">{LASTNAME} *</div>
	<div class="col-input">
		<input type="text" name="lastname" id="lastname" class="typeinp" value="{OBJECT_LASTNAME}" maxlength="50" />
	</div>
	<div class="col-error" id="err-lastname"></div>
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
	<div class="col-label"></div>
	<div class="col-input">
		<input type="button" value="{CHANGE}" class="typebtn" id="btnSend" />
		<? if(ViewController::$TemplateVars["object_user_id"] != $_SESSION["USER_ID"]){ ?>
			<?if(Acl::GetInstance()->AllowRemoveAccess()) {?>
			<input type="button" value="{DELETE}" class="typebtn" id="btnDelete" />
			<?}?>
		<?}?>
	</div>
	<div class="clear"></div>
	<input type="hidden" name="user_id" name="user_id" value="{OBJECT_USER_ID}" />
</form>
<script type="text/javascript">
<!--
var sR = "<?= ViewController::GetUrl("ValidateChange") ?>";
BindCheckTextInput("username", "username", "unique", sR, "{OBJECT_USER_ID}");
BindCheckTextInput("email", "email", "email", sR, "{OBJECT_USER_ID}");
BindCheckTextInput("firstname", "firstname", "required", sR);
BindCheckTextInput("lastname", "lastname", "required", sR);
var sR2 = "<?= ViewController::GetUrl("ValidatePass") ?>";
BindCheckPasswordInput("password", "passwordrepeat", sR2);
BindForm("frmedit", "btnSend", "<?= ViewController::GetUrl("ValidateFormEdit") ?>", "errformcheck");

<? if(ViewController::$TemplateVars["object_user_id"] != $_SESSION["USER_ID"]){ ?>
<?if(Acl::GetInstance()->AllowRemoveAccess()) {?>
$("#btnDelete").bind("click", function(){
	var r = confirm("{CONFIRM_DELETE}");
	if(r){
		window.location.href = "<?= ViewController::GetUrl("Delete") ?>/{OBJECT_USER_ID}";
	}
});
<?}?>
<?}?>
//-->
</script>
