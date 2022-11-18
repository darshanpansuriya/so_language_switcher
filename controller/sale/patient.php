<?php
class ControllerSalePatient extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/patient');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/patient');

    	$this->getList();
  	}

  	public function insert() {
		$this->load->language('sale/patient');

    	$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/patient');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			/**
			 * this block of code is written to check whether the patient exists in the records or not
			 * if record is there is will prompt the user patient already exists in the records select from the existing ones
			 */

			$patient_id = $this->request->isget('patient_id');

			//this will check if the patient id is set or not
			if($patient_id==''){



				//calling the model functions to query the records in the table
				$verify_patients = $this->model_sale_patient->verifyPatients($this->request->post);


				//if records exists then the alert message given to the user
				if ($verify_patients == 1)
				{

					$alert_message='';

					$alert_message .='<script type="text/javascript">';
					$alert_message .='alert("This patient already exists in the system, please check the details and submit again");';
					$alert_message .= '</script>';

					echo $alert_message;

				}
				else
				{
					$this->model_sale_patient->addPatient($this->request->post);

					$this->session->data['success'] = $this->language->get('text_success');

					$url = $this->hc_functions->getURL(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','page','sort','order'));

					$this->redirect(HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . $url);


				}

			}
			else
			{

      	  	$this->model_sale_patient->addPatient($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

$url = $this->hc_functions->getURL(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','page','sort','order'));

			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . $url);
			}
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->load->language('sale/patient');

    	$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/patient');


    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
//p($this->request->post,__LINE__.__FILE__); exit;
			$this->model_sale_patient->editPatient($this->request->get['patient_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

$url = $this->hc_functions->getURL(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','page','sort','order'));

			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . $url);
		}
    	$this->getForm();
  	}

  	public function delete() {
		$this->load->language('sale/patient');

    	$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/patient');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {

    		// initializing all the variables
    		$active_order_patients = '';

    		$deleted_patients_id = '';

    		$list_of_patient_orders = '';

    		$list_of_patient = '';

    		$count = 1;

			foreach ($this->request->post['selected'] as $patient_id) {

				/**
				 *	this block is written to verify that the patients queued up for deletion don't have any
				 *  orders associated with them if yes they should not be deleted and the user will be
				 *  prompted with a message.
				 */
				$patient_order_count = $this->model_sale_patient->checkPatientOrder($patient_id);


				// if patient order count is more than 0
				if ($patient_order_count > 0)
				{
					// preparing a list of patients with active orders.
					$active_order_patients .= $patient_id.', ';


					// if count is greater than zero then preparing the patient id with respective order counts
					if ($count >0){

						if ($patient_order_count == 1)
						{
							$list_of_patient_orders .= '<br>'.'Patient '.$patient_id.' is currently associated with '.$patient_order_count.' amount of order.'.'<br>';
						}
						else
						{
							$list_of_patient_orders .= '<br>'.'Patient '.$patient_id.' is currently associated with '.$patient_order_count.' amount of orders.'.'<br>';
						}
					}

					// if count is greater is 1 then preparing the error message and apppending the previously prepared variable for list of patients.
					if ($count > 1){

						$list_of_patient = 'Patient '.$active_order_patients.' have orders already associated in the system and cannot be removed. '.'<br>' ;
						$list_of_patient .= $list_of_patient_orders;
					}
					else
					{
						if ($patient_order_count == 1)
						{
							$list_of_patient = 'This patient has an order already associated in the system and cannot be removed. Patient is currently associated with '.$patient_order_count.' amount of order.';
						}
						else
						{
							$list_of_patient = 'This patient has orders already associated in the system and cannot be removed. They are currently associated with '.$patient_order_count.' amount of orders.';
						}

					}


					// preparing comma seperated patient id's that are having orders.


				}
				else {

					$deleted_patients_id .= $patient_id.',';

					// deleting the patients which don't have orders associated with them
					$this->model_sale_patient->deletePatient($patient_id);

				}

				$count++;
			}

			// sending the error message to the view for the patients that are not deleted.

			if ($list_of_patient){
			  $this->session->data['error'] = $list_of_patient;
			  $this->data['error_warning']=$this->session->data['error'];
			}

			if ($deleted_patients_id){
			  $this->session->data['success'] = 'Patient # '.$deleted_patients_id.'  deleted successfully';
			}

$url = $this->hc_functions->getURL(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','page','sort','order'));

			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . $url);
    	}

    	$this->getList();
  	}

  	private function getList() {

$refarr = $this->hc_functions->getURLvars(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','page','sort','order','page'),'patient_lastname,patient_firstname');

extract($refarr);

$url = $this->hc_functions->getURL(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','page','sort','order'));


  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['addplanner'] = HTTPS_SERVER . 'index.php?route=sale/patient/addplanner&token=' . $this->session->data['token'] . $url;

		$this->data['approve'] = HTTPS_SERVER . 'index.php?route=sale/patient/approve&token=' . $this->session->data['token'] . $url;
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=sale/patient/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=sale/patient/delete&token=' . $this->session->data['token'] . $url;

		$this->data['patients'] = array();

		$data = array(
			'filter_refno'              => $filter_refno,
			'filter_fname'              => $filter_fname,
			'filter_lname'              => $filter_lname,
			'filter_dob'             	=> $filter_dob,
			'filter_patient_email'             => $filter_patient_email,
			'filter_phone' => $filter_phone,
			'filter_clinic'=>$filter_clinic,
			'filter_patient_status'            => $filter_patient_status,
			'filter_date'        => $filter_date,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);


		$patient_total = $this->model_sale_patient->getTotalPatients($data);

		$results = $this->model_sale_patient->getPatients($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=sale/patient/update&token=' . $this->session->data['token'] . '&patient_id=' . $result['patient_id'] . $url
			);
			$this->data['patients'][] = array(
				'patient_id'    => $result['patient_id'],
				'name'     => $result['name'],
				'patient_firstname'     => $result['patient_firstname'],
				'patient_lastname'     => $result['patient_lastname'],
				'patient_dob'     => $result['patient_dob'],
				'patient_email'          => $result['patient_email'],
				'patient_telephone' => $result['patient_telephone'],
				'patient_clinic_name'=> $result['clinic_name'],
				'patient_company' => $result['patient_company'],
				'patient_code' => $result['patient_code'],
				'patient_clinic_id' => $result['patient_clinic_id'],
				'patient_clinician_id' => $result['patient_clinician_id'],
				'patient_weight' => $result['patient_weight'],
				'patient_weight_id' => $result['patient_weight_id'],
				'patient_gender_id' => $result['patient_gender_id'],
				'patient_notes' => $result['patient_notes'],
				'patient_address_1' => $result['patient_address_1'],
				'patient_address_2' => $result['patient_address_2'],
				'patient_city' => $result['patient_city'],
				'patient_country_id' => $result['patient_country_id'],
				'patient_province_id' => $result['patient_province_id'],
				'patient_postalcode' => $result['patient_postalcode'],
				'patient_notes' => $result['patient_notes'],

				'patient_status'         => ($result['patient_status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'patient_date_added'     => date($this->language->get('date_format_short'), strtotime($result['patient_date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['patient_id'], $this->request->post['selected']),
				'action'         => $action,
				'weights' => $this->getLookupValues(1),
				'patient_weight_id' => $result['patient_weight_id'],
				'genders' => $this->getLookupValues(2),
				'gender_id' => $result['patient_gender_id']
			);
		}
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_patient_email'] = $this->language->get('column_patient_email');
		$this->data['column_patient_group'] = $this->language->get('column_patient_group');
		$this->data['column_patient_status'] = $this->language->get('column_patient_status');
		$this->data['column_approved'] = $this->language->get('column_approved');
		$this->data['column_patient_date_added'] = $this->language->get('column_patient_date_added');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_ref'] = $this->language->get('column_ref');
		$this->data['column_first_name'] = $this->language->get('column_first_name');
		$this->data['column_last_name'] = $this->language->get('column_last_name');
		$this->data['column_dob'] = $this->language->get('column_dob');
		$this->data['column_phone'] = $this->language->get('column_phone');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_clinic'] = $this->language->get('column_clinic');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');


		$this->data['button_approve'] = $this->language->get('button_approve');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_add_patient'] = $this->language->get('button_add_patient');
		$this->data['button_delete_patient'] = $this->language->get('button_delete_patient');

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

$url = $this->hc_functions->getURL(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','page'));

//include "hc_scripts/inc_patient_create_url.php";

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$this->data['sort_refno'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . '&sort=refno' . $url;
		$this->data['sort_fname'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . '&sort=fname' . $url;
		$this->data['sort_lname'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . '&sort=lname' . $url;
		$this->data['sort_dob'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . '&sort=dob' . $url;
		$this->data['sort_patient_email'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . '&sort=patient_email' . $url;
		$this->data['sort_phone'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . '&sort=phone' . $url;
		$this->data['sort_patient_clinic'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . '&sort=patient_clinic' . $url;
		$this->data['sort_patient_status'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . '&sort=patient_status' . $url;
		$this->data['sort_patient_date_added'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . '&sort=patient_date_added' . $url;

$url = $this->hc_functions->getURL(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','sort','order'));

//			p($this->request->get['filter_patient_email'],__LINE__.__FILE__);


//p($url);

		$pagination = new Pagination();
		$pagination->total = $patient_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . $url . '&page={page}';

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_refno'] = $filter_refno;
		$this->data['filter_fname'] = $filter_fname;
		$this->data['filter_lname'] = $filter_lname;
		$this->data['filter_dob'] = $filter_dob;
		$this->data['filter_patient_email'] = $filter_patient_email;
		$this->data['filter_phone'] = $filter_phone;
		$this->data['filter_clinic'] = $filter_clinic;
		$this->data['filter_patient_status'] = $filter_patient_status;
		$this->data['filter_date'] = $filter_date;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/patient_list.tpl';
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
    	$this->data['entry_patient_email'] = $this->language->get('entry_patient_email');
    	$this->data['entry_patient_telephone'] = $this->language->get('entry_patient_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_patient_group'] = $this->language->get('entry_patient_group');
		$this->data['entry_patient_status'] = $this->language->get('entry_patient_status');
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

		$this->data['genders'] = $this->getLookupValues(2);


$this->data = array_merge($this->hc_functions->getErrors(array('warning', 'patient_clinic_id','patient_dob', 'patient_firstname','patient_lastname','patient_telephone'),$this->error),$this->data);


$url = $this->hc_functions->getURL(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','page','sort','order'));


  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['patient_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/patient/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/patient/update&token=' . $this->session->data['token'] . '&patient_id=' . $this->request->get['patient_id'] . $url;
		}

    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . $url;

    	if (isset($this->request->get['patient_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$patient_info = $this->model_sale_patient->getPatient($this->request->get['patient_id']);

			if (!$patient_info) {

				$this->session->data['error'] = 'Patient cannot be found!';

$url = $this->hc_functions->getURL(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','page','sort','order'));

				$this->redirect(HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . $url);
			}

    	}

		if (!isset($patient_info)) $patient_info = array();

		$this->data = array_merge($this->hc_functions->getFormVars(array('patient_id','patient_firstname','patient_lastname','patient_email','patient_telephone','patient_fax','patient_status','patient_date_added','patient_code','patient_clinic_id', 'patient_clinician_id', 'patient_weight','patient_weight_id','patient_gender_id','patient_dob','patient_notes','patient_company','patient_address_1','patient_address_2','patient_city','patient_country_id','patient_province_id','patient_postalcode'),$patient_info),$this->data);


		$this->data['weights'] = $this->getLookupValues(1);
		$this->data['genders'] = $this->getLookupValues(2);
// p($this->data,__LINE__.__FILE__);		exit;

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('sale/clinic');

		$this->data['clinics'] = $this->model_sale_clinic->getClinics();

		$this->template = 'sale/patient_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function zone() {
		$output = '';

		$this->load->model('localisation/zone');

		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$this->response->setOutput($output, $this->config->get('config_compression'));
	}

	public function approve() {
		$this->load->language('sale/patient');
		$this->load->language('mail/patient');

		if (!$this->user->hasPermission('modify', 'sale/patient')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->post['selected'])) {
				$this->load->model('sale/patient');

				foreach ($this->request->post['selected'] as $patient_id) {
					$patient_info = $this->model_sale_patient->getPatient($patient_id);

					if ($patient_info && !$patient_info['approved']) {
						$this->model_sale_patient->approve($patient_id);

						$this->load->model('setting/store');

						$store_info = $this->model_setting_store->getStore($patient_info['store_id']);

						if ($store_info) {
							$store_name = $store_info['name'];
							$store_url = $store_info['url'] . 'index.php?route=account/login';
						} else {
							$store_name = $this->config->get('config_name');
							$store_url = $this->config->get('config_url') . 'index.php?route=account/login';
						}

						$message  = sprintf($this->language->get('text_welcome'), $store_name) . "\n\n";;
						$message .= $this->language->get('text_login') . "\n";
						$message .= $store_url . "\n\n";
						$message .= $this->language->get('text_services') . "\n\n";
						$message .= $this->language->get('text_thanks') . "\n";
						$message .= $store_name;

						$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->hostname = $this->config->get('config_smtp_host');
						$mail->username = $this->config->get('config_smtp_username');
						$mail->password = $this->config->get('config_smtp_password');
						$mail->port = $this->config->get('config_smtp_port');
						$mail->timeout = $this->config->get('config_smtp_timeout');
						$mail->setTo($patient_info['patient_email']);
						$mail->setFrom($this->config->get('config_patient_email'));
						$mail->setSender($store_name);
						$mail->setSubject(sprintf($this->language->get('text_subject'), $store_name));
						$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
						$mail->send();

						$this->session->data['success'] = sprintf($this->language->get('text_approved'), $patient_info['patient_firstname'] . ' ' . $patient_info['patient_lastname']);
					}
				}
			}
		}

$url = $this->hc_functions->getURL(array('filter_refno','filter_fname','filter_lname','filter_dob','filter_patient_email','filter_phone','filter_clinic','filter_patient_status','filter_date','page','sort','order'));

		$this->redirect(HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . $url);
	}



  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/patient')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['patient_firstname'])) < 1) || (strlen(utf8_decode($this->request->post['patient_firstname'])) > 50)) {
      		$this->error['patient_firstname'] = "Last Name must be between 1 and 50 characters!" ;
    	}

    	if (! isset($this->request->post['patient_clinic_id']) || !trim($this->request->post['patient_clinic_id'])) {
      		$this->error['patient_clinic_id'] = "Please select clinic";
    	}


    	/*if (!isset($this->request->post['patient_dob']) || !($this->request->post['patient_dob']) || ($this->request->post['patient_dob']== '0000-00-00')) {

    		$this->error['patient_dob'] = "Please enter the date of birth !" ;
    	} */

    	if ((strlen(utf8_decode($this->request->post['patient_lastname'])) < 1) || (strlen(utf8_decode($this->request->post['patient_lastname'])) > 50)) {
      		$this->error['patient_lastname'] = "Last Name must be between 1 and 50 characters!" ;
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
			if (! isset($this->error['warning'])) $this->error['warning'] = 'Please Enter Data for Required Fields!';
	  		return FALSE;
		}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/patient')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

	public function getLookupValues($type = 1,$id = 0) { //W or G
		$s = "SELECT * FROM lookup_table WHERE lookup_table_lookup_table_types_id = '$type' ORDER BY lookup_table_sort";
		if ($id) $s .= " AND lookup_table_id = '$id'";
		$w = $this->db->query($s);
		return $w->rows;
	}
}
?>
