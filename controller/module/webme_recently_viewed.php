<?php
class ControllerModuleWebmeRecentlyViewed extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('module/webme_recently_viewed');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('webme_recently_viewed', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['entry_personal_limit'] = $this->language->get('entry_personal_limit');
		$this->data['entry_overall_status'] = $this->language->get('entry_overall_status');
		$this->data['entry_overall_status_on'] = $this->language->get('entry_overall_status_on');
		$this->data['entry_overall_status_off'] = $this->language->get('entry_overall_status_off');
		$this->data['entry_overall_limit'] = $this->language->get('entry_overall_limit');
		
		
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
			'href'      => HTTPS_SERVER . 'index.php?route=module/webme_recently_viewed&token=' . $this->session->data['token'],
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/webme_recently_viewed&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];
		
		if (isset($this->request->post['webme_recently_viewed_position'])) {
			$this->data['webme_recently_viewed_position'] = $this->request->post['webme_recently_viewed_position'];
		} else {
			$this->data['webme_recently_viewed_position'] = $this->config->get('webme_recently_viewed_position');
		}
		
		if (isset($this->request->post['webme_recently_viewed_status'])) {
			$this->data['webme_recently_viewed_status'] = $this->request->post['webme_recently_viewed_status'];
		} else {
			$this->data['webme_recently_viewed_status'] = $this->config->get('webme_recently_viewed_status');
		}
		
		if (isset($this->request->post['webme_recently_viewed_sort_order'])) {
			$this->data['webme_recently_viewed_sort_order'] = $this->request->post['webme_recently_viewed_sort_order'];
		} else {
			$this->data['webme_recently_viewed_sort_order'] = $this->config->get('webme_recently_viewed_sort_order');
		}
		
		if (isset($this->request->post['webme_recently_viewed_personal_limit'])) {
			$this->data['webme_recently_viewed_personal_limit'] = $this->request->post['webme_recently_viewed_personal_limit'];
		} else {
			$this->data['webme_recently_viewed_personal_limit'] = $this->config->get('webme_recently_viewed_personal_limit');
		}
		
		/* overall viewed status */
		if (isset($this->request->post['webme_recently_viewed_overall_status'])) {
			$this->data['webme_recently_viewed_overall_status'] = $this->request->post['webme_recently_viewed_overall_status'];
		} else {
			$this->data['webme_recently_viewed_overall_status'] = $this->config->get('webme_recently_viewed_overall_status');
		}
		
		if ($this->data['webme_recently_viewed_overall_status'] == "1") {
			$this->data['webme_recently_viewed_overall_status_on'] = "checked";
			$this->data['webme_recently_viewed_overall_status_off'] = "";
		} else {
			$this->data['webme_recently_viewed_overall_status_on'] = "";
			$this->data['webme_recently_viewed_overall_status_off'] = "checked";
		}
		
		/* overall viewed limit */
		if (isset($this->request->post['webme_recently_viewed_overall_limit'])) {
			$this->data['webme_recently_viewed_overall_limit'] = $this->request->post['webme_recently_viewed_overall_limit'];
		} else {
			$this->data['webme_recently_viewed_overall_limit'] = $this->config->get('webme_recently_viewed_overall_limit');
		}
		
		//$webme_recently_viewed_overall_status;
		
		
		$this->template = 'module/webme_recently_viewed.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/webme_recently_viewed')) {
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