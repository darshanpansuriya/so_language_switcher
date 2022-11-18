<?php

include "../../config.php";
	
	$a = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
	
	mysql_select_db(DB_DATABASE);
	
	$patient_details = "SELECT * FROM `order` o
	LEFT JOIN order_values ov ON ov.order_values_order_id = o.order_id
	LEFT JOIN quickbook_orders qo ON qo.order_id = o.order_id
	LEFT JOIN patient p ON patient_id = order_patient_id
	LEFT JOIN quickbook_patients qb ON qb.patient_id = p.patient_id
	LEFT JOIN country co ON co.country_id = p.patient_country_id
	LEFT JOIN clinic c ON clinic_id = patient_clinic_id
	LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
	LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='417'";
	
	//fetching the patients data into array
	$patients_data = mysql_fetch_assoc(mysql_query($patient_details));

print_r($patients_data);
			
