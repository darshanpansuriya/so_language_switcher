function selectAllTracking() {

	if (document.getElementById('select-all-packing').checked == true) {

		$(document).ready(function() {

			$('.select-packing').attr('checked', 'checked');
			$('.select-packing-number').attr('value', '1');

		});

	}

	if (document.getElementById('select-all-packing').checked == false) {

		$(document).ready(function() {
			
			$('.select-packing').removeAttr('checked');			
				
			$('.select-packing-number').attr('value', '0');

		});

	}

}
function selectOneTracking(packing_number) {
 
	if (document.getElementById(packing_number).checked == true) {

		$(document).ready(function() {			

			$('[name= packing-' + packing_number +']').attr('value', '1');

		});

	}

	if (document.getElementById(packing_number).checked == false) {

		$(document).ready(function() {

			$('[name= packing-' + packing_number +']').attr('value', '0');

		});

	}

}



function saveAllTrackingNumbers() {

	$(document).ready(function() {

		$('.select-packing-number').each(function() {	
			
			if ($(this).val() == '1') {
				
				var id_details = $(this).attr('name');	
				
				var id_array = id_details.split('-');
				
				pack_num = id_array[1];					
				
				var shipp_num = document.getElementById('tracking-'+ pack_num + '').value;								
				
				updateTrackingNumber(pack_num,shipp_num);	
				
			}

		});

	});

}

function trackingUpdate(packing_list_num,shipping_id)
{
	updateTrackingNumber(packing_list_num,shipping_id);
	
}

function updateTrackingNumber(packing_list_num,shipping_id) {	
		
	var token= getUrlVars()["token"];	
	var packing_id=packing_list_num;	
	var shipp_id= shipping_id;

	
	// message to be displayed after successfull validation and posting of the
	// values
	displayMessage = 'please wait ...';
	document.getElementById("response").innerHTML = displayMessage;

	// getting the values from the user inputs and by id and loading in a
	// variable.	
	param = "token=" + token;
	param += "&packing_id=" + packing_id;
	param += "&shipp_id=" + shipp_id;
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
	xmlhttp.open("GET", "index.php?route=common/tracking/updateShippingNumber&" + param, true);
	xmlhttp.send();
}
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}
