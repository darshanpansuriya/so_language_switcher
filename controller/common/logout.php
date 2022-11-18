<?php       
class ControllerCommonLogout extends Controller {   
	public function index() { 
    	$this->user->logout();
		
		foreach ($_SESSION as $k=>$v){
			unset($this->session->data[$k]);
		}
 
// 		unset($this->session->data['token']);

		$this->redirect(HTTPS_SERVER . 'index.php?route=common/login');
  	}
}  
?>