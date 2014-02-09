<h2>{EDIT} {ACCESS}</h2>
<br/>
<p>{MESSAGE_UPDATE_SUCCESS}</p>
<div class="formseperator"></div>
<div class="col-label">{MODULE}</div>
<div class="col-input">
	{OBJECT_MODULE}
</div>
<div class="col-error" id="err-controller_id"></div>
<div class="clear"></div>	
<div class="col-label">{GROUP}</div>
<div class="col-input">
	{OBJECT_GROUP_NAME}
</div>
<div class="col-error" id="err-group_id"></div>
<div class="clear"></div>	
<div class="col-label">{READ}</div>
<div class="col-input">
	<input type="checkbox" name="read" id="read" disabled="disabled" value="1" 
	<?php echo ViewController::$TemplateVars["object_read"] == 1 ? 'checked="checked"' : "" ?>
	/>
</div>
<div class="col-error" id="err-read"></div>
<div class="clear"></div>
<div class="col-label">{WRITE}</div>
<div class="col-input">
	<input type="checkbox" name="write" id="write" disabled="disabled" value="1" 
	<?php echo ViewController::$TemplateVars["object_write"] == 1 ? 'checked="checked"' : "" ?>
	/>
</div>
<div class="col-error" id="err-write"></div>
<div class="clear"></div>
<div class="col-label">{DELETE}</div>
<div class="col-input">
	<input type="checkbox" name="remove" id="remove" disabled="disabled" value="1" 
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
<div class="formseperator"></div>
<input type="button" value="{LIST}" class="typebtn" id="btnList" />
<input type="button" value="{ADD}" class="typebtn" id="btnAdd" />
<input type="button" value="{EDIT}" class="typebtn" id="btnEdit" />
<div class="clear"></div>
<script type="text/javascript">
<!--
$("#btnList").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("List") ?>";
});
$("#btnAdd").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("Add") ?>";
});
$("#btnEdit").bind("click", function(){
	window.location.href = "<?= ViewController::GetUrl("Edit") ?>/{OBJECT_ID}";
});
//-->
</script>
