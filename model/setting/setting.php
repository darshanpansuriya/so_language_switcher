<?php 
class ModelSettingSetting extends Model {
	public function getSetting($group) {
		$data = array(); 
		
		$query = $this->db->query("SELECT * FROM setting WHERE `group` = '" . $this->db->escape($group) . "'");
		
		foreach ($query->rows as $result) {
			$data[$result['key']] = $result['value'];
		}
				
		return $data;
	}
	
	public function editSetting($group, $data) {
		
		
		if ($data['o_id']){		
			
			$this->db->query("DELETE FROM lookup_defaults WHERE lookup_defaults_lookup_table_id = '".$data['o_id']."'");
			if(isset($data['lookup_table_value'])) {				
				
				foreach($data['lookup_table_value'] as $val) {
					if (isset($data['lookup_order'][$val])) {
						$this->db->query("INSERT INTO lookup_defaults SET lookup_defaults_lookup_table_id = '".$data['o_id']."',lookup_defaults_table_types_id = '".$val."',lookup_defaults_default_value = '".$data['lookup_order'][$val]."' ON DUPLICATE KEY UPDATE lookup_defaults_default_value = '".$data['lookup_order'][$val]."'");
					}
				}
				unset($data['lookup_table_value'],$data['lookup_order']);
			}
		} else {
			unset($data['o_id']);
			
			// updates the existing  tax rate  in the tax_rates table ----
			$date = date('Y-m-d');		
			$this->db->query("UPDATE tax_rates SET tax_rate = '".$data['tax_rate_hst']."',updated_date = '".$date."' WHERE tax_rate_id = 1");
			$this->db->query("UPDATE tax_rates SET tax_rate = '".$data['tax_rate_pst']."' ,updated_date = '".$date."' WHERE tax_rate_id = 2");
			$this->db->query("UPDATE tax_rates SET tax_rate = '".$data['tax_rate_gst']."' ,updated_date = '".$date."' WHERE tax_rate_id = 3");
			$this->db->query("UPDATE tax_rates SET tax_rate = '".$data['tax_rate_z']."' ,updated_date = '".$date."' WHERE tax_rate_id = 4");
			
			//-----------------------------------------------------------
			
			// this block updates the  clinic specific default values in the tables
			
			$this->db->query("UPDATE clinic_items_defaults SET clinic_default_item_value = '".$data['base_shipping_charge']."'");
			$this->db->query("UPDATE clinic_items_defaults SET clinic_default_item_qty = '".$data['min_shipping_pairs']."' ");
			$this->db->query("UPDATE clinic_items_defaults SET clinic_default_terms = '".$data['default_terms']."' ");
			
			//updates the tax codes in the tax codes table-------------
			
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_1']."',tax_rate_id= '".$data['tax_type_1']."',updated_date = '".$date."' WHERE tax_code_id = 1");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_2']."',tax_rate_id= '".$data['tax_type_2']."',updated_date = '".$date."' WHERE tax_code_id = 2");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_3']."',tax_rate_id= '".$data['tax_type_3']."',updated_date = '".$date."' WHERE tax_code_id = 3");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_4']."',tax_rate_id= '".$data['tax_type_4']."',updated_date = '".$date."' WHERE tax_code_id = 4");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_5']."',tax_rate_id= '".$data['tax_type_5']."',updated_date = '".$date."' WHERE tax_code_id = 5");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_6']."',tax_rate_id= '".$data['tax_type_6']."',updated_date = '".$date."' WHERE tax_code_id = 6");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_7']."',tax_rate_id= '".$data['tax_type_7']."',updated_date = '".$date."' WHERE tax_code_id = 7");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_8']."',tax_rate_id= '".$data['tax_type_8']."',updated_date = '".$date."' WHERE tax_code_id = 8");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_9']."',tax_rate_id= '".$data['tax_type_9']."',updated_date = '".$date."' WHERE tax_code_id = 9");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_10']."',tax_rate_id= '".$data['tax_type_10']."',updated_date = '".$date."' WHERE tax_code_id = 10");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_11']."',tax_rate_id= '".$data['tax_type_11']."',updated_date = '".$date."' WHERE tax_code_id = 11");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_12']."',tax_rate_id= '".$data['tax_type_12']."',updated_date = '".$date."' WHERE tax_code_id = 12");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_13']."',tax_rate_id= '".$data['tax_type_13']."',updated_date = '".$date."' WHERE tax_code_id = 13");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_14']."',tax_rate_id= '".$data['tax_type_14']."',updated_date = '".$date."' WHERE tax_code_id = 14");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_15']."',tax_rate_id= '".$data['tax_type_15']."',updated_date = '".$date."' WHERE tax_code_id = 15");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_16']."',tax_rate_id= '".$data['tax_type_16']."',updated_date = '".$date."' WHERE tax_code_id = 16");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_17']."',tax_rate_id= '".$data['tax_type_17']."',updated_date = '".$date."' WHERE tax_code_id = 17");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_18']."',tax_rate_id= '".$data['tax_type_18']."',updated_date = '".$date."' WHERE tax_code_id = 18");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_19']."',tax_rate_id= '".$data['tax_type_19']."',updated_date = '".$date."' WHERE tax_code_id = 19");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_20']."',tax_rate_id= '".$data['tax_type_20']."',updated_date = '".$date."' WHERE tax_code_id = 20");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_21']."',tax_rate_id= '".$data['tax_type_21']."',updated_date = '".$date."' WHERE tax_code_id = 21");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_22']."',tax_rate_id= '".$data['tax_type_22']."',updated_date = '".$date."' WHERE tax_code_id = 22");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_23']."',tax_rate_id= '".$data['tax_type_23']."',updated_date = '".$date."' WHERE tax_code_id = 23");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_24']."',tax_rate_id= '".$data['tax_type_24']."',updated_date = '".$date."' WHERE tax_code_id = 24");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_25']."',tax_rate_id= '".$data['tax_type_25']."',updated_date = '".$date."' WHERE tax_code_id = 25");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_26']."',tax_rate_id= '".$data['tax_type_26']."',updated_date = '".$date."' WHERE tax_code_id = 26");
			$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_27']."',tax_rate_id= '".$data['tax_type_27']."',updated_date = '".$date."' WHERE tax_code_id = 27");
			//$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_28']."',tax_rate_id= '".$data['tax_type_28']."',updated_date = '".$date."' WHERE tax_code_id = 28");
			//$this->db->query("UPDATE tax_codes SET short_code = '".$data['short_code_29']."',tax_rate_id= '".$data['tax_type_29']."',updated_date = '".$date."' WHERE tax_code_id = 29");
						
			//----------------------------------------------------------
		
			//th$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_1']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 1");is block is written to update the default values in the default master table 
			
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_1']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 1");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_2']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 2");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_3']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 3");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_4']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 4");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_5']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 5");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_6']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 6");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_7']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 7");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_8']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 8");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_9']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 9");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_10']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 10");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_11']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 11");
			$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_12']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 12");
			//$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_13']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 13");
			//$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$data['price_14']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 14");
			//$this->db->query("UPDATE pricing_default_master SET default_terms = '".$data['terms_value']."', updated_date = '".$date."' WHERE ortho_type_id= '".$data['ortho_id']."' AND  pricing_item_id = 13");
			
			
			
			
			
			//---------------------------------------------------------------------
			
			
			
			$this->db->query("DELETE FROM setting WHERE `group` = '" . $this->db->escape($group) . "'");
			
			foreach ($data as $key => $value) {
				$this->db->query("INSERT INTO setting SET `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
			}
		}
	}
	
	public function deleteSetting($group) {
		$this->db->query("DELETE FROM setting WHERE `group` = '" . $this->db->escape($group) . "'");
	}
	
	/**
	 * this function is written to update the default  values in the database for the settings page
	 * @return string
	 */
	public function updateItemPricingDefaultValues($orthotic_id,$item_id,$item_price){
				
			$date = date("Y-m-d");
		
			$this->db->query("UPDATE pricing_default_master SET default_item_price = $item_price,updated_date = '".$date."' WHERE ortho_type_id = $orthotic_id AND pricing_item_id= $item_id");
		
			return 'Updated successfully';		
	}
	
	public function updateSettingPricingValues($orthotic_id,$price_1,$price_2,$price_3,$price_4,$price_5,$price_6,$price_7,$price_8,$price_9,$price_10,$price_11,$price_12){
			
		$date = date("Y-m-d");
		
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_1."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 1");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_2."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 2");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_3."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 3");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_4."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 4");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_5."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 5");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_6."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 6");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_7."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 7");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_8."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 8");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_9."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 9");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_10."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 10");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_11."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 11");
		$this->db->query("UPDATE pricing_default_master SET default_item_price = '".$price_12."', updated_date = '".$date."' WHERE ortho_type_id= '".$orthotic_id."' AND  pricing_item_id = 12");
		
		return 'Updated Successfully';
	}
	
}
?>