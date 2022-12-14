<?php
class ControllerCommonHome extends Controller {
	public function index() {
    	$this->load->language('common/home');

		$this->document->title = $this->language->get('heading_title');
			$this->data['success_message'] = $this->language->get('success_message');
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_overview'] = $this->language->get('text_overview');
		$this->data['text_statistics'] = $this->language->get('text_statistics');
		$this->data['text_latest_10_orders'] = $this->language->get('text_latest_10_orders');
		$this->data['text_total_sale'] = $this->language->get('text_total_sale');
		$this->data['text_total_sale_year'] = $this->language->get('text_total_sale_year');
		$this->data['text_total_order'] = $this->language->get('text_total_order');
		$this->data['text_total_clinic'] = $this->language->get('text_total_clinic');
		$this->data['text_total_clinic_approval'] = $this->language->get('text_total_clinic_approval');
		$this->data['text_total_product'] = $this->language->get('text_total_product');
		$this->data['text_total_review'] = $this->language->get('text_total_review');
		$this->data['text_total_review_approval'] = $this->language->get('text_total_review_approval');
		$this->data['text_day'] = $this->language->get('text_day');
		$this->data['text_week'] = $this->language->get('text_week');
		$this->data['text_month'] = $this->language->get('text_month');
		$this->data['text_year'] = $this->language->get('text_year');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_order'] = $this->language->get('column_order');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_patient'] = $this->language->get('column_patient');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_shipping_date'] = $this->language->get('column_shipping_date');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_firstname'] = $this->language->get('column_firstname');
		$this->data['column_lastname'] = $this->language->get('column_lastname');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['entry_range'] = $this->language->get('entry_range');

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('sale/order');

		$this->data['total_sale'] = $this->currency->format($this->model_sale_order->getTotalSales(), $this->config->get('config_currency'));
		$this->data['total_sale_year'] = $this->currency->format($this->model_sale_order->getTotalSalesByYear(date('Y')), $this->config->get('config_currency'));

		$this->load->model('sale/clinic');

		$this->data['total_clinic'] = $this->model_sale_clinic->getTotalClinics();

//		$this->load->model('catalog/product');

//		$this->data['total_product'] = $this->model_catalog_product->getTotalProducts();


		$this->data['orders'] = array();

		$data = array(
			'sort'  => 'order_date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);

		$results = $this->model_sale_order->getOrders($data);
		$this->data['total_order'] = $this->model_sale_order->getTotalOrders();

    	foreach ($results as $result) {
			$action = array();

if (stristr($result['user_group_step_display'],'"' . $result['order_status_id'] . '"')) {
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=sale/order/update&token=' . $this->session->data['token'] . '&order_id=' . $result['order_id']
			);
} else {
			$action[] = array(
				'text' => 'View',
				'href' => HTTPS_SERVER . 'index.php?route=sale/order/update&token=' . $this->session->data['token'] . '&v=1&order_id=' . $result['order_id']
			);
}
//				p($result);
			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'patient_name'       => $result['patient_name'],
				'clinic_name'       => $result['clinic_name'],
				'status_id'     => $result['order_status_id'],
				'status'     => $this->hc_functions->get_field('order_status_name',"SELECT order_status_name FROM order_status WHERE order_status_id = '". $result['order_status_id'] ."'"),
				'date_added' => $result['order_date_added'],
				'order_deliverydate' => $result['order_deliverydate'],
				'order_date_needed' => $result['order_date_needed'],
				'order_shipping_date' => $result['order_shipping_date'],
				'order_contact_name' => $result['order_contact_name'],
				'order_shipping_method' => $result['order_shipping_method'],
				'order_shipping_number' => $result['order_shipping_number'],
				'order_shipping_address' => $result['order_shipping_address'],

				'total'      => $this->currency->format($result['order_total'], $result['order_currency_id'], $result['order_total']),
				'action'     => $action
			);
		}

		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->updateCurrencies();
		}

		$this->template = 'common/home.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}

	public function chart() {
		$this->load->language('common/home');

		$data = array();

		$data['order'] = array();
		$data['clinic'] = array();
		$data['xaxis'] = array();

		$data['order']['label'] = $this->language->get('text_order');
		$data['clinic']['label'] = $this->language->get('text_clinic');

		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'month';
		}

		switch ($range) {
			case 'day':
				for ($i = 0; $i < 24; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND (DATE(order_date_added) = DATE(NOW()) AND HOUR(order_date_added) = '" . (int)$i . "') GROUP BY HOUR(order_date_added) ORDER BY order_date_added ASC");

					if ($query->num_rows) {
						$data['order']['data'][]  = array($i, (int)$query->row['total']);
					} else {
						$data['order']['data'][]  = array($i, 0);
					}

					$query = $this->db->query("SELECT COUNT(*) AS total FROM clinic WHERE DATE(clinic_date_added) = DATE(NOW()) AND HOUR(clinic_date_added) = '" . (int)$i . "' GROUP BY HOUR(clinic_date_added) ORDER BY clinic_date_added ASC");

					if ($query->num_rows) {
						$data['clinic']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['clinic']['data'][] = array($i, 0);
					}

					$data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));
				}
				break;
			case 'week':
				$date_start = strtotime('-' . date('w') . ' days');

				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));

					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND DATE(order_date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(order_date_added)");

					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['order']['data'][] = array($i, 0);
					}

					$query = $this->db->query("SELECT COUNT(*) AS total FROM `clinic` WHERE DATE(clinic_date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(clinic_date_added)");

					if ($query->num_rows) {
						$data['clinic']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['clinic']['data'][] = array($i, 0);
					}

					$data['xaxis'][] = array($i, date('D', strtotime($date)));
				}

				break;
			default:
			case 'month':
				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;

					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND (DATE(order_date_added) = '" . $this->db->escape($date) . "') GROUP BY DAY(order_date_added)");

					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['order']['data'][] = array($i, 0);
					}

					$query = $this->db->query("SELECT COUNT(*) AS total FROM clinic WHERE DATE(clinic_date_added) = '" . $this->db->escape($date) . "' GROUP BY DAY(clinic_date_added)");

					if ($query->num_rows) {
						$data['clinic']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['clinic']['data'][] = array($i, 0);
					}

					$data['xaxis'][] = array($i, date('j', strtotime($date)));
				}
				break;
			case 'year':
				for ($i = 1; $i <= 12; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND YEAR(order_date_added) = '" . date('Y') . "' AND MONTH(order_date_added) = '" . $i . "' GROUP BY MONTH(order_date_added)");

					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['order']['data'][] = array($i, 0);
					}

					$query = $this->db->query("SELECT COUNT(*) AS total FROM clinic WHERE YEAR(clinic_date_added) = '" . date('Y') . "' AND MONTH(clinic_date_added) = '" . $i . "' GROUP BY MONTH(clinic_date_added)");

					if ($query->num_rows) {
						$data['clinic']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['clinic']['data'][] = array($i, 0);
					}

					$data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i, 1, date('Y'))));
				}
				break;
		}

		$this->load->library('json');

		$this->response->setOutput(Json::encode($data));
	}

	public function login() {
		if (!$this->user->isLogged()) {
			return $this->forward('common/login');
		}

		if (isset($this->request->get['route'])) {
			$route = '';

			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}

			$ignore = array(
				'common/login',
				'common/logout',
				'error/not_found',
				'error/permission'
			);

			$config_ignore = array();

			if ($this->config->get('config_token_ignore')) {
				$config_ignore = unserialize($this->config->get('config_token_ignore'));
			}

			$ignore = array_merge($ignore, $config_ignore);

			if (!in_array($route, $ignore)) {
				if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
					return $this->forward('common/login');
				}
			}
		} else {
			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
				return $this->forward('common/login');
			}
		}
	}

	public function permission() {
		if (isset($this->request->get['route'])) {
			$route = '';

			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}

			$ignore = array(
				'common/home',
				'common/login',
				'common/logout',
				'error/not_found',
				'error/permission',
				'error/token'
			);

			if (!in_array($route, $ignore)) {
				if (!$this->user->hasPermission('access', $route)) {
					return $this->forward('error/permission');
				}
			}
		}
	}
}
?>
