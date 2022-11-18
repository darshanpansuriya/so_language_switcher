<?php
class ControllerUserUser extends Controller {
	private $error = array();

  	public function index() {
//p(__LINE__.__FILE__); exit();

    	$this->load->language('user/user');

    	$this->document->title = $this->language->get('heading_title');

		$this->load->model('user/user');

    	$this->getList();
  	}

  	public function insert() {
//p(__LINE__.__FILE__); exit();

		if ($this->user->getUGstr() != '1') {

      		$this->session->data['error'] = "You cannot use this function";

			$this->redirect(HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token']);
		}
    	$this->load->language('user/user');

    	$this->document->title = $this->language->get('heading_title');

		$this->load->model('user/user');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_user_user->addUser($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->redirect(HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . $url);
    	}

    	$this->getForm();
  	}

  	public function update() {

//p(__LINE__.__FILE__); exit();
		if ($this->user->getUGstr() != '1' && $this->user->getID() != $this->request->get['user_id'] ) {

      		$this->session->data['warning'] = "You cannot edit somebody else";

			$this->redirect(HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token']);
		}

    	$this->load->language('user/user');

    	$this->document->title = $this->language->get('heading_title');

		$this->load->model('user/user');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_user->editUser($this->request->get['user_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if ($this->user->getUGstr() == '1') {
				$this->redirect(HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . $url);
			} else {
				$this->session->data['warning'] = "You have saved";
				$this->session->data['success'] = '';
				$this->redirect(HTTPS_SERVER . 'index.php?route=user/user/update&user_id='.$this->session->data['user_id'].'&token=' . $this->session->data['token'] . $url);
			}
    	}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('user/user');

    	$this->document->title = $this->language->get('heading_title');

		$this->load->model('user/user');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $user_id) {
				$this->model_user_user->deleteUser($user_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->redirect(HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . $url);
    	}

    	$this->getList();
  	}

  	private function getList() {

//	p(__LINE__.__FILE__); exit();

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'user_email';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = NULL;
		}

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = NULL;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = NULL;
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = NULL;
		}

		$url = '';

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}


  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=user/user/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=user/user/delete&token=' . $this->session->data['token'] . $url;
		$this->data['token'] = $this->session->data['token'];

    	$this->data['users'] = array();

		$data = array(

			'filter_email'              => $filter_email,
			'filter_group'              => $filter_group,
			'filter_status'             => $filter_status,
			'filter_date'        => $filter_date,

			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$user_total = $this->model_user_user->getTotalUsers($data);

		$results = $this->model_user_user->getUsers($data);
//p($results);
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=user/user/update&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url
			);
//p($result);
      		$this->data['users'][] = array(
				'user_id'    => $result['user_id'],
				'user_group_string'    => $result['user_group_string'],
				'ug_string'    => $result['ug_string'],
				'user_last_login'    => $result['user_last_login'],
				'user_email'   => $result['user_email'],
				'user_status'     => ($result['user_status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
//				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'user_date_added' => substr($result['user_date_added'],0,10),
				'selected'   => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_group'] = $this->language->get('column_group');
		$this->data['column_last_login'] = $this->language->get('column_last_login');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_add_user'] = $this->language->get('button_add_user');
		$this->data['button_delete_user'] = $this->language->get('button_delete_user');
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_edit'] = $this->language->get('button_edit');

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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_group'] = HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . '&sort=name' . $url;
		$this->data['sort_email'] = HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . '&sort=user_email' . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . '&sort=user_status' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . '&sort=user_date_added' . $url;

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . $url . '&page={page}';

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_email'] = $filter_email;
		$this->data['filter_group'] = $filter_group;
		$this->data['filter_date'] = $filter_date;
		$this->data['filter_status'] = $filter_status;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$groups = $this->db->query("SELECT * FROM user_group ORDER BY user_group_name");
		$this->data['groups'] = $groups->rows;


		$this->template = 'user/user_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}

	private function getForm() {
//p(__LINE__.__FILE__); exit();


    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');

    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_user_group'] = $this->language->get('entry_user_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

    	$this->data['tab_general'] = $this->language->get('tab_general');


		$this->data = array_merge($this->hc_functions->getErrors(array('warning', 'user_password', 'user_confirm','user_firstname','user_email','user_lastname','user_group_string'),$this->error),$this->data);


		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['user_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/user/insert&token=' . $this->session->data['token'] . $url;
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/user/update&token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url;
		}

    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token'] . $url;

    	if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$user_info = $this->model_user_user->getUser($this->request->get['user_id']);
    	}


		if (!isset($user_info)) $user_info = array();
		$this->data = array_merge($this->hc_functions->getFormVars(array('user_firstname','user_lastname','user_email','user_group_string','user_status'),$user_info),$this->data);


  		if (isset($this->request->post['user_password'])) {
    		$this->data['user_password'] = $this->request->post['user_password'];
		} else {
			$this->data['user_password'] = '';
		}

  		if (isset($this->request->post['user_confirm'])) {
    		$this->data['user_confirm'] = $this->request->post['user_confirm'];
		} else {
			$this->data['user_confirm'] = '';
		}
		$this->load->model('user/user_group');
		$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();

		$this->load->model('sale/clinic');
		$this->data['clinics'] = $this->model_sale_clinic->getClinics();


		$this->template = 'user/user_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}

  	private function validateForm() {
//p(__LINE__.__FILE__); exit();

//    	if (!$this->user->hasPermission('modify', 'user/user')) {
//      		$this->error['warning'] = $this->language->get('error_permission');
//    	}
/*
		if ($this->request->isget('route') == 'user/user/update' && $this->user->getUGstr() != '1' && $this->user->getID() != $this->request->get['user_id'] ) {

      		$this->session->data['warning'] = "You cannot edit somebody else";

			$this->redirect(HTTPS_SERVER . 'index.php?route=user/user&token=' . $this->session->data['token']);
		}
*/
    	if ((strlen(utf8_decode($this->request->post['user_firstname'])) < 3) || (strlen(utf8_decode($this->request->post['user_firstname'])) > 32)) {
      		$this->error['user_firstname'] = "Enter first name";
    	}

    	if ((strlen(utf8_decode($this->request->post['user_lastname'])) < 3) || (strlen(utf8_decode($this->request->post['user_lastname'])) > 32)) {
      		$this->error['user_lastname'] = "Enter last name";
    	}

		if ((strlen(utf8_decode($this->request->post['user_email'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['user_email']))) {
      		$this->error['user_email'] = 'Please provide a valid email';
    	}

		if (!isset($this->error['user_email']) && ! $this->request->isget('user_id')) {
			$s = $this->db->query("SELECT * FROM user WHERE user_email = '".trim($this->request->post['user_email'])."'");

			if ($s->num_rows) $this->error['user_email'] = 'This email is already used';

		}


    	if (($this->request->post['user_password']) || (!isset($this->request->get['user_id']))) {
      		if ((strlen(utf8_decode($this->request->post['user_password'])) < 4) || (strlen(utf8_decode($this->request->post['user_password'])) > 20)) {
        		$this->error['user_password'] = $this->language->get('error_password');
      		}

	  		if ($this->request->post['user_password'] != $this->request->post['user_confirm']) {
	    		$this->error['user_confirm'] = $this->language->get('error_confirm');
	  		}
    	}

		if (!isset($this->request->post['user_group_string'])) {

			if (isset($this->request->post['ugids'])) {
				$this->request->post['user_group_string'] = explode(',',$this->request->post['ugids']);
				$this->request->post['user_status'] = 1;

			} else $this->error['user_group_string'] = 'Please select at least one user group';

		} elseif (count($this->request->post['user_group_string']) > 1) {
			if (in_array('1',$this->request->post['user_group_string'])) {
				$this->error['user_group_string'] = 'Administrator cannot be combined with others';
			} elseif (in_array('11',$this->request->post['user_group_string'])) {
				$this->error['user_group_string'] = 'Clinics cannot be combined with others';
			}
		}


    	if (!$this->error) {
      		return TRUE;
    	} else {
			if (! isset($this->error['warning'])) $this->error['warning'] = 'Please Enter Data for Required Fields!';

      		return FALSE;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		foreach ($this->request->post['selected'] as $user_id) {
			if ($this->user->getId() == $user_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
		}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
}
?>
