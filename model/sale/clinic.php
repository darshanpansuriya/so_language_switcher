<?php
class ModelSaleClinic extends Model {
	public function addClinic($data) {
			
		$this->db->query("INSERT INTO clinic SET
				clinic_name = '" . $this->db->escape($data['clinic_name']) . "',
				clinic_contact = '" . $this->db->escape($data['clinic_contact']) . "',
				clinic_contact_email = '" . $this->db->escape($data['clinic_contact_email']) . "',
				clinic_telephone = '" . $this->db->escape($data['clinic_telephone']) . "',
				clinic_fax = '" . $this->db->escape($data['clinic_fax']) . "',
				clinic_code = '" . $this->db->escape($data['clinic_code']) . "',
				clinic_base_shipping_charge = '".$this->db->escape($data['base_shipping_charge'])."',
				clinic_min_ship_pairs = '".$this->db->escape($data['min_shipping_pairs'])."',
				clinic_orthotic_labels = '" . $this->db->escape($data['clinic_orthotic_labels']) . "',
				clinic_orthotic_label_name = '" . (int)($data['clinic_orthotic_label_name']) . "',
				clinic_orthotic_printer = '" . (int)($data['clinic_orthotic_printer']) . "',
				clinic_casting_method = '" . $this->db->escape($data['clinic_casting_method']) . "',
				clinic_scan = '" . $this->db->escape($data['clinic_scan']) . "',
				clinic_notes = '" . $this->db->escape($data['clinic_notes']) . "',
				clinic_default_clinician = '" . (int)$data['clinic_default_clinician'] . "',
				clinic_status = '" . (int)$data['clinic_status'] . "',
				clinic_date_added = NOW()");

		$clinic_id = $this->db->getLastId();
			
		if (isset($data['clinic_address'])) {
			foreach ($data['clinic_address'] as $k=>$address) {
				$format_address = isset($address['format_address']) ? $address['format_address'] :'';
				$format_street = isset($address['format_street']) ? $address['format_street'] :'';
				$format_city = isset($address['format_city']) ? $address['format_city'] :'';
				$format_province = isset($address['format_province']) ? $address['format_province'] :'';
				$format_postal_code = isset($address['format_postal_code']) ? $address['format_postal_code'] :'';
				$address_string = $format_address.', '.$format_street.', '.$format_city.', '.$format_province.', '.$format_postal_code;
				$this->db->query("INSERT INTO clinic_address SET
						clinic_address_clinic_id = '" . (int)$clinic_id . "',
						clinic_address_address = '" . $this->db->escape($address_string) . "',
						clinic_address_sort = '". (isset($data['default']) && $data['default'] == $k ? 4 : (isset($data['shipping']) && $data['shipping'] == $k ? 6 : ($k * 10))) ."'

						");

				$address_id = $this->db->getLastId();
				if (isset($data['default']) && $data['default'] == $k) {
					$this->db->query("UPDATE clinic SET clinic_address_id = '" . $address_id . "' WHERE clinic_id = '" . (int)$clinic_id . "'");
				}
				if (isset($data['shipping']) && $data['shipping'] == $k) {
					$this->db->query("UPDATE clinic SET clinic_shipping_address_id = '" . $address_id . "' WHERE clinic_id = '" . (int)$clinic_id . "'");
				}
			}
		}

		$orthotics_array = array('316','317','178','179','277','181','220','219','183','184','185','221','186','222','187','188');
			

		foreach ($orthotics_array as $orthotic_array){

			$date = date("Y-m-d");

			for ($i = 0; $i <= 11; $i++) {
					
				$this->db->query("INSERT INTO pricing_clinic SET
						clinic_id = '" . $clinic_id . "',
						ortho_type_id = '" . $orthotic_array . "',
						pricing_item_id = '" . ($i+1) . "',
						clinic_item_price = '". $this->db->escape($data['price_'.($i+1).'']) ."',
						updated_date = '".$date."'");
					
			}

		}

		$date = date("Y-m-d");

		for ($i = 1; $i <= 12; $i++) {


			$this->db->query("UPDATE pricing_clinic SET
					clinic_item_price = '" . $this->db->escape($data['price_'.$i.'']) . "',
					updated_date = '".$date."' WHERE
					clinic_id = '" . $clinic_id . "' AND
					ortho_type_id = '" . $this->db->escape($data['o_id']) . "' AND
					pricing_item_id = '" . $i . "'");

		}

	}

	public function editClinic($clinic_id, $data) {

		$this->db->query("UPDATE clinic SET
				clinic_name = '" . $this->db->escape($data['clinic_name']) . "',
				clinic_contact = '" . $this->db->escape($data['clinic_contact']) . "',
				clinic_contact_email = '" . $this->db->escape($data['clinic_contact_email']) . "',
				clinic_telephone = '" . $this->db->escape($data['clinic_telephone']) . "',
				clinic_fax = '" . $this->db->escape($data['clinic_fax']) . "',
				clinic_code = '" . $this->db->escape($data['clinic_code']) . "',
				clinic_base_shipping_charge = '".$this->db->escape($data['base_shipping_charge'])."',
				clinic_min_ship_pairs = '".$this->db->escape($data['min_shipping_pairs'])."',
				clinic_terms = '".$this->db->escape($data['clinic_terms_value'])."',
				clinic_orthotic_labels = '" . $this->db->escape($data['clinic_orthotic_labels']) . "',
				clinic_orthotic_label_name = '" . (int)($data['clinic_orthotic_label_name']) . "',
				clinic_orthotic_printer = '" . (int)($data['clinic_orthotic_printer']) . "',
				clinic_casting_method = '" . $this->db->escape($data['clinic_casting_method']) . "',
				clinic_scan = '" . $this->db->escape($data['clinic_scan']) . "',
				clinic_notes = '" . $this->db->escape($data['clinic_notes']) . "',
				clinic_default_clinician = '" . (int)$data['clinic_default_clinician'] . "',
				clinic_status = '" . (int)$data['clinic_status'] . "'

				WHERE clinic_id = '" . (int)$clinic_id . "'");

		$this->db->query("DELETE FROM clinic_address WHERE clinic_address_clinic_id = '" . (int)$clinic_id . "'");
		//  p($data);
		if (isset($data['clinic_address'])) {
			foreach ($data['clinic_address'] as $k=>$address) {
				$format_address = isset($address['format_address']) ? $address['format_address'] :'';
				$format_street = isset($address['format_street']) ? $address['format_street'] :'';
				$format_city = isset($address['format_city']) ? $address['format_city'] :'';
				$format_province = isset($address['format_province']) ? $address['format_province'] :'';
				$format_postal_code = isset($address['format_postal_code']) ? $address['format_postal_code'] :'';
				$address_string = $format_address.', '.$format_street.', '.$format_city.', '.$format_province.', '.$format_postal_code;
				
				$this->db->query("INSERT INTO clinic_address SET
						clinic_address_clinic_id = '" . (int)$clinic_id . "',
						clinic_address_address = '" . $this->db->escape($address_string) . "',
						clinic_address_sort = '". (isset($data['default']) && $data['default'] == $k ? 4 : (isset($data['shipping']) && $data['shipping'] == $k ? 6 : ($k * 10))) ."'

						");

				$address_id = $this->db->getLastId();
				if (isset($data['default']) && $data['default'] == $k) {
					$this->db->query("UPDATE clinic SET clinic_address_id = '" . $address_id . "' WHERE clinic_id = '" . (int)$clinic_id . "'");
				}
				if (isset($data['shipping']) && $data['shipping'] == $k) {
					$this->db->query("UPDATE clinic SET clinic_shipping_address_id = '" . $address_id . "' WHERE clinic_id = '" . (int)$clinic_id . "'");
				}
			}
		}

		/**
		 * this block is written to update the clinics pricing tab details
		 */
		if ($data['o_id']!=''){

			$date = date("Y-m-d");

			for ($i = 1; $i <= 12; $i++) {
					
				$this->db->query("UPDATE pricing_clinic SET
						clinic_item_price = '" . $this->db->escape($data['price_'.$i.'']) . "',
						updated_date = '".$date."' WHERE
						clinic_id = '" . $clinic_id . "'AND
						ortho_type_id = '" . $this->db->escape($data['o_id']) . "' AND
						pricing_item_id = '" . $i . "'");
			}

		}

	}
	public function getCliniciansByClinic($clinic_id) {

		$query = $this->db->query("SELECT * FROM  clinician WHERE clinician_clinic_id = '" . (int)$clinic_id . "' ORDER BY LOWER(clinician_name)");

		return $query->rows;

	}

	public function getAddressesByClinicId($clinic_id) {
		$address_data = array();

		$query = $this->db->query("SELECT clinic_address_id, clinic_shipping_address_id FROM  clinic WHERE clinic_id = '" . (int)$clinic_id . "'");

		if ($query->num_rows) {
			$default_address_id = $query->row['clinic_address_id'];
			$default_shipping_id =  $query->row['clinic_shipping_address_id'];
		} else {
			$default_address_id = 0;
			$default_shipping_id = 0;
		}
		$query = $this->db->query("SELECT * FROM clinic_address WHERE clinic_address_clinic_id = '" . (int)$clinic_id . "' ORDER BY clinic_address_sort");
		$address_row = 1;
		foreach ($query->rows as $result) {

			$address_data[$address_row] = $result;
			$address_data[$address_row]['default'] = ($default_address_id == $result['clinic_address_id']) ? true : false;
			$address_data[$address_row]['shipping'] = ($default_shipping_id == $result['clinic_address_id']) ? true : false;
			$address_row++;
		}
		return $address_data;
	}

	public function deleteClinic($clinic_id) {
		$this->db->query("DELETE FROM clinic WHERE clinic_id = '" . (int)$clinic_id . "'");
		$this->db->query("DELETE FROM clinic_address WHERE clinic_address_clinic_id = '" . (int)$clinic_id . "'");
	}

	public function getClinic($clinic_id) {

		$user_id = (isset($_SESSION['user_id'])?$_SESSION['user_id']:false);
		$user_group_string  = $this->user->getUGstr();
		$s = "SELECT DISTINCT * FROM clinic WHERE clinic_id = '" . (int)$clinic_id . "'";

		if (in_array('11',explode(',',$user_group_string))) $s .= " AND clinic_id IN (SELECT user_to_clinic_clinic_id FROM user_to_clinic WHERE user_to_clinic_user_id = '$user_id')";


		$query = $this->db->query($s);

		return $query->row;
	}

	public function getClinics($data = array()) {

		$sql = "SELECT * FROM clinic c ";


		$implode = array();

		$user_id = (isset($_SESSION['user_id'])?$_SESSION['user_id']:false);
		$user_group_string  = $this->user->getUGstr();

		$ax = $this->db->query("SELECT * FROM user WHERE user_id = '" . $user_id . "'");

		if (in_array('11',explode(',',$user_group_string))) $implode[] = "clinic_id IN (SELECT user_to_clinic_clinic_id FROM user_to_clinic WHERE user_to_clinic_user_id = '$user_id')";

		if (isset($data['filter_clinic_id']) && !is_null($data['filter_clinic_id'])) {
			$implode[] = "clinic_id = '" . (int)$this->db->escape($data['filter_clinic_id']) . "'";
		}

		if (isset($data['filter_clinic_name']) && !is_null($data['filter_clinic_name'])) {
			$implode[] = "UPPER(clinic_name) LIKE UPPER('%" . $this->db->escape($data['filter_clinic_name']) . "%')";
		}

		if (isset($data['filter_contact_name']) && !is_null($data['filter_contact_name'])) {
			$implode[] = "UPPER(clinic_contact) LIKE UPPER('%" . $this->db->escape($data['filter_contact_name']) . "%')";
		}

		if (isset($data['filter_contact_email']) && !is_null($data['filter_contact_email'])) {
			$implode[] = "UPPER(clinic_contact_email) LIKE UPPER('%" . $this->db->escape($data['filter_contact_email']) . "%')";
		}

		if (isset($data['filter_contact_phone']) && !is_null($data['filter_contact_phone'])) {
			$implode[] = "clinic_telephone LIKE '%" . $this->db->escape($data['filter_contact_phone']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "clinic_status = '" . (int)$data['filter_status'] . "'";
		}


		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(clinic_date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		//p($sql,__LINE__,__FILE__);
		$sort_data = array(
				'clinic_id',
				'clinic_name',
				'clinic_contact',
				'clinic_contact_email',
				'clinic_telephone',
				'clinic_status',
				'clinic_date_added'
		);
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'clinic_id') $sql .= " ORDER BY clinic_id ";
			else $sql .= " ORDER BY UPPER(" . $data['sort'].')';
		} else {
			$sql .= " ORDER BY UPPER(clinic_name)";
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

	public function getClinicsByKeyword($keyword) {
		if ($keyword) {
			$query = $this->db->query("SELECT * FROM clinic WHERE LCASE(CONCAT(patient_firstname, ' ', patient_lastname)) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' ORDER BY UPPER(CONCAT(patient_firstname, patient_lastname, email))");

			return $query->rows;
		} else {
			return array();
		}
	}

	public function getClinicsByProduct($product_id) {
		if ($product_id) {
			$query = $this->db->query("SELECT DISTINCT `email` FROM `order` o LEFT JOIN order_product op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "' AND o.order_status_id <> '0'");

			return $query->rows;
		} else {
			return array();
		}
	}

	public function getAddresses($keyword) {
		$query = $this->db->query("SELECT * FROM clinic_address WHERE clinic_address_clinic_id = '" . (int)$clinic_id . "'");

		return $query->rows;
	}

	public function getTotalClinics($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM clinic";
		// p($sql,__LINE__,__FILE__);
		$implode = array();

		$user_id = $this->session->data['user_id'];

		$user_group_string  = $this->user->getUGstr();

		//		p(" $user_group_string $user_id ",__LINE__.__FILE__);

		if (in_array('11',explode(',',$user_group_string))) {
			//	p(__LINE__.__FILE__);
			$implode[] = "clinic_id IN (SELECT user_to_clinic_clinic_id FROM user_to_clinic WHERE user_to_clinic_user_id = '$user_id')";
		}

		if (isset($data['filter_clinic_id']) && !is_null($data['filter_clinic_id'])) {
			$implode[] = "clinic_id = '" .(int)$this->db->escape($data['filter_clinic_id']) . "'";
		}

		if (isset($data['filter_clinic_name']) && !is_null($data['filter_clinic_name'])) {
			$implode[] = "UPPER(clinic_name) LIKE UPPER('%" . $this->db->escape($data['filter_clinic_name']) . "%')";
		}

		if (isset($data['filter_contact_name']) && !is_null($data['filter_contact_name'])) {
			$implode[] = "UPPER(clinic_contact) LIKE UPPER('%" . $this->db->escape($data['filter_contact_name']) . "%')";
		}

		if (isset($data['filter_contact_email']) && !is_null($data['filter_contact_email'])) {
			$implode[] = "UPPER(clinic_contact_email) LIKE UPPER('%" . $this->db->escape($data['filter_contact_email']) . "%')";
		}

		if (isset($data['filter_contact_phone']) && !is_null($data['filter_contact_phone'])) {
			$implode[] = "clinic_telephone LIKE '%" . $this->db->escape($data['filter_contact_phone']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "clinic_status = '" . (int)$data['filter_status'] . "'";
		}


		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(clinic_date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}


		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
			
		//		p($sql,__LINE__.__FILE__);
		$query = $this->db->query($sql);

		return $query->row['total'];
	}


	public function getTotalAddressesByClinicId($clinic_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM address WHERE clinic_id = '" . (int)$clinic_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM address WHERE country_id = '" . (int)$country_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM address WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row['total'];
	}

	public function getTotalClinicsByClinicGroupId($clinic_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM clinic WHERE clinic_group_id = '" . (int)$clinic_group_id . "'");

		return $query->row['total'];
	}

	/**
	 * this function is written to update the values for the clinic item price for a particular orthotic type
	 * @param unknown_type $clinic_id
	 * @param unknown_type $orthotic_type_id
	 * @param unknown_type $item_id
	 * @param unknown_type $item_price
	 */
	public function updateItemPricingValues($clinic_id,$orthotic_type_id,$item_id,$item_price){

		$date = date("Y-m-d");

		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = $item_price,updated_date = '".$date."' WHERE clinic_id = $clinic_id AND ortho_type_id=$orthotic_type_id AND pricing_item_id= $item_id");

		return 'Updated successfully';

	}
	/**
	 * this function is written to update / restore the default values in the database for the clinics
	 *
	 * @param unknown_type $clinic_id
	 */
	public  function updateItemPricingDefaults($clinic_id){

		// checking whether if data is there in the table for the requested clinic
		$data_check = $this->db->query("SELECT * FROM pricing_clinic WHERE clinic_id = '" . (int)$clinic_id . "'");

		//if data exists
		if($data_check->rows){

			//deleting the exixting data from the pricing clinic
			$default_data =  $this->db->query("DELETE FROM pricing_clinic WHERE clinic_id = '" . $clinic_id . "'");

			//getting the default system date
			$date = date("Y-m-d");

			// getting the data from the pricing defaults table
			$default_data =  $this->db->query("SELECT * FROM pricing_default_master");

			//storing the data into array
			$defaults =  $default_data->rows;


			//getting the default clinic data from clinic items table

			$clinic_default_data = $this->db->query("SELECT * FROM clinic_items_defaults");

			//storing the clinic default data in an array

			$clinic_default_shipping_charge = $clinic_default_data->row['clinic_default_item_value'];
			$clinic_default_min_pair = $clinic_default_data->row['clinic_default_item_qty'];
			$clinic_default_terms = $clinic_default_data->row['clinic_default_terms'];

			$this->db->query("UPDATE clinic SET clinic_base_shipping_charge ='".$clinic_default_shipping_charge."',
					clinic_min_ship_pairs = '".$clinic_default_min_pair."',
					clinic_terms = '.$clinic_default_terms.' WHERE clinic_id = '".$clinic_id."' ");



			//running the loop to insert all values one by one into the database
			foreach ($defaults as $default){
					
				//query responsible to insert the values into the table according to the clinic_id
				$this->db->query("INSERT INTO pricing_clinic SET
						clinic_id = '" . $clinic_id . "',
						ortho_type_id = '" . $default['ortho_type_id'] . "',
						pricing_item_id = '" . $default['pricing_item_id'] . "',
						clinic_item_price = '". $default['default_item_price'] ."',
						updated_date = '".$date."'");
					
			}
			//returning the feedback message
			return 'Restored successfully';
		}
		else {

			//getting the default system date
			$date = date("Y-m-d");

			// getting the data from the pricing defaults table
			$default_data =  $this->db->query("SELECT * FROM pricing_default_master");

			//storing the data into array
			$defaults =  $default_data->rows;

			//getting the default clinic data from clinic items table

			$clinic_default_data = $this->db->query("SELECT * FROM clinic_items_defaults");

			//storing the clinic default data in an array

			$clinic_default_shipping_charge = $clinic_default_data->row['clinic_default_item_value'];
			$clinic_default_min_pair = $clinic_default_data->row['clinic_default_item_qty'];
			$clinic_default_terms = $clinic_default_data->row['clinic_default_terms'];

			$this->db->query("UPDATE clinic SET clinic_base_shipping_charge ='".$clinic_default_shipping_charge."',
					clinic_min_ship_pairs = '".$clinic_default_min_pair."',
					clinic_terms = '.$clinic_default_terms.' WHERE clinic_id = '".$clinic_id."' ");

			//running the loop to insert all values one by one into the database
			foreach ($defaults as $default){

				//query responsible to insert the values into the table according to the clinic_id
				$this->db->query("INSERT INTO pricing_clinic SET
						clinic_id = '" . $clinic_id . "',
						ortho_type_id = '" . $default['ortho_type_id'] . "',
						pricing_item_id = '" . $default['pricing_item_id'] . "',
						clinic_item_price = '". $default['default_item_price'] ."',
						updated_date = '".$date."'");

			}
			//returning the feedback message
			return 'Restored successfully';

		}

	}
	/**
	 *  this function updates pricing values for the clinics
	 *
	 * @param unknown_type $orthotic_id
	 * @param unknown_type $clinic_id
	 * @param unknown_type $price_1
	 * @param unknown_type $price_2
	 * @param unknown_type $price_3
	 * @param unknown_type $price_4
	 * @param unknown_type $price_5
	 * @param unknown_type $price_6
	 * @param unknown_type $price_7
	 * @param unknown_type $price_8
	 * @param unknown_type $price_9
	 * @param unknown_type $price_10
	 * @param unknown_type $price_11
	 * @param unknown_type $price_12
	 * @return string
	 */
	public function updateClinicPricingValues($orthotic_id,$clinic_id,$price_1,$price_2,$price_3,$price_4,$price_5,$price_6,$price_7,$price_8,$price_9,$price_10,$price_11,$price_12){
			
		$date = date("Y-m-d");

		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_1."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 1 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_2."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 2 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_3."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 3 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_4."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 4 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_5."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 5 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_6."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 6 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_7."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 7 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_8."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 8 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_9."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 9 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_10."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 10 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_11."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 11 AND clinic_id = '".$clinic_id."'");
		$this->db->query("UPDATE pricing_clinic SET clinic_item_price = '".$price_12."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 12 AND clinic_id = '".$clinic_id."'");

		return 'Updated Successfully';
	}
	
	/**
	 * this function is to update the orthotic label name
	 */
	public function addLabelName($new_label_name)
	{
	
		// query to get the max sort value
			$max_sort = $this->db->query("SELECT MAX(lookup_table_sort) AS SORT_VALUE FROM lookup_table WHERE lookup_table_lookup_table_types_id =84 ");

			$max_sort_value = $max_sort->row['SORT_VALUE'];
			
			// calculating the new sorting value
			
			$new_sort_value = $max_sort_value + 10 ;
			
			// appending the extension to the filename
				
			$label_name = $this->db->escape($new_label_name).'.nlbl';
		
			// inserting the data posted into the lookup table
			
			$this->db->query("INSERT INTO  lookup_table SET lookup_table_lookup_table_types_id = '84',
					lookup_table_title = '" .$label_name. "',
					lookup_table_text = 'c:\Users\Owner\Documents\My Labels\Labels::OrderNumber::Clinic::ClinicCode::Clinician::ClinicPhoneNumber::DateSenttoLab::PatientName::ScanName1',
					lookup_table_sort = '".$new_sort_value."'");	

			return '<label style="color:green;">Added successfully</label>';
		
	}
	
	
	
	/**
	 * this function is to update the orthotic label name
	 */
	public function updateLabelName($ortho_label_id,$ortho_label_name)
	{
		
		$orthotic_label_name = $ortho_label_name.'.nlbl';
		
		$this->db->query("UPDATE lookup_table SET lookup_table_title = '".$orthotic_label_name."' WHERE  lookup_table_id = '".(int)$ortho_label_id."'");
		
		return '<label style="color:green;">Updated successfully</label>';
	}
	
	public function deleteLabelName($label_id)
	{
		$this->db->query("DELETE FROM lookup_table WHERE lookup_table_id = '".(int)$label_id."'");
		
		return '<label style="color:green;">Deleted successfully</label>';
	}


}
?>