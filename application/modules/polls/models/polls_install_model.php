<?php
/**
* Poll install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Polls_install_model extends Model {

	private $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'other_items' => array(
					'action'=>'none',
					'items' => array(
						'polls_menu_item' => array('action' => 'create', 'link' => 'admin/polls', 'status' => 1, 'sorter' => 2)
					)
				)
			)
		),
		'admin_polls_menu' => array(
			'action' => 'create',
			'name' => 'Polls section menu',
			'items' => array(
				'polls_list_item' => array('action' => 'create', 'link' => 'admin/polls', 'status' => 1)
			)
		),
		'guest_main_menu' => array(
			'action' => 'none',
			'items' => array(
				'guest-main-polls-item' => array('action' => 'create', 'link' => 'polls', 'status' => 1, 'sorter' => 10)
			)
		),
		'user_top_menu' => array(
			'action' => 'none',
			'items' => array(
				'user-menu-activities' => array(
					'action' => 'none',
					'items' => array(
						'user_main_polls_item' => array('action' => 'create', 'link' => 'polls', 'status' => 1, 'sorter' => 30),
					)
				)
			)
		),
	);

	function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		//// load langs
		$this->CI->load->model('Install_model');
	}

	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('polls', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( "menu" => $return );
	}

	public function deinstall_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			if($menu_data['action'] == 'create'){
				linked_install_set_menu($gid, 'delete');
			}else{
				linked_install_delete_menu_items($gid, $this->menu[$gid]['items']);
			}
		}
	}

	public function install_site_map() {
		//// Site map
		$this->CI->load->model('Site_map_model');
		$site_map_data = array(
			'module_gid' => 'polls',
			'model_name' => 'Polls_model',
			'get_urls_method' => 'get_sitemap_urls',
		);
		$this->CI->Site_map_model->set_sitemap_module('polls', $site_map_data);
	}

	private $ausers_methods = array(
		array('module' => 'polls', 'method' => 'index', 'is_default' => 1),
	);

	/**
	 * Ausers module methods
	 */
	public function install_ausers() {
		// install ausers permissions
		$this->CI->load->model('ausers/models/Ausers_model');
		foreach($this->ausers_methods as $method){
			$this->CI->Ausers_model->save_method(null, $method);
		}
	}

	public function install_ausers_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('polls', 'ausers', $langs_ids);

		// install ausers permissions
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'polls';
		$methods = $this->CI->Ausers_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Ausers_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_ausers_lang_export($langs_ids) {
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'polls';
		$methods =  $this->CI->Ausers_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('ausers' => $return);
	}

	public function deinstall_ausers() {
		// delete moderation methods in ausers
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'polls';
		$this->CI->Ausers_model->delete_methods($params);
	}

	function _arbitrary_installing() {
		///// Seo
		$seo_data = array(
			'module_gid' => 'polls',
			'model_name' => 'Polls_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('polls', $seo_data);
		$this->add_demo_content();
	}

	public function deinstall_site_map() {
		$this->CI->load->model('Site_map_model');
		$this->CI->Site_map_model->delete_sitemap_module('polls');
	}

	/**
	 * Import module languages
	 * 
	 * @param array $langs_ids array languages identifiers
	 * @return void
	 */
	public function _arbitrary_lang_install($langs_ids=null){
		$langs_file = $this->CI->Install_model->language_file_read("polls", "arbitrary", $langs_ids);
		if(!$langs_file){log_message("info", "Empty polls arbitrary langs data"); return false;}
		
		$post_data = array(
			"title" => $langs_file["seo_tags_polls_results_title"],
			"keyword" => $langs_file["seo_tags_polls_results_keyword"],
			"description" => $langs_file["seo_tags_polls_results_description"],
			"header" => $langs_file["seo_tags_polls_results_header"],
			"og_title" => $langs_file["seo_tags_polls_results_og_title"],
			"og_type" => $langs_file["seo_tags_polls_results_og_type"],
			"og_description" => $langs_file["seo_tags_polls_results_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "polls", "index", $post_data);
	}

	/**
	 * Export module languages
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function _arbitrary_lang_export($langs_ids=null){
		if(empty($langs_ids)) return false;

		//// arbitrary
		$settings = $this->CI->pg_seo->get_settings("user", "polls", "index", $langs_ids);
		$arbitrary_return["seo_tags_polls_results_title"] = $settings["title"];
		$arbitrary_return["seo_tags_polls_results_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_polls_results_description"] = $settings["description"];
		$arbitrary_return["seo_tags_polls_results_header"] = $settings["header"];
		$arbitrary_return["seo_tags_polls_results_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_polls_results_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_polls_results_og_description"] = $settings["og_description"];

		return array("arbitrary" => $arbitrary_return);
	}

	function _arbitrary_deinstalling() {
		$this->CI->pg_seo->delete_seo_module('polls');
	}

	public function add_demo_content(){
		$this->CI->load->model('Polls_model');
		$demo_content = include MODULEPATH . 'polls/install/demo_content.php';
		// Associating languages id with codes
		foreach($this->CI->pg_language->languages as $l) {
			$lang[$l['code']] = $l['id'];
		}

		foreach($demo_content as $poll) {
			$poll_data = array();
			$answer_data = array();
			// Replace language code with ID
			foreach($poll['question'] as $l => $question) {
				$poll_data['question'][$lang[$l]] = $question;
				unset($poll_data['question'][$l]);
			}
			// Same for the answers
			foreach($poll['answers'] as $number => $answer) {
				foreach($answer as $key => $value) {
					if('color' === $key) {
						$answer_data['answers_colors'][$number] = $value;
					} else {
						$answer_data['answers_languages'][$number . '_' . $lang[$key]] = $value;
					}
				}
			}
			$poll_data = $this->CI->Polls_model->validate_poll($poll_data);
			$answer_data = $this->CI->Polls_model->validate_answers($answer_data);
			$poll['question'] = $poll_data['data']['question'];
			$poll['answers_languages'] = $answer_data['data']['answers_languages'];
			$poll['answers_colors'] = $answer_data['data']['answers_colors'];
			unset($poll['answers']);
			$responses = $poll['responses'];
			unset($poll['responses']);
			$poll_id = $this->CI->Polls_model->save_poll($poll);

			// Responses
			foreach($responses as $response) {
				$response['poll_id'] = $poll_id;
				$this->CI->Polls_model->save_respond($response);
			}
		}
		return true;
	}

}
