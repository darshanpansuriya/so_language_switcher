function auto_save_all(){
	
	var token= getUrlVars()["token"];
	var orthotic_id = $('#hidden_ortho').val();
		var price_id_1 = $('#price_1').val();
		var price_id_2 = $('#price_2').val();
		var price_id_3 = $('#price_3').val();
		var price_id_4 = $('#price_4').val();
		var price_id_5 = $('#price_5').val();
		var price_id_6 = $('#price_6').val();
		var price_id_7 = $('#price_7').val();
		var price_id_8 = $('#price_8').val();
		var price_id_9 = $('#price_9').val();
		var price_id_10 = $('#price_10').val();
		var price_id_11 = $('#price_11').val();
		var price_id_12 = $('#price_12').val();
	
	// message to be displayed after successfull validation and posting of the
	// values
	displayMessage = 'please wait ...';
	document.getElementById("response").innerHTML = displayMessage;


	price_values = "token=" + token;
	price_values += "&orthotic_id=" + orthotic_id;
	price_values += "&price_1=" + price_id_1;
	price_values += "&price_2=" + price_id_2;
	price_values += "&price_3=" + price_id_3;
	price_values += "&price_4=" + price_id_4;
	price_values += "&price_5=" + price_id_5;
	price_values += "&price_6=" + price_id_6;
	price_values += "&price_7=" + price_id_7;
	price_values += "&price_8=" + price_id_8;	
	price_values += "&price_9=" + price_id_9;
	price_values += "&price_10=" + price_id_10;	
	price_values += "&price_11=" + price_id_11;
	price_values += "&price_12=" + price_id_12;
	price_values += "&valid-form=TRUE";
	
	
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			setTimeout(
					function() {
						displayResponse = xmlhttp.responseText;
						document.getElementById("response").innerHTML = displayResponse;
						
					}, 500);
		}
	}
	// passing the values in the controller
	xmlhttp.open("GET", "index.php?route=setting/setting/updateSettingPricing&" + price_values, true);
	xmlhttp.send();
	
		
}


function auto_save_clinics(){
	
var token= getUrlVars()["token"];
var clinic_id = $('#hidden_clinic_id').val();
var orthotic_id = $('#ortho_hidden').val();
		var price_id_1 = $('#price_1').val();
		var price_id_2 = $('#price_2').val();
		var price_id_3 = $('#price_3').val();
		var price_id_4 = $('#price_4').val();
		var price_id_5 = $('#price_5').val();
		var price_id_6 = $('#price_6').val();
		var price_id_7 = $('#price_7').val();
		var price_id_8 = $('#price_8').val();
		var price_id_9 = $('#price_9').val();
		var price_id_10 = $('#price_10').val();
		var price_id_11 = $('#price_11').val();
		var price_id_12 = $('#price_12').val();
	
	// message to be displayed after successfull validation and posting of the
    // values
	displayMessage = 'please wait ...';
	document.getElementById("response").innerHTML = displayMessage;

	price_values = "token=" + token;
	price_values += "&orthotic_id=" + orthotic_id;
	price_values += "&clinic_id="+clinic_id;
	price_values += "&price_1=" + price_id_1;
	price_values += "&price_2=" + price_id_2;
	price_values += "&price_3=" + price_id_3;
	price_values += "&price_4=" + price_id_4;
	price_values += "&price_5=" + price_id_5;
	price_values += "&price_6=" + price_id_6;
	price_values += "&price_7=" + price_id_7;
	price_values += "&price_8=" + price_id_8;	
	price_values += "&price_9=" + price_id_9;
	price_values += "&price_10=" + price_id_10;	
	price_values += "&price_11=" + price_id_11;
	price_values += "&price_12=" + price_id_12;
	price_values += "&valid-form=TRUE";
		
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			setTimeout(
					function() {
						displayResponse = xmlhttp.responseText;
						document.getElementById("response").innerHTML = displayResponse;
						
					}, 500);
		}
	}
	// passing the values in the controller
	xmlhttp.open("GET", "index.php?route=sale/clinic/updateClinicPricing&" + price_values, true);
	xmlhttp.send();	
		
}



function saveItemPricing(clinic_id,orthotic_type_id,item_id,item_price_value) {	
		
	var token= getUrlVars()["token"];
	var clinic_id = clinic_id;
	var ortho_id=orthotic_type_id;	
	var pricing_item_id= item_id;
	var pricing_item_value= item_price_value;

	
	// message to be displayed after successfull validation and posting of the
	// values
	displayMessage = 'please wait ...';
	document.getElementById("response").innerHTML = displayMessage;

	// getting the values from the user inputs and by id and loading in a
	// variable.	
	param = "token=" + token;
	param += "&clinic_id=" + clinic_id;
	param += "&ortho_id=" + ortho_id;
	param += "&pricing_item_id=" + pricing_item_id;
	param += "&pricing_item_value=" + pricing_item_value;
	param += "&valid-form=TRUE";

	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			setTimeout(
					function() {
						displayResponse = xmlhttp.responseText;
						document.getElementById("response").innerHTML = displayResponse;
						
					}, 500);
		}
	}
	// passing the values in the controller
	xmlhttp.open("GET", "index.php?route=sale/clinic/updateItemPricing&" + param, true);
	xmlhttp.send();
}

function saveItemPricingDefaults(orthotic_id,pricing_item_id,default_item_price){
	
	var token= getUrlVars()["token"];
	var ortho_id=orthotic_id;	
	var item_id= pricing_item_id;
	var item_value= default_item_price;

	
	// message to be displayed after successfull validation and posting of the
	// values
	displayMessage = 'please wait ...';
	document.getElementById("response").innerHTML = displayMessage;

	// getting the values from the user inputs and by id and loading in a
	// variable.	
	param = "token=" + token;
	param += "&ortho_id=" + ortho_id;
	param += "&item_id=" + item_id;
	param += "&item_value=" + item_value;
	param += "&valid-form=TRUE";

	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			setTimeout(
					function() {
						displayResponse = xmlhttp.responseText;
						document.getElementById("response").innerHTML = displayResponse;
						
					}, 500);
		}
	}
	// passing the values in the controller
	xmlhttp.open("GET", "index.php?route=setting/setting/updateItemPricingDefaults&" + param, true);
	xmlhttp.send();	
	
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}
