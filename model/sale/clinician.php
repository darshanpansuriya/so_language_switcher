<?php
class ModelSaleClinician extends Model {
	public function addClinician($data) {
      	$this->db->query("INSERT INTO clinician SET 
clinician_name = '" . $this->db->escape($data['clinician_name']) . "', 
clinician_email = '" . $this->db->escape($data['clinician_email']) . "', 
clinician_clinic_id = '" . (int) $data['clinician_clinic_id'] . "', 
clinician_telephone = '" . $this->db->escape($data['clinician_telephone']) . "', 
clinician_notes = '" . $this->db->escape($data['clinician_notes']) . "', 
clinician_status = '" . (int)$data['clinician_status'] . "', 
clinician_date_added = NOW()");
 
      	$clinician_id = $this->db->getLastId();
      	
	}
	
	public function editClinician($clinician_id, $data) {
		$this->db->query("UPDATE clinician SET 
clinician_name = '" . $this->db->escape($data['clinician_name']) . "', 
clinician_email = '" . $this->db->escape($data['clinician_email']) . "', 
clinician_clinic_id = '" . (int) $data['clinician_clinic_id'] . "', 
clinician_email = '" . $this->db->escape($data['clinician_email']) . "', 
clinician_telephone = '" . $this->db->escape($data['clinician_telephone']) . "', 
clinician_notes = '" . $this->db->escape($data['clinician_notes']) . "', 
clinician_status = '" . (int)$data['clinician_status'] . "'

WHERE clinician_id = '" . (int)$clinician_id . "'");
	
	}
	
	
	public function deleteClinician($clinician_id) {
		$this->db->query("DELETE FROM clinician WHERE clinician_id = '" . (int)$clinician_id . "'");
	}
	
	public function getClinician($clinician_id) {
		
		$user_id = (isset($_SESSION['user_id'])?$_SESSION['user_id']:false);
		$s = "SELECT * FROM clinician LEFT JOIN clinic ON clinic_id = clinician_clinic_id";


		if (in_array('11',explode(',',$this->user->getUGstr()))) {
			
			$s .= " LEFT JOIN user_to_clinic ON user_to_clinic_clinic_id = clinician_clinic_id";
		}
		
		$s .= " WHERE clinician_id = '" . (int)$clinician_id . "' ";
		
		if (in_array('11',explode(',',$this->user->getUGstr())))  {
			
			$user_id = $this->session->data['user_id'];
			
			$s .= " AND user_to_clinic_user_id = '$user_id'";
		}
		
		$query = $this->db->query($s);
		return $query->row;
	}
	
	public function getSaClinicians(){
		
		$user_id = $this->session->data['user_id'];
		
		$s = "SELECT * FROM user_to_clinic LEFT JOIN clinician ON user_to_clinic_clinic_id = clinician_clinic_id WHERE user_to_clinic_user_id = '$user_id'";
		
		$qr = $this->db->query($s);
		
		$creds = $qr->rows;
		$custs = "";
		foreach($creds as $cred) {
			$custs .= ($custs?",":"") . $cred['clinician_id'];
		}

		if(!$custs) $custs = 0;

		return $custs; 
		
	}
		
	public function getClinicians($data = array()) {
		
		$sql = "SELECT * FROM clinician c LEFT JOIN clinic ON clinic_id = clinician_clinic_id ";


		$implode = array();
		
		$user_id = (isset($_SESSION['user_id'])?$_SESSION['user_id']:false);
		
$ax = $this->db->query("SELECT * FROM user WHERE user_id = '" . $user_id . "'");

		if (in_array(11,explode(',',$ax->row['user_group_string']))) $implode[] = "clinician_id IN (".$this->getSaClinicians().")";

		if (isset($data['filter_clinic_name']) && !is_null($data['filter_clinic_name'])) {
			$implode[] = "UPPER(clinic_name) LIKE UPPER('%" . $this->db->escape($data['filter_clinic_name']) . "%')";
		}
		
		if (isset($data['filter_clinician_name']) && !is_null($data['filter_clinician_name'])) {
			$implode[] = "UPPER(clinician_name) LIKE UPPER('%" . $this->db->escape($data['filter_clinician_name']) . "%')";
		}
		
		if (isset($data['filter_clinician_phone']) && !is_null($data['filter_clinician_phone'])) {
			$implode[] = "UPPER(clinician_telephone) LIKE UPPER('%" . $this->db->escape($data['filter_clinician_phone']) . "%')";
		}
		
		if (isset($data['filter_clinician_email']) && !is_null($data['filter_clinician_email'])) {
			$implode[] = "UPPER(clinician_email) LIKE UPPER('%" . $this->db->escape($data['filter_clinician_email']) . "%')";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.clinician_status = '" . (int)$data['filter_status'] . "'";
		}	
		
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(clinician_date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
//p($sql,__LINE__,__FILE__);		
		$sort_data = array(
			'clinic_name',
			'clinician_name',
			'clinician_email',
			'clinician_telephone',
			'c.clinician_status',
			'c.clinician_date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY UPPER(" . $data['sort'].')';	
		} else {
			$sql .= " ORDER BY UPPER(clinician_name)";	
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
	
	
	public function getTotalClinicians($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM clinician c LEFT JOIN clinic on clinic_id = clinician_clinic_id";
// p($sql,__LINE__,__FILE__);		
		$implode = array();

		if (in_array(11,explode(',',$this->user->getUGstr()))) $implode[] = "clinician_id IN (".$this->getSaClinicians().")";
		
// p($this->getSaClinicians(),__LINE__,__FILE__);		
		
		if (isset($data['filter_clinic_name']) && !is_null($data['filter_clinic_name'])) {
			$implode[] = "UPPER(clinic_name) LIKE UPPER('%" . $this->db->escape($data['filter_clinic_name']) . "%')";
		}
		
		if (isset($data['filter_clinician_name']) && !is_null($data['filter_clinician_name'])) {
			$implode[] = "UPPER(clinician_name) LIKE UPPER('%" . $this->db->escape($data['filter_clinician_name']) . "%')";
		}
		
		if (isset($data['filter_clinician_phone']) && !is_null($data['filter_clinician_phone'])) {
			$implode[] = "UPPER(clinician_telephone) LIKE UPPER('%" . $this->db->escape($data['filter_clinician_phone']) . "%')";
		}
		
		if (isset($data['filter_clinician_email']) && !is_null($data['filter_clinician_email'])) {
			$implode[] = "UPPER(clinician_email) LIKE UPPER('%" . $this->db->escape($data['filter_clinician_email']) . "%')";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.clinician_status = '" . (int)$data['filter_status'] . "'";
		}	
		
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(clinician_date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
		
}
?>