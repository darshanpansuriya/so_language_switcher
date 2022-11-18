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

/**
 * Require some callback functions
 */ 
require_once dirname(__FILE__) . '/functions.php';

// Map QuickBooks actions to handler functions
//$clinic_orders = mysql_query("SELECT order_included FROM quickbook_orders WHERE  order_id = '".$extra['packing_id']."' AND clinic_id = '".$extra['clinic_id']."'  ");
		$packing_slip_number = mysql_query("SELECT order_included FROM quickbook_orders WHERE  order_id = 1030 AND clinic_id = 50  ");
	
	$orders = mysql_fetch_row($packing_slip_number);
	
	$packing_orders = $orders[0];
	
	$packing_single_orders= explode(',', $packing_orders);

	$packing_single_order =  $packing_single_orders[0];
	
	
	$invoice_data= mysql_query("SELECT * FROM `quickbook_orders` o
LEFT JOIN quickbook_clinics c ON  o.clinic_id = c.clinic_id	WHERE  o.order_id = 1030  AND o.clinic_id = 50  ");		
	
	
 	$invoice_info = mysql_fetch_assoc($invoice_data);	
	
$invoice_query= mysql_query("SELECT * FROM `order` o	
							LEFT JOIN order_values ov ON ov.order_values_order_id = o.order_id
							LEFT JOIN packing_slip ps ON o.order_packing_slip_id = ps.id
							LEFT JOIN patient p ON patient_id = order_patient_id		
							LEFT JOIN clinic c ON clinic_id = patient_clinic_id
							LEFT JOIN quickbook_clinics qc ON qc.clinic_id = c.clinic_id
							LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
							LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='".$packing_single_order."' ");
							
							
	$invoice_details = mysql_fetch_assoc($invoice_query);	


	// getting the current date
	
	$current_date = $invoice_details['create_date'];
	
	// checking whether the email exists or not
										
		if ($invoice_details['clinic_contact_email'] == '')
		{
			$to_be_emailed = 0;
		}
		else
		{
			$to_be_emailed = 1;
		}	
		
	
	
	$xml = '<?xml version="1.0" encoding="utf-8"?>
			<?qbxml version="8.0"?>
			<QBXML>
				<QBXMLMsgsRq onError="stopOnError">
					<InvoiceModRq requestID="15">
						<InvoiceMod>							
							<TxnID>'.$invoice_info['order_txn_id'].'</TxnID>
							<EditSequence>'.$invoice_info['order_edit_seq'].'</EditSequence>
							<CustomerRef>
								<ListID>'.$invoice_info['clinic_list_id'].'</ListID>
							</CustomerRef>
							<TxnDate>'.$current_date.'</TxnDate>																					
							<PONumber >'.$invoice_details['order_packing_slip_id'].'</PONumber>							
							<DueDate >'.$current_date.'</DueDate>							
							<ShipDate >'.$current_date.'</ShipDate>
							<IsToBeEmailed >'.$to_be_emailed.'</IsToBeEmailed>
							<Other >'.$invoice_details['order_shipping_number'].'</Other>
						</InvoiceMod>
					</InvoiceModRq>
				</QBXMLMsgsRq>
			</QBXML>';
	
	
	echo $xml;
	
	

?>
