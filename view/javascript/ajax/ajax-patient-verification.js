function checkPatientData(patient_fname,patient_lname,patient_dob,patient_clinic) {
	
	var token= getUrlVars()["token"];	
	var first_name=patient_fname;	
	var last_name= patient_lname;
	var birth_date= patient_dob;
	var clinic_id = patient_clinic;

	// getting the values from the user inputs and by id and loading in a
	// variable.	
	param = "token=" + token;
	param += "&first_name=" + first_name;
	param += "&last_name=" + last_name;
	param += "&birth_date=" + birth_date;
	param += "&clinic_id=" +clinic_id;
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
						if(displayResponse==1){
							alert("This patient already exists in the system, please select the patient or verify the details.");
						}
						
					}, 100);
		}
	}
	// passing the values in the controller
	xmlhttp.open("GET", "index.php?route=sale/order/checkPatientRecords&" + param, true);
	xmlhttp.send();
}
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}