<?php

/**
 *
 *	this is the soap server for sound orthotics quickbooks integration
 *
 */

// Set STRICT error mode
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// make sure the correct timezone is set
if (function_exists('date_default_timezone_set'))
{
	date_default_timezone_set('America/New_York');
}

// Require the framework
require_once '../QuickBooks.php';

// A username and password used in:
//	a) Your .QWC file
//	b) The Web Connector
//	c) The QuickBooks framework
//
// 	NOTE: This has *no relationship* with QuickBooks usernames, Windows usernames, etc.
// 	It is *only* used for the Web Connector and SOAP server

$user = 'Quickbooks';
$pass = 'password';


// Callback functions

$errmap = array(
);

// Map QuickBooks actions to handler functions
$map = array(
		QUICKBOOKS_ADD_CUSTOMER => array( '_quickbooks_customer_add_request', '_quickbooks_customer_add_response' ),
		QUICKBOOKS_ADD_INVOICE => array('_quickbooks_invoice_import_request','_quickbooks_invoice_import_request'),
		// QUICKBOOKS_ADD_SALESRECEIPT => array( '_quickbooks_salesreceipt_add_request', '_quickbooks_salesreceipt_add_response' ),
		'*' => array( '_quickbooks_customer_add_request', '_quickbooks_customer_add_response' ),
		'*' => array( '_quickbooks_invoice_import_request', '_quickbooks_invoice_import_request' ),
		// ... more action handlers here ...
);

// An array of callback hooks
$hooks = array(
		// QuickBooks_WebConnector_Handlers::HOOK_LOGINSUCCESS => '_quickbooks_hook_loginsuccess', 	// Run this function whenever a successful login occurs
);

// Logging level
//$log_level = QUICKBOOKS_LOG_NORMAL;
//$log_level = QUICKBOOKS_LOG_VERBOSE;
// $log_level = QUICKBOOKS_LOG_DEBUG;
$log_level = QUICKBOOKS_LOG_DEVELOP;

// What SOAP server you're using
//$soapserver = QUICKBOOKS_SOAPSERVER_PHP;			// The PHP SOAP extension, see: www.php.net/soap
$soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;		// A pure-PHP SOAP server (no PHP ext/soap extension required, also makes debugging easier)

$soap_options = array(		// as per http://www.php.net/soap
);

$handler_options = array(
		//'authenticate' => ' *** YOU DO NOT NEED TO PROVIDE THIS CONFIGURATION VARIABLE TO USE THE DEFAULT AUTHENTICATION METHOD FOR THE DRIVER YOU'RE USING (I.E.: MYSQL) *** '
		//'authenticate' => 'your_function_name_here',
		//'authenticate' => array( 'YourClassName', 'YourStaticMethod' ),
		'deny_concurrent_logins' => false,
		'deny_reallyfast_logins' => false,
);		// See the comments in the QuickBooks/Server/Handlers.php file

$driver_options = array(		// See the comments in the QuickBooks/Driver/<YOUR DRIVER HERE>.php file ( i.e. 'Mysql.php', etc. )
		//'max_log_history' => 1024,	// Limit the number of quickbooks_log entries to 1024
		//'max_queue_history' => 64, 	// Limit the number of *successfully processed* quickbooks_queue entries to 64
);

$callback_options = array(
);

// setup DSN

$dsn = 'mysql://root:@localhost/so_ocart_alt';


if (!QuickBooks_Utilities::initialized($dsn))
{
	// Initialize creates the neccessary database schema for queueing up requests and logging
	QuickBooks_Utilities::initialize($dsn);

	// This creates a username and password which is used by the Web Connector to authenticate
	QuickBooks_Utilities::createUser($dsn, $user, $pass);
	
}


// Create a new server and tell it to handle the requests
// __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()
$Server = new QuickBooks_WebConnector_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
$response = $Server->handle(true, true);

function _quickbooks_customer_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	
	// including the configuration file to get access to the credentials for database connection
	include "../../config.php";
	
	// connecting to the mysql database
	$a = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
	
	// selecting the database
	mysql_select_db(DB_DATABASE);
	
	$patient_details = "SELECT * FROM `order` o 
		LEFT JOIN order_values ON order_values_order_id = order_id
		LEFT JOIN patient p ON patient_id = order_patient_id
		LEFT JOIN country co ON co.country_id = p.patient_country_id
		LEFT JOIN clinic c ON clinic_id = patient_clinic_id
		LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
		LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE order_id='".$ID."'";

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
			
			$patient_notes = $patients_data['patient_notes'];
			
			
			
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="2.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<CustomerAddRq requestID="' . $requestID . '">
					<CustomerAdd>
						<Name>Option Matrix (' . mt_rand() . ')</Name>
						<CompanyName>Option Matrix</CompanyName>
						<FirstName>'.$patient_firstname.'</FirstName>
						<LastName>'.$patient_lastname.'</LastName>
						<BillAddress>
							<Addr1>'.$patient_addr_1.'</Addr1>
							<Addr2>'.$patient_addr_2.'</Addr2>
							<City>'.$patient_city.'</City>
							<State>CT</State>
							<PostalCode>'.$patient_postal_code.'</PostalCode>
							<Country>'.$patient_country_name.'</Country>
						</BillAddress>
						<Phone>'.$patient_telephone.'</Phone>
						<AltPhone>860-429-0021</AltPhone>
						<Fax>'.$patient_fax.'</Fax>
						<Email>'.$patient_email.'</Email>
						<Contact>Keith Palmer</Contact>
					</CustomerAdd>
				</CustomerAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

/**
 * Receive a response from QuickBooks
 *
 */
function _quickbooks_customer_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	// including the configuration file to get access to the credentials for database connection
	include "../../config.php";
	
	// connecting to the mysql database
	$a = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
	
	// selecting the database
	
	mysql_select_db(DB_DATABASE);
	
	mysql_query("
		UPDATE 	order SET 
			quickbooks_listid = '" . mysql_real_escape_string($idents['ListID']) . "', 
			quickbooks_editsequence = '" . mysql_real_escape_string($idents['EditSequence']) . "'
		WHERE 
			id = " . (int) $ID);	
}

/**
 * This function is used to import the invoice details of the customer orders  
 */

function _quickbooks_invoice_import_request($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// including the configuration file to get access to the credentials for database connection
	include "../../config.php";
	
	// connecting to the mysql database
	$a = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
	
	// selecting the database
	mysql_select_db(DB_DATABASE);
	
	$orders_data = "SELECT * FROM `order` o
		LEFT JOIN order_values ON order_values_order_id = order_id
		LEFT JOIN patient p ON patient_id = order_patient_id
		LEFT JOIN country co ON co.country_id = p.patient_country_id
		LEFT JOIN clinic c ON clinic_id = patient_clinic_id
		LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
		LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE order_id='".$ID."'";
	
	//fetching the patients data into array
	$data_orders = mysql_fetch_assoc(mysql_query($orders_data));

	//storing the order values in variables
	
	$order_quantity = $data_orders['order_quantity'];
	
	$order_tax_totals = $data_orders['order_total'];
	
	
	$xml = '<?xml version="1.0" encoding="utf-8"?>
			<?qbxml version="2.0"?>
			<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
			<InvoiceAddRq>
			<InvoiceAdd>
			<CustomerRef>
			<FirstName>shailesh</FirstName>
			</CustomerRef>
			<TemplateRef>
			<FullName>Paris - red</FullName>
			</TemplateRef>
			<InvoiceLineAdd>
			<ItemRef>
			<FullName>Desks - 5</FullName>
			</ItemRef>
			<Desc>Garbanzo Bean Colored Desk</Desc>
			<Quantity>10</Quantity>
			<Rate>5.50</Rate>
			</InvoiceLineAdd>
			<InvoiceLineAdd>
			<ItemRef>
			<FullName>Installation</FullName>
			</ItemRef>
			<Desc>Crew of 16 people, for 16 days, for 16 jobs.</Desc>
			<Quantity>16</Quantity>
			<Rate>16.00</Rate>
			<SalesTaxCodeRef>
			<FullName>TAX</FullName>
			</SalesTaxCodeRef>
			</InvoiceLineAdd>
			<InvoiceLineAdd>
			<ItemRef>
			<FullName>Conference Table - 4</FullName>
			</ItemRef>
			<Desc>Maple wood boat shaped conferance table.  Seats 42.</Desc>
			<Quantity>2</Quantity>
			<Amount>6000.00</Amount>
			<SalesTaxCodeRef>
			<FullName>NON</FullName>
			</SalesTaxCodeRef>
			</InvoiceLineAdd>
			</InvoiceAdd>
			</InvoiceAddRq>
			</QBXMLMsgsRq>
			</QBXML>';
	
			return $xml;
	

	
}

function _quickbooks_invoice_import_response($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	
	
}

function _quickbooks_salesreceipt_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{

	$xml = '<?xml version="1.0" encoding="utf-8"?>
			<?qbxml version="2.0"?>
			<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
			<SalesReceiptAddRq requestID="' . $requestID . '">
					<SalesReceiptAdd>
					<CustomerRef>
					<FullName>Keith Palmer Jr.</FullName>
					</CustomerRef>
					<TxnDate>2009-01-09</TxnDate>
					<RefNumber>16466</RefNumber>
					<BillAddress>
					<Addr1>Keith Palmer Jr.</Addr1>
					<Addr3>134 Stonemill Road</Addr3>
					<City>Storrs-Mansfield</City>
					<State>CT</State>
					<PostalCode>06268</PostalCode>
					<Country>United States</Country>
					</BillAddres>
					<SalesReceiptLineAdd>
					<ItemRef>
					<FullName>Gift Certificate</FullName>
					</ItemRef>
					<Desc>$25.00 gift certificate</Desc>
					<Quantity>1</Quantity>
					<Rate>25.00</Rate>
					<SalesTaxCodeRef>
					<FullName>NON</FullName>
					</SalesTaxCodeRef>
					</SalesReceiptLineAdd>
					<SalesReceiptLineAdd>
					<ItemRef>
					<FullName>Book</FullName>
					</ItemRef>
					<Desc>The Hitchhiker\'s Guide to the Galaxy</Desc>
					<Amount>19.95</Amount>
					<SalesTaxCodeRef>
					<FullName>TAX</FullName>
					</SalesTaxCodeRef>
					</SalesReceiptLineAdd>
					</SalesReceiptAdd>
					</SalesReceiptAddRq>
					</QBXMLMsgsRq>
					</QBXML>';

	return $xml;
}


function _quickbooks_salesreceipt_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{

}