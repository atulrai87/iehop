<?php
/**
* Content api controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
**/

Class Api_Content extends Controller
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::Controller();
		$this->load->model("Content_model");
	}

	/**
	 * Get content
	 *
	 * @param string $gid
	 */
	public function get() {
		$gid = $this->input->post('gid', true);
		if(!$gid) {
			log_message('error', 'content API: Empty content gid');
			$this->set_api_content('errors', l('error_content_gid_invalid', 'content'));
			return false;
		}
		$lang_id = $this->pg_language->current_lang_id;
		$page_data = $this->Content_model->get_page_by_gid($lang_id, $gid);
		$this->set_api_content('data', array('page_data' => $page_data));
	}

	/**
	 * Get content tree
	 *
	 * @param string $gid
	 * @param aarray $params
	 */
	public function tree() {
		$lang_id = $this->pg_language->current_lang_id;
		$parent_id = (int)$this->input->post('gid', true);
		$params = (array)$this->input->post('params', true);
		$page_data = $this->Content_model->get_pages_list($lang_id, $parent_id, $params);
		$this->set_api_content('data', array('page_data' => $page_data));
	}

}