<?php
class ModelCommonTracking extends Model {
	
	/**
	 * this function is written to get the list of all orders that have met shipping in the orders tracking list
	 *
	 * @param unknown_type $data
	 */
	public function getTrackingOrders($data=array()) {	

		
		$sql = "SELECT ps.id AS packing_list_num, ps.ship_date,ps.orders AS packing_orders, o.order_shipping_number	FROM packing_slip ps
				LEFT JOIN `order` o ON ps.id = o.order_packing_slip_id WHERE (order_status_id IN ( 130, 140, 150, 160,170 ))";
	
		if (isset($data['filter_packing_num']) && !is_null($data['filter_packing_num'])) {
			 
			$sql .= " AND ps.id = '" . (int) $data['filter_packing_num'] . "'";
		}
		 
		if (isset($data['filter_shipping_date']) && !is_null($data['filter_shipping_date'])) {
			 
			$sql .= " AND ps.ship_date = '" . $data['filter_shipping_date'] . "'";
		}
		
		if (isset($data['filter_tracking_num']) && !is_null($data['filter_tracking_num'])) {
		
			$sql .= " AND o.order_shipping_number = '" . $data['filter_tracking_num'] . "'";
		}	

		
		$sort_data = array(				
				'packing_list_num'=>'packing_list_num',
				'ship_date'=>'ship_date'
		);
		
		
		
		
		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
				
			if ($sort_data[$data['sort']] == 'packing_list_num') $sql .= " GROUP BY o.order_packing_slip_id ORDER BY ps.id ";
			else{
				//$sql .= " ORDER BY UPPER(CONCAT(" . $sort_data[$data['sort']]."))";
				$sql .= " ORDER BY CONCAT(" . $sort_data[$data['sort']].")";
			}
		} else {
			$sql .= " GROUP BY o.order_packing_slip_id ORDER BY ps.id";
		}
			
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
				
				
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
	
		$query = $this->db->query($sql);
		 
		return $query->rows;
		 
	}
	
	/**
	 * this dunction is written for the pagination of the list in the orders tracking page
	 * @param unknown_type $data
	 */
	public function getTotalTrackingOrders($data=array()) {
		$sql ="SELECT COUNT(DISTINCT order_packing_slip_id) AS total FROM packing_slip ps
				LEFT JOIN `order` o ON ps.id = o.order_packing_slip_id WHERE (order_status_id IN ( 130, 140, 150, 160 ))";
		 
		 
		if (isset($data['filter_packing_num']) && !is_null($data['filter_packing_num'])) {
	
			$sql .= " AND ps.id = '" . (int) $data['filter_packing_num'] . "'";
		}
		 
		if (isset($data['filter_shipping_date']) && !is_null($data['filter_shipping_date'])) {
	
			$sql .= " AND ps.ship_date = '" . $data['filter_shipping_date'] . "'";
		}
		if (isset($data['filter_tracking_num']) && !is_null($data['filter_tracking_num'])) {
		
			$sql .= " AND o.order_shipping_number = '" . $data['filter_tracking_num'] . "'";
		}
		 
		$query = $this->db->query($sql);
	
		return $query->row['total'];
	}
	
	/**
	 * this block of code is written to save/update the packing slip numbers for order or group of orders
	 * this is updated in the orders table as order_packing_slip_id
	 * 
	 * @param unknown_type $packing_number
	 * @param unknown_type $shipping_number
	 */
	public function updateOrderShippingNumber($packing_number,$shipping_number)
	{		 		
		
		// including the config for qb queueing
			
		require_once 'so_app_web_connector/config.php';
			
		$dsn = 'mysql://dbo562806560:9bpSTW-_O@d!@localhost:/tmp/mysql5.sock/db562806560';
			
		$Queue = new QuickBooks_WebConnector_Queue($dsn);
		
		//checking whether orders exists or not for the given packing slip in the orders table
		
		$order_check = mysql_query("SELECT * FROM `packing_slip` WHERE id='".$packing_number."'");
		
		$order_verify = mysql_fetch_object($order_check);		
		
		if($order_verify){
			
			$packing_list_id = $packing_number;
			
			mysql_query("UPDATE `quickbook_orders` SET is_synced = '2' where `order_id`=".$packing_list_id);
			
			$packing_list_orders = mysql_query("SELECT orders FROM  `packing_slip` WHERE  `id` ='".$packing_list_id."' ");
			
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
			
			
			if ($clinic_id != 1)
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
			
			
					// checking whether the same set of values are existing in the database table orders
					$queue_check =  mysql_query("SELECT is_mod_queue  FROM `quickbook_orders`
											WHERE  order_id = '".$packing_list_id."'
											AND  clinic_id= '".$clinic_primary_id."'
											AND  order_included= '".$matched_clinic_orders."'");
						
					$queue_verify = mysql_fetch_row($queue_check);
						
					$queue_mod_verify = $queue_verify[0];
						
			
					// adding the invoice for the order
					if($queue_mod_verify == '0')
					{
						$invoice_priority = '5';
			
						$Queue->enqueue(QUICKBOOKS_MOD_INVOICE,null,$invoice_priority, array( 'packing_id' => $packing_list_id, 'clinic_id' => $clinic_primary_id ));
						mysql_query("UPDATE  `quickbook_orders` SET  is_mod_queue = '1' WHERE
												`order_id`= '".$packing_list_id."' AND `clinic_id`= '".$clinic_primary_id."' AND order_included= '".$matched_clinic_orders."'");
					}
				}
					
			}	
			
		}
		else {
			//checking whether orders exists or not for the given packing slip in the retail orders table
			
			$retail_order_check = mysql_query("SELECT * FROM `quickbook_retail_orders` WHERE order_id='".$packing_number."'");
			
			$retail_order_verify = mysql_fetch_object($retail_order_check);
			
			if($retail_order_verify){
					
				mysql_query("UPDATE `quickbook_retail_orders` SET is_synced = '2' where `order_id`=".$packing_number);
					
				$invoice_priority = '5';
			
				$Queue->enqueue(QUICKBOOKS_RETAIL_MOD_INVOICE,$packing_number,$invoice_priority);
			}
			
		}
		
			//preparing the query for updating order_packing_slip_id in  the order table
			$sql = "UPDATE `" . DB_PREFIX . "order` SET order_shipping_number = '" . $shipping_number."' WHERE order_packing_slip_id = '" . (int)$packing_number . "'";
		
			$query = $this->db->query($sql);			
		 
			//returning the response on successfull update
		 return "Updated successfully";
	
	}
	
	
	
}