<?php
class ModelSalePatient extends Model {
	public function addPatient($data) {		
		
      	$this->db->query("INSERT INTO patient SET 
is_synced = '0',
patient_firstname = '" . $this->db->escape($data['patient_firstname']) . "', 
patient_lastname = '" . $this->db->escape($data['patient_lastname']) . "', 
patient_email = '" . $this->db->escape($data['patient_email']) . "', 
patient_telephone = '" . $this->db->escape($data['patient_telephone']) . "', 
patient_code = '" . $this->db->escape($data['patient_code']) . "', 
patient_dob = '" . $this->db->escape($data['patient_dob']) . "', 
patient_fax = '" . $this->db->escape($data['patient_fax']) . "', 
patient_clinic_id = '" . (int)$data['patient_clinic_id'] . "', 
patient_clinician_id = '" . (int)$data['patient_clinician_id'] . "', 
patient_status = '" . (int)$data['patient_status'] . "', 
patient_weight = '" . (float)$data['patient_weight'] . "', 
patient_weight_id = '" . (int)$data['patient_weight_id'] . "', 
patient_gender_id = '" . (int)$data['patient_gender_id'] . "', 
patient_notes = '" . $this->db->escape($data['patient_notes']) . "', 
patient_company = '" . $this->db->escape($data['patient_company']) . "', 
patient_address_1 = '" . $this->db->escape($data['patient_address_1']) . "', 
patient_address_2 = '" . $this->db->escape($data['patient_address_2']) . "', 
patient_city = '" . $this->db->escape($data['patient_city']) . "', 
patient_country_id = '" . (int)($data['patient_country_id']) . "', 
patient_province_id = '" . (int)($data['patient_province_id']) . "', 
patient_postalcode = '" . $this->db->escape($data['patient_postalcode']) . "', 
patient_date_modified = NOW(), 
patient_date_added = NOW()");
      	
      	$patient_id = $this->db->getLastId();
     
	}
	
	public function editPatient($patient_id, $data) {
	
// p($data,__LINE__.__FILE__); 
$s = "UPDATE patient SET 
is_synced = '3',
patient_firstname = '" . $this->db->escape($data['patient_firstname']) . "', 
patient_lastname = '" . $this->db->escape($data['patient_lastname']) . "', 
patient_email = '" . $this->db->escape($data['patient_email']) . "', 
patient_telephone = '" . $this->db->escape($data['patient_telephone']) . "', 
patient_code = '" . $this->db->escape($data['patient_code']) . "', 
patient_dob = '" . $this->db->escape($data['patient_dob']) . "', 
patient_fax = '" . $this->db->escape($data['patient_fax']) . "', 
patient_clinic_id = '" . (int)$data['patient_clinic_id'] . "', 
patient_clinician_id = '" . (int)$data['patient_clinician_id'] . "', 
patient_status = '" . (int)$data['patient_status'] . "', 
patient_weight = '" . (float)$data['patient_weight'] . "', 
patient_weight_id = '" . (int)$data['patient_weight_id'] . "', 
patient_gender_id = '" . (int)$data['patient_gender_id'] . "', 
patient_notes = '" . $this->db->escape($data['patient_notes']) . "', 
patient_company = '" . $this->db->escape($data['patient_company']) . "', 
patient_address_1 = '" . $this->db->escape($data['patient_address_1']) . "', 
patient_address_2 = '" . $this->db->escape($data['patient_address_2']) . "', 
patient_city = '" . $this->db->escape($data['patient_city']) . "', 
patient_country_id = '" . (int)($data['patient_country_id']) . "', 
patient_postalcode = '" . $this->db->escape($data['patient_postalcode']) . "', 
patient_date_modified = NOW(), 
patient_province_id = '" . (int)($data['patient_province_id']) . "'

WHERE patient_id = '" . (int)$patient_id . "'";

		$this->db->query($s);		
	
//p($s,__LINE__.__FILE__); exit;		
	}
	
	
	public function deletePatient($patient_id) {
		$this->db->query("DELETE FROM patient WHERE patient_id = '" . (int)$patient_id . "'");
	}
	
	public function getPatient($patient_id) {
		

		$s = "SELECT DISTINCT * FROM patient p LEFT JOIN clinician c ON p.patient_clinician_id = c.clinician_id WHERE p.patient_id = '" . (int)$patient_id . "'";
		

		if ($this->user->getUGstr() != '1') {	
			$s .= " AND patient_id IN (".$this->getSaPatients().")";
		}
		
		$query = $this->db->query($s);

		return $query->row;
	}
        
        public function getPatientAddress($patient_id){
            $s = "SELECT `patient`.`patient_address_1`, `patient`.`patient_address_2`, `patient`.`patient_city` ,`zone`.`name` as province,`patient`.`patient_postalcode`,  `country`.`name`
FROM `patient`, `zone`, `country`
WHERE ((`patient`.`patient_id` = '" . (int)$patient_id . "') AND (`patient`.`patient_country_id` =`country`.`country_id`) AND (`patient`.`patient_province_id` =`zone`.`zone_id`))" ;
		
		$query = $this->db->query($s);

		return $query->row;
        }
	
	public function getSaPatients(){
		
		$user_id = $this->session->data['user_id'];
		
		$s = "SELECT DISTINCT patient_id FROM user_to_clinic LEFT JOIN patient ON user_to_clinic_clinic_id = patient_clinic_id  WHERE user_to_clinic_user_id = '$user_id'";
		
		$qr = $this->db->query($s);
		
		$creds = $qr->rows;

		$custs = array();
		foreach($creds as $cred) {
			if ($cred['patient_id']) $custs[] = $cred['patient_id'];
		}
		
		$patients = implode(',',$custs);
		
		if(!$patients) $patients = 0;

		return $patients;
		
	}
		
	public function getPatients($data = array()) {
		
		$sql = "SELECT *, CONCAT(patient_firstname, ' ', patient_lastname) AS name FROM patient LEFT JOIN clinic c ON clinic_id = patient_clinic_id";

		$implode = array();
		
		if ($this->user->getUGstr() != '1') $implode[] = "patient_id IN (".$this->getSaPatients().")";
		
		if (isset($data['filter_refno']) && !is_null($data['filter_refno'])) {
			$implode[] = "patient_id = '" . $this->db->escape($data['filter_refno']) . "'";
		}
		
		if (isset($data['filter_fname']) && !is_null($data['filter_fname'])) {
			$implode[] = "UPPER(patient_firstname) LIKE UPPER('%" . $this->db->escape($data['filter_fname']) . "%')";
		}
		
		if (isset($data['filter_lname']) && !is_null($data['filter_lname'])) {
			$implode[] = "UPPER(patient_lastname) LIKE UPPER('%" . $this->db->escape($data['filter_lname']) . "%')";
		}
		
		if (isset($data['filter_patient_email']) && !is_null($data['filter_patient_email'])) {
			$implode[] = "UPPER(patient_email) LIKE  UPPER('%" . $this->db->escape($data['filter_patient_email']) . "%')";
		}
		
		if (isset($data['filter_phone']) && !is_null($data['filter_phone'])) {
			$implode[] = "REPLACE(patient_telephone,' ','') LIKE '%" . str_replace(' ','',$this->db->escape($data['filter_phone'])) . "%'";
		}	
		if (isset($data['filter_clinic']) && !is_null($data['filter_clinic'])) {
			$implode[] = "UPPER(c.clinic_name) LIKE UPPER('%" . $this->db->escape($data['filter_clinic']) . "%')";
		}
		
		if (isset($data['filter_patient_status']) && !is_null($data['filter_patient_status'])) {
			$implode[] = "patient_status = '" . (int)$data['filter_patient_status'] . "'";
		}	
		
		if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
			$implode[] = "DATE(patient_date_added) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		}
		if (isset($data['filter_dob']) && !is_null($data['filter_dob'])) {
			$implode[] = "DATE(patient_dob) = DATE('" . $this->db->escape($data['filter_dob']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'fname'=>'patient_firstname',
			'lname'=>'patient_lastname',
			'dob'=>'patient_dob',
			'refno'=>'patient_id',
			'patient_email'=>'patient_email',
			'phone'=>'patient_telephone',
			'patient_clinic' =>'clinic_name',
			'patient_status'=>'patient_status',
			'patient_date_added'=>'patient_date_added'
		);	

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			
			if ($sort_data[$data['sort']] == 'patient_id') $sql .= " ORDER BY  patient_id ";
			else $sql .= " ORDER BY UPPER(CONCAT(" . $sort_data[$data['sort']]."))";	
		} else {
			$sql .= " ORDER BY UPPER(patient_firstname), UPPER(patient_lastname) " ;
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
	
	
	public function getPatientsByKeyword($keyword) {
		if ($keyword) {
			$query = $this->db->query("SELECT * FROM patient WHERE LCASE(CONCAT(patient_firstname, ' ', patient_lastname)) LIKE '%" . $this->db->escape(strtolower($keyword)) . "%' ORDER BY UPPER(CONCAT(patient_firstname, patient_lastname, patient_email))");
	
			return $query->rows;
		} else {
			return array();	
		}
	}
	
	public function getPatientsByProduct($product_id) {
		if ($product_id) {
			$query = $this->db->query("SELECT DISTINCT `patient_email` FROM `order` o LEFT JOIN order_product op ON (o.order_id = op.order_id) WHERE op.product_id = '" . (int)$product_id . "' AND o.order_patient_status_id <> '0'");
	
			return $query->rows;
		} else {
			return array();	
		}
	}
	
	public function getTotalPatients($data = array()) {
      	/*$sql = "SELECT COUNT(*) AS total FROM patient LEFT JOIN clinic c on patient_clinic_id = c.clinic_id";
// p($sql,__LINE__,__FILE__);		
		$implode = array();

		$user_id = (isset($_SESSION['user_id'])?$_SESSION['user_id']:false);

$ax = $this->db->query("SELECT * FROM user WHERE user_id = '" . $user_id . "'");


		if (in_array('11',explode(',',$ax->row['user_group_string']))) $implode[] = "patient_id IN (".$this->getSaPatients().")";
		
// p($this->getSaPatients(),__LINE__,__FILE__);		
		
		if (isset($data['filter_refno']) && !is_null($data['filter_refno'])) {
			$implode[] = "patient_id = '". $this->db->escape($data['filter_refno']) . "'";
		}
		
		if (isset($data['filter_fname']) && !is_null($data['filter_fname'])) {
			$implode[] = "UPPER(patient_firstname) LIKE UPPER('%" . $this->db->escape($data['filter_fname']) . "%')";
		}
		
		if (isset($data['filter_lname']) && !is_null($data['filter_lname'])) {
			$implode[] = "UPPER(patient_lastname) LIKE UPPER('%" . $this->db->escape($data['filter_lname']) . "%')";
		}
		
		if (isset($data['filter_patient_email']) && !is_null($data['filter_patient_email'])) {
			$implode[] = "UPPER(patient_email) LIKE  UPPER('%" . $this->db->escape($data['filter_patient_email']) . "%')";
		}
		
		if (isset($data['filter_phone']) && !is_null($data['filter_phone'])) {
			$implode[] = "REPLACE(patient_telephone,' ','') LIKE '%" . str_replace(' ','',$this->db->escape($data['filter_phone'])) . "%'";
		}	
		
		if (isset($data['filter_clinic']) && !is_null($data['filter_clinic'])) {
			$implode[] = " c.clinic_name LIKE '%" . $this->db->escape($data['filter_clinic']) . "%'";
		}
		
		if (isset($data['filter_patient_status']) && !is_null($data['filter_patient_status'])) {
			$implode[] = "patient_status = '" . (int)$data['filter_patient_status'] . "'";
		}	
		
		if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
			$implode[] = "DATE(patient_date_added) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		}
		if (isset($data['filter_dob']) && !is_null($data['filter_dob'])) {
			$implode[] = "DATE(patient_dob) = DATE('" . $this->db->escape($data['filter_dob']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}*/
		
		$sql = "SELECT COUNT(*) AS total FROM patient LEFT JOIN clinic c ON clinic_id = patient_clinic_id";

		$implode = array();
		
		if ($this->user->getUGstr() != '1') $implode[] = "patient_id IN (".$this->getSaPatients().")";
		
		if (isset($data['filter_refno']) && !is_null($data['filter_refno'])) {
			$implode[] = "patient_id = '" . $this->db->escape($data['filter_refno']) . "'";
		}
		
		if (isset($data['filter_fname']) && !is_null($data['filter_fname'])) {
			$implode[] = "UPPER(patient_firstname) LIKE UPPER('%" . $this->db->escape($data['filter_fname']) . "%')";
		}
		
		if (isset($data['filter_lname']) && !is_null($data['filter_lname'])) {
			$implode[] = "UPPER(patient_lastname) LIKE UPPER('%" . $this->db->escape($data['filter_lname']) . "%')";
		}
		
		if (isset($data['filter_patient_email']) && !is_null($data['filter_patient_email'])) {
			$implode[] = "UPPER(patient_email) LIKE  UPPER('%" . $this->db->escape($data['filter_patient_email']) . "%')";
		}
		
		if (isset($data['filter_phone']) && !is_null($data['filter_phone'])) {
			$implode[] = "REPLACE(patient_telephone,' ','') LIKE '%" . str_replace(' ','',$this->db->escape($data['filter_phone'])) . "%'";
		}	
		if (isset($data['filter_clinic']) && !is_null($data['filter_clinic'])) {
			$implode[] = "UPPER(c.clinic_name) LIKE UPPER('%" . $this->db->escape($data['filter_clinic']) . "%')";
		}
		
		if (isset($data['filter_patient_status']) && !is_null($data['filter_patient_status'])) {
			$implode[] = "patient_status = '" . (int)$data['filter_patient_status'] . "'";
		}	
		
		if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
			$implode[] = "DATE(patient_date_added) = DATE('" . $this->db->escape($data['filter_date']) . "')";
		}
		if (isset($data['filter_dob']) && !is_null($data['filter_dob'])) {
			$implode[] = "DATE(patient_dob) = DATE('" . $this->db->escape($data['filter_dob']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'fname'=>'patient_firstname',
			'lname'=>'patient_lastname',
			'dob'=>'patient_dob',
			'refno'=>'patient_id',
			'patient_email'=>'patient_email',
			'phone'=>'patient_telephone',
			'patient_clinic' =>'clinic_name',
			'patient_status'=>'patient_status',
			'patient_date_added'=>'patient_date_added'
		);
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
		
	public function getTotalPatientsAwaitingApproval() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM patient WHERE patient_status = '0' OR approved = '0'");

		return $query->row['total'];
	}
	
	public function verifyPatients($data){
			
		$patient_firstname = $data['patient_firstname'];
		$patient_lastname = $data['patient_lastname'];
		$patient_dob = $data['patient_dob'];
		$patient_clinic_id = $data['patient_clinic_id'];
			
			
		$query = $this->db->query("SELECT * FROM patient WHERE UPPER(patient_firstname) LIKE UPPER('%".mysql_real_escape_string($patient_firstname)."%') AND UPPER(patient_lastname) LIKE UPPER('%".mysql_real_escape_string($patient_lastname)."%') AND patient_dob = '".$patient_dob."' AND patient_clinic_id = '".$patient_clinic_id."'");
			
		if($query->num_rows){
	
			return 1;
	
		}
			
	}
	
	public function checkPatientOrder($patient_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_patient_id = '".$patient_id."'");
				
		return $query->row['total'];
		
	
	}
}
?>