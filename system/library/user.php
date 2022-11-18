<?php
final class User {
	private $user_id;
	private $username;
	private $email;
  	private $permission = array();
	private $user_group_id;
	private $user_group_string;
	private $broker_code;
	private $user_last_login;

  	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		
    	if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->query("SELECT * FROM user WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->email = $user_query->row['user_email'];
				$this->user_group_string = $user_query->row['user_group_string'];
				$this->username = $user_query->row['user_firstname'] . ' ' . $user_query->row['user_lastname'];

      			$this->db->query("UPDATE user SET user_ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");
				$ugs = $user_query->row['user_group_string'];
      			$user_group_query = $this->db->query("SELECT * FROM user_group WHERE find_in_set(user_group_id, '$ugs')");

				$permissions['modify'] = array();
				$permissions['access'] = array();
				$stepdisplay['display'] = array();
				$stepfull['full'] = array();

				foreach($user_group_query->rows as $group_query) {	
				
					if (is_array(unserialize($group_query['user_group_permission']))) {
						$px = unserialize($group_query['user_group_permission']);

						if(isset($px['modify'])) $permissions['modify'] = array_merge($permissions['modify'],$px['modify']);
						if(isset($px['access'])) $permissions['access'] = array_merge($permissions['modify'],$px['access']);

					}
					if (is_array(unserialize($group_query['user_group_step_display']))) {
						$px = unserialize($group_query['user_group_step_display']);
						if(isset($px['display'])) $stepdisplay['display'] = array_merge($stepdisplay['display'], $px['display']);
					}

					if (is_array(unserialize($group_query['user_group_step_full']))) {
						$px = unserialize($group_query['user_group_step_full']);
						if(isset($px['full'])) $stepfull['full'] = array_merge($stepfull['full'],$px['full']);
					}

				}
				foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
				}
				
				foreach ($stepdisplay as $key => $value) {
					$this->step_display[$key] = $value;
				}
//				p($this->step_display);
				foreach ($stepfull as $key => $value) {
					$this->step_full[$key] = $value;
				}
			} else {
				$this->logout();
			}
    	}
  	}
		
  	public function login($email, $password) {
  		
  		date_default_timezone_set('America/Toronto');
  		
  		$current_date_time = date('Y-m-d H:i:s');
  		
    	$user_query = $this->db->query("SELECT * FROM `user` WHERE LOWER(user_email) = '" . $this->db->escape(strtolower($email)) . "' AND user_password = '" . $this->db->escape(md5($password)) . "' AND user_status > 0");

    	if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];
			$this->session->data['user_group'] = $user_query->row['user_group_string'];
			
			$this->user_id = $user_query->row['user_id'];
			$this->email = $user_query->row['user_email'];			

      		$this->session->data['user_last_login'] = $user_query->row['user_last_login'];
			$this->db->query("UPDATE " . DB_PREFIX . "user SET  user_last_login = '".$current_date_time."' WHERE user_id = '" . $this->user_id . "'");
			$ugs = $user_query->row['user_group_string'];
   			$user_group_query = $this->db->query("SELECT * FROM user_group WHERE find_in_set(user_group_id, '$ugs')");

				$permissions['modify'] = array();
				$permissions['access'] = array();
				$stepdisplay['display'] = array();
				$stepfull['full'] = array();

				foreach($user_group_query->rows as $group_query) {	
				
					if (is_array(unserialize($group_query['user_group_permission']))) {
						$px = unserialize($group_query['user_group_permission']);

						if(isset($px['modify'])) $permissions['modify'] = array_merge($permissions['modify'],$px['modify']);
						if(isset($px['access'])) $permissions['access'] = array_merge($permissions['modify'],$px['access']);

					}
					if (is_array(unserialize($group_query['user_group_step_display']))) {
						$px = unserialize($group_query['user_group_step_display']);
						if(isset($px['display'])) $stepdisplay['display'] = array_merge($stepdisplay['display'], $px['display']);
					}

					if (is_array(unserialize($group_query['user_group_step_full']))) {
						$px = unserialize($group_query['user_group_step_full']);
						if(isset($px['full'])) $stepfull['full'] = array_merge($stepfull['full'],$px['full']);
					}

				}
				foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
				}
				
				foreach ($stepdisplay as $key => $value) {
					$this->step_display[$key] = $value;
				}
//				p($this->step_display);
				foreach ($stepfull as $key => $value) {
					$this->step_full[$key] = $value;
				}
				
	      	return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

  	public function logout() {
		unset($this->session->data['user_id']);
	
		$this->user_id = '';
		$this->email = '';
  	}

  	public function hasPermission($key, $value) {
		if ($this->getUGstr() == '1' || (isset($this->request->get['user_id']) && $this->getID() == $this->request->get['user_id']) && $this->request->isget('route') == 'user/user/update' ) {
			
			return TRUE;
		}
		
    	if (isset($this->permission[$key])) {
	  		return in_array($value, $this->permission[$key]);
		} else {
	  		return FALSE;
		}
  	}
  
  	public function stepDisplay($value) {
    	if (isset($this->step_display['display'])) {
//			p($value);
//			p($this->step_display['display']);
	  		return in_array($value, $this->step_display['display']);
		} else {
	  		return FALSE;
		}
  	}
  
  	public function stepFull($value) {
    	if (isset($this->step_full['full'])) {
	  		return in_array($value, $this->step_full['full']);
		} else {
	  		return FALSE;
		}
  	}
  
  	public function isLogged() {
    	return $this->user_id;
  	}
  
  	public function getId() {
    	return $this->user_id;
  	}
	
  	public function getUserName() {
    	return $this->username;
  	}	
  	public function getUGstr() {
    	return $this->user_group_string;
  	}	
  	public function getBroker() {
    	return strtolower($this->broker_code);
  	}	
  	public function getLastLogin() {
    	return $this->session->data['user_last_login'];
  	}	
}
?>