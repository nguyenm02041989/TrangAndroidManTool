<h2>{VIEW} {APPS}</h2>
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
<p><span class="title-sep">{SUBSCRIPTIONS}</span></p>
<p>{EXPLAIN_TEXT_SUBSCRIPTIONS}</p>
<div class="col-label">{TOTAL}</div>
<div class="col-input">
	{OBJECT_TOTAL_SUBSCRIPTIONS}
</div>
<div class="clear"></div>
<div class="formseperator"></div>
<p><span class="title-sep">{MESSAGES_PUSHED}</span></p>
<div class="col-label">{TOTAL}</div>
<div class="col-input"> 
	<a href="/push/search/?q={OBJECT_DESCRIPTION}">{OBJECT_TOTAL_MESSAGES}</a>
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
<input type="button" value="{LIST}" class="typebtn" id="btnList" />
<?if(Acl::GetInstance()->AllowWriteAccess()) {?>
<input type="button" value="{DELETE}" class="typebtn" id="btnDelete" />
<?}?>
<input type="button" value="{EDIT}" class="typebtn" id="btnEdit" />	
<input type="button" value="{SEND_PUSH_MESSAGES}" class="typebtnbig" id="btnSend" />	
<div class="clear"></div>
<script type="text/javascript">
<!--
$("#btnList").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("List") ?>";
});
<?if(Acl::GetInstance()->AllowWriteAccess()) {?>
$("#btnDelete").bind("click", function(){

	var r = confirm("{CONFIRM_DELETE}");
	if(r){
		window.location.href = "<?= ViewController::GetUrl("Delete") ?>/{OBJECT_ID}";
	}
});
<?}?>
$("#btnEdit").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("Edit") ?>/{OBJECT_ID}";
});
$("#btnSend").bind("click", function(){
	window.location.href = "/push/add/{OBJECT_ID}";
});
//-->
</script>