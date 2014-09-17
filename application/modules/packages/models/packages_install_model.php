<?php
/**
* Packages install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Chernov <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: mchernov $
**/

class Packages_install_model extends Model
{
	var $CI;
	private $menu = array(
		'admin_payments_menu' => array(
			'action' => 'none',
			'items' => array(
				'packages_menu_item' => array('action' => 'create', 'link' => 'admin/packages/index', 'status' => 1),
			)
		)
	);
	private $payment_types = array(
		array('gid' => 'packages', 'callback_module' => 'packages', 'callback_model' => 'Packages_model', 'callback_method' => 'payment_package_status'),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->CI->load->model('Install_model');
	}
	
	function _arbitrary_installing() {
		// SEO
		$seo_data = array(
			'module_gid' => 'packages',
			'model_name' => 'Packages_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('packages', $seo_data);
	}

	/**
	 * Import module languages
	 * 
	 * @param array $langs_ids array languages identifiers
	 * @return void
	 */
	public function _arbitrary_lang_install($langs_ids=null){
		$langs_file = $this->CI->Install_model->language_file_read("packages", "arbitrary", $langs_ids);
		if(!$langs_file){log_message("info", "Empty packages arbitrary langs data"); return false;}
		
		$post_data = array(
			"title" => $langs_file["seo_tags_packages_title"],
			"keyword" => $langs_file["seo_tags_packages_keyword"],
			"description" => $langs_file["seo_tags_packages_description"],
			"header" => $langs_file["seo_tags_packages_header"],
			"og_title" => $langs_file["seo_tags_packages_og_title"],
			"og_type" => $langs_file["seo_tags_packages_og_type"],
			"og_description" => $langs_file["seo_tags_packages_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "packages", "packages", $post_data);
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
		$settings = $this->CI->pg_seo->get_settings("user", "packages", "packages", $langs_ids);
		$arbitrary_return["seo_tags_packages_title"] = $settings["title"];
		$arbitrary_return["seo_tags_packages_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_packages_description"] = $settings["description"];
		$arbitrary_return["seo_tags_packages_header"] = $settings["header"];
		$arbitrary_return["seo_tags_packages_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_packages_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_packages_og_description"] = $settings["og_description"];

		return array("arbitrary" => $arbitrary_return);
	}

	function _arbitrary_deinstalling(){
		$this->CI->pg_seo->delete_seo_module('packages');
	}
	
	public function install_payments () {
		// add account payment type
		$this->CI->load->model("Payments_model");
		foreach($this->payment_types as $payment_type) {
			$data = array(
				'gid' => $payment_type['gid'],
				'callback_module' => $payment_type['callback_module'],
				'callback_model' => $payment_type['callback_model'],
				'callback_method' => $payment_type['callback_method'],
			);
			$this->CI->Payments_model->save_payment_type(null, $data);
		}
	}

	public function install_payments_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('packages', 'payments', $langs_ids);
		if(!$langs_file) { log_message('info', 'Empty payments langs data'); return false; }
		$this->CI->load->model('Payments_model');
		$this->CI->Payments_model->update_langs($this->payment_types, $langs_file, $langs_ids);
	}

	public function install_payments_lang_export($langs_ids = null) {
		$this->CI->load->model('Payments_model');
		$return = $this->CI->Payments_model->export_langs($this->payment_types, $langs_ids);
		return array( "payments" => $return );
	}

	public function deinstall_payments () {
		$this->CI->load->model('Payments_model');
		foreach($this->payment_types as $payment_type) {
			$this->CI->Payments_model->delete_payment_type_by_gid($payment_type['gid']);
		}
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
		$langs_file = $this->CI->Install_model->language_file_read('packages', 'menu', $langs_ids);

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
	
	public function install_cronjob() {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array(
			"name" => "Update users packages",
			"module" => "packages",
			"model" => "Packages_users_model",
			"method" => "update_packages",
			"cron_tab" => "*/10 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);
	}
	
	public function deinstall_cronjob () {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array();
		$cron_data["where"]["module"] = "packages";
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}
}
