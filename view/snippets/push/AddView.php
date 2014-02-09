<h2>{SEND_PUSH_MESSAGES}</h2>
<form action="<?= ViewController::GetUrl("AddSave") ?>" method="post" id="frmadd">
	<div class="col-label">{MESSAGE}<br/>
	{MAX_1000_CHARS}
	</div>
	<div class="col-input">
		<textarea rows="20" cols="70" name="message" class="textarea" id="textmessage"></textarea>
	</div>
	<div class="clear"></div>
	<div class="error" id="charstogo"></div>
	<div class="col-label"></div>
	<div class="col-input">
		<input type="button" value="{SEND}" class="typebtn" id="btnSend" />
	</div>
	<div class="clear"></div>
	<input type="hidden" name="app_id" value="{OBJECT_APP_ID}" />
</form>

<script type="text/javascript">
$("#btnSend").bind("click", function(){
	if($("#textmessage").val().length == 0) {
		alert("{MSG_FIELD_EMPTY}");
	}
	else {
		var r=confirm("{CONFIRM_SUBMIT}");
		if(r){
			$("#frmadd").submit();
		}
	}
});

$('#textmessage').keypress(function(e) {
	max = 1000;
    if (e.which < 0x20) {
        // e.which < 0x20, then it's not a printable character
        // e.which === 0 - Not a character
        return;     // Do nothing
    }
    if (this.value.length == max) {
        e.preventDefault();
    } else if (this.value.length > max) {
        // Maximum exceeded
        this.value = this.value.substring(0, max);
    }
});

var CheckTextarea = function() {
	var msg = $("#textmessage").val();
	var toGo = 1000 - msg.length;
	if(toGo == 1) {
		$("#charstogo").text(toGo + " " + "{CHARACTER}");
	}
	else {
		$("#charstogo").text(toGo + " " + "{CHARACTERS}");
	}
};
setInterval(CheckTextarea,500);
</script>