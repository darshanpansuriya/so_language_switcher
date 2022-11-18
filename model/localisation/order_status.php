<?php 
class ModelLocalisationOrderStatus extends Model {
	public function addOrderStatus($data) {
		foreach ($data['order_status'] as $language_id => $value) {
			if (isset($order_status_id)) {
				$this->db->query("INSERT INTO order_status SET order_status_id = '" . (int)$order_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO order_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
				
				$order_status_id = $this->db->getLastId();
			}
		}
		
		$this->cache->delete('order_status');
	}

	public function editOrderStatus($order_status_id, $data) {
		$this->db->query("DELETE FROM order_status WHERE order_status_id = '" . (int)$order_status_id . "'");

		foreach ($data['order_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO order_status SET order_status_id = '" . (int)$order_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
				
		$this->cache->delete('order_status');
	}
	
	public function deleteOrderStatus($order_status_id) {
		$this->db->query("DELETE FROM order_status WHERE order_status_id = '" . (int)$order_status_id . "'");
	
		$this->cache->delete('order_status');
	}
		
	public function getOrderStatus($order_status_id) {
		$query = $this->db->query("SELECT * FROM order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
		
	public function getOrderStatuses($data = array()) {
      	if ($data) {
			$sql = "SELECT * FROM order_status ORDER BY order_status_sort, order_status_name";	
			
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
		} else {
			
$user_group_string = $this->user->getUGstr();
$perm = $this->db->query("SELECT * FROM user_group WHERE find_in_set(user_group_id,'$user_group_string')");
			
			$sql = "SELECT order_status_id, order_status_name FROM order_status ";
			
//			if ($user_group_string != '1') {
//				$sql .= " WHERE locate(concat('\"',order_status_id,'\"'),'".$perm->row['user_group_step_display']."') > 0";
//			}
			$sql .= " ORDER BY order_status_sort ASC";
//	p($sql,__LINE__.__FILE__);
			
			$query = $this->db->query($sql);
//	p($query,__LINE__.__FILE__);
			$order_status_data = $query->rows;
		
			return $order_status_data;				
		}
	}
	
	public function getOrderStatusDescriptions($order_status_id) {
		$order_status_data = array();
		
		$query = $this->db->query("SELECT * FROM order_status WHERE order_status_id = '" . (int)$order_status_id . "'");
		
		foreach ($query->rows as $result) {
			$order_status_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $order_status_data;
	}
	
	public function getTotalOrderStatuses() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}	
}
?>