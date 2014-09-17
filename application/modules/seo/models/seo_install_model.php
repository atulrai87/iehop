<?php

/**
 * Seo install model
 *
 * @package PG_RealEstate
 * @subpackage Seo
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 **/
class Seo_install_model extends Model
{
	/**
	 * Link to CodeIgniter object
	 *
	 * @var object
	 */
	protected $CI;
	
	/**
	 * Menu configuration
	 * 
	 * @var array
	 */
	protected $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'system-items' => array(
							'action'=>'none',
							'items' => array(
								'seo_menu_item' => array('action' => 'create', 'link' => 'admin/seo', 'status' => 1, 'sorter' => 3)
							)
						)
					)
				)
			)
		),
		'admin_seo_menu' => array(
			'action' => 'create',
			'name' => 'Admin mode - System - SEO settings',
			'items' => array(
				'seo_default_list_item' => array('action' => 'create', 'link' => 'admin/seo/index', 'status' => 1),
				'seo_list_item' => array('action' => 'create', 'link' => 'admin/seo/listing', 'status' => 1),
				'seo_analytics' => array('action' => 'create', 'link' => 'admin/seo/analytics', 'status' => 1),
				'seo_tracker' => array('action' => 'create', 'link' => 'admin/seo/tracker', 'status' => 1),
				'seo_robots' => array('action' => 'create', 'link' => 'admin/seo/robots', 'status' => 1)
			)
		)
	);
	
	/**
	 * Ausers configuration
	 * 
	 * @var array
	 */
	protected $ausers_methods = array(
		array('module' => 'seo', 'method' => 'index', 'is_default' => 1),
		array('module' => 'seo', 'method' => 'default_listing', 'is_default' => 0),
		array('module' => 'seo', 'method' => 'listing', 'is_default' => 0),
		array('module' => 'seo', 'method' => 'analytics', 'is_default' => 0),
		array('module' => 'seo', 'method' => 'tracker', 'is_default' => 0),
		array('module' => 'seo', 'method' => 'robots', 'is_default' => 0),
	);
	
	/**
	 * Cronjobs configuration
	 * 
	 * @var array
	 */
	protected $cronjobs = array(
		array(
			"name" => "Sitemap generation",
			"module" => "seo",
			"model" => "Seo_model",
			"method" => "generate_sitemap_xml_cron",
			"cron_tab" => "0 */3 * * *",
			"status" => "1",
		),
	);

	/**
	 * Constructor
	 *
	 * @return Seo_install_model
	 */
	public function Seo_install_model(){
		parent::Model();
		$this->CI = & get_instance();
	}

	/**
	 * Install data of menu module
	 * 
	 * @var array
	 */
	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	/**
	 * Import languages of menu module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return boolean
	 */
	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('seo', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	/**
	 * Export languages of menu module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
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

	/**
	 * Uninstall data of menu module
	 * 
	 * @return void
	 */
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

	/**
	 * Install data of ausers module
	 * 
	 * @return void
	 */
	public function install_ausers() {
		// install ausers permissions
		$this->CI->load->model('Ausers_model');

		foreach($this->ausers_methods as $method){
			$this->CI->Ausers_model->save_method(null, $method);
		}
	}

	/**
	 * Import languages of ausers module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return boolean
	 */
	public function install_ausers_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('seo', 'ausers', $langs_ids);

		// install ausers permissions
		$this->CI->load->model('Ausers_model');
		$params['where']['module'] = 'seo';
		$methods = $this->CI->Ausers_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Ausers_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
		
		return true;
	}

	/**
	 * Import languages of ausers module
	 * 
	 * @param array $langs_ids languages identifiers
	 * @return array
	 */
	public function install_ausers_lang_export($langs_ids) {
		$this->CI->load->model('Ausers_model');
		$params['where']['module'] = 'seo';
		$methods =  $this->CI->Ausers_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('ausers' => $return);
	}

	/**
	 * Uninstall data of ausers module
	 * 
	 * @return void
	 */
	public function deinstall_ausers() {
		// delete moderation methods in ausers
		$this->CI->load->model('Ausers_model');
		$params['where']['module'] = 'seo';
		$this->CI->Ausers_model->delete_methods($params);
	}
	
	/**
	 * Install data of cronjobs module
	 * 
	 * @return void
	 */
	public function install_cronjob(){
		////// add lift up cronjob
		$this->CI->load->model('Cronjob_model');
		foreach((array)$this->cronjobs as $cron_data){
			$validation_data = $this->CI->Cronjob_model->validate_cron(null, $cron_data);
			if(!empty($validation_data['errors'])) continue;
			$this->CI->Cronjob_model->save_cron(null, $validation_data['data']);
		}
	}
	
	/**
	 * Uninstall data of cronjobs module
	 * 
	 * @return void
	 */
	public function deinstall_cronjob(){
		$this->CI->load->model('Cronjob_model');
		$cron_data = array();
		$cron_data["where"]["module"] = "seo";
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}

	/**
	 * Install module data
	 * 
	 * @return void
	 */
	public function _arbitrary_installing(){
		$this->CI->load->model('Seo_model');
		$content = "User-agent: *\n";
		$content .= "Disallow: /admin/\n";
		$content .= "Sitemap: ".site_url()."sitemap.xml\n";
		$this->CI->Seo_model->set_robots_content($content);
		
		// Update file config/langs_route.php
		$lang_dm_data = array(
			'module' => 'seo',
			'model' => 'Seo_model',
			'method_add' => 'lang_dedicate_module_callback_add',
			'method_delete' => 'lang_dedicate_module_callback_delete'
		);
		$this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);
		$this->CI->Seo_model->lang_dedicate_module_callback_add();
	}

	/**
	 * Uninstall module data
	 * 
	 * @return void
	 */
	public function _arbitrary_deinstalling(){
		/// delete entries in dedicate modules
		$lang_dm_data['where'] = array( 'module' => 'seo', 'model' => 'Seo_model');
		$this->CI->pg_language->delete_dedicate_modules_entry($lang_dm_data);
	}
}
