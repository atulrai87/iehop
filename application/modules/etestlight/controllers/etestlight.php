<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Etestlight extends Controller {
    
	public function __construct() {
		parent::Controller();
		$this->load->model('Etestlight_model');
	}
	
	public function index()
	{       
	        
		$testevents = $this->Etestlight_model->testlite();
		$this->template_lite->assign('testlight', $testevents);
		$this->template_lite->view('etestlight');
	}
    
}