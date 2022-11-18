<?php
class ModelUserUserGroup extends Model {
	public function addUserGroup($data) {
		$this->db->query("INSERT INTO user_group SET 
		user_group_name = '" . $this->db->escape($data['user_group_name']) . "', 
		user_group_permission = '" . (isset($data['user_group_permission']) ? serialize($data['user_group_permission']) : '') . "',
		user_group_step_full = '" . (isset($data['user_group_step_full']) ? serialize($data['user_group_step_full']) : '') . "',
		user_group_step_display = '" . (isset($data['user_group_step_display']) ? serialize($data['user_group_step_display']) : '') . "'
		");
	}
	
	public function editUserGroup($user_group_id, $data) {
		$this->db->query("UPDATE user_group SET 
		user_group_name = '" . $this->db->escape($data['user_group_name']) . "', 
		user_group_permission = '" . (isset($data['user_group_permission']) ? serialize($data['user_group_permission']) : '') . "',
		user_group_step_display = '" . (isset($data['user_group_step_display']) ? serialize($data['user_group_step_display']) : '') . "' ,
		user_group_step_full = '" . (isset($data['user_group_step_full']) ? serialize($data['user_group_step_full']) : '') . "' 
		WHERE user_group_id = '" . (int)$user_group_id . "'");
	}
	
	public function deleteUserGroup($user_group_id) {
		$this->db->query("DELETE FROM user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function addPermission($user_id, $type, $page) {
		$user_query = $this->db->query("SELECT DISTINCT user_group_id FROM user WHERE user_id = '" . (int)$user_id . "'");
		
		if ($user_query->num_rows) {
			$user_group_query = $this->db->query("SELECT DISTINCT * FROM user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
		
			if ($user_group_query->num_rows) {
				$data = unserialize($user_group_query->row['user_group_permission']);
		
				$data[$type][] = $page;
		
				$this->db->query("UPDATE user_group SET user_group_permission = '" . serialize($data) . "' WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
			}
		}
	}
	
	public function getUserGroup($user_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
		
		$user_group = array(
			'user_group_name'       => $query->row['user_group_name'],
			'user_group_permission' => unserialize($query->row['user_group_permission']),
			'user_group_step_full' => unserialize($query->row['user_group_step_full']),
			'user_group_step_display' => unserialize($query->row['user_group_step_display'])
		);
		
		return $user_group;
	}
	
	public function getUserGroups($data = array()) {
		$sql = "SELECT * FROM user_group";
		
		$sql .= " ORDER BY user_group_name";	
			
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
	
	public function getTotalUserGroups() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM user_group");
		
		return $query->row['total'];
	}	
}
?>