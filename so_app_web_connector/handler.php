<?php

/**
 * Example Web Connector application
 * 
 * This is a very simple application that allows someone to enter a customer 
 * name into a web form, and then adds the customer to QuickBooks.
 * 
 * @author Keith Palmer <keith@consolibyte.com>
 * 
 * @package QuickBooks
 * @subpackage Documentation
 */

/**
 * Require some configuration stuff
 */ 
require_once dirname(__FILE__) . '/config.php';



// query to get the orders one by one from the database
// $order_query = "SELECT * FROM `order` o 
// 		LEFT JOIN order_values ON order_values_order_id = order_id
// 		LEFT JOIN patient p ON patient_id = order_patient_id
// 		LEFT JOIN country co ON co.country_id = p.patient_country_id
// 		LEFT JOIN clinic c ON clinic_id = patient_clinic_id
// 		LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
// 		LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.is_synced = '0'";
	
// $data_order = mysql_fetch_assoc(mysql_query($order_query));
//storing the order id in a variable


$id = 49;
	
//update query to update the synced status of the patient
	
mysql_query("
		INSERT INTO
			quickbook_clinics
		(clinic_id)
		VALUES ('".$id."')");

mysql_query("UPDATE `quickbook_clinics` SET `is_synced` = '2' where `clinic_id`=".$id);

$dsn = 'mysql://dbo562806560:9bpSTW-_O@d!@localhost:/tmp/mysql5.sock/db562806560';
$Queue = new QuickBooks_WebConnector_Queue($dsn);


$Queue->enqueue(QUICKBOOKS_QUERY_CUSTOMER, $id);


echo 'Completed successfully for:'.$id . " current queue size is: " . $Queue->size();
echo '<br>';
die('Successfully queued up a request');