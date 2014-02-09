<h2>{VIEW} {GROUP}</h2>
<div class="col-label">{NAME}</div>
<div class="col-input">
	{OBJECT_NAME}
</div>
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
<div class="col-input">
<input type="button" value="{LIST}" class="typebtn" id="btnList" />
<?if(Acl::GetInstance()->AllowRemoveAccess()) {?>
<input type="button" value="{DELETE}" class="typebtn" id="btnDelete" />
<?}?>
<input type="button" value="{EDIT}" class="typebtn" id="btnEdit" />	
</div>
<div class="clear"></div>

<script type="text/javascript">
<!--
$("#btnList").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("List") ?>";
});
<?if(Acl::GetInstance()->AllowRemoveAccess()) {?>
$("#btnDelete").bind("click", function(){

	var r = confirm("{CONFIRM_DELETE}");
	if(r){
		window.location.href = "<?= ViewController::GetUrl("Delete") ?>/{OBJECT_GROUP_ID}";
	}
});
<?}?>
$("#btnEdit").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("Edit") ?>/{OBJECT_GROUP_ID}";
});
//-->
</script>