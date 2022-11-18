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
{
	$invoice_details= "SELECT * FROM `order` o	
							LEFT JOIN order_values ov ON ov.order_values_order_id = o.order_id
							LEFT JOIN packing_slip ps ON o.order_packing_slip_id = ps.id
							LEFT JOIN quickbook_retail_orders qro ON qro.order_id = o.order_id
							LEFT JOIN patient p ON patient_id = order_patient_id
							LEFT JOIN country co ON co.country_id = p.patient_country_id		
							LEFT JOIN clinic c ON clinic_id = patient_clinic_id
							LEFT JOIN quickbook_patients qp ON qp.patient_id= p.patient_id
							LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
							LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='3423'";


	//fetching the patients data into array
	$invoice_data = mysql_fetch_assoc(mysql_query($invoice_details));
	
	// getting the item description according to clinic and orthotics type
	
		
	$item_code = 'Custom Made Orthotics';

	
	// checking if the patient email exists
	
	$to_be_emailed = "";
	
	if ($invoice_data['patient_email'] == '')
	{
		$to_be_emailed = 0;
	}
	else
	{
		$to_be_emailed = 1;
	}

	$current_date = $invoice_data['create_date'];

	$xml = '<?xml version="1.0" encoding="utf-8"?>
			<?qbxml version="8.0"?>
			<QBXML>
				<QBXMLMsgsRq onError="stopOnError">
					<InvoiceModRq requestID="15">
						<InvoiceMod>
							<TxnID>'.$invoice_data['order_txn_id'].'</TxnID>
							<EditSequence>'.$invoice_data['order_edit_seq'].'</EditSequence>
							<CustomerRef>
								<ListID>'.$invoice_data['patient_list_id'].'</ListID>
							</CustomerRef>
							<TxnDate>'.$current_date.'</TxnDate>
							<BillAddress>
								<Addr1>'.$invoice_data['patient_address_1'].'</Addr1>
								<Addr2>'.$invoice_data['patient_address_2'].'</Addr2>
								<City>'.$invoice_data['patient_city'].'</City>
								<State>ON</State>
								<PostalCode>'.$invoice_data['patient_postalcode'].'</PostalCode>
								<Country>'.$invoice_data['name'].'</Country>
							</BillAddress>	
							<PONumber >'.$invoice_data['order_packing_slip_id'].'</PONumber>
							<IsToBePrinted >1</IsToBePrinted>
							<IsToBeEmailed >'.$to_be_emailed.'</IsToBeEmailed>
							<Other>'.$invoice_data['order_shipping_number'].'</Other>							
							<InvoiceLineMod>
								<TxnLineID >'.$invoice_data['order_txn_line_id'].'</TxnLineID>
								<ItemRef>
									<FullName>'.$item_code.'</FullName>
								</ItemRef>
								<Desc>'.$invoice_data['order_description'].'</Desc>
								<Quantity>'.$invoice_data['order_quantity'].'</Quantity>
								<Amount>'.$invoice_data['order_total'].'</Amount>
							</InvoiceLineMod>
						</InvoiceMod>
					</InvoiceModRq>
				</QBXMLMsgsRq>
			</QBXML>';


	echo $xml;
	}

?>
