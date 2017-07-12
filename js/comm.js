$(document).ready(function() {
	// make sure dom is ready before processing

	function check_dup()
	{
		
		// something was entered into contact form
		
		// get mode/band/contact values at time of key press
		var mode = $("#mode").val();
		var band = $("#band").val();
		var contact = $("#contact").val();
		var gota = $('#gota').prop('checked')?1:0;
		
		// query "check_dupe.php" for existing records
		$.post("check_dupe.php", { "mode": mode, "band": band, "contact": contact.toUpperCase(), "gota": gota}, function(data){

			// "data" contains response from "check_dupe.php"	
			var data = jQuery.parseJSON(data);
			var response = data.response;
			
			
			// if a duplicate record is found show box
			if(response) $("#contact").css("background-color","#FF6666");
			else $("#contact").css("background-color","#99FF99");
			
            // if a duplicate record is found show box
            // if(response) $("#body").css("background-color","#F6CED8");
            // else $("#body").css("background-color","#FFFFFF");

		});
	}
	
	$("#mode").click(function() { check_dup(); });
	$("#band").click(function() { check_dup(); });
	$("#gota").click(function() { check_dup(); });
	$("#contact").keyup(function() { check_dup(); });
	
	// kill background color
	$(".a_button").click(function() { $("#contact").css("background-color","#99FF99"); } );
	//$(".a_button").click(function() { $("#body").css("background-color","white"); } );
	
	
	/////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////
	
	
	function load_contact_table()
	{
		// loads contact table into div id "Table" a.k.a $("#Table");
		// this function returns "true" if table was loaded
		
		// this is the url that is responsible for returning the data table
		var url = "table.php?limit=10&can_delete=1";
		
		// do not cache
		$.ajaxSetup({ cache: false });
	
		// get data contents and load into table
		$.get(url,function(data) {
		
			$("#Table").html(data).fadeIn("slow");
			
			return true;
		
		});
		
		
	}
	
	//----------------------------------------------------
	// when page loads - load contact table
	load_contact_table();
	// when page loads - get record count
	var last_record_count = 0;
	$.get("count.php", function(data) {
			
		var data = jQuery.parseJSON(data);
		last_record_count = data.response;
		//console.log(last_record_count);
	});
	//----------------------------------------------------
	
	
	// check record count every 5,000 ms (5 seconds)
	setInterval(function() {
	
		// do not cache
		$.ajaxSetup({ cache: false });
		
		// check count
		$.get("count.php", function(data) {
			
			var data = jQuery.parseJSON(data);
			
			// see if count differs from before
			if(data.response != last_record_count)
			{
				// update table
				load_contact_table();
				
				// set new last_record_count
				last_record_count = data.response
				
				// log change
				//console.log("changed");
			}
			else
			{
				//console.log("no change");
			}
			
			
		});
	
	}, 5000);
	
	// if the submit button is pressed, reload the contact table
	//$(".a_button").click(function() { load_contact_table(); });
	
});
