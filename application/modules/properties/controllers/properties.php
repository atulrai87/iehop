<?php 
/**
* Properties user side controller
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Properties extends Controller {

	public function ajax_get_category_form($max=5) {
		$this->load->model('properties/models/Job_categories_model');
		
		$data["max"] = $max;
		$data["categories"] = $this->Job_categories_model->get_job_categories();

		$this->template_lite->assign("data", $data);
		$this->template_lite->view('ajax_category_form');
	}

	public function ajax_get_category_search_form($var=0) {
		$this->load->model('properties/models/Job_categories_model');

		$data["categories"] = $this->Job_categories_model->get_job_categories($var);

		$this->template_lite->assign("data", $data);
		$this->template_lite->view('ajax_category_search_form');
	}
	
	public function ajax_get_category_data($var) {
		$this->load->model('properties/models/Job_categories_model');
		$data = array();

		$var = intval($var);
		if($var){
			$data["current_category"] = $this->Job_categories_model->get_job_category_by_id($var, $this->pg_language->current_lang_id);
		}
		$data["categories"] = $this->Job_categories_model->get_job_categories($var);

		echo json_encode($data);
		return;
	}

}