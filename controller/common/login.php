<?php  
class ControllerCommonLogin extends Controller { 
	private $error = array();
	          
	public function index() { 
    	$this->load->language('common/login');

		$this->document->title = $this->language->get('heading_title');

		if ($this->user->isLogged() && isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->redirect(HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token']);
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { 
			$this->session->data['token'] = md5(mt_rand()); 
		
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
			} else {
				$this->redirect(HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token']);
			}
		}
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_login'] = $this->language->get('text_login');
		
		$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_password'] = $this->language->get('entry_password');

    	$this->data['button_login'] = $this->language->get('button_login');
		
		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
			$this->error['warning'] = $this->language->get('error_token');
		}
		
		if (isset($this->error['success'])) {
			$this->data['error_success'] = $this->error['success'];
		} else {
			$this->data['error_success'] = '';
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
    	$this->data['action'] = HTTPS_SERVER . 'index.php?route=common/login';

		if (isset($this->request->post['username'])) {
			$this->data['username'] = $this->request->post['username'];
		} else {
			$this->data['username'] = '';
		}
		
		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
			
			unset($this->request->get['route']);
			
			if (isset($this->request->get['token'])) {
				unset($this->request->get['token']);
			}
			
			$url = '';
			
			if ($this->request->get) {
				$url = '&' . http_build_query($this->request->get);
			}
			
			$this->data['redirect'] = HTTPS_SERVER . 'index.php?route=' . $route . $url;
		} else {
			$this->data['redirect'] = '';	
		}
						
		$this->template = 'common/login.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
		
	private function validate() {
		
		if (isset($this->request->post['forgotpass']) && isset($this->request->post['username'])) {
			$un = $this->db->query("SELECT * FROM user WHERE user_email = '".$this->db->escape($this->request->post['username'])."'");
			if ($un->num_rows) {
				$this->error['success'] = "Your info has been sent";
				$t = trim(substr(str_replace(array('.',' '),'',microtime()),0,20));
				$this->db->query("UPDATE user SET user_token = '$t' WHERE user_id ='" .$un->row['user_id']. "'");
				$msg = "Dear User,\n\n";
				$msg .= "If you requested resetting your password, please click on the following link.\n\n";
				$msg .= "Otherwise, ignore this email.\n\n";
				$msg .= HTTPS_SERVER . "index.php?route=common/login/reset&id=".$un->row['user_id']."&t=$t";
				$h = "From: ".$this->config->get('config_email')."\r\nReply-To: ".$this->config->get('config_email')."\r\n";
				mail($un->row['user_email'],"Instructions to get password for SoundOrthotics",$msg,$h);
			}
			else $this->error['warning'] = "This email does not exists";
		} else 
		
		
		if (isset($this->request->post['username']) && isset($this->request->post['password']) && !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
			$this->error['warning'] = $this->language->get('error_login');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}  
?>