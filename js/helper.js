// cookie time
var today = new Date();
var expiry = new Date(today.getTime() + 30 * 24 * 3600 * 1000); // plus 30 days

function getCookie(name)
{	
	var re=new RegExp(name+"=([^;]+)");
	var value=re.exec(document.cookie);
	return(value!=null)?unescape(value[1]):null;
}

function setCookie(name, value)
{
	document.cookie=name + "=" + escape(value) + "; path=/; expires=" + expiry.toGMTString();
}

function storeValues(form)
{
	setCookie("call", form.call.value);
	setCookie("band", form.band.value);
	setCookie("mode", form.mode.value);
	setCookie("gota", form.gota.checked);
	return true;
}

$(document).ready(function() {
	if(call = getCookie("call")) $('#call').val(call.toUpperCase());
	if(band = getCookie("band")) $('#band').val(band);
	if(mode = getCookie("mode")) $('#mode').val(mode);
	if(gota = getCookie("gota")) {
    if(gota == 'false') { $('#gota').removeAttr('checked');  }
    else {$('#gota').prop('checked',gota);}
	}
});

// General from top file 6 23 2014

$(document).ready(function() {
	$('#myForm').ajaxForm({
		target: '#showdata',
		success: function() {
			$('#showdata').fadeIn('slow');
			$('#contact').clearFields();
			$('#class').clearFields();
			$('#section').clearFields();
			$('#comment').clearFields();
			$('#gota_mentor').clearFields();
			$('#contact').focus();
		}
	});
});

$(document).ready(function () {
	if (!$('#gota').prop('checked'))
	{
		$('#mentor').hide();
	}
	$('#gota').click(function () {
		var $this = $(this);
		if ($this.is(':checked')) {
			$('#mentor').show();
		} else {
			$('#mentor').hide();
		}
	});
});
 
function formReset()
{ 
	$('#contact').val("");
	$('#class').val("");
	$('#section').val("");
	$('#comment').val("");
	$('#contact').focus();
}

