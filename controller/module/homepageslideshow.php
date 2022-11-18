<?php
/*
Home Slide Show v1.0
Brought to you by Stephen - www.gamekeybox.com
*/

class ControllerModuleHomepageslideshow extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/homepageslideshow');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('setting/setting');
		$this->load->model('tool/image');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

        if(isset($this->request->post['slide_image'])){
        $slide_image_settings = serialize($this->request->post['slide_image']);
        }else{
        $slide_image_settings = "";
        }

        $slide_settings = array(
        'homepageslideshow_position'    => $this->request->post['slideshow_position'],
        'homepageslideshow_sort_order'  => "0",
        'homepageslideshow_status'      => $this->request->post['slideshow_status'],
        'homepageslideshow_delay'       => $this->request->post['slideshow_delay'],
        'homepageslideshow_speed'       => $this->request->post['slideshow_speed'],
        'homepageslideshow_pause'       => $this->request->post['slideshow_pause'],
        'homepageslideshow_height'      => $this->request->post['slideshow_height'],
        'homepageslideshow_width'       => $this->request->post['slideshow_width'],
        'homepageslideshow_slide_image' => $slide_image_settings,
        );

			$this->model_setting_setting->editSetting('homepageslideshow', $slide_settings);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}

		$this->data['heading_title']        = $this->language->get('heading_title');

		$this->data['text_enabled']         = $this->language->get('text_enabled');
		$this->data['text_disabled']        = $this->language->get('text_disabled');
		$this->data['text_true']            = $this->language->get('text_true');
		$this->data['text_false']           = $this->language->get('text_false');
        $this->data['text_pleaseselect']    = $this->language->get('text_pleaseselect');
        $this->data['text_image_manager']   = $this->language->get('text_image_manager');

		$this->data['entry_position']       = $this->language->get('entry_position');
        $this->data['entry_status']         = $this->language->get('entry_status');
		$this->data['entry_delay']          = $this->language->get('entry_delay');
		$this->data['entry_speed']          = $this->language->get('entry_speed');
        $this->data['entry_pause']          = $this->language->get('entry_pause');
        $this->data['entry_slide']          = $this->language->get('entry_slide');
        $this->data['entry_url']            = $this->language->get('entry_url');
        $this->data['entry_alt']            = $this->language->get('entry_alt');
        $this->data['entry_target']         = $this->language->get('entry_target');
        $this->data['entry_sort']           = $this->language->get('entry_sort');
        $this->data['entry_height']         = $this->language->get('entry_height');
        $this->data['entry_width']          = $this->language->get('entry_width');
        $this->data['text_left']            = $this->language->get('text_left');
        $this->data['text_right']           = $this->language->get('text_right');
        $this->data['text_middletop']       = $this->language->get('text_middletop');
        $this->data['text_home']            = $this->language->get('text_home');

		$this->data['button_save']          = $this->language->get('button_save');
		$this->data['button_cancel']        = $this->language->get('button_cancel');
        $this->data['button_addslide']      = $this->language->get('button_addslide');
        $this->data['button_remove']        = $this->language->get('button_remove');

        $this->data['help_slidedelay'] = $this->language->get('help_slidedelay');
        $this->data['help_transitionspeed'] = $this->language->get('help_transitionspeed');
        $this->data['help_linkurl']        = $this->language->get('help_linkurl');
        $this->data['help_alttext']        = $this->language->get('help_alttext');
        $this->data['help_browsealt']      = $this->language->get('help_browsealt');
        $this->data['help_browsetitle']    = $this->language->get('help_browsetitle');
        $this->data['help_addslidehdr']    = $this->language->get('help_addslidehdr');
        $this->data['help_addslidetext']   = $this->language->get('help_addslidetext');
        $this->data['help_height']         = $this->language->get('help_height');
        $this->data['help_width']          = $this->language->get('help_width');
        $this->data['help_target']         = $this->language->get('help_target');

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
       		'href'      => HTTPS_SERVER . 'index.php?route=module/homepageslideshow&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/homepageslideshow&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];


		if (isset($this->request->post['slideshow_status'])) {
			$this->data['slideshow_position'] = $this->request->post['slideshow_position'];
		} else {
			$this->data['slideshow_position'] = $this->config->get('homepageslideshow_position');
		}

		if (isset($this->request->post['slideshow_status'])) {
			$this->data['slideshow_status'] = $this->request->post['slideshow_status'];
		} else {
			$this->data['slideshow_status'] = $this->config->get('homepageslideshow_status');
		}

		if (isset($this->request->post['slideshow_delay'])) {
			$this->data['slideshow_delay'] = $this->request->post['slideshow_delay'];
		} else {
			$this->data['slideshow_delay'] = $this->config->get('homepageslideshow_delay');
		}

		if (isset($this->request->post['slideshow_speed'])) {
			$this->data['slideshow_speed'] = $this->request->post['slideshow_speed'];
		} else {
			$this->data['slideshow_speed'] = $this->config->get('homepageslideshow_speed');
		}

		if (isset($this->request->post['slideshow_pause'])) {
			$this->data['slideshow_pause'] = $this->request->post['slideshow_pause'];
		} else {
			$this->data['slideshow_pause'] = $this->config->get('homepageslideshow_pause');
		}

		if (isset($this->request->post['slideshow_height'])) {
			$this->data['slideshow_height'] = $this->request->post['slideshow_height'];
		} else {
			$this->data['slideshow_height'] = $this->config->get('homepageslideshow_height');
		}

		if (isset($this->request->post['slideshow_width'])) {
			$this->data['slideshow_width'] = $this->request->post['slideshow_width'];
		} else {
			$this->data['slideshow_width'] = $this->config->get('homepageslideshow_width');
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);


        $this->data['slide_images'] = array();
        $this->data['slide_images'] = unserialize($this->config->get('homepageslideshow_slide_image'));


		$this->template = 'module/homepageslideshow.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/homepageslideshow')) {
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