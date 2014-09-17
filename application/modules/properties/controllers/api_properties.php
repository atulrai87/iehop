<?php
/**
* Properties api controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
**/
Class Api_Properties extends Controller {

	/**
	 * Returns categories list
	 *
	 * @param int $parent_id Default = null
	 */
	public function get_categories() {
		$this->load->model('properties/models/Job_categories_model');
		$parent_id = $this->input->post('parent_id', true);
		$cateories = $this->Job_categories_model->get_job_categories($parent_id);
		$this->set_api_content('data', array('categories' => $cateories));
	}

	/**
	 * Returns category
	 *
	 * @param int $category_id
	 */
	public function get_category() {
		$this->load->model('properties/models/Job_categories_model');

		$category_id = $this->input->post('category_id', true);
		if (!$category_id){
			log_message('error', 'properties API: Empty category id');
			$this->set_api_content('error', l('api_error_empty_category_id', 'properties'));
			return false;
		}
		$category = $this->Job_categories_model->get_job_category_by_id($category_id, $this->pg_language->current_lang_id);
		if(!$category) {
			$this->set_api_content('error', l('api_error_category_not_found', 'properties'));
			return false;
		}
		$this->set_api_content('data', array('category' => $category));
	}

	public function get_property(){
		$gid = trim(strip_tags($this->input->post('gid', true)));
		$lang_id = filter_input(INPUT_POST, 'lang_id');
		$this->load->model('Properties_model');
		$propertie = $this->Properties_model->get_property($gid, $lang_id);
		$this->set_api_content('data', $propertie);
	}

}