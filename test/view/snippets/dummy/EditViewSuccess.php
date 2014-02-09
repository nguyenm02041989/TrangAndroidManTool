<h2>{EDIT} {DUMMY}</h2>
<br/>
<p>{MESSAGE_UPDATE_SUCCESS}</p>
<div class="formseperator"></div>
<div class="col-label">{APP_ID} *</div>
<div class="col-input">
	{OBJECT_APP_ID}
</div>
<div class="col-error" id="err-name"></div>
<div class="clear"></div>
<div class="col-label">{NAME} *</div>
<div class="col-input">
	{OBJECT_DESCRIPTION}
</div>
<div class="col-error" id="err-name"></div>
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
	window.location.href = "<?= ViewController::GetUrl("Edit") ?>/{OBJECT_ID}";
});
//-->
</script>
