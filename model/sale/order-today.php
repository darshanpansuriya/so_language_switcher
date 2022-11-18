<?php

class ModelSaleOrder extends Model {

	public function addOrder($data) {
	
	date_default_timezone_set('America/Toronto');

		// p(__LINE__.__FILE__); exit;
		if (isset($data['order_values_left_50']))
			$data['order_values_left_50'] = implode(",", $data['order_values_left_50']);
		elseif (isset($data['order_values_right_50']))
		$data['order_values_right_50'] = implode(",", $data['order_values_right_50']);
		elseif (isset($data['order_values_60']) && $data['order_values_60'] != '254' && isset($_SESSION['s_order_id']) && $_SESSION['s_order_id']) {
			exec('rm -rf hc_scripts/plupload/uploads/' . $_SESSION['s_order_id']);
		}

		$o = "INSERT INTO `order` SET order_date_added = NOW()";
			
		if ($data['patient_id'])
			$op = "UPDATE patient SET patient_date_modified = NOW(), patient_status = '1'";
		else
			$op = "INSERT INTO patient SET patient_date_added = NOW(),patient_status = 1";


		if(isset($data['order_values_left_56'])) {
			if ($data['order_values_left_56'] == '322')
				$data['order_values_left_23'] = '114';

		}
			
		if(isset($data['order_values_right_56'])) {
			if ($data['order_values_right_56'] == '322')
				$data['order_values_right_23'] = '114';

		}

		if(isset($data['order_values_left_72'])) {
			if ($data['order_values_left_72'] == '331')
				$data['order_values_left_73'] = '338';
		}

		if(isset($data['order_values_right_72'])) {
			if ($data['order_values_right_72'] == '331')
				$data['order_values_right_73'] = '338';
		}
			

		$ov = "";


		foreach ($data as $key => $val) {

			if (substr($key, 0, 12) == 'order_values') {


				$exist = $this->db->query("SHOW COLUMNS FROM `order_values` LIKE '$key'");

				if (!$exist->num_rows) {

					echo "ALTER TABLE `order_values` ADD COLUMN $key VARCHAR(50) NOT NULL <br>;";
					$this->db->query("ALTER TABLE `order_values` ADD COLUMN $key VARCHAR(50) NOT NULL");
				}

				$ov .= ", $key = '" . $this->db->escape($val) . "'";
			} elseif (substr($key, 0, 13) == 'order_history') {
				// skip this value for order history comment as it is stored separately in order history table
			} elseif (substr($key, 0, 5) == 'order') {
				if (substr($key, -4) == 'date' || $key == 'order_date_needed')
					$o .= ", $key = '" . $this->db->escape($this->hc_functions->ch_date($val)) . "'";
				else
					$o .= ", $key = '" . $this->db->escape($val) . "'";
			} elseif (substr($key, 0, 7) == 'patient'){
				$op .= ", $key = '" . $this->db->escape($val) . "'";
			}
		}

		if ($data['patient_id']) {

			$patient_id = $data['patient_id'];

			$op = str_replace("INSERT INTO", "UPDATE", $op) . " WHERE patient_id = '$patient_id'";

		}

			

		$this->db->query($op);
		//p(__LINE__);
		if (!$data['patient_id'])
			$patient_id = $this->db->getLastId();

		$o .= ", order_patient_id = '$patient_id', order_originaldelivery = '" . $this->hc_functions->ch_date($this->db->escape(strip_tags($data['order_deliverydate']))) . "'";

		$this->db->query($o);

		$order_id = $this->db->getLastId();
		if (substr($_SESSION['s_order_id'], 0, 4) == 'temp') {
			if (file_exists("hc_scripts/plupload/uploads/" . $_SESSION['s_order_id']))
				rename("hc_scripts/plupload/uploads/" . $_SESSION['s_order_id'], "hc_scripts/plupload/uploads/" . $order_id);
			$_SESSION['s_order_id'] = $order_id;
		}
			





		// updating the priority of the enqueue command
		//$this->db->query("UPDATE quickbooks_queue SET priority = '1' WHERE ident = '".$patient_id."'");

		////////////

		$ov = "INSERT INTO order_values SET order_values_order_id = '$order_id'" . $ov;
		$this->db->query($ov);

		date_default_timezone_set('America/Toronto');
		$current_date_time = date('Y-m-d H:i:s');

		$this->session->order_id = $order_id;

		// Add order setup status to order_history table
		$s = "INSERT INTO order_history SET
		order_history_order_id = '$order_id',
		order_history_order_status_id = '" . $data['order_status_id'] . "',
				order_history_date = NOW(),
				order_history_user_id = '" . $this->session->data['user_id'] . "',
						order_history_comment = '" . $this->db->escape($data['order_history_comment']) . "'";


		$this->db->query($s);


	}

	//////////////////////////////
	public function editOrder($order_id, $data) {
	
		date_default_timezone_set('America/Toronto');

		/**
			this block is executed to fetch the value of the current orthotic shell type
			*/
		// defining the query
		$s = "SELECT order_values_7,order_status_id FROM
				`order_values` ov LEFT JOIN `order` o ON o.order_id = ov.order_values_order_id WHERE order_values_order_id = '".$data['order_id'] . "'";
			
		// executing the query and storing the result set
		$result = mysql_query($s);
		// fetching the result
		$existing_order_value = mysql_fetch_object($result);
		// storing the result in a variable fetched from my sql object
		$previous_order_value =  $existing_order_value->order_values_7;
		$previous_order_status = $existing_order_value->order_status_id;
			
		/********************************************************************/

		if (isset($data['order_values_left_50'])){

			$data['order_values_left_50'] = implode(",", $data['order_values_left_50']);
		}
		if (isset($data['order_values_right_50'])){

			$data['order_values_right_50'] = implode(",", $data['order_values_right_50']);
		}
		elseif (isset($data['order_values_60']) && $data['order_values_60'] != '254') {
			exec('rm -rf hc_scripts/plupload/uploads/' . $_SESSION['s_order_id']);
		}

		if (isset($data['order_values_left_56']) && $data['order_values_left_56'] == '322')
			$data['order_values_left_23'] = '114';
		if (isset($data['order_values_right_56']) && $data['order_values_right_56'] == '322')
			$data['order_values_right_23'] = '114';
		if (isset($data['order_values_left_72']) && $data['order_values_left_72'] == '331')
			$data['order_values_left_73'] = '338';
		if (isset($data['order_values_right_72']) && $data['order_values_right_72'] == '331')
			$data['order_values_right_73'] = '338';

		$ov = "";
		$data['order_values_order_id'] = $order_id;

		$ordervals = $this->db->query("SHOW COLUMNS FROM `order_values`");

		//p($ordervals->rows,__LINE__.__FILE__);
		// p($data,__LINE__.__FILE__);

		foreach ($ordervals->rows as $v) {
			if (!isset($data[$v['Field']])) {
				$data[$v['Field']] = '';
				//				echo $v['Field'] . ' . . ';
			}
		}

		//p($data,__LINE__.__FILE__);
		$o = "UPDATE `order` SET order_date_modified = NOW()";
		$op = "UPDATE `patient` SET patient_date_modified = NOW()";
		//p($data);
		foreach ($data as $key => $val) {
			if (substr($key, 0, 12) == 'order_values') {


				$exist = $this->db->query("SHOW COLUMNS FROM `order_values` LIKE '$key'");

				if (!$exist->num_rows) {
					$this->db->query("ALTER TABLE `order_values` ADD COLUMN $key VARCHAR(50) NOT NULL");
				}

				$ov .= ", $key = '" . $this->db->escape($val) . "'";
			} elseif (substr($key, 0, 13) == 'order_history') {
				if ($this->db->escape(strip_tags($data['order_history_comment']))) {
					// $this->db->query("INSERT INTO order_history SET order_history_order_id = '" . (int) $order_id . "', order_history_order_status_id = '" . (int) $data['order_status_id'] . "', order_history_comment = '" . $this->db->escape(strip_tags($data['order_history_comment'])) . "', order_history_date = NOW(), order_history_user_id = '" . $this->session->data['user_id'] . "'");
				}
			} elseif (substr($key, 0, 5) == 'order') {
					
				if (substr($key, -4) == 'date' || $key == 'order_date_needed')
					$o .= ", $key = '" . $this->db->escape($this->hc_functions->ch_date($val)) . "'";
				else
					$o .= ", $key = '" . $this->db->escape($val) . "'";
			} elseif (substr($key, 0, 7) == 'patient') {

				$op .= ", $key = '" . $this->db->escape($val) . "'";

				// if (substr($key, -3) == 'dob'){
				// 	$op .= ", $key = '" .$this->db->escape($this->hc_functions->ch_date($val)) . "'";
				//  }
			}
		}


			

		if ((int) $data['order_status_id'] < 30) { // if still in clinic
			$o .= ", order_originaldelivery = '" . $this->hc_functions->ch_date($this->db->escape(strip_tags($data['order_deliverydate']))) . "'";
		}
		$o .= " WHERE order_id = '$order_id'";
		//p($o,__LINE__.__FILE__); exit;
			
		$this->db->query($o);

		$ov = "UPDATE order_values SET " . substr($ov, 1) . " WHERE  order_values_order_id = '$order_id'";
		//p($ov,__LINE__.__FILE__); exit;

		$this->db->query($ov);

		$op .= " WHERE patient_id = '" . $data['patient_id'] . "'";
		//p($op,__LINE__.__FILE__); exit;


		$this->db->query($op);
		// here i will add order status to order_history table also, if status changed
		if (isset($data['history_updated']) && $data['history_updated']) {
			$s = "INSERT INTO order_history SET
			order_history_order_id = '$order_id',
			order_history_order_status_id = '" . $data['order_status_id'] . "',
					order_history_date_added = NOW(),
					order_history_comment = '" . $this->db->escape($data['order_history_comment']) . "'";

			$this->db->query($s);
		}
		/********************************************************************/
		// this block checks for the orthotic shell type is same or not if not it updates the order staus
		// to design
		if ($previous_order_value != $data['order_values_7']){

			if($previous_order_status > '40' && $previous_order_status < '90'){

				//executing the query to update the order status to design
				$this->db->query("UPDATE `order` SET order_status_id = '50' WHERE order_id = '".$data['order_id']."'");
			}
		}
		/********************************************************************/

	}

	public function deleteOrder($order_id) {//

		$this->db->query("DELETE FROM `order` WHERE order_id = '" . (int) $order_id . "'");
		$this->db->query("DELETE FROM order_history WHERE order_history_order_id = '" . (int) $order_id . "'");
		$this->db->query("DELETE FROM order_values WHERE order_values_order_id = '" . (int) $order_id . "'");
		exec('rm -rf hc_scripts/plupload/uploads/' . (int) $order_id);
	}

	public function updateOrderPackingList($packing_slip_id)
	{

		$query_update_packing_slip = "SELECT orders FROM packing_slip WHERE id = '".$packing_slip_id."'";

		$query = $this->db->query($query_update_packing_slip);

		$packing_slips_orders = $query->row['orders'];

		foreach(explode(',',$packing_slips_orders) as $order_id){

			$this->db->query("UPDATE `order` SET order_packing_slip_id = '" . $packing_slip_id . "' WHERE order_id = '" . (int) $order_id . "'");

		}
	}

	public function updateShippingAddress($order_id, $data) {
		$this->db->query("UPDATE `order` SET shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int) $data['shipping_zone_id'] . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int) $data['shipping_country_id'] . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
	}

	public function updatePaymentAddress($order_id, $data) {
		$this->db->query("UPDATE `order` SET payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int) $data['payment_zone_id'] . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int) $data['payment_country_id'] . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
	}

	public function addOrderHistory($order_id, $data) {


		//         if ((int) $data['order_status_id'] == 35) {
		//             $time = date("H");
		//             if ($time < "12")
		//             {
		//                 $day = date("Y-m-d", strtotime("+5 weekdays"));
		//             }
		//             else{
		//                 $day = date("Y-m-d", strtotime("+6 weekdays"));
		//             }

		//             if (date("N", strtotime($day)) == 6 || date("N", strtotime($day)) == 7)
		//                 $day = date("Y-m-d", strtotime($day . " +" . (8 - date("N", strtotime($day))) . " days"));

		//             $order_deliverydate = $day;

		//             $getorderdate = $this->db->query("SELECT order_deliverydate FROM `order` WHERE order_id = '$order_id'");

		//             if ($getorderdate->row['order_deliverydate'] < $order_deliverydate) {
		//                 $this->db->query("UPDATE `order` SET order_deliverydate = '$order_deliverydate' WHERE order_id = '$order_id'");
		//             }
		//         }

		/**
		 * this block updates the delivery date when the order is sent to lab
		 */
		if ((int) $data['order_status_id'] == 30) {

			date_default_timezone_set('America/Toronto');

			$time = date("H");
			if ($time < "12")
			{
				$day = date("Y-m-d", strtotime("+4 weekdays"));
			}
			else{
				$day = date("Y-m-d", strtotime("+5 weekdays"));
			}

			if (date("N", strtotime($day)) == 6 || date("N", strtotime($day)) == 7)
				$day = date("Y-m-d", strtotime($day . " +" . (8 - date("N", strtotime($day))) . " days"));

			$order_deliverydate = $day;

			$getorderdate = $this->db->query("SELECT order_deliverydate, order_date_needed FROM `order` WHERE order_id = '$order_id'");

			if ($getorderdate->row['order_deliverydate'] < $order_deliverydate) {
				$this->db->query("UPDATE `order` SET order_deliverydate = '$order_deliverydate' WHERE order_id = '$order_id'");
			}
			//this block is written to update the rush dates
			if ($getorderdate->row['order_date_needed'] < date("Y-m-d")) {

				$this->db->query("UPDATE `order` SET order_date_needed = '$order_deliverydate' WHERE order_id = '$order_id'");
			}

		}

		$this->db->query("UPDATE `order` SET order_status_id = '" . (int) $data['order_status_id'] . "', order_date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");


		$this->db->query("INSERT INTO order_history SET order_history_order_id = '" . (int) $order_id . "', order_history_order_status_id = '" . (int) $data['order_status_id'] . "', order_history_comment = '" . $this->db->escape(strip_tags($data['order_history_comment'])) . "', order_history_date = '".date('Y-m-d H:i:s')."', order_history_user_id = '" . $this->session->data['user_id'] . "'");
			

	}

	public function getItemName($id) {

		$s = "SELECT lookup_table_title FROM lookup_table WHERE lookup_table_id = " . (int) $id;

		$order_query = $this->db->query($s);

		if ($order_query->num_rows) {

			foreach ($order_query->row as $k => $v) {
				return $v;
			}
			return false;
		} else {
			return FALSE;
		}
	}

	public function getOrder($order_id) {

		$s = "SELECT * FROM `order` o
				LEFT JOIN order_values ON order_values_order_id = order_id
				LEFT JOIN patient p ON patient_id = order_patient_id
				LEFT JOIN clinic c ON clinic_id = patient_clinic_id
				LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
				LEFT JOIN clinician cl ON patient_clinician_id = clinician_id
				LEFT JOIN user_group ON user_group_step_display LIKE CONCAT('%\"',o.order_status_id,'\"%')

				WHERE order_id = '" . (int) $order_id . "' AND user_group_step_display IS NOT NULL";


		$user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false);
		$user_group_string = $this->user->getUGstr();

		if (in_array('11', explode(',', $user_group_string)))
			$s .= " AND clinic_id IN (SELECT user_to_clinic_clinic_id FROM user_to_clinic WHERE user_to_clinic_user_id = '$user_id')";

		$order_query = $this->db->query($s);

		//		p($order_query,__LINE__.__FILE__);

		if ($order_query->num_rows) {

			$order_data = array();

			foreach ($order_query->row as $k => $v) {
				$order_data[$k] = $v;
			}

			// p(	$order_data	,__LINE__.__FILE__);
			return $order_data;
		} else {
			return FALSE;
		}
	}

	public function getShipAddressFromOrder($order_id){
		$sql = "SELECT `order`.`order_shipping_address` FROM `order` WHERE (`order`.`order_id`='" . $order_id . "');";
		$query = $this->db->query($sql);
		return $query;
	}

	public function insertPackingSlip($orders, $ship_date, $create_date, $ship_via, $ship_to) {

		$ship_address = mysql_real_escape_string($ship_to);
		$sql = "INSERT INTO packing_slip(orders, ship_date, create_date, ship_via, ship_to) VALUES ('" . $orders . "','" . $ship_date . "','" . $create_date . "','" . $ship_via . "','" . $ship_address . "')";
		$query = $this->db->query($sql);

		return $query;

	}

	public function quickbooksQueue($packing_list_numbers) {

		$packing_list_ids = explode(',', $packing_list_numbers);

		foreach ($packing_list_ids as $packing_list_id)
		{
			$packing_list_orders = mysql_query("SELECT orders FROM  `packing_slip` WHERE  `id` ='".$packing_list_id."'");

			$orders = mysql_fetch_row($packing_list_orders);

			$packing_slip_order = $orders[0];

			// checking the orders which got clinic_id as 1 and seperating them from wholesale list

			$retail_orders = mysql_query("SELECT * FROM `order` o
					LEFT JOIN `patient` p ON o.order_patient_id = p.patient_id
					WHERE o.order_id IN ('".$packing_slip_order."')  AND p.patient_clinic_id = 1");

			$order_clinic = mysql_fetch_object($retail_orders);

			$clinic_id = '';

			if($order_clinic)
			{
				$clinic_id = $order_clinic->patient_clinic_id;
			}


			if ($clinic_id == 1)
			{
				$packing_retail_orders = explode(',', $packing_slip_order);
					
					
				foreach ($packing_retail_orders as $packing_retail_order)
				{

					// checking if the order exists it will be updated otherwise it will be queued up as a fresh entry
					// to the quickbooks

					$order_check = mysql_query("SELECT * FROM `quickbook_retail_orders` WHERE order_id='".$packing_retail_order."'");

					$order_verify = mysql_fetch_object($order_check);

					if (!$order_verify)
					{
						$order_clinic_id =  mysql_query("SELECT * FROM `order` o
								LEFT JOIN patient p ON patient_id = order_patient_id
								LEFT JOIN clinic c ON clinic_id = patient_clinic_id
								LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
								LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='".$packing_retail_order."'");
							
						$order_clinic = mysql_fetch_object($order_clinic_id);
							
						$patient_id = $order_clinic->patient_id;
							
						$order_quantity = $order_clinic->order_quantity;
							
						// including the config for qb queueing

						require_once 'so_app_web_connector/config.php';

$dsn = 'mysql://dbo562806560:9bpSTW-_O@d!@localhost:/tmp/mysql5.sock/db562806560';
						$Queue = new QuickBooks_WebConnector_Queue($dsn);


						// query if the clinic exists in the database or not  then insert the record in the quickbooks clinics

						$patient_check =  mysql_query("SELECT * FROM `quickbook_patients` WHERE patient_id='".$patient_id."'");

						$patient_verify = mysql_fetch_object($patient_check);

						if(!$patient_verify){

							// inserting clinic details to the quickbooks table

							mysql_query("INSERT INTO `quickbook_patients`(patient_id) VALUES ('".$patient_id."')");

						}
							
						// insert order details to orders table

						mysql_query("INSERT INTO `quickbook_retail_orders`
								(order_id,order_quantity)
								VALUES ('".$packing_retail_order."','".$order_quantity."')");


						mysql_query("UPDATE `quickbook_patients` SET is_synced = '2' where `patient_id`=".$patient_id);

						mysql_query("UPDATE `quickbook_retail_orders` SET is_synced = '2' where `order_id`=".$packing_retail_order);
							
							
						/**
						 * this block is responsible for queue up the requests to the
						 * quickbooks web connector
						*/


						// checking if the patient doesn't exists then only create new record
						if (!$patient_verify){

							$patient_priority = '10';

							$Queue->enqueue(QUICKBOOKS_RETAIL_ADD_CUSTOMER, $patient_id,$patient_priority);

						}

						// adding the invoice for the order

						$invoice_priority = '5';

						$Queue->enqueue(QUICKBOOKS_RETAIL_ADD_INVOICE,$packing_retail_order,$invoice_priority);
							
					}
					else
					{
							
						// getting the order quantity and clinic id for the current order
							
						$order_clinic_id =  mysql_query("SELECT * FROM `order` o
								LEFT JOIN patient p ON patient_id = order_patient_id
								LEFT JOIN clinic c ON clinic_id = patient_clinic_id
								LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
								LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='".$packing_retail_order."'");
							
						$order_clinic = mysql_fetch_object($order_clinic_id);
							
						$patient_id = $order_clinic->patient_id;
							
						$order_quantity = $order_clinic->order_quantity;
							
							
						// inserting order details to the quickbooks table
							
						mysql_query("UPDATE `quickbook_retail_orders`	SET is_synced = '1', order_quantity ='".$order_quantity."'
								WHERE order_id = '".$packing_retail_order."'");
							

						// this block is responsible for queue up the requests to the
						// quickbooks web connector
							
							
						// including the config for qb queueing
							
						require_once 'so_app_web_connector/config.php';
							
						mysql_query("UPDATE `quickbook_patients` SET is_synced = '2' where `patient_id`=".$patient_id);
							
						mysql_query("UPDATE `quickbook_orders` SET is_synced = '2' where `order_id`=".$packing_retail_order);
							
						$dsn = 'mysql://soundort_sound:password1!@localhost/soundort_admin_alt';
							
						$Queue = new QuickBooks_WebConnector_Queue($dsn);
							
						// initialising  priorities for the enqueue operations
							
						$patient_priority= '10';
						$invoice_priority = '5';
							
						$Queue->enqueue(QUICKBOOKS_RETAIL_MOD_CUSTOMER,$patient_id,$patient_priority);
							
						$Queue->enqueue(QUICKBOOKS_RETAIL_MOD_INVOICE,$packing_retail_order,$invoice_priority);
							
							
							
					}



				}
					
			}
			else
			{

				$packing_orders = explode(',', $packing_slip_order);

				
				$clinic_primary_qty = '';
				
				foreach ($packing_orders as $packing_order)
				{

					$packing_single_order = $packing_order;

					$order_first_clinic_id =  mysql_query("SELECT * FROM `order` o
							LEFT JOIN patient p ON patient_id = order_patient_id
							LEFT JOIN clinic c ON clinic_id = patient_clinic_id
							LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
							LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='".$packing_single_order."'");

					$order_primary_clinic = mysql_fetch_object($order_first_clinic_id);

					$clinic_id = '';

					$clinic_primary_id = $order_primary_clinic->clinic_id;
					
					$clinic_primary_qty = $order_primary_clinic->order_quantity;

					$matched_orders = '';
					$matched_clinic_orders = '';
					
					foreach ($packing_orders as $packing_second_order)
					{
						$order_second_clinic_id =  mysql_query("SELECT * FROM `order` o
								LEFT JOIN patient p ON patient_id = order_patient_id
								LEFT JOIN clinic c ON clinic_id = patient_clinic_id
								LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id
								LEFT JOIN clinician cl ON patient_clinician_id = clinician_id  WHERE o.order_id='".$packing_second_order."'");

						$order_secondary_clinic = mysql_fetch_object($order_second_clinic_id);

						$clinic_secondary_id = $order_secondary_clinic->clinic_id;						

						if ($clinic_primary_id == $clinic_secondary_id)
						{

							$matched_orders .= $packing_second_order.',';							

						}

					}

					$matched_clinic_orders = rtrim($matched_orders,',');					
					
					
					// mysql_query("INSERT INTO quickbooks_orders SET clinic_id = '".$clinic_primary_id."', clinic_orders= '".$matched_clinic_orders."', packing_id='".$packing_list_id."'");

					// including the config for qb queueing

					require_once 'so_app_web_connector/config.php';

					$dsn = 'mysql://soundort_sound:password1!@localhost/soundort_admin_alt';

					$Queue = new QuickBooks_WebConnector_Queue($dsn);



					$query_priority = '20';

					$Queue->enqueue(QUICKBOOKS_QUERY_CUSTOMER,$clinic_primary_id,$query_priority);
					
					
					
					$clinic_check =  mysql_query("SELECT * FROM `quickbook_clinics` WHERE clinic_id='".$clinic_primary_id."'");

							$clinic_verify = mysql_fetch_object($clinic_check);

							if(!$clinic_verify){
							
							// inserting clinic details to the quickbooks table

								mysql_query("INSERT INTO `quickbook_clinics`(clinic_id) VALUES ('".$clinic_primary_id."')");

						}
						// insert order details to orders table
						mysql_query("INSERT INTO `quickbook_orders`
					 					(order_id,clinic_id,order_included,order_quantity)
										VALUES ('".$packing_list_id."','".$clinic_primary_id."','".$matched_clinic_orders."','".$clinic_primary_qty."')");
					
					

					 					mysql_query("UPDATE `quickbook_clinics` SET is_synced = '2' where `clinic_id`=".$clinic_primary_id);

					 					mysql_query("UPDATE `quickbook_orders` SET is_synced = '2' where `order_id`=".$packing_list_id);

			
					// adding the invoice for the order
					
							$invoice_priority = '10';
								
							$Queue->enqueue(QUICKBOOKS_ADD_INVOICE,null,$invoice_priority, array( 'packing_id' => $packing_list_id, 'clinic_id' => $clinic_primary_id ));

				}
			}
		}
			
	}

	public function updateOrderStatusShipped($order_id) {
		$sql = "UPDATE `order` SET order_status_id = 150 WHERE order_id = " . $order_id;
		$query = $this->db->query($sql);
		return $query;
	}
	/**
	 * this function updates the order history in the table regarding the change of status for an order
	 * @param unknown_type $order_id
	 * @param unknown_type $user_id
	 */
	public function updateOrderHistory($packing_slip_id) {

		$query_update_packing_slip = "SELECT orders FROM packing_slip WHERE id = '".$packing_slip_id."'";

		$query = $this->db->query($query_update_packing_slip);

		$packing_slips_orders = $query->row['orders'];

		foreach(explode(',',$packing_slips_orders) as $order_id){

			$this->db->query("INSERT INTO `order_history` SET order_history_order_status_id = 150,order_history_user_id = ".$_SESSION['user_id']. ",order_history_comment= 'Packing slip generated',order_history_date= NOW() ,order_history_order_id = " . $order_id);

		}

	}


	public function getPackingSlip($id) {
		$sql = "SELECT * FROM `packing_slip` p WHERE p.id = " . $id;
		$query = $this->db->query($sql);
		$GLOBALS['number_of_orders'] = $query->totals;
		return $query->rows;
	}

	public function getOrdersForPackingSlip($ids) {
		$sql = "SELECT * FROM `order` o WHERE o.order_id IN (" . $ids . ")";
		$query = $this->db->query($sql);
		$GLOBALS['number_of_orders'] = $query->totals;
		return $query->rows;
	}

	//function to get the clinics for the packing slips
	public  function  getPackingSlipClinics($slip_id){
			
		$sql = "SELECT DISTINCT clinic_id FROM `order` o
				LEFT JOIN patient p ON patient_id = order_patient_id
				LEFT JOIN clinic c ON clinic_id = patient_clinic_id
				WHERE order_packing_slip_id = '".$slip_id."'";
		$query = $this->db->query($sql);
		return $query->rows;
			
	}

	// function to get the packing slip orders with same clinic_id and packing_slip_id
	public  function  getPackingSlipClinicsOrders($slip_id,$clinic_id){
			
		$sql = "SELECT * FROM `order` o
				LEFT JOIN order_values ON order_values_order_id = order_id
				LEFT JOIN patient p ON patient_id = order_patient_id
				LEFT JOIN clinic c ON clinic_id = patient_clinic_id
				WHERE clinic_id = '".$clinic_id."' AND order_packing_slip_id = '".$slip_id."' ORDER BY order_id";
		$query = $this->db->query($sql);
		return $query->rows;

	}

	// this function gets the clinic name based on clinic id

	public function getClinicName($clinic_id){

		$sql = "SELECT clinic_name FROM clinic WHERE clinic_id = '".$clinic_id."'";

		$query = $this->db->query($sql);

		return $query->rows;

	}

	public function getOrders($data = array()) {

		$user_id = $this->session->data['user_id'];

		$user_group_string = $this->user->getUGstr();

		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT  o.order_id,clinic_name,	
				CONCAT(p.patient_firstname, ' ',
				p.patient_lastname) AS patient_name,
				order_date_added,
				order_status_name AS status,
				order_deliverydate,
				order_originaldelivery,
				order_date_needed,
				order_shipping_address,
				order_shipping_number,
				order_shipping_method,
				order_contact_name,
				order_shipping_date,
				order_total,
				o.order_status_id,
				order_values_rush_75,
				order_values_7,
				order_currency_id, ug.* FROM `order` o

				LEFT JOIN order_status os
				ON os.order_status_id = o.order_status_id
				LEFT JOIN order_values ov
				ON order_values_order_id = o.order_id
				LEFT JOIN patient p
				ON patient_id = order_patient_id
				LEFT JOIN clinic c
				ON patient_clinic_id = clinic_id
				LEFT JOIN user_to_clinic
				ON clinic_id = user_to_clinic_clinic_id";
		//p($user_group_string);
		if (in_array('11', explode(',', $user_group_string))) {
			$sql .= "
					LEFT JOIN `user` u
					ON user_to_clinic_user_id = user_id
					LEFT JOIN user_group ug
					ON FIND_IN_SET (user_group_id,user_group_string) > 0";
		} else {
			$sql .= "
					LEFT JOIN user_group ug
					ON LOCATE(CONCAT('\"',o.order_status_id,'\"'),user_group_step_display) > 0
					LEFT JOIN `user` u
					ON FIND_IN_SET(ug.user_group_id,u.user_group_string) > 0";
		}
		// 252 AND ug.user_group_id = '$user_group_id'";

		$sql .= "
		WHERE
		user_id = '$user_id'";


		if (in_array('11', explode(',', $user_group_string))) {
			$sql .= "
			AND clinic_id IN
			(SELECT user_to_clinic_clinic_id FROM user_to_clinic
			WHERE user_to_clinic_user_id = '$user_id')
			AND FIND_IN_SET(ug.user_group_id,u.user_group_string) > 0";
		}


		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= "
					AND o.order_status_id = '" . (int) $data['filter_status'] . "'";
		} else {
			$sql .= "
					AND o.order_status_id > '0'";
		}


		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= "
					AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
		}

		if (isset($data['filter_clinic_name']) && !is_null($data['filter_clinic_name'])) {
			$sql .= "
					AND LCASE(clinic_name) LIKE '%" . strtolower($this->db->escape($data['filter_clinic_name'])) . "%'";
		}

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= "
					AND DATE(order_date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		// Mod Delivery Date
		if (isset($data['filter_deliverydate']) && !is_null($data['filter_deliverydate'])) {
			$sql .= "
					AND (DATE(order_deliverydate) = DATE('" . $this->db->escape($data['filter_deliverydate']) . "')";
			$sql.="OR (DATE(order_date_needed) = DATE('" . $this->db->escape($data['filter_deliverydate']) . "') AND ov.order_values_rush_75='342'))";
		}
		// End:Mod Delivery Date

		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= "
					AND order_total = '" . (float) $data['filter_total'] . "'";
		}


		if (isset($data['filter_patient_name']) && !is_null($data['filter_patient_name'])) {
			$sql .= "
					HAVING
					LCASE(patient_name) LIKE '%" . strtolower($this->db->escape($data['filter_patient_name'])) . "%'";
		}


		$sort_data = array(
				'order_id',
				'clinic_name',
				'patient_name',
				'status',
				'order_date_added',
				'order_deliverydate',
				'order_total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {

			if ($data['sort'] == 'order_id')
				$sql .= " ORDER BY order_id ";
			else
				$sql .= " ORDER BY LOWER(" . $data['sort'] . ")";
		} else {
			$sql .= " ORDER BY order_deliverydate";
		}
		//p($data,__LINE__);
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= "
					LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		}
		//p($sql, __LINE__.__FILE__);

		//echo $sql;
		//die();

		$query = $this->db->query($sql);
		//p($query);
		$GLOBALS['number_of_orders'] = $query->totals;

		return $query->rows;
	}

	public function generateInvoiceId($order_id) {
		$query = $this->db->query("SELECT MAX(invoice_id) AS invoice_id FROM `order`");

		if ($query->row['invoice_id']) {
			$invoice_id = (int) $query->row['invoice_id'] + 1;
		} elseif ($this->config->get('config_invoice_id')) {
			$invoice_id = $this->config->get('config_invoice_id');
		} else {
			$invoice_id = 1;
		}

		$this->db->query("UPDATE `order` SET invoice_id = '" . (int) $invoice_id . "', invoice_prefix = '" . $this->db->escape($this->config->get('config_invoice_prefix')) . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");

		return $this->config->get('config_invoice_prefix') . $invoice_id;
	}

	public function getOrderProducts($order_id) {
		$sql = "SELECT *,op.name AS name, op.quantity AS quantity, m.name AS mname FROM order_product op";

		$user_group_string = $this->user->getUGstr();

		$sql .= " LEFT JOIN product p ON p.product_id = op.product_id LEFT JOIN manufacturer m ON m.manufacturer_id = p.manufacturer_id ";
		//}

		$sql .= " WHERE order_id = '" . (int) $order_id . "'";

		//p($sql,__LINE__,__FILE__);
		$query = $this->db->query($sql);

		//p($query,__LINE__,__FILE__);
		return $query->rows;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

		return $query->rows;
	}

	public function getOrderTotals($order_id) {

		$s = "SELECT * FROM order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order";

		$query = $this->db->query($s);

		return $query->rows;
	}

	public function getOrderHistory($order_id) {
		$query = $this->db->query("SELECT oh.date_added, order_status_name AS status, oh.comment, oh.notify FROM order_history oh LEFT JOIN order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added");

		return $query->rows;
	}

	public function getOrderDownloads($order_id) {
		$query = $this->db->query("SELECT * FROM order_download WHERE order_id = '" . (int) $order_id . "' ORDER BY LOWER(name)");

		return $query->rows;
	}

	public function getOrderTax($product_id, $order_id) { // TU
		$query = $this->db->query("SELECT tr.rate AS rate, tr.description, tr.priority FROM tax_rate tr
				LEFT JOIN zone_to_geo_zone z2gz
				ON (tr.geo_zone_id = z2gz.geo_zone_id)
				LEFT JOIN geo_zone gz
				ON (tr.geo_zone_id = gz.geo_zone_id)
				WHERE (z2gz.country_id = '0' OR z2gz.country_id = (SELECT payment_country_id FROM `order` WHERE order_id = '" . (int) $order_id . "')) AND (z2gz.zone_id = '0' OR z2gz.zone_id = (SELECT payment_zone_id FROM `order` WHERE order_id = '" . (int) $order_id . "')) AND tr.tax_class_id = (SELECT tax_class_id FROM product WHERE product_id = '" . (int) $product_id . "')");

		$tax = array();

		//p($query,__LINE__,__FILE__);

		$tax = $query->row;
		//p($tax,__LINE__,__FILE__);
		if (!isset($tax['rate'])) {
			$tax['rate'] = 0;
		}

		$tax['sort_order'] = $this->config->get('tax_sort_order');

		return $tax;
	}

	public function getTotalOrders($data = array()) {

		return $GLOBALS['number_of_orders'];

		$user_id = $this->session->data['user_id'];

		$user_group_string = $this->user->getUGstr();

		$sql = "select count(distinct o.order_id) as total from `order` o
				LEFT JOIN patient p
				ON patient_id = order_patient_id
				LEFT JOIN clinic c
				ON patient_clinic_id = clinic_id
				LEFT JOIN user_to_clinic
				ON clinic_id = user_to_clinic_clinic_id";

		if (in_array('11', explode(',', $user_group_string))) {
			$sql .= "
					LEFT JOIN `user` u ON user_to_clinic_user_id = user_id";
		}
		$sql .= "
				LEFT JOIN user_group ug
				ON locate(concat('\"',order_status_id,'\"'),user_group_step_display) > 0";


		if (!in_array('11', explode(',', $user_group_string))) {
			$sql .= "
					LEFT JOIN `user` u
					ON find_in_set(ug.user_group_id,u.user_group_string) > 0";
		}


		// line 444 AND ug.user_group_id = '$user_group_id'";

		$sql .= " WHERE
		locate(concat('\"',order_status_id,'\"'),user_group_step_display) > 0
		AND user_id = '$user_id'";


		if (in_array('11', explode(',', $user_group_string))) {
			$sql .= "  AND clinic_id IN (SELECT user_to_clinic_clinic_id FROM user_to_clinic WHERE user_to_clinic_user_id = '$user_id') AND find_in_set(ug.user_group_id,u.user_group_string) > 0";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND order_status_id = '" . (int) $data['filter_status'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}

		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
		}

		if (isset($data['filter_clinic_name']) && !is_null($data['filter_clinic_name'])) {
			$sql .= " AND LCASE(clinic_name) LIKE '%" . strtolower($this->db->escape($data['filter_clinic_name'])) . "%'";
		}

		if (isset($data['filter_patient_name']) && !is_null($data['filter_patient_name'])) {
			$sql .= " AND LCASE(CONCAT(p.patient_firstname, ' ', p.patient_lastname)) LIKE '%" . strtolower($this->db->escape($data['filter_patient_name'])) . "%'";
		}

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$sql .= " AND DATE(order_date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		// Mod Delivery Date
		if (isset($data['filter_deliverydate']) && !is_null($data['filter_deliverydate'])) {
			$sql .= " AND DATE(order_deliverydate) = DATE('" . $this->db->escape($data['filter_deliverydate']) . "')";
		}
		// End:Mod Delivery Date

		if (isset($data['filter_total']) && !is_null($data['filter_total'])) {
			$sql .= " AND order_total = '" . (float) $data['filter_total'] . "'";
		}

		//p($sql,__LINE__,__FILE__);

		$query = $this->db->query($sql);
		// p(__LINE__);
		return $query->row['total'];
	}

	public function getTotalOrdersByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE store_id = '" . (int) $store_id . "'");

		return $query->row['total'];
	}

	public function getOrderHistoryTotalByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT oh.order_id FROM order_history oh LEFT JOIN `order` o ON (oh.order_id = o.order_id) WHERE oh.order_status_id = '" . (int) $order_status_id . "' AND order_status_id > '0' GROUP BY order_id");

		return $query->num_rows;
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id = '" . (int) $order_status_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE language_id = '" . (int) $language_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE currency_id = '" . (int) $currency_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSales() {
		$query = $this->db->query("SELECT SUM(order_total) AS total FROM `order`");

		return $query->row['total'];
	}

	public function getTotalSalesByYear($year) {
		$query = $this->db->query("SELECT SUM(order_total) AS total FROM `order` WHERE  YEAR(order_date_added) = '" . (int) $year . "'");

		return $query->row['total'];
	}

	// TU START
	public function getProductPrice($order_id, $product_id, $quantity, $default_price) {
		$query = $this->db->query("SELECT clinic_group_id FROM `order` WHERE order_id = '" . (int) $order_id . "'");
		$group = $query->row['clinic_group_id'];

		$date = date('Y-m-d');


		$query = $this->db->query("SELECT price FROM `product_discount` WHERE product_id = '" . (int) $product_id . "' AND clinic_group_id = '" . (int) $group . "' AND quantity <= '" . (int) $quantity . "' AND '" . $date . "' BETWEEN date_start AND date_end ORDER BY priority ASC LIMIT 1");
		if (!empty($query->row)) {
			return $query->row['price'];
		}

		$query = $this->db->query("SELECT price FROM `product_special` WHERE product_id = '" . (int) $product_id . "' AND clinic_group_id = '" . (int) $group . "' AND '" . $date . "' BETWEEN date_start AND date_end ORDER BY priority ASC LIMIT 1");
		if (!empty($query->row)) {
			return $query->row['price'];
		}

		$query = $this->db->query("SELECT price FROM `product_group_price` WHERE product_id = '" . (int) $product_id . "' AND clinic_group_id = '" . (int) $group . "' AND price > 0 LIMIT 1");
		if (!empty($query->row)) {
			return $query->row['price'];
		}

		return $default_price;
	}

	public function verifyPatients($data){
			
		$patient_firstname = $data['patient_firstname'];
		$patient_lastname = $data['patient_lastname'];
		$patient_dob = $data['patient_dob'];
		$patient_clinic_id = $data['patient_clinic_id'];
			
			
		$query = $this->db->query("SELECT * FROM patient WHERE UPPER(patient_firstname) LIKE UPPER('%".mysql_real_escape_string($patient_firstname)."%') AND UPPER(patient_lastname) LIKE UPPER('%".mysql_real_escape_string($patient_lastname)."%') AND patient_dob = '".$patient_dob."' AND patient_clinic_id = '".$patient_clinic_id."' ");
			
		if($query->num_rows){

			return 1;

		}
			
	}

	public function verifyPatientRecords($firstname,$lastname,$birthdate,$clinic_id){

		$query = $this->db->query("SELECT * FROM patient WHERE UPPER(patient_firstname) LIKE UPPER('%".mysql_real_escape_string($firstname)."%') AND UPPER(patient_lastname) LIKE UPPER('%".mysql_real_escape_string($lastname)."%') AND patient_dob = '".$birthdate."' AND patient_clinic_id='".$clinic_id."' ");

		if($query->num_rows){

			return 1;

		}

	}

	public function getPricingValues($ortho_id,$clinic_id){
			
		$query = $this->db->query("SELECT * FROM pricing_clinic pc
				LEFT JOIN pricing_items_master pim ON pc.pricing_item_id = pim.pricing_item_id
				LEFT JOIN tax_codes tc ON  pc.ortho_type_id = tc.ortho_id
				LEFT JOIN tax_rates tr ON tc.tax_rate_id = tr.tax_rate_id
				WHERE clinic_id = '".$clinic_id."' AND ortho_type_id = '".$ortho_id."'");
			
		return $query->rows;
	}

	public function getPricingItemTaxRate(){
			
		$query = $this->db->query("SELECT * FROM tax_codes tc
				LEFT JOIN tax_rates tr ON tc.tax_rate_id = tr.tax_rate_id
				WHERE tc.pricing_items > 0 ORDER BY pricing_items ");
			
		return $query->rows;
			
	}

	public function verifyClinics($clinic_id){
			
		$query = $this->db->query("SELECT * FROM pricing_clinic pc where clinic_id = '".$clinic_id."'");
			
		if($query->num_rows){

			return 1;
		}
		else {

			return 0;
		}
			
	}
	/**
	 * function to update the order totals in the database
	 *
	 *
	 * @param unknown_type $order_id   -------------------> order_id of current order
	 * @param unknown_type $order_totals_tax -------------> order_totals for the current order
	 */
	public function updateOrderTotals($order_id,$order_totals_tax){
			
		// query to update the order totals in the database tables
		$this->db->query("UPDATE `order` SET order_total='".$order_totals_tax."' WHERE order_id = '".$order_id."'");
			
			
	}
	/**
	 * function to update the order unit price in the database tables
	 *
	 * @param unknown_type $order_id -----------------------> order id for the current order
	 * @param unknown_type $order_unit_price ---------------> order unit price for the current order
	 */

	public function updateUnitPrice($order_id,$order_unit_price){

		// query to update the order unit price in the database tables
		$this->db->query("UPDATE `order` SET order_unit_price='".$order_unit_price."'  WHERE order_id = '".$order_id."'");

	}

	public function updateOrderDescription($order_id,$order_description){
			
		// query to update the order unit price in the database tables
		$this->db->query("UPDATE `order` SET order_description= '".mysql_real_escape_string($order_description)."'  WHERE order_id = '".$order_id."'");
			
	}

	public function checkCurrentRushDate($order_id)
	{
		$query = $this->db->query("SELECT order_date_needed FROM `order` WHERE order_id =".$order_id);

		return $query->row['order_date_needed'];
	}

	public function checkdeliveryDate($order_id)
	{
		$query = $this->db->query("SELECT order_deliverydate FROM `order` WHERE order_id =".$order_id);

		return $query->row['order_deliverydate'];
	}
}

?>
