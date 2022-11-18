<?php    
class ControllerSaleClinician extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('sale/clinician');
		 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/clinician');
		
    	$this->getList();
  	}
  
  	public function insert() {
		$this->load->language('sale/clinician');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/clinician');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      	  	$this->model_sale_clinician->addClinician($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
		  
$url = $this->hc_functions->getURL(array('filter_clinic_name','filter_clinician_name','filter_clinician_phone','filter_clinician_email','filter_status','filter_date_added','page','sort','order'));

			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . $url);
		}
    	
    	$this->getForm();
  	} 
   
  	public function update() {
		$this->load->language('sale/clinician');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/clinician');
		
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$this->model_sale_clinician->editClinician($this->request->get['clinician_id'], $this->request->post);
	  		
			$this->session->data['success'] = $this->language->get('text_success');
	  

$url = $this->hc_functions->getURL(array('filter_clinic_name','filter_clinician_name','filter_clinician_phone','filter_clinician_email','filter_status','filter_date_added','page','sort','order'));

			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . $url);
		}
    	$this->getForm();
  	}   

  	public function delete() {
		$this->load->language('sale/clinician');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/clinician');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $clinician_id) {
				$this->model_sale_clinician->deleteClinician($clinician_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

$url = $this->hc_functions->getURL(array('filter_clinic_name','filter_clinician_name','filter_clinician_phone','filter_clinician_email','filter_status','filter_date_added','page','sort','order'));

			
			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . $url);
    	}
    
    	$this->getList();
  	}  
    
  	private function getList() {
		
$refarr = $this->hc_functions->getURLvars(array('filter_clinic_name','filter_clinician_name','filter_clinician_phone','filter_clinician_email','filter_status','filter_date_added','sort','order','page'),'clinician_name');
		
extract($refarr);

$url = $this->hc_functions->getURL(array('filter_clinic_name','filter_clinician_name','filter_clinician_phone','filter_clinician_email','filter_status','filter_date_added','page','sort','order'));

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=sale/clinician/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=sale/clinician/delete&token=' . $this->session->data['token'] . $url;

		$this->data['clinicians'] = array();

$url = $this->hc_functions->getURL(array('filter_clinic_name','filter_clinician_name','filter_clinician_phone','filter_clinician_email','filter_status','filter_date_added','page','sort','order'));

		$data = array(
			'filter_clinic_name'              => $filter_clinic_name, 
			'filter_clinician_name'              => $filter_clinician_name, 
			'filter_clinician_email'            => $filter_clinician_email, 
			'filter_clinician_phone'          => $filter_clinician_phone, 
			'filter_status'          => $filter_status, 
			'filter_date_added'        => $filter_date_added,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		
		$clinician_total = $this->model_sale_clinician->getTotalClinicians($data);
	
		$results = $this->model_sale_clinician->getClinicians($data);

    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=sale/clinician/update&token=' . $this->session->data['token'] . '&clinician_id=' . $result['clinician_id'] . $url
			);
			$this->data['clinicians'][] = array(
				'clinician_id'    => $result['clinician_id'],
				'clinician_name'           => $result['clinician_name'],
				'clinic_name'           => $result['clinic_name'],
				'clinician_email'          => $result['clinician_email'],
				'clinician_telephone' => $result['clinician_telephone'],
				'clinician_status'         => ($result['clinician_status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'clinician_date_added'     => substr($result['clinician_date_added'],0,10),
				'selected'       => isset($this->request->post['selected']) && in_array($result['clinician_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_approved'] = $this->language->get('column_approved');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_approve'] = $this->language->get('button_approve');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
	
$url = $this->hc_functions->getURL(array('filter_clinic_name','filter_clinician_name','filter_clinician_phone','filter_clinician_email','filter_status','filter_date_added','page'));
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$this->data['sort_clinic_name'] = HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . '&sort=clinic_name' . $url;
		$this->data['sort_clinician_name'] = HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . '&sort=clinician_name' . $url;
		$this->data['sort_clinician_telephone'] = HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . '&sort=clinician_telephone' . $url;
		$this->data['sort_clinician_email'] = HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . '&sort=clinician_email' . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . '&sort=c.clinician_status' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . '&sort=c.clinician_date_added' . $url;
		
$url = $this->hc_functions->getURL(array('filter_clinic_name','filter_clinician_name','filter_clinician_phone','filter_clinician_email','filter_status','filter_date_added','sort','order'));

		$pagination = new Pagination();
		$pagination->total = $clinician_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
$url = $this->hc_functions->getURL(array('filter_clinic_name','filter_clinician_name','filter_clinician_phone','filter_clinician_email','filter_status','filter_date_added','page','sort','order'));

		$this->data['filter_clinic_name'] = $filter_clinic_name;
		$this->data['filter_clinician_name'] = $filter_clinician_name;
		$this->data['filter_clinician_email'] = $filter_clinician_email;
		$this->data['filter_clinician_phone'] = $filter_clinician_phone;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_date_added'] = $filter_date_added;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'sale/clinician_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
  
  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
    	
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_default'] = $this->language->get('entry_default');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_city_postcode'] = $this->language->get('entry_city_postcode');
		$this->data['entry_country_zone'] = $this->language->get('entry_country_zone');
		$this->data['entry_default'] = $this->language->get('entry_default');
 
		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add'] = $this->language->get('button_add');
    	$this->data['button_remove'] = $this->language->get('button_remove');
	
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_address'] = $this->language->get('tab_address');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['clinician_name'])) {
			$this->data['error_clinician_name'] = $this->error['clinician_name'];
		} else {
			$this->data['error_clinician_name'] = '';
		}

 		if (isset($this->error['clinician_email'])) {
			$this->data['error_clinician_email'] = $this->error['clinician_email'];
		} else {
			$this->data['error_clinician_email'] = '';
		}

 		if (isset($this->error['clinician_telephone'])) {
			$this->data['error_clinician_telephone'] = $this->error['clinician_telephone'];
		} else {
			$this->data['error_clinician_telephone'] = '';
		}
		
 		if (isset($this->error['clinician_clinic_id'])) {
			$this->data['error_clinician_clinic_id'] = $this->error['clinician_clinic_id'];
		} else {
			$this->data['error_clinician_clinic_id'] = '';
		}

$url = $this->hc_functions->getURL(array('filter_clinic_name','filter_clinician_name','filter_clinician_phone','filter_clinician_email','filter_status','filter_date_added','page','sort','order'));

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['clinician_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/clinician/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/clinician/update&token=' . $this->session->data['token'] . '&clinician_id=' . $this->request->get['clinician_id'] . $url;
		}
		  
    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'] . $url;  
		
    	if (isset($this->request->get['clinician_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$clinician_info = $this->model_sale_clinician->getClinician($this->request->get['clinician_id']);
    	}

		
    	if (isset($this->request->post['clinician_name'])) {
      		$this->data['clinician_name'] = $this->request->post['clinician_name'];
		} elseif (isset($clinician_info)) { 
			$this->data['clinician_name'] = $clinician_info['clinician_name'];
		} else {
      		$this->data['clinician_name'] = '';
    	}

    	if (isset($this->request->post['clinician_email'])) {
      		$this->data['clinician_email'] = $this->request->post['clinician_email'];
    	} elseif (isset($clinician_info)) { 
			$this->data['clinician_email'] = $clinician_info['clinician_email'];
		} else {
      		$this->data['clinician_email'] = '';
    	}

    	if (isset($this->request->post['clinician_telephone'])) {
      		$this->data['clinician_telephone'] = $this->request->post['clinician_telephone'];
    	} elseif (isset($clinician_info)) { 
			$this->data['clinician_telephone'] = $clinician_info['clinician_telephone'];
		} else {
      		$this->data['clinician_telephone'] = '';
    	}

    	if (isset($this->request->post['clinician_clinic_id'])) {
      		$this->data['clinician_clinic_id'] = $this->request->post['clinician_clinic_id'];
    	} elseif (isset($clinician_info)) { 
			$this->data['clinician_clinic_id'] = $clinician_info['clinician_clinic_id'];
		} else {
      		$this->data['clinician_clinic_id'] = '';
    	}

    	if (isset($this->request->post['clinician_notes'])) {
      		$this->data['clinician_notes'] = $this->request->post['clinician_notes'];
    	} elseif (isset($clinician_info)) { 
			$this->data['clinician_notes'] = $clinician_info['clinician_notes'];
		} else {
      		$this->data['clinician_notes'] = '';
    	}

    	if (isset($this->request->post['clinician_status'])) {
      		$this->data['clinician_status'] = $this->request->post['clinician_status'];
    	} elseif (isset($clinician_info)) { 
			$this->data['clinician_status'] = $clinician_info['clinician_status'];
		} else {
      		$this->data['clinician_status'] = 1;
    	}

		$this->load->model('sale/clinic');
		$this->data['clinics'] = $this->model_sale_clinic->getClinics();

		$this->template = 'sale/clinician_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	
	
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/clinician')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['clinician_name'])) < 1) || (strlen(utf8_decode($this->request->post['clinician_name'])) > 64)) {
      		$this->error['clinician_name'] = "Enter clinician name";
    	}

		if ((strlen(utf8_decode($this->request->post['clinician_email'])) > 0) && ((strlen(utf8_decode($this->request->post['clinician_email'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['clinician_email'])))) {
      		$this->error['clinician_email'] = $this->language->get('error_email');
    	}

    	if ((strlen(utf8_decode($this->request->post['clinician_telephone'])) < 3) || (strlen(utf8_decode($this->request->post['clinician_telephone'])) > 32)) {
      		$this->error['clinician_telephone'] = $this->language->get('error_telephone');
    	}

    	if (! isset($this->request->post['clinician_clinic_id']) || !trim($this->request->post['clinician_clinic_id'])) {
      		$this->error['clinician_clinic_id'] = "Please select clinic";
    	}


		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}    

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/clinician')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  
  	} 	
}
?>
