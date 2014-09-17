<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Events extends CI_Controller {
    
	public function index()
	{
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
}