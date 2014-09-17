<?php
/**
* Start user side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/


Class Start extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function Start()
	{
		parent::Controller();
		$this->load->model('Menu_model');
	}


/*-----Original function-------------
function index(){
		if($this->session->userdata("auth_type") == "user" && $this->session->userdata('user_id')){
			redirect(site_url()."start/homepage");
		}
		$this->session->set_userdata('demo_user_type', 'user');
		$this->template_lite->view('index');
	}
-----End Original function-------------*/
	
/*-----New modified function--------------*/
	function index(){
		//echo $_SESSION['login_as_business'];exit();
		if($this->session->userdata("auth_type") == "user" && $this->session->userdata('user_id')){
			if($_SESSION['login_as_business']){
				redirect(site_url()."start/eHopperdash");
			} else {
				redirect(site_url()."businessdash/eHopperdash");
			}
		}
		$this->session->set_userdata('demo_user_type', 'user');
		$this->template_lite->view('index');
	}

	function eHopperdash(){
		$this->load->model('Users_model');
		$stats = array();
		$this->Menu_model->breadcrumbs_set_parent('user-main-home-item');
		$this->template_lite->view('akki');
	}
	/* -----End New modified function-------------*/
	function homepage(){
		$this->load->model('Users_model');
		$stats = array();
		$this->Menu_model->breadcrumbs_set_parent('user-main-home-item');
		$this->template_lite->view('homepage');
	}

	function myfamily(){
		$this->load->model('Users_model');
		$stats = array();
		$this->Menu_model->breadcrumbs_set_parent('user-main-home-item');
		$this->template_lite->view('myfamily');
	}

	function mybuddies(){
		$this->load->model('Users_model');
		$stats = array();
		$this->Menu_model->breadcrumbs_set_parent('user-main-home-item');
		$this->template_lite->view('mybuddies');
	}
	

	function myevents(){
	        $this->load->model('events_model');       
	        $data['events'] = $this->events_model->fetchEvents();
	        $this->load->view('events/events', $data);
	}
	
	public function searchEvent(){
                $this->load->model('events_model');       
                $data['events'] = $this->events_model->searchEvents($_POST);
                $this->load->view('events/events', $data);
        }
            
        public function saveEvent(){
                $this->load->model('events_model');        
                $this->events_model->saveEvent($_POST);
                $data['events'] = $this->events_model->fetchEvents();
                $this->load->view('events/events', $data);
        }
            
        public function newEvent() {
                $data = array();
                $this->load->view('events/add_new_event', $data);
        }

	function howtouseiehop(){
		$this->load->model('Users_model');
		$stats = array();
		$this->Menu_model->breadcrumbs_set_parent('user-main-home-item');
		$this->template_lite->view('howtouseiehop');
	}	

	function error(){

		$this->Menu_model->breadcrumbs_set_active(l('header_error', 'start'));
		$this->template_lite->view('error');
	}

	function print_version(){
		echo $this->pg_module->get_module_config('start', 'product_version');
	}

	//// test methods
	function test_file_upload(){

		$this->load->model("file_uploads/models/File_uploads_config_model");

		$configs = $this->File_uploads_config_model->get_config_list();
		$this->template_lite->assign('configs', $configs);

		if($this->input->post('btn_save') && $this->input->post('config') ){
			$config = $this->input->post('config');
			$file_name = 'file';

			if( isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])){
				$this->load->model("File_uploads_model");
				$return = $this->File_uploads_model->upload($config, '', $file_name);

				if(!empty($return["errors"])){
					$this->system_messages->add_message('error', $return["errors"]);
				}else{
					$this->system_messages->add_message('success', $return["file"]);
				}
			}
		}

		$this->template_lite->view('test_file_upload');
	}

	function demo($type='user'){
		$this->session->set_userdata('demo_user_type', $type);
		redirect();
	}


	public function ajax_backend(){
		$data = (array)$this->input->post('data');
		$user_session_id = ($this->session->userdata('auth_type') == 'user') ? intval($this->session->userdata('user_id')) : 0;
		$return_arr['user_session_id'] = $user_session_id;
		foreach($data as $gid => $params){
			$return_arr[$gid] = array();
			if(
				!(empty($params['module']) && empty($params['model']) && empty($params['method']))
				&& $this->pg_module->is_module_installed($params['module'])
				&& $this->load->model($params['module'].'/models/'.$params['model'], $gid.'_backend_model', false, true, true)
				&& method_exists($this->{$gid.'_backend_model'}, 'backend_'.$params['method'])
			){
				$return_arr[$gid] = $this->{$gid.'_backend_model'}->{'backend_'.$params['method']}($params);
				$return_arr[$gid]['user_session_id'] = $user_session_id;
			}
		}

		exit(json_encode($return_arr));
	}
	
	public function multi_request_script(){
		$js = file_get_contents(APPPATH.'modules/users_lists/js/users_lists_multi_request.js');
		//header('Content-Type: text/javascript');
		echo $js;
	}
}