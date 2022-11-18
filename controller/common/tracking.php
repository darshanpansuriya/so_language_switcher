<?php   
class ControllerCommonTracking extends Controller {   
	public function index() {
    	$this->load->language('common/tracking');
	 
		$this->document->title = $this->language->get('heading_title');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['packing_number'] = $this->language->get('text_packing_list_num');
		$this->data['packing_orders'] = $this->language->get('text_packing_orders');
		$this->data['shipping_date'] = $this->language->get('text_shipping_date');
		$this->data['tracking_number'] = $this->language->get('text_tracking');
		$this->data['action'] = $this->language->get('text_action');
		
		
		$this->load->model('sale/order');
		
		$this->getList();
	}	
		
		
		private function getList() {
			
			$url = $this->hc_functions->getURL(array('filter_packing_num', 'filter_shipping_date', 'filter_tracking_num','sort','order'));

			$refarr = $this->hc_functions->getURLvars(array('filter_packing_num', 'filter_shipping_date', 'filter_tracking_num','sort','order'),'shipping_date','DESC');
			
			extract($refarr);
	
			
		if (isset($this->request->get['page'])) { //checking if page variable is set in URL or not
			$page = $this->request->get['page'];// if value is set it assigns to page variable
		} else { //if value is not set
			$page = 1; // page variable is set to 1
		}
		$url = ''; //declaring the url varaiable to empty
		
		if (isset($this->request->get['page'])) { //checking page is set in URL or not
			$url .= '&page=' . $this->request->get['page']; // if page variable is set it is appended to url variable
		}

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
   		$this->document->breadcrumbs[] = array(
   				'href' => HTTPS_SERVER . 'index.php?route=common/tracking&token=' . $this->session->data['token'],
   				'text' => $this->language->get('heading_title'),
   				'separator' => ' :: '
   		);
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('common/tracking');
				
		$this->data['orders'] = array();
		
// 		$data = array(
// 			'sort'  => 'tracking_orders',
// 			'order' => 'DESC',
// 			'start' => 0,
// 			'limit' => 10
// 		);
		
// 		echo $order.'<br>';
// 		die();
		
		$data = array(
				'filter_packing_num' => $filter_packing_num,
				'filter_shipping_date' => $filter_shipping_date,
				'filter_tracking_num' => $filter_tracking_num,	
				'sort'                => $sort,
				'order'               => $order,
				'start' => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit' => $this->config->get('config_admin_limit')
				);
		
		
				
		$results = $this->model_common_tracking->getTrackingOrders($data);
		
		$totals = $this->model_common_tracking->getTotalTrackingOrders($data);
		
		//print_r($results);
		
				
    	foreach ($results as $result) {
			$action = array();

//				p($result);	

			$this->data['orders'][] = array(
				'packing_list_num'   => $result['packing_list_num'],
				'pack_orders'		=> $result['packing_orders'],		
				'ship_date' => $result['ship_date'],
				'order_shipping_number' => $result['order_shipping_number'],					
			);
		}

		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');
		
			$this->model_localisation_currency->updateCurrencies();
		}
		
		$this->template = 'common/tracking.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);		
		
		$url = $this->hc_functions->getURL(array('filter_packing_num', 'filter_shipping_date', 'filter_tracking_num'));
		
		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		
		$this->data['sort_packing_list_num'] = HTTPS_SERVER . 'index.php?route=common/tracking&token=' . $this->session->data['token'] . '&sort=packing_list_num' . $url;
		$this->data['sort_shipping_date'] = HTTPS_SERVER . 'index.php?route=common/tracking&token=' . $this->session->data['token'] . '&sort=ship_date' . $url;
		//$this->data['sort_lname'] = HTTPS_SERVER . 'index.php?route=sale/patient&token=' . $this->session->data['token'] . '&sort=lname' . $url;
		
		$url = $this->hc_functions->getURL(array('filter_packing_num', 'filter_shipping_date', 'filter_tracking_num','sort','order'));
		
		$pagination = new Pagination();
		$pagination->total = $totals;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=common/tracking&token=' . $this->session->data['token'] . $url . '&page={page}';
		
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_packing_num'] = $filter_packing_num;
		$this->data['filter_shipping_date'] = $filter_shipping_date;
		$this->data['filter_tracking_num'] = $filter_tracking_num;	
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		
  	}	
  	/**
  	 * this function is written to update the shipping number for orders in orders table
  	 */
  	public function updateShippingNumber(){
  		
  		//loading the model which is responsible for the update function
  		$this->load->model('common/tracking');
  		
  		//getting the packing id from the URL if it is set
  		if (isset($this->request->get['packing_id'])) {
  			$packing_number =  $this->request->get['packing_id'];
  		}
  		//getiing the shipping number from the URL if it is set
  		if (isset($this->request->get['shipp_id'])) {
  			$shipping_number =  $this->request->get['shipp_id'];
  		}
  		
  		// calling the model function to update  the values in the database
  		echo $this->model_common_tracking->updateOrderShippingNumber($packing_number,$shipping_number);
  		
  	}

}