<?php
class ControllerSaleClinic extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/clinic');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/clinic');

		$this->getList();
	}

	public function insert() {
		$this->load->language('sale/clinic');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/clinic');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_sale_clinic->addClinic($this->request->post);
			$cid = mysql_insert_id();
			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->hc_functions->getURL(array('filter_clinic_id','filter_clinic_name','filter_contact_name','filter_contact_email','filter_contact_phone','filter_status','filter_date_added','page','sort','order'));

			if (isset($this->request->post['add_clinician_f']) && $this->request->post['add_clinician_f'])
				$this->redirect($this->request->post['add_clinician_f'] . $url . '&cid=' . $cid);
			//			else $this->redirect(HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . $url);


			$loc = "location";
			if(isset($this->request->post['where_am_i']) && $this->request->post['where_am_i']) {
				$loc .= "+'&w=".$this->request->post['where_am_i'] . "'";
				$loc .= "+'&ortho_id=".$this->request->post['o_id'] . "'";
			}
			echo "<script>location=$loc;</script>";
			exit();
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('sale/clinic');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/clinic');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_sale_clinic->editClinic($this->request->get['clinic_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');


			$url = $this->hc_functions->getURL(array('filter_clinic_id','filter_clinic_name','filter_contact_name','filter_contact_email','filter_contact_phone','filter_status','filter_date_added','page','sort','order'));

			//p($this->request->post['add_clinician_f'],__LINE__); exit;
			if (isset($this->request->post['add_clinician_f']) && $this->request->post['add_clinician_f'])
				$this->redirect($this->request->post['add_clinician_f'] . $url);
			//			else $this->redirect(HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . $url);

			$loc = "location";
			if(isset($this->request->post['where_am_i']) && $this->request->post['where_am_i']) {
				$loc .= "+'&w=".$this->request->post['where_am_i'] . "'";
				$loc .= "+'&ortho_id=".$this->request->post['o_id'] . "'";
			}
			echo "<script>location=$loc;</script>";
			exit();

		}
		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/clinic');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/clinic');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $clinic_id) {
				$this->model_sale_clinic->deleteClinic($clinic_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->hc_functions->getURL(array('filter_clinic_id','filter_clinic_name','filter_contact_name','filter_contact_email','filter_contact_phone','filter_status','filter_date_added','page','sort','order'));


			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}

	private function getList() {

		$refarr = $this->hc_functions->getURLvars(array('filter_clinic_id','filter_clinic_name','filter_contact_name','filter_contact_email','filter_contact_phone','filter_status','filter_date_added','sort','order','page'),'clinic_name');

		extract($refarr);

		$url = $this->hc_functions->getURL(array('filter_clinic_id','filter_clinic_name','filter_contact_name','filter_contact_email','filter_contact_phone','filter_status','filter_date_added','page','sort','order'));

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
				'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
				'text'      => $this->language->get('text_home'),
				'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
				'href'      => HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . $url,
				'text'      => $this->language->get('heading_title'),
				'separator' => ' :: '
		);

		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=sale/clinic/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=sale/clinic/delete&token=' . $this->session->data['token'] . $url;

		$this->data['clinics'] = array();

		$url = $this->hc_functions->getURL(array('filter_clinic_id','filter_clinic_name','filter_contact_name','filter_contact_email','filter_contact_phone','filter_status','filter_date_added','page','sort','order'));

		$data = array(
				'filter_clinic_id'              => $filter_clinic_id,
				'filter_clinic_name'              => $filter_clinic_name,
				'filter_contact_name'             => $filter_contact_name,
				'filter_contact_email'            => $filter_contact_email,
				'filter_contact_phone'          => $filter_contact_phone,
				'filter_status'          => $filter_status,
				'filter_date_added'        => $filter_date_added,
				'sort'                     => $sort,
				'order'                    => $order,
				'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                    => $this->config->get('config_admin_limit')
		);

		$clinic_total = $this->model_sale_clinic->getTotalClinics($data);

		$results = $this->model_sale_clinic->getClinics($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => HTTPS_SERVER . 'index.php?route=sale/clinic/update&token=' . $this->session->data['token'] . '&clinic_id=' . $result['clinic_id'] . $url
			);
			$this->data['clinics'][] = array(
					'clinic_id'    => $result['clinic_id'],
					'clinic_name'           => $result['clinic_name'],
					'clinic_contact'          => $result['clinic_contact'],
					'clinic_contact_email'          => $result['clinic_contact_email'],
					'clinic_telephone' => $result['clinic_telephone'],
					'clinic_orthotic_labels' => $result['clinic_orthotic_labels'],
					'clinic_orthotic_label_name' => $result['clinic_orthotic_label_name'],
					'clinic_orthotic_printer' => $result['clinic_orthotic_printer'],
					'clinic_casting_method' => $result['clinic_casting_method'],
					'clinic_scan' => $result['clinic_scan'],
					'clinic_fax' => $result['clinic_fax'],
					'clinic_code' => $result['clinic_code'],
					'clinic_status'         => ($result['clinic_status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
					'clinic_date_added'     => substr($result['clinic_date_added'],0,10),
					'selected'       => isset($this->request->post['selected']) && in_array($result['clinic_id'], $this->request->post['selected']),
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
		$this->data['column_contact'] = $this->language->get('column_contact');
		$this->data['column_phone'] = $this->language->get('column_phone');
		$this->data['column_acct'] = $this->language->get('column_acct');

		$this->data['button_approve'] = $this->language->get('button_approve');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_add_clinic'] = $this->language->get('button_add_clinic');
		$this->data['button_delete_clinic'] = $this->language->get('button_delete_clinic');

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

		$url = $this->hc_functions->getURL(array('filter_clinic_id','filter_clinic_name','filter_contact_name','filter_contact_email','filter_contact_phone','filter_status','filter_date_added','page'));

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$this->data['sort_clinic_id'] = HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . '&sort=clinic_id' . $url;
		$this->data['sort_clinic_name'] = HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . '&sort=clinic_name' . $url;
		$this->data['sort_clinic_contact'] = HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . '&sort=clinic_contact' . $url;
		$this->data['sort_clinic_telephone'] = HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . '&sort=clinic_telephone' . $url;
		$this->data['sort_clinic_email'] = HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . '&sort=clinic_contact_email' . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . '&sort=clinic_status' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . '&sort=clinic_date_added' . $url;

		$url = $this->hc_functions->getURL(array('filter_clinic_id','filter_clinic_name','filter_contact_name','filter_contact_email','filter_contact_phone','filter_status','filter_date_added','sort','order'));

		$pagination = new Pagination();
		$pagination->total = $clinic_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . $url . '&page={page}';

		$this->data['pagination'] = $pagination->render();
		$url = $this->hc_functions->getURL(array('filter_clinic_id','filter_clinic_name','filter_contact_name','filter_contact_email','filter_contact_phone','filter_status','filter_date_added','page','sort','order'));

		$this->data['filter_clinic_id'] = $filter_clinic_id;
		$this->data['filter_clinic_name'] = $filter_clinic_name;
		$this->data['filter_contact_name'] = $filter_contact_name;
		$this->data['filter_contact_email'] = $filter_contact_email;
		$this->data['filter_contact_phone'] = $filter_contact_phone;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_date_added'] = $filter_date_added;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/clinic_list.tpl';
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

		// ERROR SECTION TO FORM
		$this->data = array_merge($this->hc_functions->getErrors(array('warning', 'clinic_name', 'clinic_contact','clinic_contact_email','clinic_telephone'),$this->error),$this->data);


		$url = $this->hc_functions->getURL(array('filter_clinic_id','filter_clinic_name','filter_contact_name','filter_contact_email','filter_contact_phone','filter_status','filter_date_added','page','sort','order'));

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
				'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
				'text'      => $this->language->get('text_home'),
				'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
				'href'      => HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . $url,
				'text'      => $this->language->get('heading_title'),
				'separator' => ' :: '
		);

		if (!isset($this->request->get['clinic_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/clinic/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/clinic/update&token=' . $this->session->data['token'] . '&clinic_id=' . $this->request->get['clinic_id'] . $url;
		}

		$this->data['insert_clinician'] = HTTPS_SERVER . 'index.php?route=sale/clinician/insert&token=' . $this->session->data['token'];


		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=sale/clinic&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['clinic_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$clinic_info = $this->model_sale_clinic->getClinic($this->request->get['clinic_id']);
		}

		if (!isset($clinic_info)) $clinic_info = array();

		$this->data = array_merge($this->hc_functions->getFormVars(array('clinic_name','clinic_contact','clinic_contact_email','clinic_base_shipping_charge','clinic_min_ship_pairs','clinic_terms','clinic_telephone','clinic_fax','clinic_code','clinic_status','clinic_notes', 'clinic_default_clinician','clinic_orthotic_printer','clinic_casting_method','clinic_scan','clinic_orthotic_label_name','clinic_orthotic_labels'),$clinic_info),$this->data);

		if (isset($this->request->post['addresses'])) {
			$this->data['addresses'] = $this->request->post['addresses'];
		} elseif (isset($this->request->get['clinic_id'])) {
			$this->data['addresses'] = $this->model_sale_clinic->getAddressesByClinicId($this->request->get['clinic_id']);
		} else {
			$this->data['addresses'] = array();
		}

		if (isset($this->request->get['clinic_id'])) {
			$this->data['clinicians'] = $this->model_sale_clinic->getCliniciansByClinic($this->request->get['clinic_id']);
		} else {
			$this->data['clinicians'] = array();
		}

		$this->template = 'sale/clinic_form.tpl';
		$this->children = array(
				'common/header',
				'common/footer'
		);
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/clinic')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		if (strlen(utf8_decode(trim($this->request->post['clinic_name']))) < 1) {
			$this->error['clinic_name'] = "Enter company name";
		}

		if (strlen(utf8_decode(trim($this->request->post['clinic_contact']))) < 1) {
			$this->error['clinic_contact'] = "Enter contact name";
		}

		if ((strlen(utf8_decode($this->request->post['clinic_contact_email'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['clinic_contact_email']))) {
			$this->error['clinic_contact_email'] = "Enter a valid email";
		}

		if ((strlen(utf8_decode($this->request->post['clinic_telephone'])) < 3) || (strlen(utf8_decode($this->request->post['clinic_telephone'])) > 32)) {
			$this->error['clinic_telephone'] = "Enter a valid telephone number";
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/clinic')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function updateItemPricing(){

		//loading the model which is responsible for the update function
		$this->load->model('sale/clinic');

		if (isset($this->request->get['clinic_id'])) {
			$clinic_id =  $this->request->get['clinic_id'];
		}

		if (isset($this->request->get['ortho_id'])) {
			$orthotic_type_id =  $this->request->get['ortho_id'];
		}

		if (isset($this->request->get['pricing_item_id'])) {
			$item_id =  $this->request->get['pricing_item_id'];
		}

		if (isset($this->request->get['pricing_item_value'])) {
			$item_price =  $this->request->get['pricing_item_value'];
		}

		// calling the model function to update  the values in the database
		echo $this->model_sale_clinic->updateItemPricingValues($clinic_id,$orthotic_type_id,$item_id,$item_price);

	}

	public  function itemPricingDefaults(){

		//loading the model which is responsible for the update function
		$this->load->model('sale/clinic');

		if (isset($this->request->get['c_id'])) {
			$clinic_id =  $this->request->get['c_id'];

		}
			// calling the model function to update  the values in the database
			echo $this->model_sale_clinic->updateItemPricingDefaults($clinic_id);
	}

	/**
	 * this function saves the newly added orthotic label to the system
	 */

	public function updateLabel()
	{
		//loading the model which is responsible for the update function
		$this->load->model('sale/clinic');

		if (isset($this->request->get['label_id'])) {
			$orthotic_label_id =  $this->request->get['label_id'];
		}
		if (isset($this->request->get['label_name'])) {
			$orthotic_label_name =  $this->request->get['label_name'];
		}

		// calling the model function to update  the values in the database
		echo $this->model_sale_clinic->updateLabelName($orthotic_label_id,$orthotic_label_name);

	}

	/**
	 * this function saves the newly added orthotic label to the system
	 */

	public function addNewLabel()
	{
		//loading the model which is responsible for the update function
		$this->load->model('sale/clinic');

		if (isset($this->request->get['label_name'])) {
			$orthotic_label_name =  $this->request->get['label_name'];
		}

		// calling the model function to update  the values in the database
		echo $this->model_sale_clinic->addLabelName($orthotic_label_name);

	}

	public function labelList()
	{
		$r = $this->db->query("SELECT * FROM lookup_table LEFT JOIN lookup_table_types ON lookup_table_types_id = lookup_table_lookup_table_types_id WHERE lookup_table_types_id = 84 ORDER BY lookup_table_sort");


		$label_list = '<select name="clinic_orthotic_label_name" id="clinic_orthotic_label_name" onchange="displayButtons();" >';
		$label_list .= '<option value"">Select..</option>';
		 foreach ($r->rows as $labelnames) {

			$label_list .= '<option value="'.$labelnames["lookup_table_id"].'"> '.$labelnames["lookup_table_title"].'</option>';

		 }
		$label_list .= '</select>';

		echo $label_list;
	}

	public function labelDelete()
	{
		//loading the model which is responsible for the update function
		$this->load->model('sale/clinic');

		if (isset($this->request->get['label_id'])) {
			$orthotic_label_id =  $this->request->get['label_id'];
		}

		// calling the model function to update  the values in the database
		echo $this->model_sale_clinic->deleteLabelName($orthotic_label_id);
	}

	/**
	 * this function saves the clinic pricing for the clinics
	 */

	public function updateClinicPricing(){

		$this->load->model('sale/clinic');

		if (isset($this->request->get['orthotic_id'])) {
			$orthotic_id =  $this->request->get['orthotic_id'];
		}
		if (isset($this->request->get['clinic_id'])) {
			$clinic_id =  $this->request->get['clinic_id'];
		}
		if (isset($this->request->get['price_1'])) {
			$price_1 =  $this->request->get['price_1'];
		}
		if (isset($this->request->get['price_2'])) {
			$price_2 =  $this->request->get['price_2'];
		}
		if (isset($this->request->get['price_3'])) {
			$price_3 =  $this->request->get['price_3'];
		}
		if (isset($this->request->get['price_4'])) {
			$price_4 =  $this->request->get['price_4'];
		}
		if (isset($this->request->get['price_5'])) {
			$price_5 =  $this->request->get['price_5'];
		}
		if (isset($this->request->get['price_6'])) {
			$price_6 =  $this->request->get['price_6'];
		}
		if (isset($this->request->get['price_7'])) {
			$price_7 =  $this->request->get['price_7'];
		}
		if (isset($this->request->get['price_8'])) {
			$price_8 =  $this->request->get['price_8'];
		}
		if (isset($this->request->get['price_9'])) {
			$price_9 =  $this->request->get['price_9'];
		}
		if (isset($this->request->get['price_10'])) {
			$price_10 =  $this->request->get['price_10'];
		}
		if (isset($this->request->get['price_11'])) {
			$price_11 =  $this->request->get['price_11'];
		}
		if (isset($this->request->get['price_12'])) {
			$price_12 =  $this->request->get['price_12'];
		}

		echo $this->model_sale_clinic->updateClinicPricingValues($orthotic_id,$clinic_id,$price_1,$price_2,$price_3,$price_4,$price_5,$price_6,$price_7,$price_8,$price_9,$price_10,$price_11,$price_12);

	}
}
?>
