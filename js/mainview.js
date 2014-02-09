var BindCheckTextInput = function(elmId, colName, fieldType, url, recordId) {

	var rId = (recordId != null ? recordId : "");
	var col = colName;
	var fType = fieldType;
	var urlReq = url;
	$("#" + elmId).bind("blur", function() {

		var u = urlReq + "/?column="+col+"&columntype="+fType+"&value="+$("#" + elmId).val()+"&recordid="+rId;
		var jqxhr = $.ajax(u).done(function(msg) {
			var resp = jQuery.parseJSON(msg);
			
			if(resp.response.status == "ERROR") {
				$("#err-"+col).html(resp.response.message);
				$("#"+col).attr("class", "typeinp-error");
			}
			else {
				$("#err-"+col).html("");
				$("#"+col).attr("class", "typeinp");				
			}
			
		}).fail(function() {
		}).always(function() {
		});
	});
};

var BindCheckPasswordInput = function(elmId1, elmId2, url) {

	var col1 = elmId1;
	var col2 = elmId2;	
	var urlReq = url;
	$("#" + elmId2).bind("blur", function() {
		
		var elm = $("#" + elmId1);
		var u = urlReq + "/?p1="+$("#" + col1).val()+"&p2="+$("#" + col2).val();
		var jqxhr = $.ajax(u).done(function(msg) {
			
			var resp = jQuery.parseJSON(msg);
			
			if(resp.response.status == "ERROR") {
				$("#err-"+col1).html(resp.response.message);
				$("#"+col1).attr("class", "typeinp-error");
				$("#err-"+col2).html(resp.response.message);
				$("#"+col2).attr("class", "typeinp-error");
			}
			else {
				$("#err-"+col1).html("");
				$("#"+col1).attr("class", "typeinp");				
				$("#err-"+col2).html("");
				$("#"+col2).attr("class", "typeinp");				
			}
			
		}).fail(function() {
		}).always(function() {
		});			
	});
};

var BindForm = function(frmId, btnId, url, errorTarget) {
	
	$("#"+frmId).submit(function(e)
	{
	    var postData = $(this).serializeArray();
	    var formURL = url;
	    $.ajax(
	    {
	        url : formURL,
	        type: "POST",
	        data : postData,
	        success:function(data, textStatus, jqXHR) 
	        {
	        	var resp = jQuery.parseJSON(data);
	        	if(resp.response.status == "ERROR") {
	        		
	        		$("#"+errorTarget).html(resp.response.message);
	        	}
	        	else {
		        	document.getElementById(frmId).submit();	        		
	        	}	        	
	        },
	        error: function(jqXHR, textStatus, errorThrown) 
	        {
	        }
	    });
	    e.preventDefault();
	});
	
	$("#"+btnId).bind("click", function() {
		$("#"+frmId).submit();
	});
};