function restoreItemPricingDefaults(clinic_id){
	
		
	var token= getUrlVars()["token"];
	var c_id=clinic_id;
	
	// message to be displayed after successfull validation and posting of the
	// values
	displayMessage = 'please wait ...';
	document.getElementById("response").innerHTML = displayMessage;

	// getting the values from the user inputs and by id and loading in a
	// variable.	
	param = "token=" + token;
	param += "&c_id=" + c_id;	
	param += "&valid-form=TRUE";
	
	alert("This process may take time. \n Please be patient.");

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
						location.reload();
						
					}, 500);
		}
	}
	// passing the values in the controller
	xmlhttp.open("GET", "index.php?route=sale/clinic/itemPricingDefaults&" + param, true);
	xmlhttp.send();	
	
}

function addNewOrthoLabel(label_name)
{
	document.getElementById('save-new-button').style.display = "none";
	
	if(document.getElementById('clinic_new_label_name').value == '')
		{
			document.getElementById('clinic_new_label_name').style.border = "1px solid red";
			document.getElementById("response-div").innerHTML = "<label style='color:red;'>Enter label name</label>";
			document.getElementById('clinic_new_label_name').focus();
			document.getElementById('save-new-button').style.display = "block";
			return false;
		}
	else
		{
		document.getElementById('clinic_new_label_name').style.border = "none";
		document.getElementById('save-new-button').style.display = "none";
		
	
	var token= getUrlVars()["token"];	
	var ortho_label_name = label_name;	
	
	// message to be displayed after successfull validation and posting of the
	// values
	displayMessage = '<label>please wait ...</label>';
	document.getElementById("response-div").innerHTML = displayMessage;

	// getting the values from the user inputs and by id and loading in a
	// variable.	
	param = "token=" + token;	
	param += "&label_name=" + ortho_label_name;	
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
						document.getElementById("response-div").innerHTML = displayResponse;											
					}, 500);
		}
	}
	// passing the values in the controller
	xmlhttp.open("GET", "index.php?route=sale/clinic/addNewLabel&" + param, true);
	xmlhttp.send();
	setTimeout(
			function() {
				getLabelList();											
			}, 2000);
		}
	
}

function getLabelList()
{
	var token= getUrlVars()["token"];		
	
	// message to be displayed after successfull validation and posting of the
	// values
	displayMessage = '<label>Refreshing...</label>';
	document.getElementById("response-div").innerHTML = displayMessage;

	// getting the values from the user inputs and by id and loading in a
	// variable.	
	param = "token=" + token;	
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
						document.getElementById("response-div").innerHTML = '';	
						document.getElementById("ortho-label-name").style.display = 'block';	
						document.getElementById("new-orthotic-container").style.display = 'none';
						document.getElementById('save-new-button').style.display = "none";
						document.getElementById("clinic_orthotic_label_name").innerHTML = displayResponse;										
					}, 500);
		}
	}
	// passing the values in the controller
	xmlhttp.open("GET", "index.php?route=sale/clinic/labelList&" + param, true);
	xmlhttp.send();
	setTimeout(
			function() {
				getButtons();				
			}, 2000);
}

function getButtons()
{
	document.getElementById('add-new-button').style.display = "block";
	// document.getElementById('delete-new-button').style.display = "block";	
	// document.getElementById('rename-new-button').style.display = "block";
}

function updateOrthoLabel(label_id,label_name)
{
	document.getElementById('update-new-button').style.display = "none";
	
	var token= getUrlVars()["token"];
	var ortho_label_id = label_id;
	var ortho_label_name = label_name;	
	
	// message to be displayed after successfull validation and posting of the
	// values
	displayMessage = '<label>please wait ...</label>';
	document.getElementById("response-div").innerHTML = displayMessage;

	// getting the values from the user inputs and by id and loading in a
	// variable.	
	param = "token=" + token;
	param += "&label_id=" + ortho_label_id;	
	param += "&label_name=" + ortho_label_name;	
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
						document.getElementById('update-new-button').style.display = "none";
						document.getElementById("response-div").innerHTML = displayResponse;										
					}, 500);
		}
	}
	// passing the values in the controller
	xmlhttp.open("GET", "index.php?route=sale/clinic/updateLabel&" + param, true);
	xmlhttp.send();
	setTimeout(
			function() {
				getLabelList();											
			}, 2000);
}

function deleteOrtho(label_id)
{	
	var token= getUrlVars()["token"];
	var ortho_label_id = label_id;
	
	// message to be displayed after successfull validation and posting of the
	// values
	displayMessage = '<label>please wait ...</label>';
	document.getElementById("response-div").innerHTML = displayMessage;

	// getting the values from the user inputs and by id and loading in a
	// variable.	
	param = "token=" + token;
	param += "&label_id=" + ortho_label_id;		
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
						document.getElementById("response-div").innerHTML = displayResponse;										
					}, 500);
		}
	}
	// passing the values in the controller
	xmlhttp.open("GET", "index.php?route=sale/clinic/labelDelete&" + param, true);
	xmlhttp.send();
	setTimeout(
			function() {
				getLabelList();											
			}, 2000);
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}