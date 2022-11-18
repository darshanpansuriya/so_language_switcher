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
	$clinic_orders = mysql_query("SELECT order_included FROM quickbook_orders WHERE  order_id = 1048 AND clinic_id = 2  ");
	
	$orders = mysql_fetch_row($clinic_orders);
	
	//$pSort = sort($orders['order_included'],1);
	
		
	$packing_orders = $orders[0];
								
								
	$packing_slip_orders = explode(',', $packing_orders); 
	
	$packing_single_order = $packing_slip_orders[0];
	
	
	
	$invoice_data= "SELECT * FROM `order` o	
							LEFT JOIN order_values ov ON ov.order_values_order_id = o.order_id
							LEFT JOIN packing_slip ps ON o.order_packing_slip_id = ps.id
							LEFT JOIN patient p ON patient_id = order_patient_id		
							LEFT JOIN clinic c ON clinic_id = patient_clinic_id
							LEFT JOIN quickbook_clinics qc ON qc.clinic_id = c.clinic_id
							LEFT JOIN clinic_address ca ON c.clinic_address_id = ca.clinic_address_id	
							LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='".$packing_single_order."'";
		
	
	
	$invoice_info = mysql_fetch_assoc(mysql_query($invoice_data));	
	



	
	//echo $invoice_info['clinic_address_address'];
	//echo $invoice_info['clinic_address_address'];	
	
	 
	
	
	$address_clinic = $invoice_info['clinic_address_address'];	
	
	
	
	
	$address_split = explode(',',$address_clinic);			
	
	$limit =  strlen($address_split [1] );			
		
			
			if ($limit  > 35)
			{ 
				$address_truncated = substr($address_split[1],0,35);
				$address_truncated2= substr($address_split[1],36,$limit);
			}
			else
			{
				$address_truncated = $address_split [1] ;
				$address_truncated2 = '';
			}
			
			$postal_limit = strlen($address_split [4] );
			
			if ($postal_limit  >= 8)
			{ 
				$postal_truncated = substr($address_split[4],0,8);
			}
			else
			{
				$postal_truncated = $address_split[4];
			}
			

	$order_address = $invoice_info['order_shipping_address'];
	
	$order_address_split = explode(',',$order_address);	
	
	
	$limit =  strlen($order_address_split [1] );			
			
				
				if ($limit  > 35)
				{ 
					$order_address_truncated = substr($order_address_split[1],0,35);
					$order_address_truncated2= substr($order_address_split[1],36,$limit);
				}
				else
				{
					$order_address_truncated = $order_address_split [1] ;
					$order_address_truncated2 = '';
				}			
	
	$order_postal_limit = strlen($address_split [4] );
			
			if ($order_postal_limit  >= 8)
			{ 
				$order_postal_truncated = substr($order_address_split[4],0,8);
			}
			else
			{
				$order_postal_truncated = $order_address_split[4];
			}
	
	/**
	*  getting the terms of the clinic 	
	*/
	
	   if($invoice_info['clinic_terms'] == 1 )
	   {
			$clinic_term =  'End of Month';
	   }
	   elseif($invoice_info['clinic_terms'] == 2 )
	   {
			$clinic_term =  'Due on receipt';
	   }
	   elseif($invoice_info['clinic_terms'] == 3 )
	   {
			$clinic_term =  'Net 30';
	   }
	
	// geting the current date
	
	$current_date = $invoice_info['create_date'];
	
	// checking whether the email exists or not
										
		if ($invoice_info['clinic_contact_email'] == '')
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
					<InvoiceAddRq requestID="15">
						<InvoiceAdd>
							<CustomerRef>
								<ListID>'.$invoice_info['clinic_list_id'].'</ListID>
							</CustomerRef>
							<TxnDate>'.$current_date.'</TxnDate>							
							<BillAddress>	
								<Addr1>'.$address_split[0].'</Addr1>
								<Addr2>'.$address_truncated.'</Addr2>
								<Addr3>'.$address_truncated2.'</Addr3>								
								<City>'.$address_split[2].'</City>
								<State>'.$address_split[3].'</State>
								<PostalCode>'.$postal_truncated.'</PostalCode>
								<Country></Country>
							</BillAddress>
							<ShipAddress>
								<Addr1>'.$order_address_split[0].'</Addr1>
								<Addr2>'.$order_address_truncated.'</Addr2>
								<Addr3>'.$order_address_truncated2.'</Addr3>
								<City >'.$order_address_split[2].'</City>
								<State >'.$order_address_split[3].'</State>
								<PostalCode >'.$order_postal_truncated.'</PostalCode>
								<Country ></Country>
							</ShipAddress>							
							<PONumber >'.$invoice_info['order_packing_slip_id'].'</PONumber>
							<TermsRef>
								<FullName>'.$clinic_term.'</FullName>
							</TermsRef>	
							<DueDate >'.$current_date.'</DueDate>							
							<ShipDate >'.$current_date.'</ShipDate>	
							<IsToBePrinted >1</IsToBePrinted>
							<IsToBeEmailed >'.$to_be_emailed.'</IsToBeEmailed>
							<Other>'.$invoice_info['order_shipping_number'].'</Other>';
							
							$packing_slip_number = mysql_query("SELECT order_included FROM quickbook_orders WHERE  order_id = 1048 AND clinic_id = 2  ");
	
								$orders = mysql_fetch_row($packing_slip_number);
		
								$packing_orders = $orders[0];
									$new_ps = explode(',', $orders[0]); 

								
								$packing_slip_orders = explode(',', $packing_orders); 
								sort($packing_slip_orders);
								foreach ($packing_slip_orders as $packing_slip_order) {
									
									$invoice_details= "SELECT * FROM `order` o
							LEFT JOIN order_values ov ON ov.order_values_order_id = o.order_id
							LEFT JOIN patient p ON patient_id = order_patient_id
							LEFT JOIN clinic c ON clinic_id = patient_clinic_id
							LEFT JOIN quickbook_clinics qc ON qc.clinic_id = c.clinic_id
							LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
							LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='".$packing_slip_order."'";
									
									
									//fetching the patients data into array
									$invoice_data = mysql_fetch_assoc(mysql_query($invoice_details));
									
									// getting the invoice item details
									$item_code = '';						
									
									
									// getting the invoice item details
									if ($invoice_data['clinic_id'] != '1')
									
									{
									
										if ($invoice_data['order_values_37'] == '316'){
									
											$item_code = 'CDM';
									
										}
										elseif($invoice_data['order_values_37'] == '317'){
									
											$item_code = 'CVP';
									
										}
										elseif ($invoice_data['order_values_37'] == '178'){
									
											$item_code = 'FStd';
									
										}
										elseif ($invoice_data['order_values_37'] == '179'){
									
											$item_code = 'FStdL';
									
										}
										elseif ($invoice_data['order_values_37'] == '277'){
									
											$item_code = 'FStdF';
									
										}
										elseif ($invoice_data['order_values_37'] == '181'){
									
											$item_code = 'SPIMPACT';
									
										}
										elseif ($invoice_data['order_values_37'] == '185'){
									
											$item_code = 'DFC';
									
										}										
										elseif ($invoice_data['order_values_37'] == '220'){
									
											$item_code = 'SPSOS';
									
										}
										elseif ($invoice_data['order_values_37'] == '219'){
									
											$item_code = 'SPGOLF';
									
										}
										elseif ($invoice_data['order_values_37'] == '183'){
									
											$item_code = 'AGENTLE';
									
										}
										elseif ($invoice_data['order_values_37'] == '184'){
									
											$item_code = 'DWOM';
									
										}
										elseif ($invoice_data['order_values_37'] == '221'){
									
											$item_code = 'DMEN';
									
										}
										elseif ($invoice_data['order_values_37'] == '186'){
									
											$item_code = 'SDIABETIC';
									
										}
										elseif ($invoice_data['order_values_37'] == '222'){
									
											$item_code = 'SUCBLM';
									
										}
										elseif ($invoice_data['order_values_37'] == '187'){
									
											$item_code = 'SUCBLF';
									
										}
										elseif ($invoice_data['order_values_37'] == '188'){
									
											$item_code = 'SRWF';
									
										}
									
									}
									else
									{
										$item_code = 'Custom Made Orthotics';
									
									
									}										
									
									$xml .= '<InvoiceLineAdd>
										<ItemRef>
											<FullName>'.$item_code.'</FullName>
										</ItemRef>
										<Desc>'.$invoice_data['order_description'].'</Desc>
										<Quantity>'.$invoice_data['order_quantity'].'</Quantity>
										<Amount>'.$invoice_data['order_total'].'</Amount>
									</InvoiceLineAdd>';									
								}
							
									
						$xml .= '</InvoiceAdd>
					</InvoiceAddRq>
				</QBXMLMsgsRq>
			</QBXML>';	
	
	
	echo $xml;
	

?>
