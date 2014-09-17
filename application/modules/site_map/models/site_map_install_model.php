<?php
/**
* Site map install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Site_map_install_model extends Model
{
	var $CI;
	private $menu = array(
		'user_footer_menu' => array(
			'action' => 'none',
			'items' => array(
				'footer-menu-help-item' => array(
					'action'=>'none',
					'items' => array(
						'footer-menu-map-item' => array('action' => 'create', 'link' => 'site_map/', 'status' => 1, 'sorter' => 2)
					)
				)
			)
		)
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Site_map_install_model()
	{
		parent::Model();
		$this->CI = & get_instance();
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
		$langs_file = $this->CI->Install_model->language_file_read('site_map', 'menu', $langs_ids);

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

	function _arbitrary_installing(){
		$seo_data = array(
			'module_gid' => 'site_map',
			'model_name' => 'Site_map_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('site_map', $seo_data);
	}
	
	/**
	 * Import module languages
	 * 
	 * @param array $langs_ids array languages identifiers
	 * @return void
	 */
	public function _arbitrary_lang_install($langs_ids=null){
		$langs_file = $this->CI->Install_model->language_file_read("site_map", "arbitrary", $langs_ids);
		if(!$langs_file){log_message("info", "Empty site_map arbitrary langs data"); return false;}
		
		$post_data = array(
			"title" => $langs_file["seo_tags_index_title"],
			"keyword" => $langs_file["seo_tags_index_keyword"],
			"description" => $langs_file["seo_tags_index_description"],
			"header" => $langs_file["seo_tags_index_header"],
			"og_title" => $langs_file["seo_tags_index_og_title"],
			"og_type" => $langs_file["seo_tags_index_og_type"],
			"og_description" => $langs_file["seo_tags_index_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "site_map", "index", $post_data);
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
		$settings = $this->CI->pg_seo->get_settings("user", "site_map", "index", $langs_ids);
		$arbitrary_return["seo_tags_index_title"] = $settings["title"];
		$arbitrary_return["seo_tags_index_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_index_description"] = $settings["description"];
		$arbitrary_return["seo_tags_index_header"] = $settings["header"];
		$arbitrary_return["seo_tags_index_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_index_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_index_og_description"] = $settings["og_description"];

		return array("arbitrary" => $arbitrary_return);
	}

	function _arbitrary_deinstalling(){
		$this->CI->pg_seo->delete_seo_module('site_map');
	}

}
