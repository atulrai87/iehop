<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Events extends Controller {
    
	public function __construct() {
		parent::Controller();
		$this->load->model('Events_Model');
	}
	
	public function index()
	{       
		$events = $this->Events_Model->fetchEvents();
		$this->template_lite->assign('events', $events);
		$this->template_lite->view('events');
	}
    
    public function searchEvent(){ 
        $events = $this->Events_Model->searchEvents($_POST);
		$this->template_lite->assign('events', $events);
		$this->template_lite->view('events');
    }
    
    public function saveEvent(){
        $this->Events_Model->saveEvent($_POST);
		$events = $this->Events_Model->fetchEvents();
		$this->template_lite->assign('events', $events);
		$this->template_lite->view('events');
    }
    
    public function newEvent() {
		$this->template_lite->view('add_new_event');
    }
}