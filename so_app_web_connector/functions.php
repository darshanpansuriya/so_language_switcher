<?php

/**
 * Generate a qbXML response to add a particular customer to QuickBooks
 */
function _quickbooks_customer_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Grab the data from our MySQL database

	$clinic_details = "SELECT * FROM `clinic` c
		LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
		WHERE clinic_id='".$ID."'";

	//fetching the patients data into array
	$clinic_data = mysql_fetch_assoc(mysql_query($clinic_details));


	//getting the patient details and storing it into variables

			$company_name = $clinic_data['clinic_name'];

			$contact_name = $clinic_data['clinic_contact'];

			$clinic_email = $clinic_data['clinic_contact_email'];

			$clinic_telephone = $clinic_data['clinic_telephone'];

			$clinic_fax = $clinic_data['clinic_fax'];

			$clinic_address = $clinic_data['clinic_address_address'];


			$address_split = explode(',',$clinic_address);

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

			if ($limit  > 8)
			{
				$postal_truncated = substr($address_split[4],0,8);
			}
			else
			{
				$postal_truncated = $address_split[4];
			}



	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="2.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<CustomerAddRq requestID="' . $requestID . '">
					<CustomerAdd>
						<Name>'.$company_name.'</Name>
						<CompanyName>'.$company_name.'</CompanyName>
						<BillAddress>
							<Addr1>'.$address_truncated.'</Addr1>
							<Addr2>'.$address_truncated2.'</Addr2>
							<City>'.$address_split[2].'</City>
							<State>'.$address_split[3].'</State>
							<PostalCode>'.$postal_truncated.'</PostalCode>
							<Country></Country>
						</BillAddress>
						<Phone>'.$clinic_telephone.'</Phone>
						<AltPhone></AltPhone>
						<Fax>'.$clinic_fax.'</Fax>
						<Email>'.$clinic_email.'</Email>
						<Contact>'.$contact_name.'</Contact>
					</CustomerAdd>
				</CustomerAddRq>
			</QBXMLMsgsRq>
		</QBXML>';


	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'version'=>$version,'locale'=>$locale]);
		saveLogs($xml,$param_json,'_quickbooks_customer_add_request',$ID);
	}catch(Exception $ex){

	}
	return $xml;
}

/**
 * Receive a response from QuickBooks
 */
function _quickbooks_customer_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	// query to update the order details
	mysql_query("UPDATE quickbook_clinics SET
			is_synced = '3',
			clinic_list_id = '" . mysql_real_escape_string($idents['ListID']) . "',
			clinic_edit_seq = '" . mysql_real_escape_string($idents['EditSequence']) . "'
		WHERE
			clinic_id = " . (int) $ID);

	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_customer_add_response',$ID);
	}catch(Exception $ex){

	}
}

/**
 * this function is written to edit or update a patient data when a order is updated
 */
function _quickbooks_customer_edit_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	// Grab the data from our MySQL database

	$clinic_details = "SELECT * FROM `clinic` c
		LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
		LEFT JOIN quickbook_clinics qc ON qc.clinic_id = c.clinic_id
		WHERE c.clinic_id='".$ID."'";

	//fetching the patients data into array
	$clinic_data = mysql_fetch_assoc(mysql_query($clinic_details));


	//getting the patient details and storing it into variables

			$company_name = $clinic_data['clinic_name'];

			$contact_name = $clinic_data['clinic_contact'];

			$clinic_email = $clinic_data['clinic_contact_email'];

			$clinic_telephone = $clinic_data['clinic_telephone'];

			$clinic_fax = $clinic_data['clinic_fax'];

			$clinic_address = $clinic_data['clinic_address_address'];

// 			$patient_addr_2 = $patients_data['patient_address_2'];

// 			$patient_city = $patients_data['patient_city'];

// 			$patient_postal_code = $patients_data['patient_postalcode'];

// 			$patient_country_name = $patients_data['name'];

	$xml = '<?xml version="1.0" encoding="utf-8"?>
				<?qbxml version="8.0"?>
				<QBXML>
				  <QBXMLMsgsRq onError="stopOnError">
				    <CustomerModRq requestID="'.$requestID.'">
				      <CustomerMod>
				        <ListID>'.$clinic_data['clinic_list_id'].'</ListID>
				        <EditSequence>'.$clinic_data['clinic_edit_seq'].'</EditSequence>
				 		<Name>'.$contact_name.'*</Name>
						<CompanyName>'.$company_name.'</CompanyName>
						<BillAddress>
							<Addr1>'.$clinic_address.'</Addr1>
							<City></City>
							<State></State>
							<PostalCode></PostalCode>
							<Country></Country>
						</BillAddress>
						<Phone>'.$clinic_telephone.'</Phone>
						<AltPhone></AltPhone>
						<Fax>'.$clinic_fax.'</Fax>
						<Email>'.$clinic_email.'</Email>
						<Contact>'.$contact_name.'</Contact>
				      </CustomerMod>
				    </CustomerModRq>
				  </QBXMLMsgsRq>
				</QBXML>';


	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_customer_edit_request',$ID);
	}catch(Exception $ex){

	}
	return $xml;
}

function _quickbooks_customer_edit_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{

	mysql_query("
		UPDATE
			quickbook_clinics
		SET
			is_synced = '3',
			clinic_list_id = '" . mysql_real_escape_string($idents['ListID']) . "',
			clinic_edit_seq = '" . mysql_real_escape_string($idents['EditSequence']) . "'
		WHERE
			clinic_id = " . (int) $ID);


	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_customer_edit_response',$ID);
	}catch(Exception $ex){

	}
}

/**
 * Catch and handle an error from QuickBooks
 */
function _quickbooks_error_catchall($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
{
	mysql_query("
		UPDATE
			quickbook_clinics
		SET
			quickbooks_errnum = '" . mysql_real_escape_string($errnum) . "',
			quickbooks_errmsg = '" . mysql_real_escape_string($errmsg) . "'
		WHERE
			clinic_id = " . (int) $ID);

	if ($errnum == '1')
	{
		require_once 'config.php';

$dsn = 'mysql://dbo562806560:9bpSTW-_O@d!@localhost:/tmp/mysql5.sock/db562806560';
// $dsn = 'mysql://soundort_app:C$~_O^T-6z+~@d!@localhost/soundort_app';
		$Queue = new QuickBooks_WebConnector_Queue($dsn);

		$query_priority = '15';

		$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER,$ID,$query_priority);
	}


	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'errnum'=>$errnum,'errmsg'=>$errmsg]);
		saveLogs($xml,$param_json,'_quickbooks_error_catchall',$ID);
	}catch(Exception $ex){

	}
}


function _quickbooks_invoice_import_request($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $version, $locale){

	$clinic_orders = mysql_query("SELECT order_included FROM quickbook_orders WHERE  order_id = '".$extra['packing_id']."' AND clinic_id = '".$extra['clinic_id']."'  ");

	$orders = mysql_fetch_row($clinic_orders);

	$packing_orders = $orders[0];

	$packing_slip_orders = explode(',', $packing_orders);

	$packing_single_order = $packing_slip_orders[0];


	// LEFT JOIN clinic_address ca ON c.clinic_address_id = ca.clinic_address_id
	// March 6th 2014 Invoice address not coming through
	$invoice_data= "SELECT * FROM `order` o
							LEFT JOIN order_values ov ON ov.order_values_order_id = o.order_id
							LEFT JOIN packing_slip ps ON o.order_packing_slip_id = ps.id
							LEFT JOIN patient p ON patient_id = order_patient_id
							LEFT JOIN clinic c ON clinic_id = patient_clinic_id
							LEFT JOIN quickbook_clinics qc ON qc.clinic_id = c.clinic_id
							LEFT JOIN clinic_address ca ON c.clinic_address_id = ca.clinic_address_id
							LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='".$packing_single_order."'";



	$invoice_info = mysql_fetch_assoc(mysql_query($invoice_data));

	$address_clinic = $invoice_info['clinic_address_address'];

	$address_split = explode(',',$address_clinic);

	$limit =  strlen($address_split [1] );


			if ($limit  > 35)
			{
				// $address_truncated = substr($invoice_info['clinic_name'],0,35);
				$address_truncated = substr($address_split[1],0,35);
				$address_truncated2= substr($address_split[1],36,$limit);
			}
			else
			{
				// $address_truncated = $invoice_info['clinic_name'];
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


	// this might be the issue here
	$order_address = $invoice_info['order_shipping_address'];

	$order_address_split = explode(',',$order_address);

	// $truncated_address = substr($order_address_split[0],0,35);
	// address_split
	$truncated_address = substr($address_split[0],0,35);
	$truncated_other = substr($invoice_info['order_shipping_number'],0,35);

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
					<InvoiceAddRq requestID="'.$requestID.'">
						<InvoiceAdd>
							<CustomerRef>
								<ListID>'.$invoice_info['clinic_list_id'].'</ListID>
							</CustomerRef>
							<TxnDate>'.$current_date.'</TxnDate>
							<BillAddress>
								<Addr1>'.$truncated_address.'</Addr1>
								<Addr2>'.$address_truncated.'</Addr2>
								<Addr3>'.$address_truncated2.'</Addr3>
								<City>'.substr($address_split[2],0,35).'</City>
								<State>'.$address_split[3].'</State>
								<PostalCode>'.$postal_truncated.'</PostalCode>
								<Country></Country>
							</BillAddress>
							<ShipAddress>
								<Addr1>'.substr($order_address_split[0],0,35).'</Addr1>
								<Addr2>'.$order_address_truncated.'</Addr2>
								<Addr3>'.$order_address_truncated2.'</Addr3>
								<City >'.substr($order_address_split[2],0,35).'</City>
								<State >'.substr($order_address_split[3],0,35).'</State>
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
							<Other>'.$truncated_other.'</Other>';


							$packing_slip_number = mysql_query("SELECT order_included FROM quickbook_orders WHERE  order_id = '".$extra['packing_id']."' AND clinic_id = '".$extra['clinic_id']."'  ");

								$orders = mysql_fetch_row($packing_slip_number);

								$packing_orders = $orders[0];

								$packing_slip_orders = explode(',', $packing_orders);
								sort($packing_slip_orders);
								foreach ($packing_slip_orders as $packing_slip_order) {

									$invoice_details= "SELECT * FROM `order` o
							LEFT JOIN order_values ov ON ov.order_values_order_id = o.order_id
							LEFT JOIN patient p ON patient_id = order_patient_id
							LEFT JOIN clinic c ON clinic_id = patient_clinic_id
							LEFT JOIN quickbook_clinics qc ON qc.clinic_id = c.clinic_id
							LEFT JOIN clinic_address ca ON c.clinic_address_id = ca.clinic_address_id
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

									// thinking there might be an over-run and description needs truncation
									// substr($invoice_data['order_description'],0,4096);
									$xml .= '<InvoiceLineAdd>
										<ItemRef>
											<FullName>'.$item_code.'</FullName>
										</ItemRef>
										<Desc>'.iconv('UTF-8', 'ASCII//TRANSLIT', $invoice_data['order_description']).'</Desc>
										<Quantity>'.$invoice_data['order_quantity'].'</Quantity>
										<Amount>'.$invoice_data['order_total'].'</Amount>
									</InvoiceLineAdd>';
								}


						$xml .= '</InvoiceAdd>
					</InvoiceAddRq>
				</QBXMLMsgsRq>
			</QBXML>';


	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'version'=>$version,'locale'=>$locale]);
		saveLogs($xml,$param_json,'_quickbooks_invoice_import_request',$ID);
	}catch(Exception $ex){

	}
	return $xml;

}

function _quickbooks_invoice_import_response($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	mysql_query("
		UPDATE
			quickbook_orders
		SET
			is_synced = '3',
			order_list_id = '" . mysql_real_escape_string($idents['ListID']) . "',
			order_txn_id = '" . mysql_real_escape_string($idents['TxnID']) . "',
			order_txn_line_id = '" . mysql_real_escape_string($idents['TxnLineID']) . "',
			order_edit_seq = '".mysql_real_escape_string($idents['EditSequence'])."'
		WHERE
			order_id = '".$extra['packing_id']."' AND clinic_id = '".$extra['clinic_id']."' ");

	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_invoice_import_response',$ID);
	}catch(Exception $ex){

	}

}

function _quickbooks_invoice_edit_request($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $version, $locale)
{
	$packing_slip_number = mysql_query("SELECT order_included FROM quickbook_orders WHERE  order_id = '".$extra['packing_id']."' AND clinic_id = '".$extra['clinic_id']."'  ");

	$orders = mysql_fetch_row($packing_slip_number);

	$packing_orders = $orders[0];

	$packing_single_orders= explode(',', $packing_orders);

	$packing_single_order =  $packing_single_orders[0];


	$invoice_data= mysql_query("SELECT * FROM `quickbook_orders` o
LEFT JOIN quickbook_clinics c ON  o.clinic_id = c.clinic_id	WHERE  o.order_id = '".$extra['packing_id']."' AND o.clinic_id = '".$extra['clinic_id']."'  ");


 	$invoice_info = mysql_fetch_assoc($invoice_data);

$invoice_query= mysql_query("SELECT * FROM `order` o
							LEFT JOIN order_values ov ON ov.order_values_order_id = o.order_id
							LEFT JOIN packing_slip ps ON o.order_packing_slip_id = ps.id
							LEFT JOIN patient p ON patient_id = order_patient_id
							LEFT JOIN clinic c ON clinic_id = patient_clinic_id
							LEFT JOIN quickbook_clinics qc ON qc.clinic_id = c.clinic_id
							LEFT JOIN clinic_address ca ON c.clinic_address_id = ca.clinic_address_id
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
					<InvoiceModRq requestID="'.$requestID.'">
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
							<Other>'.$invoice_details['order_shipping_number'].'</Other>
						</InvoiceMod>
					</InvoiceModRq>
				</QBXMLMsgsRq>
			</QBXML>';


	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'version'=>$version,'locale'=>$locale]);
		saveLogs($xml,$param_json,'_quickbooks_invoice_edit_request',$ID);
	}catch(Exception $ex){

	}
	return $xml;

}

function _quickbooks_invoice_edit_response($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	mysql_query("
		UPDATE
			quickbook_orders
		SET
			is_synced = '3',
			is_mod_queue = '0',
			order_txn_id = '" . mysql_real_escape_string($idents['TxnID']) . "'	,
			order_edit_seq = '".mysql_real_escape_string($idents['EditSequence'])."'
			WHERE  order_id = '".$extra['packing_id']."' AND clinic_id = '".$extra['clinic_id']."'  ");

	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_invoice_edit_response',$ID);
	}catch(Exception $ex){

	}
}

function _quickbooks_retail_customer_add_request($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Grab the data from our MySQL database

	$patient_details = "SELECT * FROM `patient` p
		LEFT JOIN country co ON co.country_id = p.patient_country_id
		LEFT JOIN clinic c ON clinic_id = patient_clinic_id
		LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
		LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE patient_id='".$ID."'";

	//fetching the patients data into array
	$patients_data = mysql_fetch_assoc(mysql_query($patient_details));


	//getting the patient details and storing it into variables

	$patient_firstname = $patients_data['patient_firstname'];

	$patient_lastname = $patients_data['patient_lastname'];

	$patient_email = $patients_data['patient_email'];

	$patient_telephone = $patients_data['patient_telephone'];

	$patient_fax = $patients_data['patient_fax'];

	$patient_addr_1 = $patients_data['patient_address_1'];

	$patient_addr_2 = $patients_data['patient_address_2'];

	$patient_city = $patients_data['patient_city'];

	$patient_postal_code = $patients_data['patient_postalcode'];

	$patient_country_name = $patients_data['name'];

	$patient_company = $patients_data['patient_company'];

	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="2.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<CustomerAddRq requestID="' . $requestID . '">
					<CustomerAdd>
						<Name>'.$patient_firstname.' '.$patient_lastname.'</Name>
						<CompanyName>'.$patient_company.'</CompanyName>
						<FirstName>'.$patient_firstname.'</FirstName>
						<LastName>'.$patient_lastname.'</LastName>
						<BillAddress>
							<Addr1>'.$patient_firstname.' '.$patient_lastname.'</Addr1>
							<Addr2>'.$patient_addr_2.' '.$patient_addr_1.'</Addr2>
							<City>'.$patient_city.'</City>
							<State>ON</State>
							<PostalCode>'.$patient_postal_code.'</PostalCode>
							<Country>'.$patient_country_name.'</Country>
						</BillAddress>
						<Phone>'.$patient_telephone.'</Phone>
						<AltPhone></AltPhone>
						<Fax>'.$patient_fax.'</Fax>
						<Email>'.$patient_email.'</Email>
						<Contact>'.$patient_firstname.' '.$patient_lastname.'</Contact>
					</CustomerAdd>
				</CustomerAddRq>
			</QBXMLMsgsRq>
		</QBXML>';

	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'version'=>$version,'locale'=>$locale]);
		saveLogs($xml,$param_json,'_quickbooks_retail_customer_add_request',$ID);
	}catch(Exception $ex){

	}
	return $xml;

}

/**
 * Receive a response from QuickBooks
 */
function _quickbooks_retail_customer_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	// query to update the order details
	mysql_query("UPDATE quickbook_patients SET
			is_synced = '3',
			patient_list_id = '" . mysql_real_escape_string($idents['ListID']) . "',
			patient_edit_seq = '" . mysql_real_escape_string($idents['EditSequence']) . "'
		WHERE
			patient_id = " . (int) $ID);

	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_retail_customer_add_response',$ID);
	}catch(Exception $ex){

	}
}

function _quickbooks_retail_customer_edit_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{

	$patient_details = "SELECT * FROM `patient` p
		LEFT JOIN quickbook_patients qp ON p.patient_id = qp.patient_id
		LEFT JOIN country co ON co.country_id = p.patient_country_id
		LEFT JOIN clinic c ON clinic_id = patient_clinic_id
		LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
		LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE p.patient_id='".$ID."'";

	//fetching the patients data into array
	$patients_data = mysql_fetch_assoc(mysql_query($patient_details));

	//getting the patient details and storing it into variables

	$patient_firstname = $patients_data['patient_firstname'];

	$patient_lastname = $patients_data['patient_lastname'];

	$patient_email = $patients_data['patient_email'];

	$patient_telephone = $patients_data['patient_telephone'];

	$patient_fax = $patients_data['patient_fax'];

	$patient_addr_1 = $patients_data['patient_address_1'];

	$patient_addr_2 = $patients_data['patient_address_2'];

	$patient_city = $patients_data['patient_city'];

	$patient_postal_code = $patients_data['patient_postalcode'];

	$patient_country_name = $patients_data['name'];

	$patient_company = $patients_data['patient_company'];


	$xml = '<?xml version="1.0" encoding="utf-8"?>
				<?qbxml version="8.0"?>
				<QBXML>
				  <QBXMLMsgsRq onError="stopOnError">
				    <CustomerModRq requestID="'.$requestID.'">
				      <CustomerMod>
				        <ListID>'.$patients_data['patient_list_id'].'</ListID>
				        <EditSequence>'.$patients_data['patient_edit_seq'].'</EditSequence>
				 		<Name>'.$patient_firstname.' '.$patient_lastname.'</Name>
						<CompanyName>'.$patient_company.'</CompanyName>
						<BillAddress>
							<Addr1>'.$patient_addr_1.'</Addr1>
							<Addr2>'.$patient_addr_2.'</Addr2>
							<City>'.$patient_city.'</City>
							<State></State>
							<PostalCode>'.$patient_postal_code.'</PostalCode>
							<Country>'.$patient_country_name.'</Country>
						</BillAddress>
						<Phone>'.$patient_telephone.'</Phone>
						<AltPhone></AltPhone>
						<Fax>'.$patient_fax.'</Fax>
						<Email>'.$patient_email.'</Email>
						<Contact>'.$patient_firstname.' '.$patient_lastname.'</Contact>
				      </CustomerMod>
				    </CustomerModRq>
				  </QBXMLMsgsRq>
				</QBXML>';

	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_retail_customer_edit_request',$ID);
	}catch(Exception $ex){

	}
	return $xml;

}

function _quickbooks_retail_customer_edit_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{

	mysql_query("
		UPDATE
			quickbook_patients
		SET
			is_synced = '3',
			patient_list_id = '" . mysql_real_escape_string($idents['ListID']) . "',
			patient_edit_seq = '" . mysql_real_escape_string($idents['EditSequence']) . "'
		WHERE
			patient_id = " . (int) $ID);

	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_retail_customer_edit_response',$ID);
	}catch(Exception $ex){

	}
}



function _quickbooks_retail_invoice_import_request($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $version, $locale)
{
	$invoice_details= "SELECT * FROM `order` o
							LEFT JOIN order_values ov ON ov.order_values_order_id = o.order_id
							LEFT JOIN packing_slip ps ON o.order_packing_slip_id = ps.id
							LEFT JOIN patient p ON patient_id = order_patient_id
							LEFT JOIN country co ON co.country_id = p.patient_country_id
							LEFT JOIN clinic c ON clinic_id = patient_clinic_id
							LEFT JOIN quickbook_patients qp ON qp.patient_id= p.patient_id
							LEFT JOIN clinic_address ca ON c.clinic_address_id = ca.clinic_address_id
							LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='".$ID."'";


	//fetching the patients data into array
	$invoice_data = mysql_fetch_assoc(mysql_query($invoice_details));


	$patient_firstname = $invoice_data['patient_firstname'];

	$patient_lastname = $invoice_data['patient_lastname'];

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

	/**
	*  getting the terms of the clinic
	*/

	   if($invoice_data['clinic_terms'] == 1 )
	   {
			$clinic_term =  'End of Month';
	   }
	   elseif($invoice_data['clinic_terms'] == 2 )
	   {
			$clinic_term =  'Due on receipt';
	   }
	   elseif($invoice_data['clinic_terms'] == 3 )
	   {
			$clinic_term =  'Net 30';
	   }


	$current_date = $invoice_data['create_date'];

	$xml = '<?xml version="1.0" encoding="utf-8"?>
			<?qbxml version="8.0"?>
			<QBXML>
				<QBXMLMsgsRq onError="stopOnError">
					<InvoiceAddRq requestID="'.$requestID.'">
						<InvoiceAdd>
							<CustomerRef>
								<ListID>'.$invoice_data['patient_list_id'].'</ListID>
							</CustomerRef>
							<TxnDate>'.$current_date.'</TxnDate>
							<BillAddress>
								<Addr1>'.$patient_firstname.''.$patient_lastname.'</Addr1>
								<Addr2>'.$invoice_data['patient_address_1'].'</Addr2>
								<Addr3>'.$invoice_data['patient_address_2'].'</Addr3>
								<City>'.$invoice_data['patient_city'].'</City>
								<State>ON</State>
								<PostalCode>'.$invoice_data['patient_postalcode'].'</PostalCode>
								<Country>'.$invoice_data['name'].'</Country>
							</BillAddress>
							<PONumber >'.$invoice_data['order_packing_slip_id'].'</PONumber>
							<TermsRef>
								<FullName>'.$clinic_term.'</FullName>
							</TermsRef>
							<IsToBePrinted >1</IsToBePrinted>
							<IsToBeEmailed >'.$to_be_emailed.'</IsToBeEmailed>
							<Other>'.$invoice_data['order_shipping_number'].'</Other>
							<InvoiceLineAdd>
								<ItemRef>
									<FullName>'.$item_code.'</FullName>
								</ItemRef>
								<Desc>'.$invoice_data['order_description'].'</Desc>
								<Quantity>'.$invoice_data['order_quantity'].'</Quantity>
								<Amount>'.$invoice_data['order_total'].'</Amount>
							</InvoiceLineAdd>
						</InvoiceAdd>
					</InvoiceAddRq>
				</QBXMLMsgsRq>
			</QBXML>';


	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'version'=>$version,'locale'=>$locale]);
		saveLogs($xml,$param_json,'_quickbooks_retail_invoice_import_request',$ID);
	}catch(Exception $ex){

	}
	return $xml;

}

function _quickbooks_retail_invoice_import_response($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	mysql_query("
		UPDATE
			quickbook_retail_orders
		SET
			is_synced = '3',
			order_list_id = '" . mysql_real_escape_string($idents['ListID']) . "',
			order_txn_id = '" . mysql_real_escape_string($idents['TxnID']) . "',
			order_txn_line_id = '" . mysql_real_escape_string($idents['TxnLineID']) . "',
			order_edit_seq = '".mysql_real_escape_string($idents['EditSequence'])."'
		WHERE
			order_id = " . (int) $ID);

	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_retail_invoice_import_response',$ID);
	}catch(Exception $ex){

	}
}

function _quickbooks_retail_invoice_edit_request($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $version, $locale)
{
	$invoice_details= "SELECT * FROM `order` o
							LEFT JOIN order_values ov ON ov.order_values_order_id = o.order_id
							LEFT JOIN packing_slip ps ON o.order_packing_slip_id = ps.id
							LEFT JOIN quickbook_retail_orders qro ON qro.order_id = o.order_id
							LEFT JOIN patient p ON patient_id = order_patient_id
							LEFT JOIN country co ON co.country_id = p.patient_country_id
							LEFT JOIN clinic c ON clinic_id = patient_clinic_id
							LEFT JOIN quickbook_patients qp ON qp.patient_id= p.patient_id
							LEFT JOIN clinic_address ca ON c.clinic_address_id = ca.clinic_address_id
							LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='".$ID."'";


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
					<InvoiceModRq requestID="'.$requestID.'">
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


	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'version'=>$version,'locale'=>$locale]);
		saveLogs($xml,$param_json,'_quickbooks_retail_invoice_edit_request',$ID);
	}catch(Exception $ex){

	}
	return $xml;

}

function _quickbooks_retail_invoice_edit_response($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	mysql_query("
		UPDATE
			quickbook_retail_orders
		SET
			is_synced = '3',
			order_list_id = '" . mysql_real_escape_string($idents['ListID']) . "',
			order_txn_id = '" . mysql_real_escape_string($idents['TxnID']) . "',
			order_txn_line_id = '" . mysql_real_escape_string($idents['TxnLineID']) . "',
			order_edit_seq = '".mysql_real_escape_string($idents['EditSequence'])."'
		WHERE
			order_id = " . (int) $ID);

	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_retail_invoice_edit_response',$ID);
	}catch(Exception $ex){

	}
}

function _quickbooks_customer_query_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	$clinic_query = "SELECT * FROM `clinic` WHERE clinic_id ='".$ID."'";


	//fetching the patients data into array
	$clinic_data = mysql_fetch_assoc(mysql_query($clinic_query));


	$xml = '<?xml version="1.0" encoding="utf-8"?>
	<?qbxml version="5.0"?>
	<QBXML>
		<QBXMLMsgsRq onError="continueOnError">
			<CustomerQueryRq requestID="'.$requestID.'" iterator="Start">
				<MaxReturned>5</MaxReturned>
				<NameFilter>
				<MatchCriterion >StartsWith</MatchCriterion>
				<Name >' . $clinic_data['clinic_name'] . '</Name>
				</NameFilter>
				<OwnerID>0</OwnerID>
			</CustomerQueryRq>
		</QBXMLMsgsRq>
	</QBXML>';

	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'version'=>$version,'locale'=>$locale]);
		saveLogs($xml,$param_json,'_quickbooks_customer_query_request',$ID);
	}catch(Exception $ex){

	}
	return $xml;
}

function _quickbooks_customer_query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	mysql_query("
		UPDATE
			quickbook_clinics
		SET
			is_synced = '3',
			clinic_list_id = '" . mysql_real_escape_string($idents['ListID']) . "',
			clinic_edit_seq = '" . mysql_real_escape_string($idents['EditSequence']) . "'
		WHERE
			clinic_id = " . (int) $ID);


	try{
		$param_json = json_encode(['requestID'=>$requestID,'user'=>$user,'action'=>$action,'id'=>$ID,'extra'=>$extra,'err'=>$err,'last_action_time'=>$last_action_time,'last_actionident_time'=>$last_actionident_time,'idents'=>$idents]);
		saveLogs($xml,$param_json,'_quickbooks_customer_query_response',$ID);
	}catch(Exception $ex){

	}
}

function saveLogs($xml,$param_json,$function_name,$param_id){
	$sql = "INSERT INTO quickbook_logs(param_id,function_name,param_json,xml) values('". mysql_real_escape_string($param_id)."',
	'". mysql_real_escape_string($function_name)."',
	'". mysql_real_escape_string($param_json)."',
	'". mysql_real_escape_string($xml)."'
	)";
	mysql_query($sql);
}
