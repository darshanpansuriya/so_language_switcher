<?php
class ModelUserUser extends Model {
	public function addUser($data) {
		$user_groups = implode(',',$data['user_group_string']);

		$this->db->query("INSERT INTO `user` SET 
user_password = '" . $this->db->escape(md5($data['user_password'])) . "', 
user_firstname = '" . $this->db->escape($data['user_firstname']) . "', 
user_lastname = '" . $this->db->escape($data['user_lastname']) . "', 
user_email = '" . $this->db->escape($data['user_email']) . "', 
user_group_string = '$user_groups', 
user_status = '" . (int)$data['user_status'] . "', 
user_date_added = NOW()");
		
		$user_id = mysql_insert_id();
	
		if (isset($data['user_to_clinic'])) foreach($data['user_to_clinic'] as $valx) {
			$this->db->query("INSERT INTO user_to_clinic SET user_to_clinic_clinic_id = '$valx', user_to_clinic_user_id = '" . (int)$user_id . "'");
		} 
	}
		
	
	public function editUser($user_id, $data) { 
	
		$user_groups = implode(',',$data['user_group_string']);
		
		$sql = "UPDATE `user` SET 
user_firstname = '" . $this->db->escape($data['user_firstname']) . "', 
user_lastname = '" . $this->db->escape($data['user_lastname']) . "', 
user_email = '" . $this->db->escape($data['user_email']) . "',
user_group_string = '$user_groups',
user_status = '" . (int)$data['user_status'] . "' 
WHERE user_id = '" . (int)$user_id . "'";
		
		$this->db->query($sql);
		

		if (isset($data['user_to_clinic'])) {
			$this->db->query("DELETE FROM user_to_clinic WHERE user_to_clinic_user_id = '" . (int)$user_id . "'");
			foreach($data['user_to_clinic'] as $valx) {
				$this->db->query("INSERT INTO user_to_clinic SET user_to_clinic_clinic_id = '$valx', user_to_clinic_user_id = '" . (int)$user_id . "'");
			}
		}
		if ($data['user_password']) {
			$this->db->query("UPDATE `user` SET user_password = '" . $this->db->escape(md5($data['user_password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}
	
	public function deleteUser($user_id) {
		$this->db->query("DELETE FROM `user` WHERE user_id = '" . (int)$user_id . "';");
		$this->db->query("DELETE FROM `user_to_clinic` WHERE user_to_clinic_user_id = '" . (int)$user_id . "'");
		
	}
	
	public function getUser($user_id) {
		
//p(__LINE__.__FILE__); exit();		
		$query = $this->db->query("SELECT * FROM `user` WHERE user_id = '" . (int)$user_id . "'");

		return $query->row;
	}
	
	public function getUsers($data = array()) {
		$sql = "SELECT *,u.user_group_string AS ug_string FROM `user` u LEFT JOIN user_group ug ON (find_in_set(ug.user_group_id,u.user_group_string))"  ;
		$implode = array();
		
		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			//$implode[] = "UPPER(user_email) LIKE UPPER('%" . $this->db->escape($data['filter_email']) . "%')";
			$implode[] = "user_email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}
		
		if (isset($data['filter_group']) && !is_null($data['filter_group'])) {
			$implode[] = "find_in_set(". (int)$this->db->escape($data['filter_group']) .",u.user_group_string)";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "user_status = '" . (int)$data['filter_status'] . "'";
		}	
		
		if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
			$implode[] = "DATE(user_date_added) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
//		p($sql);

		$sort_data = array(
			'user_name',
			'user_status',
			'user_email',
			'user_date_added'
		);	
		$sql .= " GROUP BY user_id";
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY user_email";	
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
//	p($query,__LINE__,__FILE__);
		return $query->rows;
	}

	public function getTotalUsers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `user`";
		
		$implode = array();
		
		
		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			//$implode[] = "UPPER(user_email) LIKE UPPER('%" . $this->db->escape($data['filter_email']) . "%')";
			$implode[] = "user_email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}
		
		if (isset($data['filter_group']) && !is_null($data['filter_group'])) {
			$implode[] = "find_in_set(". (int)$this->db->escape($data['filter_group']) .",user_group_string)";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "user_status = '" . (int)$data['filter_status'] . "'";
		}	
		
		if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
			$implode[] = "DATE(user_date_added) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		
      	$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `user` WHERE user_group_id = '" . (int)$user_group_id . "'");
		
		return $query->row['total'];
	}
}
?>