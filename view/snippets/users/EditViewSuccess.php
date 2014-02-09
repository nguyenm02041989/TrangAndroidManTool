<h2>{EDIT} {USER}</h2>
<p>{MESSAGE_UPDATE_SUCCESS}</p>
<div class="formseperator"></div>
<p><span class="title-sep">{LOGIN}</span></p>
<div class="col-label">{USERNAME}</div>
<div class="col-input">
	{OBJECT_USERNAME}
</div>
<div class="clear"></div>
<div class="col-label">{EMAIL}</div>
<div class="col-input">
	{OBJECT_EMAIL}
</div>
<div class="clear"></div>
<div class="formseperator"></div>
<p><span class="title-sep">{PERMISSIONS}</span></p>
<div class="col-label">{GROUP}</div>
<div class="col-input">
	{OBJECT_GROUP_NAME}
</div>
<div class="clear"></div>
<div class="col-label">{LANGUAGE}</div>
<div class="col-input">
	{OBJECT_LANGUAGE}
</div>
<div class="clear"></div>
<div class="formseperator"></div>
<p><span class="title-sep">{PERSONAL_INFO}</span></p>
<div class="col-label">{FIRSTNAME}</div>
<div class="col-input">
	{OBJECT_FIRSTNAME}
</div>
<div class="clear"></div>
<div class="col-label">{MIDDLENAME}</div>
<div class="col-input">
	{OBJECT_MIDDLENAME}
</div>
<div class="clear"></div>
<div class="col-label">{LASTNAME}</div>
<div class="col-input">
	{OBJECT_LASTNAME}
</div>
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
<div class="formseperator"></div>
<input type="button" value="{LIST}" class="typebtn" id="btnList" />
<?if(Acl::GetInstance()->AllowWriteAccess()) {?>
<input type="button" value="{ADD}" class="typebtn" id="btnAdd" />
<?}?>
<input type="button" value="{EDIT}" class="typebtn" id="btnEdit" />	
<div class="clear"></div>
<script type="text/javascript">
<!--
$("#btnList").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("List") ?>";
});
<?if(Acl::GetInstance()->AllowWriteAccess()) {?>
$("#btnAdd").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("Add") ?>";
});
<?}?>
$("#btnEdit").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("Edit") ?>/{OBJECT_USER_ID}";
});
//-->
</script>
