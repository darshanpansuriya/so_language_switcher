<?php
class ControllerModuleDeliverydate extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/deliverydate');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('deliverydate', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_interval'] = $this->language->get('entry_interval');
		$this->data['entry_unavailable_after'] = $this->language->get('entry_unavailable_after');
		$this->data['entry_display_same_day'] = $this->language->get('entry_display_same_day');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=module/deliverydate&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/deliverydate&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];

		if (isset($this->request->post['deliverydate_status'])) {
			$this->data['deliverydate_status'] = $this->request->post['deliverydate_status'];
		} else {
			$this->data['deliverydate_status'] = $this->config->get('deliverydate_status');
		}
		
		if (isset($this->request->post['deliverydate_interval_days'])) {
			$this->data['deliverydate_interval_days'] = $this->request->post['deliverydate_interval_days'];
		} else {
			$this->data['deliverydate_interval_days'] = $this->config->get('deliverydate_interval_days');
		}

		if (isset($this->request->post['deliverydate_interval_days'])) {
       		$this->data['deliverydate_interval_days'] = $this->request->post['deliverydate_interval_days'];
		} else {
			$this->data['deliverydate_interval_days'] = $this->config->get('deliverydate_interval_days');
		}

		if (isset($this->request->post['deliverydate_unavailable_after'])) {
      		$this->data['deliverydate_unavailable_after'] = $this->request->post['deliverydate_unavailable_after'];
    	} else {
      		$this->data['deliverydate_unavailable_after'] = $this->config->get('deliverydate_unavailable_after');
    	}

		if (isset($this->request->post['deliverydate_same_day'])) {
      		$this->data['deliverydate_same_day'] = $this->request->post['deliverydate_same_day'];
    	} else {
      		$this->data['deliverydate_same_day'] = $this->config->get('deliverydate_same_day');
    	}

		$this->template = 'module/deliverydate.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/deliverydate')) {
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