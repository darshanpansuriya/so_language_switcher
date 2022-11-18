<?php

class ControllerSaleOrder extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('sale/order');
		$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/order');
		$this->getList();
	}

	public function update() {
		$this->load->language('sale/order');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('sale/order');

		// heading
		$this->data['heading_place'] = $this->language->get('heading_place');
		$this->data['heading_update'] = $this->language->get('heading_update');
		$this->data['heading_order'] = $this->language->get('heading_order');

		// top values [tv]
		$this->data['tv_order_date'] = $this->language->get('tv_order_date');
		$this->data['tv_status'] = $this->language->get('tv_status');

		// left tabs
		$this->data['left_tabs_patient_info'] = $this->language->get('left_tabs_patient_info');
		$this->data['left_tabs_ortho_type'] = $this->language->get('left_tabs_ortho_type');
		$this->data['left_tabs_mods'] = $this->language->get('left_tabs_mods');
		$this->data['left_tabs_patient_info'] = $this->language->get('left_tabs_patient_info');
		$this->data['left_tabs_additions'] = $this->language->get('left_tabs_additions');
		$this->data['left_tabs_posting'] = $this->language->get('left_tabs_posting');
		$this->data['left_tabs_covers'] = $this->language->get('left_tabs_covers');
		$this->data['left_tabs_delivery'] = $this->language->get('left_tabs_delivery');
		$this->data['left_tabs_general'] = $this->language->get('left_tabs_general');
		$this->data['left_tabs_files'] = $this->language->get('left_tabs_files');
		$this->data['left_tabs_status'] = $this->language->get('left_tabs_status');

		// tab 1
		$this->data['tab1_title'] = $this->language->get('tab1_title');

		// tab 2
		$this->data['tab2_title'] = $this->language->get('tab2_title');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_order->editOrder($this->request->get['order_id'], $this->request->post);
			$this->model_sale_order->addOrderHistory($this->request->get['order_id'], $this->request->post);
			$this->session->data['success'] = "You have saved the order";
			parse_str(str_replace('&amp;', '&', $_SERVER['QUERY_STRING']), $url);

			if (isset($this->request->post['where_am_i']) && $this->request->post['where_am_i']) {
				$url['w'] = $this->request->post['where_am_i'];
			}

			if(isset($this->request->post['save_new'])){
						$url = '';
						$url['route'] = 'sale/order/insert';
						$url['token']=$this->session->data['token'];
						$url['w'] = 'tab_patient_info';
						unset($this->session->order_id);
			}

			$loc = urldecode(http_build_query($url, '&'));
			echo "<script>location='?$loc';</script>";
			exit();
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/order');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('sale/order');

		if (isset($this->request->post['selected']) && ($this->validateDelete())) {

			foreach ($this->request->post['selected'] as $order_id) {
				$this->model_sale_order->deleteOrder($order_id);
			}

			$this->session->data['success'] = "You have deleted orders";
			$url = $this->hc_functions->getURL(array('filter_order_id', 'filter_clinic_name', 'filter_patient_name', 'filter_status', 'filter_date_added', 'filter_deliverydate', 'total', 'page', 'sort', 'order'));
			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}

	public function verifypackingslipaddresses() {
		$this->load->language('sale/order');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('sale/order');
		$json = array();
		$json['result'] = "";
		$this->load->library('json');

		if (isset($this->request->post['selected'])) {
			$all_orders = $this->request->post['selected'];
			$first_time_loop = true;
			$first_address = "";

			foreach ($all_orders as $one_order) {

				if($first_time_loop) {
					$first_address = $this->model_sale_order->getShipAddressFromOrder($one_order)->row['order_shipping_address'];
					$first_time_loop = false;
				}

			}
			$json['result'] = true;
			$this->response->setOutput(Json::encode($json));
			return;
		}

	}

	public function createpackingslip() {

		$this->load->language('sale/order');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('sale/order');

		if (isset($this->request->post['selected'])) {
			// capture the list of selected order id
			$order_list = array();
			$order_list = $this->request->post['selected'];
			$packing_slip_ids = '';

			foreach($order_list as $single_order_id) {
				$matched_orders = '';
				$order_primary = $this->model_sale_order->getOrder($single_order_id);
				$order_status_primary = $order_primary['order_status_id'];
				$order_address_primary = $this->model_sale_order->getShipAddressFromOrder($single_order_id)->row['order_shipping_address'];

				if ($order_status_primary == '130') {

					foreach($order_list as $reference_order_id) {
						$order_secondary = $this->model_sale_order->getOrder($reference_order_id);
						$order_status_secondary = $order_secondary['order_status_id'];
						$order_address_secondary = $this->model_sale_order->getShipAddressFromOrder($reference_order_id)->row['order_shipping_address'];

						if ($order_address_primary == $order_address_secondary) {
							$matched_orders .= $reference_order_id . ",";
							$this->model_sale_order->updateOrderStatusShipped($reference_order_id);
						}

					}

					$matched_orders = rtrim($matched_orders, ',');
					$success = $this->model_sale_order->insertPackingSlip($matched_orders, date("Y-m-d"), date("Y-m-d"), "ICS", $order_address_primary);

					if ($success) {
						$packing_slip_ids .= $this->db->getLastId() . ',';
						$this->model_sale_order->updateOrderStatusShipped($single_order_id);
					}

				}

			}

			$packing_slip_ids = rtrim($packing_slip_ids, ',');
			// queueing the request to quickbooks and passing the concatenated packing slip id
			$this->model_sale_order->quickbooksQueue($packing_slip_ids);
			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/order/viewpackingslip&packing_slip_ids=' . $packing_slip_ids . '&token=' . $this->session->data['token']);
		}

	}

	public function viewpackingslip() {
		$this->load->language('sale/order');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('sale/order');
		$this->data['orders'] = array();
		$this->data['products'] = array();
		$packing_slip_order_info = array();

		if (isset($this->request->get['packing_slip_ids'])) {
			$packing_slip_ids = explode(',', $this->request->get['packing_slip_ids']);

			foreach ($packing_slip_ids as $packing_slip_id) {
				//$order_clinics = '';
				$this->data['title'] = $this->language->get('heading_title');

				if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
					$this->data['base'] = HTTPS_SERVER;
				} else {
					$this->data['base'] = HTTP_SERVER;
				}

				$this->data['direction'] = $this->language->get('direction');
				$this->data['language'] = $this->language->get('code');
				$this->data['logo'] = DIR_IMAGE . $this->config->get('config_logo');

				//getting the packing slip ids
				$packing_list_data = $this->model_sale_order->getPackingSlip($packing_slip_id);

				$packing_lists[] = $packing_list_data[0];

				//updating the packing list numbers in the table
				$this->model_sale_order->updateOrderPackingList($packing_slip_id);

				// this checks whether the packin gslip are in generate mode or they are in view mode
				// if in view mode the status of the order will not change .

				if (!isset($this->request->get['view'])) {

					//this update the status of the orders.
					$this->model_sale_order->updateOrderHistory($packing_slip_id,$_SESSION['user_id']);
				}

			}

			// passing the packing slip data to the view
			$this->data['packing_list_info'][] = $packing_lists;

			//updating the packing list numbers in the table
			$this->model_sale_order->updateOrderPackingList($packing_slip_id);

			// this code block generates information of packing slips based on clinics and their orders
			$packing_slip_clinic_order_info = array();
			$master_packing_list_data = array();
			$packing_list_data = array();
			$packing_list_combined = array();

			foreach($packing_lists as  $packing_list){

				// getting the list of packing list clinics based on packing slip id
				$packing_list_clinics = $this->model_sale_order->getPackingSlipClinics($packing_list['id']);

				// initialize the array to capture the clinic information
				$packing_list_clinic_info = array();

				foreach($packing_list_clinics as $packing_list_clinic){

					$packing_list_clinic_name_temp = $this->model_sale_order->getClinicName($packing_list_clinic['clinic_id']);

					$packing_list_clinic_name = $packing_list_clinic_name_temp[0]['clinic_name'];

					$packing_list_clinic_info['clinic-name'] = $packing_list_clinic_name;

					// getting the list of orders for a particular clinic_id and packing_list_id

					$packing_list_clinic_orders = $this->model_sale_order->getPackingSlipClinicsOrders($packing_list['id'],$packing_list_clinic['clinic_id']);

					$packing_list_clinic_info['clinic-orders'] = $packing_list_clinic_orders;

					$packing_list_data[] = $packing_list_clinic_info;

					$packing_list_clinic_info = '';

				}

				$packing_list_combined['clinic-order-info'] = $packing_list_data;

				$packing_list_combined['general-info'] = $packing_list;

				$master_packing_list_data[] = $packing_list_combined;

				$packing_list_combined = '';

				$packing_list_data = '';
			}

			$this->data['master_packing_list_data'] = $master_packing_list_data;

			$this->template = 'sale/order_packing_slip.tpl';
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));

		} else {

			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/order/update&token=' . $this->session->data['token']);
		}


	}


	public function compareOrdersByClinicID($a, $b) {
		if ($a['clinic_id'] == $b['clinic_id']) {
			return 0;
		}
		return ($a['clinic_id'] < $b['clinic_id']) ? -1 : 1;
	}


	private function getList() {

		$url = $this->hc_functions->getURL(array('filter_order_id', 'filter_clinic_name', 'filter_patient_name', 'filter_status', 'filter_date_added', 'filter_date_added_2','filter_deliverydate','filter_deliverydate_2', 'filter_total', 'page', 'sort', 'order'));

		$refarr = $this->hc_functions->getURLvars(array('filter_order_id', 'filter_clinic_name', 'filter_patient_name', 'filter_status', 'filter_date_added', 'filter_date_added_2', 'filter_deliverydate','filter_deliverydate_2', 'filter_total', 'sort', 'order', 'page'), 'deliverydate', 'DESC');

		extract($refarr);

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
				'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
				'text' => $this->language->get('text_home'),
				'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
				'href' => HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . $url,
				'text' => $this->language->get('heading_title'),
				'separator' => ' :: '
		);

		$this->data['invoice'] = HTTPS_SERVER . 'index.php?route=sale/order/invoice&token=' . $this->session->data['token'];
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=sale/order/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=sale/order/delete&token=' . $this->session->data['token'] . $url;
		$this->data['packingslip'] = HTTPS_SERVER . 'index.php?route=sale/order/createpackingslip&token=' . $this->session->data['token'] . $url;

		$this->data['orders'] = array();
		$filter_date_added = $filter_date_added ? $filter_date_added :'0000-00-00';
		$filter_deliverydate = $filter_deliverydate ? $filter_deliverydate :'0000-00-00';
		$data = array(
				'filter_order_id' => $filter_order_id,
				'filter_clinic_name' => $filter_clinic_name,
				'filter_patient_name' => $filter_patient_name,
				'filter_status' => $filter_status,
				'filter_date_added' => $filter_date_added,
				'filter_date_added_2' => $filter_date_added_2,
				'filter_deliverydate' => $filter_deliverydate, // Mod Delivery Date
				'filter_deliverydate_2' => $filter_deliverydate_2, // Mod Delivery Date
				'filter_total' => $filter_total,
				'sort' => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit' => $this->config->get('config_admin_limit')
		);

		$results = $this->model_sale_order->getOrders($data);
		$order_total = $this->model_sale_order->getTotalOrders($data);
		$newResults = $results;

		foreach ($newResults as $result) {
			$action = array();

			if (stristr($result['user_group_step_display'], '"' . $result['order_status_id'] . '"')) {
				$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => HTTPS_SERVER . 'index.php?route=sale/order/update&token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url
				);
			} else {
				$action[] = array(
						'text' => 'View',
						'href' => HTTPS_SERVER . 'index.php?route=sale/order/update&token=' . $this->session->data['token'] . '&v=1&order_id=' . $result['order_id'] . $url
				);
			}

			$this->data['orders'][] = array(
					'order_id' => $result['order_id'],
					'patient_name' => $result['patient_name'],
					'clinic_name' => $result['clinic_name'],
					'order_status_id' => $result['order_status_id'],
					'order_status' => $this->hc_functions->get_field('order_status_name', "SELECT order_status_name FROM order_status WHERE order_status_id = '" . $result['order_status_id'] . "'"),
					'order_date_added' => date($this->language->get('date_format_short'), strtotime($result['order_date_added'])),
					'order_deliverydate' => $result['order_deliverydate'] !== '0000-00-00' ? date($this->language->get('date_format_short'), strtotime($result['order_deliverydate'])) : 'N/A', // Mod Delivery Date
					'order_total' => $result['order_total'],
					'order_date_needed' => $result['order_date_needed'],
					'order_shipping_date' => $result['order_shipping_date'],
					'order_contact_name' => $result['order_contact_name'],
					'order_shipping_method' => $result['order_shipping_method'],
					'order_shipping_number' => $result['order_shipping_number'],
					'order_shipping_address' => $result['order_shipping_address'],
					'order_values_rush_75' => $result['order_values_rush_75'],
					'order_values_59' => $result['order_values_59'],
					'order_values_7' => $result['order_values_7'],
					'order_currency_id' => $result['order_currency_id'],
					'order_originaldelivery' => $result['order_originaldelivery'] !== '0000-00-00' ? date($this->language->get('date_format_short'), strtotime($result['order_originaldelivery'])) : 'N/A',
					'selected' => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
					'action' => $action
			);
		}

		// heading
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['heading_note'] = $this->language->get('heading_note');

		// text message
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing_orders'] = $this->language->get('text_missing_orders');

		// column headers
		$this->data['column_order'] = $this->language->get('column_order');
		$this->data['column_clinic_name'] = $this->language->get('column_clinic_name');
		$this->data['column_patient_name'] = $this->language->get('column_patient_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_shipping_date'] = $this->language->get('column_shipping_date');
		$this->data['column_deliverydate'] = $this->language->get('column_deliverydate'); // Mod Delivery Date
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_action'] = $this->language->get('column_action');

		// buttons
		$this->data['button_invoices'] = $this->language->get('button_invoices');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_clear_filter'] = $this->language->get('button_clear_filter');
		$this->data['button_print_pack_list'] = $this->language->get('button_print_pack_list');
		$this->data['button_download_order'] = $this->language->get('button_download_order');
		$this->data['button_print_work_order'] = $this->language->get('button_print_work_order');
		$this->data['button_print_order'] = $this->language->get('button_print_order');
		$this->data['button_delete_order'] = $this->language->get('button_delete_order');
		$this->data['button_create_labels'] = $this->language->get('button_create_labels');
		$this->data['button_add_order'] = $this->language->get('button_add_order');
		$this->data['button_copy_order'] = $this->language->get('button_copy_order');
		$this->data['button_filter_order'] = $this->language->get('button_filter_order');
		$this->data['button_lab_order_list'] = $this->language->get('button_lab_order_list');
		$this->data['button_save_status'] = $this->language->get('button_save_status');
		$this->data['button_save'] = $this->language->get('button_save');

		// token
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
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

		$url = $this->hc_functions->getURL(array('filter_order_id', 'filter_clinic_name', 'filter_patient_name', 'filter_status', 'filter_date_added','filter_date_added_2', 'filter_deliverydate','filter_deliverydate_2', 'filter_total', 'page'));


		if ($order == 'ASC') {
			$url .= '&order=' . 'DESC';
		} else {
			$url .= '&order=' . 'ASC';
		}

		$this->data['sort_order_id'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=order_id&token=' . $this->session->data['token'] . $url;
		$this->data['sort_clinic_name'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=clinic_name&token=' . $this->session->data['token'] . $url;
		$this->data['sort_patient_name'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=patient_name&token=' . $this->session->data['token'] . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=status&token=' . $this->session->data['token'] . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=order_date_added&token=' . $this->session->data['token'] . $url;
		// Mod Delivery Date
		$this->data['sort_deliverydate'] = HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . '&sort=order_deliverydate' . $url;
		$this->data['deliverydate_status'] = $this->config->get('deliverydate_status');
		// End:Mod Delivery Date
		$this->data['sort_total'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=order_total&token=' . $this->session->data['token'] . $url;


		$url = $this->hc_functions->getURL(array('filter_order_id', 'filter_clinic_name', 'filter_patient_name', 'filter_status', 'filter_date_added','filter_date_added_2', 'filter_deliverydate', 'filter_deliverydate_2', 'filter_total', 'sort', 'order'));

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . $url . '&page={page}';

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_clinic_name'] = $filter_clinic_name;
		$this->data['filter_patient_name'] = $filter_patient_name;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_date_added_2'] = $filter_date_added_2;
		$this->data['filter_deliverydate'] = $filter_deliverydate; // Mod Delivery Date
		$this->data['filter_deliverydate_2'] = $filter_deliverydate_2; // Mod Delivery Date
		$this->data['filter_total'] = $filter_total;

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/order_list.tpl';
		$this->children = array(
				'common/header',
				'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function generate() {
		$this->load->model('sale/order');

		$json = array();

		if (isset($this->request->get['order_id'])) {
			$json['invoice_id'] = $this->model_sale_order->generateInvoiceId($this->request->get['order_id']);
		}

		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
	}

	public function usePatientAddress(){
		$this->language->load('sale/order');
		$this->load->model('sale/patient');
		$patientID = $this->request->get['patient_id'];
		$patient = $this->model_sale_patient->getPatientAddress($patientID);

		$addressExist= ((isset($patient['patient_address_1'])&&strlen(trim($patient['patient_address_1']))>0) ||
				(isset($patient['patient_address_2'])&&strlen(trim($patient['patient_address_2']))>0))?true:false;
		$json = array();
		$json['success'] = $addressExist;
		$json['patient_addrees'] = implode(" ", $patient) ;

		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
	}

	public function history()
	{
		$this->language->load('sale/order');

		$this->load->model('sale/order');

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->session->data['warning'] = $this->language->get('error_permission');
		} else {

			$this->model_sale_order->addOrderHistory($this->request->get['order_id'], $this->request->post);


			$this->session->data['success'] = $this->language->get('text_success');
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function invoice() {
		$this->load->language('sale/order');

		$this->data['title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');

		$this->data['text_invoice'] = $this->language->get('text_invoice');

		$this->data['text_order_id'] = $this->language->get('text_order_id');
		$this->data['text_invoice_id'] = $this->language->get('text_invoice_id');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_comment'] = $this->language->get('column_comment');

		$this->data['logo'] = DIR_IMAGE . $this->config->get('config_logo');

		$this->load->model('sale/order');

		$this->data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}

		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);


			if ($order_info) {
				if ($order_info['invoice_id']) {
					$invoice_id = $order_info['invoice_prefix'] . $order_info['invoice_id'];
				} else {
					$invoice_id = '';
				}

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{patient_firstname} {patient_lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
						'{patient_firstname}',
						'{patient_lastname}',
						'{company}',
						'{address_1}',
						'{address_2}',
						'{city}',
						'{postcode}',
						'{zone}',
						'{zone_code}',
						'{country}'
				);

				$replace = array(
						'patient_firstname' => $order_info['shipping_firstname'],
						'patient_lastname' => $order_info['shipping_lastname'],
						'company' => $order_info['shipping_company'],
						'address_1' => $order_info['shipping_address_1'],
						'address_2' => $order_info['shipping_address_2'],
						'city' => $order_info['shipping_city'],
						'postcode' => $order_info['shipping_postcode'],
						'zone' => $order_info['shipping_zone'],
						'zone_code' => $order_info['shipping_zone_code'],
						'country' => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{patient_firstname} {patient_lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
						'{patient_firstname}',
						'{patient_lastname}',
						'{company}',
						'{address_1}',
						'{address_2}',
						'{city}',
						'{postcode}',
						'{zone}',
						'{zone_code}',
						'{country}'
				);

				$replace = array(
						'patient_firstname' => $order_info['payment_firstname'],
						'patient_lastname' => $order_info['payment_lastname'],
						'company' => $order_info['payment_company'],
						'address_1' => $order_info['payment_address_1'],
						'address_2' => $order_info['payment_address_2'],
						'city' => $order_info['payment_city'],
						'postcode' => $order_info['payment_postcode'],
						'zone' => $order_info['payment_zone'],
						'zone_code' => $order_info['payment_zone_code'],
						'country' => $order_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$product_data = array();

				$products = $this->model_sale_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						$option_data[] = array(
								'name' => $option['name'],
								'value' => $option['value']
						);
					}

					$product_data[] = array(
							'order_products' => $product,
							'name' => $product['name'],
							'model' => $product['model'],
							'option' => $option_data,
							'quantity' => $product['quantity'],
							'price' => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
							'total' => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
					);
				}

				$total_data = $this->model_sale_order->getOrderTotals($order_id);

				$this->data['orders'][] = array(
						'order_id' => $order_id,
						'order_info' => $order_info,
						'invoice_id' => $invoice_id,
						'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
						'store_name' => $order_info['store_name'],
						'store_url' => rtrim($order_info['store_url'], '/'),
						'address' => nl2br($this->config->get('config_address')),
						'telephone' => $this->config->get('config_telephone'),
						'fax' => $this->config->get('config_fax'),
						'email' => $this->config->get('config_email'),
						'shipping_address' => $shipping_address,
						'payment_address' => $payment_address,
						'clinic_email' => $order_info['email'],
						'ip' => $order_info['ip'],
						'clinic_telephone' => $order_info['telephone'],
						'comment' => $order_info['comment'],
						'product' => $product_data,
						'item_name' => $item_name,
						'total' => $total_data,
						'hc_po_no' => $order_info['hc_po_no'],
						'hc_placed_by' => $order_info['hc_placed_by'],
						// Mod Delivery Date

						'deliverydate' => date($this->language->get('date_format_short'), strtotime($order_info['deliverydate']))
						// End:Mod Delivery Date
				);
			}
		}


		$this->template = 'sale/order_invoice.tpl';

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function address() {
		$this->language->load('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = (int) $this->request->get['order_id'];
		} else {
			return;
		}

		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			return;
		}

		$json = array();

		$this->load->model('sale/order');

		$this->load->model('localisation/country');

		$result = $this->model_localisation_country->getCountry($this->request->post[$type . '_country_id']);

		$this->request->post[$type . '_country'] = $result['name'];

		$this->load->model('localisation/zone');

		$result = $this->model_localisation_zone->getZone($this->request->post[$type . '_zone_id']);

		$this->request->post[$type . '_zone'] = $result['name'];

		if (isset($this->request->get['type']) && $this->request->get['type'] == 'shipping') {
			$this->model_sale_order->updateShippingAddress($order_id, $this->request->post);
		} elseif (isset($this->request->get['type']) && $this->request->get['type'] == 'payment') {
			$this->model_sale_order->updatePaymentAddress($order_id, $this->request->post);
		}

		$json['success'] = $this->language->get('text_success_address');

		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
	}

	public function zone() {
		$output = '<select name="' . $this->request->get['type'] . '_id">';

		$this->load->model('localisation/zone');

		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

		$selected_name = '';

		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
				$selected_name = $result['name'];
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$output .= '</select>';
		$output .= '<input type="hidden" id="' . $this->request->get['type'] . '_name" name="' . $this->request->get['type'] . '" value="' . $selected_name . '" />';

		$this->response->setOutput($output, $this->config->get('config_compression'));
	}

	public function mDate2Unix($date) {

		$d = substr($date, 0, 2);
		$m = substr($date, 3, 2);
		$y = substr($date, -4);
		echo "$y-$m-$d";
	}

	public function insert() {
		$this->load->language('sale/order');

		// language variables

		// heading
		$this->data['heading_place'] = $this->language->get('heading_place');
		$this->data['heading_update'] = $this->language->get('heading_update');
		$this->data['heading_order'] = $this->language->get('heading_order');

		// top values [tv]
		$this->data['tv_order_date'] = $this->language->get('tv_order_date');
		$this->data['tv_status'] = $this->language->get('tv_status');

		// left tabs
		$this->data['left_tabs_patient_info'] = $this->language->get('left_tabs_patient_info');
		$this->data['left_tabs_ortho_type'] = $this->language->get('left_tabs_ortho_type');
		$this->data['left_tabs_mods'] = $this->language->get('left_tabs_mods');
		$this->data['left_tabs_patient_info'] = $this->language->get('left_tabs_patient_info');
		$this->data['left_tabs_additions'] = $this->language->get('left_tabs_additions');
		$this->data['left_tabs_posting'] = $this->language->get('left_tabs_posting');
		$this->data['left_tabs_covers'] = $this->language->get('left_tabs_covers');
		$this->data['left_tabs_delivery'] = $this->language->get('left_tabs_delivery');
		$this->data['left_tabs_general'] = $this->language->get('left_tabs_general');
		$this->data['left_tabs_files'] = $this->language->get('left_tabs_files');
		$this->data['left_tabs_status'] = $this->language->get('left_tabs_status');

		// tab 1
		$this->data['tab1_title'] = $this->language->get('tab1_title');

		// tab 2
		$this->data['tab2_title'] = $this->language->get('tab2_title');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/order');
		//echo "test";
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			// p(__LINE__.__FILE__); exit;

			/**
			 * this block of code is written to check whether the patient exists in the records or not
			 * if record is there is will prompt the user patient already exists in the records select from the existing ones
			 */

			$patient_id = $this->request->isget('pid');

			//this will check if the patient id is set or not
			if($patient_id==''){

				//calling the model functions to query the records in the table
				$verify_patients = $this->model_sale_order->verifyPatients($this->request->post);


				//if records exists then the alert message given to the user
				if ($verify_patients == 1)
				{

					$alert_message='';

					$alert_message .='<script type="text/javascript">';
					$alert_message .='alert("This patient already exists in the system, please select the patient from the drop-down menu");';
					$alert_message .= '</script>';

					echo $alert_message;

				}
				else
				{

					//calling the model function to save the records for the new user
					$this->model_sale_order->addOrder($this->request->post);

					$this->session->data['success'] = $this->language->get('text_success');

					$url = '';

					// $url = $this->hc_functions->getURL(array('filter_order_id','filter_clinic_name','filter_patient_name','filter_status','filter_date_added','filter_deliverydate','filter_total','page','sort','order'));


					parse_str(str_replace('&amp;', '&', $_SERVER['QUERY_STRING']), $url);

					if (isset($this->request->post['where_am_i']) && $this->request->post['where_am_i']) {
						$url['w'] = $this->request->post['where_am_i'];
						$url['order_id'] = $this->session->order_id;
						$url['route'] = 'sale/order/update';
						unset($this->session->order_id);
					}
					if(isset($this->request->post['save_new'])){
						$url = '';

						$url['route'] = 'sale/order/insert';
						$url['token']=$this->session->data['token'];
						$url['w'] = 'tab_patient_info';

						unset($this->session->order_id);

					}
					$loc = urldecode(http_build_query($url, '&'));
					echo "<script>location='?$loc';</script>";
					exit();

					//			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . $url);
				}
			}
			else {
				//calling the model function to save the records for the new user
				$this->model_sale_order->addOrder($this->request->post);

				$this->session->data['success'] = $this->language->get('text_success');

				$url = '';

				// $url = $this->hc_functions->getURL(array('filter_order_id','filter_clinic_name','filter_patient_name','filter_status','filter_date_added','filter_deliverydate','filter_total','page','sort','order'));


				parse_str(str_replace('&amp;', '&', $_SERVER['QUERY_STRING']), $url);

				if (isset($this->request->post['where_am_i']) && $this->request->post['where_am_i']) {
					$url['w'] = $this->request->post['where_am_i'];
					$url['order_id'] = $this->session->order_id;
					$url['route'] = 'sale/order/update';
					unset($this->session->order_id);
				}
				if(isset($this->request->post['save_new'])){
						$url = '';

						$url['route'] = 'sale/order/insert';
						$url['token']=$this->session->data['token'];
						$url['w'] = 'tab_patient_info';

						unset($this->session->order_id);

					}
				$loc = urldecode(http_build_query($url, '&'));
				echo "<script>location='?$loc';</script>";
				exit();
			}
		}

		$this->getForm();
	}

	private function getForm() {

		if (isset($this->request->post['format_address']) && $this->request->post['format_address'] == '') {
			$this->error['shipping_address'] = "Please select or enter correct address";
			$this->error['7'] = 1;
		}
		if (isset($this->request->post['format_street']) && $this->request->post['format_street'] == '') {
			$this->error['shipping_address_street'] = "Please select or enter correct street";
			$this->error['7'] = 1;
		}
		if (isset($this->request->post['format_city']) && $this->request->post['format_city'] == '') {
			$this->error['shipping_address_city'] = "Please select or enter correct city";
			$this->error['7'] = 1;
		}
		if (isset($this->request->post['format_province']) && $this->request->post['format_province'] == '') {
			$this->error['shipping_address_province'] = "Please select or enter correct province";
			$this->error['7'] = 1;
		}
		if (isset($this->request->post['format_postal_code']) && $this->request->post['format_postal_code'] == '') {
			$this->error['shipping_address_postal'] = "Please select or enter correct postal code";
			$this->error['7'] = 1;
		}

		$this->data = array_merge($this->hc_functions->getErrors(array('warning', 'patient_clinic_id', 'patient_dob', 'patient_firstname', 'patient_lastname','patient_birthdate','order_values_length','order_values_37','shipping_address','shipping_address_street','shipping_address_city','shipping_address_province','shipping_address_postal','order_values_wedge','order_values_rush','order_rush_date', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'order_quantity', 'order_values_files_notes', 'top_4', 'top_3', 'heel_horse', 'ray_hallux', 'arc_fills', 'm_excl', 'bar_pads', 'mat_pad_thickness'), $this->error), $this->data);
		//		$this->data = array_merge($this->hc_functions->getErrors(array('warning', 'patient_clinic_id', 'patient_firstname','patient_lastname'),$this->error),$this->data);
		//p($this->data,__LINE__.__FILE__);
		$url = $this->hc_functions->getURL(array('filter_order_id', 'filter_clinic_name', 'filter_patient_name', 'filter_status', 'filter_date_added', 'filter_deliverydate', 'filter_total', 'sort', 'order', 'page'));

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
				'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
				'text' => $this->language->get('text_home'),
				'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
				'href' => HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . $url,
				'text' => $this->language->get('heading_title'),
				'separator' => ' :: '
		);

		if (!isset($this->request->get['product_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/order/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/order/update&token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url;
		}

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . $url;

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

			//echo '<pre>';
			//print_r($order_info['order_values_left_50']);
		}

		$this->data['met_cutoutsorder_values_left_50'] = isset($order_info['order_values_left_50']) ? $order_info['order_values_left_50'] : '';
		$this->data['met_cutoutsorder_values_right_50'] = isset($order_info['order_values_right_50']) ? $order_info['order_values_right_50'] : '';

		//p($order_info,__LINE.__FILE__);
		$order_value = array();
		$order_values = $this->db->query("SHOW COLUMNS FROM `order_values`");



		foreach ($order_values->rows as $ov) {

			$order_value[] = $ov['Field'];

		}

		if (!isset($order_info)) {
			$order_info = array();
		}

		$formvars = array_merge(array('order_id', 'order_date_added', 'patient_id', 'patient_firstname', 'patient_lastname','patient_dob', 'patient_clinic_id', 'patient_clinician_id','clinician_name', 'reference_no', 'patient_weight', 'patient_weight_id', 'patient_gender_id', 'order_total', 'order_currency_id', 'order_deliverydate', 'patient_notes', 'order_status_id', 'order_quantity', 'order_date_needed', 'order_shipping_date', 'order_contact_name', 'order_shipping_method', 'order_shipping_number', 'shoes_width','fill_with_poron', 'order_shipping_address', 'order_originaldelivery','shipping_address','format_address','format_street','format_city','format_province','format_postal_code','order_values_wedge','clinic_addresses'), $order_value);




		$this->data = array_merge($this->hc_functions->getFormVars($formvars, $order_info), $this->data);

		/* 		if ($this->request->post['patient_id']) {
		 $patient = $this->db->query("SELECT * FROM patient WHERE patient_id = '$patient_id'")$expression;
		extract($patient->row);
		} */
		/**
		 * this block is written to get the values for an order for pricing
		 * @var unknown_type
		*/

		$this->data['patient_name'] = $this->data['patient_firstname'] . ' ' . $this->data['patient_lastname'];
		$this->data['patient_dob'] = $this->data['patient_dob'];

		$this->data['clinician_name'] = $this->data['clinician_name'];
		$this->data['status'] = $this->hc_functions->get_field('order_status_name', "SELECT order_status_name FROM order_status WHERE order_status_id = '" . $this->data['order_id'] . "'");
		$this->data['order_packing_slip_id'] = $this->hc_functions->get_field('order_packing_slip_id', "SELECT `order_packing_slip_id` FROM `order` WHERE `order_id` = '" . $this->data['order_id'] . "'");



		$pricing_values = array();

		if (isset($this->request->get['order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {

			$clinic_verify = $this->model_sale_order->verifyClinics($order_info['clinic_id']);

			if($clinic_verify == 1)

			{



				$pricing_values = $this->model_sale_order->getPricingValues($order_info['order_values_37'],$order_info['clinic_id']);

				// print_r($pricing_values);

				$pricing_values_tax_rates = $this->model_sale_order->getPricingItemTaxRate();

				// print_r($pricing_values_tax_rates);

				$this->data['pricing_values_tax_rates'] = $pricing_values_tax_rates;

				$this->data['pricing_values'] = $pricing_values;


				//         foreach($pricing_values as $pricing_value){

				//         	//$this->data['pricing_value'] = $pricing_value;

				//         }
				$this->data['orthotic_id'] = $order_info['order_values_37'];

				if (isset($this->request->get['order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {

					$item_name = $this->model_sale_order->getItemName($order_info['order_values_37']);
					$this->data['orthotic_name'] = $item_name;

					$this->data['clinic_name'] = $order_info['clinic_name'];

				}

$pricing_values[0]['tax_rate'] = isset($pricing_values[0]['tax_rate']) ? $pricing_values[0]['tax_rate'] : 'null';
$pricing_values[0]['clinic_item_price'] = isset($pricing_values[0]['clinic_item_price']) ? $pricing_values[0]['clinic_item_price'] : 'null';
$pricing_values[1]['clinic_item_price'] = isset($pricing_values[1]['clinic_item_price']) ? $pricing_values[1]['clinic_item_price'] : 'null';
$pricing_values[2]['clinic_item_price'] = isset($pricing_values[2]['clinic_item_price']) ? $pricing_values[2]['clinic_item_price'] : 'null';
$pricing_values[3]['clinic_item_price'] = isset($pricing_values[3]['clinic_item_price']) ? $pricing_values[3]['clinic_item_price'] : 'null';
$pricing_values[4]['clinic_item_price'] = isset($pricing_values[4]['clinic_item_price']) ? $pricing_values[4]['clinic_item_price'] : 'null';
$pricing_values[5]['clinic_item_price'] = isset($pricing_values[5]['clinic_item_price']) ? $pricing_values[5]['clinic_item_price'] : 'null';
$pricing_values[6]['clinic_item_price'] = isset($pricing_values[6]['clinic_item_price']) ? $pricing_values[6]['clinic_item_price'] : 'null';
$pricing_values[7]['clinic_item_price'] = isset($pricing_values[7]['clinic_item_price']) ? $pricing_values[7]['clinic_item_price'] : 'null';
$pricing_values[8]['clinic_item_price'] = isset($pricing_values[8]['clinic_item_price']) ? $pricing_values[8]['clinic_item_price'] : 'null';
$pricing_values[9]['clinic_item_price'] = isset($pricing_values[9]['clinic_item_price']) ? $pricing_values[9]['clinic_item_price'] : 'null';
$pricing_values[10]['clinic_item_price'] = isset($pricing_values[10]['clinic_item_price']) ? $pricing_values[10]['clinic_item_price'] : 'null';


				/**
				 * this block is responsible for the calculation of the  pricing value for a particular order
				 */


				if (isset($this->request->get['order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {


					/**
					 * calculation for the base price based on the foot
					 */

					//if  order_qty >1 and foot single only
					if($order_info['order_values_58'] != '248' && $order_info['order_quantity']>1){

						$net_chargeable_quantity = $order_info['order_quantity']/2;

						$base_price = $net_chargeable_quantity*$pricing_values[0]['clinic_item_price'];

						$base_price_tax = ((($base_price*$pricing_values[0]['tax_rate'])/100)+$base_price);

					}
					//    	// if repeat is yes order_qty > 1 and foot single only
					else if ($order_info['order_values_58'] != '248' && $order_info['order_quantity']=='1'){

						$net_chargeable_quantity = $order_info['order_quantity']/2;

						$base_price = $net_chargeable_quantity *$pricing_values[0]['clinic_item_price'];

						$base_price_tax = ((($base_price*$pricing_values[0]['tax_rate'])/100)+$base_price);
					}

					// if the foot is both
					else {

						$net_chargeable_quantity = $order_info['order_quantity'];

						$base_price = $pricing_values[0]['clinic_item_price']* $order_info['order_quantity'];

						$base_price_tax = ((($base_price*$pricing_values[0]['tax_rate'])/100)+$base_price);
					}

					$this->data['base_price'] = sprintf('%0.2f', $base_price);

					//getting the base price including tax


					$this->data['base_price_tax'] = sprintf('%0.2f', $base_price_tax);



					/**
					 * calculation for repeat discount
					*/

					$repeat_active = '';
					$repeat_discount = '';

					// if repeat is yes order qty >1 and foot is single
					if ($order_info['order_quantity'] > 1 && $order_info['order_values_59'] == '252' && $order_info['order_values_58'] != '248') {

						$net_repeat_quantity = $order_info['order_quantity']/2;

						$repeat_discount = ($net_repeat_quantity*$pricing_values[9]['clinic_item_price']);

						$this->data['repeat_discount']= sprintf('%0.2f', $repeat_discount);

						// echo $repeat_discount.'repeat yes qty > 1';
						// die();

					}
					// if repeat order is no order_qty >1 and foot is single
					else if ($order_info['order_quantity'] > 1 && $order_info['order_values_59'] == '251' && $order_info['order_values_58'] != '248') {

						$net_repeat_quantity = $order_info['order_quantity']/2;

						$repeat_discount = (($net_repeat_quantity -1)*$pricing_values[9]['clinic_item_price']);

						         	// echo $repeat_discount.'repeat no qty > 1';
						         	// die();

						$this->data['repeat_discount']= sprintf('%0.2f', $repeat_discount);

					}
					// if repeat is yes, order quantity is 1 and foot is single
					else if ($order_info['order_quantity'] == '1' && $order_info['order_values_59'] == '252' && $order_info['order_values_58'] != '248') {

						$net_repeat_quantity = $order_info['order_quantity']/2;

						$repeat_discount = $net_repeat_quantity *$pricing_values[9]['clinic_item_price'];

						         //	echo $repeat_discount.'repeat ye qty == 1';
						         //	die();

						$this->data['repeat_discount']= sprintf('%0.2f', $repeat_discount);

					}
// 					// if repeat is no, order quantity is 1 and foot is single

// 					else if ($order_info['order_quantity'] == '1' && $order_info['order_values_59'] == '251' && $order_info['order_values_58'] != '248') {

// 						$net_repeat_quantity = $order_info['order_quantity']/2;

// 						$repeat_discount = $net_repeat_quantity *$pricing_values[9]['clinic_item_price'];

// 						echo $repeat_discount.'repeat ye qty == 3';
// 						die();

// 						$this->data['repeat_discount']= sprintf('%0.2f', $repeat_discount);

// 					}

					//if repeat order is no ,order_qty > 1
					else if ($order_info['order_values_59'] == '251' && $order_info['order_values_58'] == '248') {

						$net_repeat_quantity = $order_info['order_quantity'];

						$repeat_discount = $pricing_values[9]['clinic_item_price']* ($net_repeat_quantity-1);

						$this->data['repeat_discount']= sprintf('%0.2f', $repeat_discount);

						         	//echo $repeat_discount.'repeat no';
						         	//die();

					}
					else if ($order_info['order_values_59'] == '252') {

						$repeat_active = '1';

						$repeat_discount = $pricing_values[9]['clinic_item_price']* $order_info['order_quantity'];

						$this->data['repeat_discount']= sprintf('%0.2f', $repeat_discount);

						  	//echo $repeat_discount.'repeat';
						 	//die();

					}

					/**
					 * calculation for single surcharge
					 */

					//if single foot only

					if ($order_info['order_values_58'] != '248') {

						$surchargeable_qty = $order_info['order_quantity']%2;

						if($surchargeable_qty > 0 ){

							$total_single_surcharge = $pricing_values[10]['clinic_item_price'];

						}
					}
					if (isset($total_single_surcharge)){
						$this->data['total_single_surcharge']= sprintf('%0.2f', $total_single_surcharge);
					}



					$unit_cost_pair = $base_price;

					//single surcharge

					if (isset($total_single_surcharge)){

						$unit_cost_pair += $total_single_surcharge;
					}


					// intialising the order description variable

					$order_description = '';


					$order_description .= $this->request->get['order_id'].' - '.$order_info['patient_firstname'].' '.$order_info['patient_lastname'].' - ';


					//leather charge
					if ($order_info['order_values_24'] == '123'){

						$leather_cost = ($net_chargeable_quantity*$pricing_values[1]['clinic_item_price']);

						$this->data['leather_cost'] = sprintf('%0.2f', $leather_cost);

						$unit_cost_pair += $leather_cost;

						$order_description .= 'leather - ';

					}


					// to append the foort details in the order description

					if ($order_info['order_values_58'] == '249') {

						$foot_desc = 'Left Only';

					}
					elseif ($order_info['order_values_58'] == '250'){

						$foot_desc = 'Right Only';
					}
					else {

						$foot_desc = '';
					}

					// appending the foot details to the order description
					if ($order_description != ''){
						$order_description .= $foot_desc.' - ';
					}


					//arch fill charge
					if ($order_info['order_values_11'] != '60'){

						$arch_fill_cost = $pricing_values[2]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['arch_fill_cost'] = sprintf('%0.2f', $arch_fill_cost);

						$unit_cost_pair += $arch_fill_cost;

						$order_description .= 'ArchFill - ';
					}

					//mid layer charge
					if ($order_info['order_values_28'] != '144'){

						$mid_layer_cost = $pricing_values[4]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['mid_layer_cost'] = sprintf('%0.2f', $mid_layer_cost);

						$unit_cost_pair += $mid_layer_cost;

						$order_description .= 'MidLayer - ';
					}

					/**
					 * initialising the variable for additions
					 * to the get the count of the number of additions to be added
					 */

					$addition_count = 0 ;


					//additions - heel plug
					if ($order_info['order_values_left_17'] != '213' || $order_info['order_values_right_17'] != '213' ){

						$add_heel_plug_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_heel_plug_cost'] = sprintf('%0.2f', $add_heel_plug_cost);

						$unit_cost_pair += $add_heel_plug_cost;

						// additions count incremented here
						$addition_count ++ ;

					}
					//additions -- met pad
					if ($order_info['order_values_left_19'] != '264' || $order_info['order_values_right_19'] != '264' ){

						$add_met_pad_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_met_pad_cost'] = sprintf('%0.2f', $add_met_pad_cost);

						$unit_cost_pair += $add_met_pad_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//additions -- met bar
					if ($order_info['order_values_left_15'] != '' || $order_info['order_values_right_15'] != '' ){

						$add_met_bar_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_met_bar_cost'] = sprintf('%0.2f', $add_met_bar_cost);

						$unit_cost_pair += $add_met_bar_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//additions -- mortons extension
					if ($order_info['order_values_left_39'] != '' || $order_info['order_values_right_39'] != '' ){

						$add_mortons_ext_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_mortons_ext_cost'] = sprintf('%0.2f', $add_mortons_ext_cost);

						$unit_cost_pair += $add_mortons_ext_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//additions -- reverse mortans extension
					if ($order_info['order_values_left_38'] != '' || $order_info['order_values_right_38'] != '' ){

						$add_rev_mortans_ext_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_rev_mortans_ext_cost'] = sprintf('%0.2f', $add_rev_mortans_ext_cost);

						$unit_cost_pair += $add_rev_mortans_ext_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//Additions - 2-5 Bar
					if ($order_info['order_values_left_41'] != '' || $order_info['order_values_right_41'] != '' ){

						$add_bar_2_5_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_bar_2_5_cost'] = sprintf('%0.2f', $add_bar_2_5_cost);

						$unit_cost_pair += $add_bar_2_5_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//Additions - Hallux Rigidus Splint
					if ($order_info['order_values_left_49'] != '' || $order_info['order_values_right_49'] != '' ){

						$add_hallux_spl_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_hallux_spl_cost'] = sprintf('%0.2f', $add_hallux_spl_cost);

						$unit_cost_pair += $add_hallux_spl_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//additions -- cuboid pad
					if ($order_info['order_values_left_42'] != '' || $order_info['order_values_right_42'] != '' ){

						$add_cuboid_pad_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_cuboid_pad_cost'] = sprintf('%0.2f', $add_cuboid_pad_cost );

						$unit_cost_pair += $add_cuboid_pad_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//additions -- kinetic wedge
					if ($order_info['order_values_left_43'] != '' || $order_info['order_values_right_43'] != '' ){

						$add_kinetic_wedge_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_kinetic_wedge_cost'] = sprintf('%0.2f', $add_kinetic_wedge_cost);

						$unit_cost_pair += $add_kinetic_wedge_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//Additions - Lateral Arch Fill
					if ($order_info['order_values_left_44'] != '' || $order_info['order_values_right_44'] != '' ){

						$add_lateral_arch_fill_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_lateral_arch_fill_cost'] = sprintf('%0.2f', $add_lateral_arch_fill_cost);

						$unit_cost_pair += $add_lateral_arch_fill_cost;

						// additions count incremented here
						$addition_count ++ ;


					}

					//additions -- horse shoe spur
					if ($order_info['order_values_left_45'] != '' || $order_info['order_values_right_45'] != '' ){

						$add_horse_shoe_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_horse_shoe_cost'] = sprintf('%0.2f', $add_horse_shoe_cost);

						$unit_cost_pair += $add_horse_shoe_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//additions -- neuroma pad
					if ($order_info['order_values_left_20'] != '97' || $order_info['order_values_right_20'] != '97' ){

						$add_neuroma_pad_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_neuroma_pad_cost'] = sprintf('%0.2f', $add_neuroma_pad_cost);

						$unit_cost_pair += $add_neuroma_pad_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//additions -- met cut outs
					if ($order_info['order_values_left_50'] != '' || $order_info['order_values_right_50'] != '' ){

						$add_met_cut_outs_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_met_cut_outs_cost'] = sprintf('%0.2f', $add_met_cut_outs_cost);

						$unit_cost_pair += $add_met_cut_outs_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					//additions -- heel lifts
					if ($order_info['order_values_left_52'] != '211' || $order_info['order_values_right_52'] != '211'){

						$add_heel_lifts_cost = $pricing_values[8]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['add_heel_lifts_cost'] = sprintf('%0.2f', $add_heel_lifts_cost);

						$unit_cost_pair += $add_heel_lifts_cost;

						// additions count incremented here
						$addition_count ++ ;
					}

					// appending the additions count to the order description

					$order_description .= $addition_count.' Additions - ' ;


					//ff ext cost
					if ($order_info['order_values_31'] != '170'){

						$ff_ext_cost = $pricing_values[3]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['ff_ext_cost'] = sprintf('%0.2f', $ff_ext_cost);

						$unit_cost_pair += $ff_ext_cost;

						$order_description .= 'FFExt - ';
					}

					//Rf EVA post cost
					if ($order_info['order_values_22'] == '113'){

						$rf_eva_post_cost = $pricing_values[5]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['rf_eva_post_cost'] = sprintf('%0.2f', $rf_eva_post_cost);

						$unit_cost_pair += $rf_eva_post_cost;

						$order_description .= 'RF EVA POST - ';
					}

					//FF EVA  post cost
					if ($order_info['order_values_70'] == '328'){

						$ff_eva_post_cost = $pricing_values[6]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['ff_eva_post_cost'] = sprintf('%0.2f', $ff_eva_post_cost);

						$unit_cost_pair += $ff_eva_post_cost;

						$order_description .= 'FF EVA POST - ';
					}
					//EVA  shell surcharge
					if ($order_info['order_values_53'] != '224' && $order_info['order_values_53'] != '259'){

						$eva_shell_cost = $pricing_values[7]['clinic_item_price']*$net_chargeable_quantity;

						$this->data['eva_shell_cost'] = sprintf('%0.2f', $eva_shell_cost);

						$unit_cost_pair += $eva_shell_cost;

						$order_description .= 'EVA Shell - ';
					}
					// Carbon Fibre Surcharge here
					if ($order_info['order_values_53'] == '445'){

						$carbon_fibre_shell_cost = $pricing_values[12]['clinic_item_price']*$net_chargeable_quantity;

						// echo $carbon_fibre_shell_cost;

						$this->data['carbon_fibre_shell_cost'] = sprintf('%0.2f', $carbon_fibre_shell_cost);

						$unit_cost_pair += $carbon_fibre_shell_cost;

						$order_description .= 'Carbon Fibre Shell - ';
					}

					//rush charge for an order
					if ($order_info['order_values_rush_75'] == '342'){

						$rush_cost = $pricing_values[11]['clinic_item_price'];

						$this->data['rush_cost'] = sprintf('%0.2f', $rush_cost);

						$unit_cost_pair += $rush_cost;

						$order_description .= 'Rush - ';
					}

					//         //base shipping rate
					//         $unit_cost_pair += $pricing_values[12]['clinic_item_price'];
					if  ($repeat_discount) {

						$unit_cost_pair = $unit_cost_pair - $repeat_discount;
					}



					if  ($repeat_active == '1') {

						$order_description .= 'Repeat - ';

					}

					/**
					 * this block is written to get the file names that are attached with a particular order
					 * and they will be added to the order description for quickbooks
					 */
					$file = ''; // initialising the file variable
					$total_file = ''; // declared the variable to store concatenated string
					foreach (glob("hc_scripts/plupload/uploads/" . $this->request->get['order_id'] . "/*.*") as $filename) {
						$file = basename($filename);

						$total_file .= $file.',';
					}

					$order_description .= $total_file; // appending the file names to the order decription

					// updating the order description into the orders table

					// if the clinic is sound orthotics the order description updated
					if ($order_info['clinic_id'] == 1){

						// declaring the order description for SO
						$order_description = 'Custom made foot orthoses by slipper cast procedure for daily use';

						$this->model_sale_order->updateOrderDescription($this->request->get['order_id'],$order_description);

					}
					else{

						$this->model_sale_order->updateOrderDescription($this->request->get['order_id'],$order_description);

					}

					$order_unit_pair_cost =  sprintf('%0.2f', $unit_cost_pair);

					//updating the order unit price and order description in the database table

					$this->model_sale_order->updateUnitPrice($this->request->get['order_id'],$order_unit_pair_cost/$order_info['order_quantity']);

					$this->data['total_unit_cost_pair'] = $order_unit_pair_cost;



					/**
					 * block for calculating the price including tax rates
					 */

					$total_including_tax = $base_price_tax;

					if (isset($total_single_surcharge)){

						$total_including_tax += $total_single_surcharge;
					}
					//leather charge
					if ($order_info['order_values_24'] == '123'){

						$leather_cost_tax = ((($pricing_values[1]['clinic_item_price']*$pricing_values_tax_rates[0]['tax_rate'])/100)+$pricing_values[1]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['leather_cost_tax'] =  sprintf('%0.2f', $leather_cost_tax);

						$total_including_tax += $leather_cost_tax;
					}



					//arch fill charge
					if ($order_info['order_values_11'] != '60'){

						$arch_fill_tax = ((($pricing_values[2]['clinic_item_price']*$pricing_values_tax_rates[1]['tax_rate'])/100)+$pricing_values[2]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['arch_fill_tax'] =  sprintf('%0.2f', $arch_fill_tax);

						$total_including_tax += $arch_fill_tax;
					}

					//mid layer charge
					if ($order_info['order_values_28'] != '144'){

						$mid_layer_tax = ((($pricing_values[4]['clinic_item_price']*$pricing_values_tax_rates[3]['tax_rate'])/100)+$pricing_values[4]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['mid_layer_tax'] =  sprintf('%0.2f', $mid_layer_tax);

						$total_including_tax += $mid_layer_tax;
					}

					//additions - heel plug
					if ($order_info['order_values_left_17'] != '213' || $order_info['order_values_right_17'] != '213' ){

						$add_heel_plug = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_heel_plug'] =  sprintf('%0.2f', $add_heel_plug);

						$total_including_tax += $add_heel_plug;
					}

					//additions -- met pad
					if ($order_info['order_values_left_19'] != '264' || $order_info['order_values_right_19'] != '264' ){

						$add_met_pad = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_met_pad'] =  sprintf('%0.2f', $add_met_pad);

						$total_including_tax += $add_met_pad;
					}

					//additions -- met bar
					if ($order_info['order_values_left_15'] != '' || $order_info['order_values_right_15'] != '' ){

						$add_met_bar = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_met_bar'] =  sprintf('%0.2f', $add_met_bar);

						$total_including_tax += $add_met_bar;
					}

					//additions -- mortons extension
					if ($order_info['order_values_left_39'] != '' || $order_info['order_values_right_39'] != '' ){

						$add_mortons_ext = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_mortons_ext'] =  sprintf('%0.2f', $add_mortons_ext);

						$total_including_tax += $add_mortons_ext;
					}

					//additions -- reverse mortans extension
					if ($order_info['order_values_left_38'] != '' || $order_info['order_values_right_38'] != '' ){

						$add_rev_mortans_ext= ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_rev_mortans_ext'] =  sprintf('%0.2f', $add_rev_mortans_ext);

						$total_including_tax += $add_rev_mortans_ext;
					}

					//Additions - 2-5 Bar
					if ($order_info['order_values_left_41'] != '' || $order_info['order_values_right_41'] != '' ){

						$add_bar_2_5 = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_bar_2_5'] =  sprintf('%0.2f', $add_bar_2_5);

						$total_including_tax += $add_bar_2_5;
					}

					//Additions - Hallux Rigidus Splint
					if ($order_info['order_values_left_49'] != '' || $order_info['order_values_right_49'] != '' ){

						$add_hallux_spl = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_hallux_spl'] =  sprintf('%0.2f', $add_hallux_spl);

						$total_including_tax += $add_hallux_spl;
					}

					//additions -- cuboid pad
					if ($order_info['order_values_left_42'] != '' || $order_info['order_values_right_42'] != '' ){

						$add_cuboid_pad = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_cuboid_pad'] =  sprintf('%0.2f', $add_cuboid_pad);

						$total_including_tax += $add_cuboid_pad;
					}

					//additions -- kinetic wedge
					if ($order_info['order_values_left_43'] != '' || $order_info['order_values_right_43'] != '' ){

						$add_kinetic_wedge = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_kinetic_wedge'] =  sprintf('%0.2f', $add_kinetic_wedge);

						$total_including_tax += $add_kinetic_wedge;
					}

					//Additions - Lateral Arch Fill
					if ($order_info['order_values_left_44'] != '' || $order_info['order_values_right_44'] != '' ){

						$add_lateral_arch_fill = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_lateral_arch_fill'] =  sprintf('%0.2f', $add_lateral_arch_fill);

						$total_including_tax += $add_lateral_arch_fill;
					}
					//additions -- horse shoe spur
					if ($order_info['order_values_left_45'] != '' || $order_info['order_values_right_45'] != '' ){

						$add_horse_shoe = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_horse_shoe'] =  sprintf('%0.2f', $add_horse_shoe);

						$total_including_tax += $add_horse_shoe;
					}

					//additions -- neuroma pad
					if ($order_info['order_values_left_20'] != '97' || $order_info['order_values_right_20'] != '97' ){

						$add_neuroma_pad = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_neuroma_pad'] =  sprintf('%0.2f', $add_neuroma_pad);

						$total_including_tax += $add_neuroma_pad;
					}

					//additions -- met cut outs
					if ($order_info['order_values_left_50'] != '' || $order_info['order_values_right_50'] != '' ){

						$add_met_cut_outs = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_met_cut_outs'] =  sprintf('%0.2f', $add_met_cut_outs);

						$total_including_tax += $add_met_cut_outs;
					}

					//additions -- heel lifts
					if ($order_info['order_values_left_52'] != '211' || $order_info['order_values_right_52'] != '211'){

						$add_heel_lifts = ((($pricing_values[8]['clinic_item_price']*$pricing_values_tax_rates[7]['tax_rate'])/100)+$pricing_values[8]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['add_heel_lifts'] =  sprintf('%0.2f', $add_heel_lifts);

						$total_including_tax += $add_heel_lifts;
					}


					//ff ext cost
					if ($order_info['order_values_31'] != '170'){
					$pricing_values[3]['clinic_item_price'] = isset($pricing_values[3]['clinic_item_price']) ? $pricing_values[3]['clinic_item_price'] : 'null';
						$ff_ext_tax = ((($pricing_values[3]['clinic_item_price']* $pricing_values_tax_rates[2]['tax_rate'])/100)+$pricing_values[3]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['ff_ext_tax'] =  sprintf('%0.2f', $ff_ext_tax);

						$total_including_tax += $ff_ext_tax;
					}

					//Rf EVA post cost
					if ($order_info['order_values_22'] == '113'){

						$rf_eva_post_tax = ((($pricing_values[5]['clinic_item_price']*$pricing_values_tax_rates[5]['tax_rate'])/100)+$pricing_values[5]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['rf_eva_post_tax'] =  sprintf('%0.2f', $rf_eva_post_tax);

						$total_including_tax += $rf_eva_post_tax;
					}

					//FF EVA  post cost
					if ($order_info['order_values_70'] == '328'){

						$ff_eva_post_tax = ((($pricing_values[6]['clinic_item_price']*$pricing_values_tax_rates[4]['tax_rate'])/100)+$pricing_values[6]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['ff_eva_post_tax'] =  sprintf('%0.2f', $ff_eva_post_tax);

						$total_including_tax += $ff_eva_post_tax;

					}

					//EVA  shell surcharge
					if ($order_info['order_values_53'] != '224' && $order_info['order_values_53'] != '259'){
						$pricing_values[7]['clinic_item_price'] = isset($pricing_values[7]['clinic_item_price']) ? $pricing_values[7]['clinic_item_price'] : 'null';
						$eva_shell_tax = ((($pricing_values[7]['clinic_item_price']*$pricing_values_tax_rates[6]['tax_rate'])/100)+$pricing_values[7]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['eva_shell_tax'] =  sprintf('%0.2f', $eva_shell_tax);

						$total_including_tax += $eva_shell_tax;

					}

					// Carbon Fibre Shell Surcharge
					if ($order_info['order_values_53'] == '445'){
						$pricing_values[12]['clinic_item_price'] = isset($pricing_values[12]['clinic_item_price']) ? $pricing_values[12]['clinic_item_price'] : 'null';
						$carbon_fibre_shell_tax = ((($pricing_values[12]['clinic_item_price']*$pricing_values_tax_rates[6]['tax_rate'])/100)+$pricing_values[12]['clinic_item_price'])*$net_chargeable_quantity;

						$this->data['carbon_fibre_shell_tax'] = sprintf('%0.2f', $carbon_fibre_shell_tax);

						$total_including_tax += $carbon_fibre_shell_tax;

					}

					//rush charge for an order with tax
					if ($order_info['order_values_rush_75'] == '342'){

						$rush_tax = ((($pricing_values[11]['clinic_item_price']*$pricing_values_tax_rates[10]['tax_rate'])/100)+$pricing_values[11]['clinic_item_price']);

						$this->data['rush_tax'] =  sprintf('%0.2f', $rush_tax);

						$total_including_tax += $rush_tax;

					}


					if(isset($repeat_discount)){
						$total_including_tax = $total_including_tax-$repeat_discount;
					}


					// passing the variable to the view

					$order_total_including_tax = sprintf('%0.2f', $total_including_tax);



					// updating the order totals to the orders table in the database

					$this->model_sale_order->updateOrderTotals($this->request->get['order_id'],$order_total_including_tax);

					$this->data['total_including_tax'] =  $order_total_including_tax;

				}

			}

			/* //    else {

			//    	$clinic_alert='';

			//    	$clinic_alert .='<script type="text/javascript">';
			//    	$clinic_alert .='alert("Pricing parameters are not set for the associated clinic ");';
			//    	$clinic_alert .= '</script>';

			//    	echo $clinic_alert;

			//    }
         */

		}


		$this->load->model('sale/clinic');

		$this->data['clinics'] = $this->model_sale_clinic->getClinics();


		$this->template = 'sale/order_form.tpl';

		$this->children = array(
				'common/header',
				'common/footer'
		);

		if (!$this->data['order_status_id'])
			$this->data['order_status_id'] = 10;

		//p($this->data,__LINE__.__FILE__);
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen(utf8_decode($this->request->post['patient_firstname'])) < 1) || (strlen(utf8_decode($this->request->post['patient_firstname'])) > 80)) {
			$this->error['patient_firstname'] = 'Enter patient first name';
			$this->error['1'] = 1;
		}

		if ((strlen(utf8_decode($this->request->post['patient_lastname'])) < 1) || (strlen(utf8_decode($this->request->post['patient_lastname'])) > 80)) {
			$this->error['patient_lastname'] = 'Enter patient last name';
			$this->error['1'] = 1;
		}

		if (!isset($this->request->post['patient_clinic_id']) || !trim($this->request->post['patient_clinic_id'])) {
			$this->error['patient_clinic_id'] = "Please select clinic";
			$this->error['1'] = 1;
		}
		/**
		 * this block is written for validation of date of birth for patient
		 */

		/*if (!isset($this->request->post['patient_dob']) || !($this->request->post['patient_dob']) || ($this->request->post['patient_dob']== '0000-00-00')) {
			$this->error['patient_birthdate'] = "Please select a valid date";
			$this->error['1'] = 1;
		}*/

		// this code is to validate the mandatory orthotic type

		if (! isset($this->request->post['order_values_37'])) {
			$this->error['order_values_37'] = "Please select an orthotic type";
			$this->error['2'] = 1;
		}

		// this code is to validate the mandatory wedge value
		if (! isset($this->request->post['order_values_89']) || $this->request->post['order_values_89'] != '412') {
			if(isset($this->request->post['order_values_right_90']) || isset($this->request->post['order_values_right_90'])){

				if ($this->request->post['order_values_left_90'] == '415' && $this->request->post['order_values_right_90']=='415' ) {
					$this->error['order_values_wedge'] = "Please select a wedge value";
					$this->error['5'] = 1;
				}
			}
		}


		/**
		 * this block of code is written to validate the length fields in the covers tab
		 */

		if (isset($this->request->post['order_values_24']) && $this->request->post['order_values_24'] != 117 ){

			if ($this->request->post['order_values_27']==140){

				$this->error['order_values_length'] = "Please select the length fields";
				$this->error['6'] = 1;
			}
		}

		/**
		 * this block is written to validate the rush value in the order
		 */

		if (isset($this->request->post['order_values_rush_75']) && $this->request->post['order_date_needed']== ''){
			$this->error['order_values_rush'] = "Please enter rush date";
			$this->error['7'] = 1;
		}

		// this code is to validate the mandatory shipping address

		if (isset($this->request->post['format_address']) && $this->request->post['format_address'] == '') {
			$this->error['shipping_address'] = "Please select or enter correct address";
			$this->error['7'] = 1;
		}
		if (isset($this->request->post['format_street']) && $this->request->post['format_street'] == '') {
			$this->error['shipping_address_street'] = "Please select or enter correct street";
			$this->error['7'] = 1;
		}
		if (isset($this->request->post['format_city']) && $this->request->post['format_city'] == '') {
			$this->error['shipping_address_city'] = "Please select or enter correct city";
			$this->error['7'] = 1;
		}
		if (isset($this->request->post['format_province']) && $this->request->post['format_province'] == '') {
			$this->error['shipping_address_province'] = "Please select or enter correct province";
			$this->error['7'] = 1;
		}
		if (isset($this->request->post['format_postal_code']) && $this->request->post['format_postal_code'] == '') {
			$this->error['shipping_address_postal'] = "Please select or enter correct postal code";
			$this->error['7'] = 1;
		}


		/**
		 * this block of code is written to validate the rush date values
		 */
		if (isset($this->request->post['order_date_needed'])){

			$selected_date = $this->request->post['order_date_needed'];




			// checking the order rush date exists as a current date or not

			$existing_date = '';

			if(isset($this->request->get['order_id']))
			{
			 // getting the current rush date of the order to be inspected
				$existing_date = $this->model_sale_order->checkCurrentRushDate($this->request->get['order_id']);

			}

			// getting the expected delivery date


			if(isset($this->request->get['order_id']))
			{
			 // getting  the calculated delivery date stored in the records
				$existing_delivery_date = $this->model_sale_order->checkdeliveryDate($this->request->get['order_id']);


				// converting the date
				$delivery_date  = $this->hc_functions->ch_date($existing_delivery_date);

				// comparing both the dates for validation.

				if ($_SESSION['user_group'] != 1){

					if (strtotime($selected_date) > strtotime($delivery_date))
					{
						$this->error['order_rush_date'] = "Please enter valid date";
						$this->error['7'] = 1;
					}
				}
			}

/*			// getting the current date
			$current_date = date('Y-m-d');

			if ($existing_date != $current_date)
			{

				// setting the default time zone
				date_default_timezone_set('America/Toronto');
				$time = date("H");


				if ($time < "12" && $selected_date == date("Y-M-d")) {

					$this->error['order_rush_date'] = "Please enter valid date";
					$this->error['7'] = 1;
				}
				if ($time > "12" && $selected_date == date("Y-M-d",strtotime("+1 weekdays"))) {
					$this->error['order_rush_date'] = "Please enter valid date";
					$this->error['7'] = 1;
				}
			}*/

		}




		/*
		 if (! isset($this->request->post['order_values_68']) || $this->request->post['order_values_68'] == 301) {
		$this->error['order_values_68'] = " Right";
		$this->error['3'] = 1;
		}
		*/
		if (!isset($this->request->post['order_quantity']) || (int) $this->request->post['order_quantity'] < 1) {
			$this->error['order_quantity'] = "Should be greater than zero";
			$this->error['8'] = 1;
		}

		if (isset($this->request->post['order_values_60']) && $this->request->post['order_values_60'] == '253') {
			if ((strlen(utf8_decode($this->request->post['order_values_files_notes'])) < 1)) {
				$this->error['order_values_files_notes'] = 'Notes is required';
				$this->error['9'] = 1;
			}
		}
		$order_values_upload_files = '';
		if($this->error && substr($_SESSION['s_order_id'], 0, 4) == 'temp') {
			$this->error['order_values_files_notes'] = 'For security reasons, uploaded files have been destroyed, and will need to be uploaded again';
			$this->error['9'] = 1;
		}




		//heel plugs --- horshoe spur
		$cnt = 0;
		if (isset($this->request->post['order_values_left_17']) && $this->request->post['order_values_left_17'] != '213')
			$cnt++;
		if (isset($this->request->post['order_values_left_45']) && $this->request->post['order_values_left_45'] == '107')
			$cnt++;
		if ($cnt > 1) {
			if (!isset($this->error['top_4']))
				$this->error['top_4'] = "";
			$this->error['top_4'] .= "Select only one of these: <b>(1) Left</b><br>";
			$this->error['4'] = 1;
			$this->error['heel_horse'] = '<span style="color:red"> (1) </span>';
		}
		$cnt = 0;
		if (isset($this->request->post['order_values_right_17']) && $this->request->post['order_values_right_17'] != '213')
			$cnt++;
		if (isset($this->request->post['order_values_right_45']) && $this->request->post['order_values_right_45'] == '205')
			$cnt++;
		if ($cnt > 1) {
			if (!isset($this->error['top_4']))
				$this->error['top_4'] = "";
			$this->error['top_4'] .= "Select only one of these: <b>(1) Right<br>";
			$this->error['4'] = 1;
			$this->error['heel_horse'] = '<span style="color:red"> (1) </span>';
		}

		// 1st ray -- hallux
		$cnt = 0;
		if (isset($this->request->post['order_values_left_48']) && $this->request->post['order_values_left_48'] == '80')
			$cnt++;
		if (isset($this->request->post['order_values_left_49']) && $this->request->post['order_values_left_49'] == '103')
			$cnt++;
		if ($cnt > 1) {
			if (!isset($this->error['top_3']))
				$this->error['top_3'] = "";
			if (!isset($this->error['top_4']))
				$this->error['top_4'] = "";
			$this->error['top_3'] .= "Select only one of these: <b>(2) Left</b> 1st Ray Cutout in <b>Mods</b> - <b>Left</b> Hallux Rigidus Splint in <b>Additions</b><br>";
			$this->error['top_4'] .= "Select only one of these: <b>(2) Left</b> 1st Ray Cutout in <b>Mods</b> - <b>Left</b> Hallux Rigidus Splint in <b>Additions</b><br>";
			$this->error['3'] = 1;
			$this->error['4'] = 1;
			$this->error['ray_hallux'] = '<span style="color:red; float:left;"> (2) </span>';
		}

		$cnt = 0;
		if (isset($this->request->post['order_values_right_48']) && $this->request->post['order_values_right_48'] == '208')
			$cnt++;
		if (isset($this->request->post['order_values_right_49']) && $this->request->post['order_values_right_49'] == '203')
			$cnt++;
		if ($cnt > 1) {
			if (!isset($this->error['top_3']))
				$this->error['top_3'] = "";
			if (!isset($this->error['top_4']))
				$this->error['top_4'] = "";
			$this->error['top_3'] .= "Select only one of these: <b>(2) Right</b> 1st Ray Cutout in <b>Mods</b> - <b>Right</b> Hallux Rigidus Splint in <b>Additions</b><br>";
			$this->error['top_4'] .= "Select only one of these: <b>(2) Right</b> 1st Ray Cutout in <b>Mods</b> - <b>Right</b> Hallux Rigidus Splint in <b>Additions</b><br>";
			$this->error['3'] = 1;
			$this->error['4'] = 1;
			$this->error['ray_hallux'] = '<span style="color:red; float:left;"> (2) </span>';
		}

		// multi exclusive
		$cnt = 0;
		if (isset($this->request->post['order_values_left_49']) && $this->request->post['order_values_left_49'] == '103')
			$cnt++;
		if (isset($this->request->post['order_values_left_39']) && $this->request->post['order_values_left_39'] == '95')
			$cnt++;
		if (isset($this->request->post['order_values_left_38']) && $this->request->post['order_values_left_38'] == '96')
			$cnt++;
		if (isset($this->request->post['order_values_left_43']) && $this->request->post['order_values_left_43'] == '105')
			$cnt++;
			//print_r($this->request->post['order_values_left_50']);
		if (isset($this->request->post['order_values_left_50']) && in_array('1', $this->request->post['order_values_left_50']))
			$cnt++;
		if ($cnt > 1) {
			if (!isset($this->error['top_4']))
				$this->error['top_4'] = "";
			$this->error['top_4'] .= "Select only one of <b>(4) Left</b><br>";
			$this->error['4'] = 1;
			$this->error['m_excl'] = '<span style="color:red; float:left;"> (4) </span>';
			;
		}

		$cnt = 0;
		if (isset($this->request->post['order_values_right_49']) && $this->request->post['order_values_right_49'] == '203')
			$cnt++;
		if (isset($this->request->post['order_values_right_39']) && $this->request->post['order_values_right_39'] == '198')
			$cnt++;
		if (isset($this->request->post['order_values_right_38']) && $this->request->post['order_values_right_38'] == '197')
			$cnt++;
		if (isset($this->request->post['order_values_right_43']) && $this->request->post['order_values_right_43'] == '202')
			$cnt++;
		if (isset($this->request->post['order_values_right_50']) && in_array('1', $this->request->post['order_values_right_50']))
			$cnt++;
		if ($cnt > 1) {
			if (!isset($this->error['top_4']))
				$this->error['top_4'] = "";
			$this->error['top_4'] .= "Select only one of <b>(4) Right</b><br>";
			$this->error['4'] = 1;
			$this->error['m_excl'] = '<span style="color:red; float:left;"> (4) </span>';
		}
		// multi exclusive met bar pads
		$cnt = 0;
		if (isset($this->request->post['order_values_37']) && $this->request->post['order_values_37'] == '220') {
			if (isset($this->request->post['order_values_left_19']) && $this->request->post['order_values_left_19'] != '264' && isset($this->request->post['order_values_left_20']) && $this->request->post['order_values_left_20'] != '97')
				$cnt = 2;
		} else {

			if (isset($this->request->post['order_values_left_19']) && $this->request->post['order_values_left_19'] != '264')
				$cnt++;
			if (isset($this->request->post['order_values_left_15']) && $this->request->post['order_values_left_15'] == '93')
				$cnt++;
			if (isset($this->request->post['order_values_left_20']) && $this->request->post['order_values_left_20'] != '97')
				$cnt++;
		}
		if ($cnt > 1) {
		//	if (!isset($this->error['top_4']))
			//	$this->error['top_4'] = "";
			//$this->error['top_4'] .= "Select only one of <b>(5) Left</b><br>";
			//$this->error['4'] = 1;
			//$this->error['bar_pads'] = '<span style="color:red; float:left;"> (5) </span>';
			;
		}
		$cnt = 0;
		if (isset($this->request->post['order_values_37']) && $this->request->post['order_values_37'] == '220') {
			if (isset($this->request->post['order_values_right_19']) && $this->request->post['order_values_right_19'] != '264' && isset($this->request->post['order_values_right_20']) && $this->request->post['order_values_right_20'] != '97')
				$cnt = 2;
		} else {
			if (isset($this->request->post['order_values_right_19']) && $this->request->post['order_values_right_19'] != '264')
				$cnt++;
			if (isset($this->request->post['order_values_right_15']) && $this->request->post['order_values_right_15'] == '196')
				$cnt++;

			if (isset($this->request->post['order_values_right_20']) && $this->request->post['order_values_right_20'] != '97')
				$cnt++;
		}
		if ($cnt > 1) {
			//if (!isset($this->error['top_4']))
			//	$this->error['top_4'] = "";
			//$this->error['top_4'] .= "Select only one of <b>(5) Right</b><br>";
			//$this->error['4'] = 1;
			//$this->error['bar_pads'] = '<span style="color:red; float:left;"> (5) </span>';
		}

		if (isset($this->request->post['order_values_18']) &&
				$this->request->post['order_values_18'] != '214' &&
				isset($this->request->post['order_values_right_19']) &&
				$this->request->post['order_values_right_19'] == '264' &&
				isset($this->request->post['order_values_left_19']) &&
				$this->request->post['order_values_left_19'] == '264') {

			$this->error['4'] = 1;
			$this->error['mat_pad_thickness'] = "Select thickness...";
		}



		//p($this->error,__LINE__.__FILE__);
		//p($this->request->post['order_values_right_50'],__LINE__.__FILE__);


		if (!$this->error) {
			//			echo "Please Wait... Saving...";
			return TRUE;
		} else {
			if (!isset($this->error['warning']) || !$this->error['warning']) {
				$this->error['warning'] = 'Please fill all required fields or correct the errors...';
			}
			return FALSE;
		}
	}

	private function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkPatientRecords(){

		$this->load->model('sale/order');

		if (isset($this->request->get['first_name'])) {
			$first_name =  $this->request->get['first_name'];
		}
		if (isset($this->request->get['last_name'])) {
			$last_name =  $this->request->get['last_name'];
		}
		if (isset($this->request->get['birth_date'])) {
			$birth_date =  $this->request->get['birth_date'];
		}
		if (isset($this->request->get['clinic_id'])) {
			$clinic_id =  $this->request->get['clinic_id'];
		}

		echo $this->model_sale_order->verifyPatientRecords($first_name,$last_name,$birth_date,$clinic_id);

	}

}

?>
