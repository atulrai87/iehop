<?php
/**
* Content install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
class Content_install_model extends Model
{
	private $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'content_items' => array(
							'action'=>'none',
							'items' => array(
								'content_menu_item' => array('action' => 'create', 'link' => 'admin/content', 'status' => 1, 'sorter' => 3),
								'promo_menu_item' => array('action' => 'create', 'link' => 'admin/content/promo', 'status' => 1, 'sorter' => 4)
							)
						)
					)
				)
			)
		),

		'user_footer_menu' => array(
			'action' => 'none',
			'items' => array(
				'footer-menu-policy-item' => array(
					'action' => 'none',
					'items' => array(
						'footer-menu-privacy-item' => array('action' => 'create', 'link' => 'content/view/privacy-and-security', 'status' => 1, 'sortet' => 1),
						'footer-menu-terms-item' => array('action' => 'create', 'link' => 'content/view/legal-terms', 'status' => 1, 'sortet' => 2)
					)
				),
				'footer-menu-about-item' => array(
					'action' => 'none',
					'items' => array(
						'footer-menu-about-us-item' => array('action' => 'create', 'link' => 'content/view/about_us', 'status' => 1, 'sortet' => 1)
					)
				)
			)
		)
	);
	private $ausers_methods = array(
		array('module' => 'content', 'method' => 'index', 'is_default' => 1),
		array('module' => 'content', 'method' => 'promo', 'is_default' => 0)
	);
	private $dynamic_blocks = array(
		array(
			'gid'		=> 'info_pages',
			'module'	=> 'content',
			'model'		=> 'Content_model',
			'method'	=> '_dynamic_block_get_info_pages',
			'params'	=> array(
				'keyword' => array('gid'=>'keyword', 'type'=>'string', 'default'=>''),
				'transparent' => array('gid'=>'transparent', 'type'=>'checkbox', 'default'=>'1'),
				'show_subsections' => array('gid'=>'show_subsections', 'type'=>'checkbox', 'default'=>''),
				'trim_subsections_text' => array('gid'=>'trim_subsections_text', 'type'=>'checkbox', 'default'=>'1'),
			),
			'views'		=> array(array('gid'=>'default')),
			'area'		=> array(
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"0";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 14,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lavender',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"0";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 14,
					'cache_time' => 0,
				),
				array(
					'gid' => 'community',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 6,
					'cache_time' => 0,
				),
				array(
					'gid' => 'christian',
					'params' => 'a:4:{s:7:"keyword";s:8:"about_us";s:11:"transparent";s:1:"1";s:16:"show_subsections";s:1:"0";s:21:"trim_subsections_text";s:1:"1";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 11,
					'cache_time' => 0,
				),
			),
		),
		array(
			'gid'		=> 'content_promo',
			'module'	=> 'content',
			'model'		=> 'Content_promo_model',
			'method'	=> '_dynamic_block_get_content_promo',
			'params'	=> array(),
			'views'		=> array(array('gid'=>'default')),
			'area'		=> array(
				array(
					'gid' => 'girlfriends',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 4,
					'cache_time' => 0,
				)
			),
		),
	);

	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	public function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
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
		$langs_file = $this->CI->Install_model->language_file_read('content', 'menu', $langs_ids);

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
		///// site map
		$this->CI->load->model('Site_map_model');
		$site_map_data = array(
			'module_gid' => 'content',
			'model_name' => 'Content_model',
			'get_urls_method' => 'get_sitemap_urls',
		);
		$this->CI->Site_map_model->set_sitemap_module('content', $site_map_data);
	}

	public function install_banners() {
		///// add banners module
		$this->CI->load->model('Content_model');
		$this->CI->load->model('banners/models/Banner_group_model');
		$this->CI->load->model('banners/models/Banner_place_model');
		$this->CI->Banner_group_model->set_module("content", "Content_model", "_banner_available_pages");

		$group_id = $this->CI->Banner_group_model->get_group_id_by_gid('content_groups');
		///add pages in group
		$pages = $this->CI->Content_model->_banner_available_pages();
		if ($pages){
			foreach($pages  as $key => $value){
				$page_attrs = array(
					'group_id' => $group_id,
					'name' => $value['name'],
					'link' => $value['link'],
				);
				$this->CI->Banner_group_model->add_page($page_attrs);
			}
		}
	}

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
		$langs_file = $this->CI->Install_model->language_file_read('content', 'ausers', $langs_ids);

		// install ausers permissions
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'content';
		$methods = $this->CI->Ausers_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Ausers_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_ausers_lang_export($langs_ids) {
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'content';
		$methods =  $this->CI->Ausers_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('ausers' => $return);
	}

	public function deinstall_ausers() {
		// delete moderation methods in ausers
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'contents';
		$this->CI->Ausers_model->delete_methods($params);
	}

	public function install_uploads() {
		///// add uploads configs for promo
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = array(
			'gid' => 'promo-content-img',
			'name' => 'Promo content image',
			'max_height' => 4000,
			'max_width' => 4000,
			'max_size' => 10000000,
			'name_format' => 'generate',
			'file_formats' => 'a:3:{i:0;s:3:"jpg";i:1;s:3:"gif";i:2;s:3:"png";}',
			'default_img' => '',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_config(null, $config_data);
	}

	public function install_file_uploads() {
		$this->CI->load->model('file_uploads/models/File_uploads_config_model');
		$config_data = array(
			'gid' => 'promo-content-flash',
			'name' => 'Promo content flash',
			'max_size' => 1000000,
			'name_format' => 'generate',
			'file_formats' => 'a:1:{i:0;s:3:"swf";}',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->File_uploads_config_model->save_config(null, $config_data);
	}

	public function install_social_networking() {
		///// add social netorking page
		$this->CI->load->model('social_networking/models/Social_networking_pages_model');
		$page_data = array(
			'controller' => 'content',
			'method' => 'view',
			'name' => 'View content page',
			'data' => 'a:3:{s:4:"like";a:3:{s:8:"facebook";s:2:"on";s:9:"vkontakte";s:2:"on";s:6:"google";s:2:"on";}s:5:"share";a:4:{s:8:"facebook";s:2:"on";s:9:"vkontakte";s:2:"on";s:8:"linkedin";s:2:"on";s:7:"twitter";s:2:"on";}s:8:"comments";s:1:"1";}',
		);
		$this->CI->Social_networking_pages_model->save_page(null, $page_data);
	}

	public function _arbitrary_installing(){
		///// seo
		$seo_data = array(
			'module_gid' => 'content',
			'model_name' => 'Content_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('content', $seo_data);

		///// add entries for lang data updates
		$lang_dm_data = array(
			'module' => 'content',
			'model' => 'Content_promo_model',
			'method_add' => 'lang_dedicate_module_callback_add',
			'method_delete' => 'lang_dedicate_module_callback_delete'
		);
		$this->CI->pg_language->add_dedicate_modules_entry($lang_dm_data);

		$this->CI->load->model("content/models/Content_promo_model");
		foreach ($this->CI->pg_language->languages as $id => $value){
			$this->CI->Content_promo_model->lang_dedicate_module_callback_add($value['id']);
		}
		$this->add_demo_content();
	}

	public function add_demo_content() {
		$this->CI->load->model('Content_model');
		$demo_content = include MODULEPATH . 'content/install/demo_content.php';

		// Associating languages id with codes
		foreach($this->CI->pg_language->languages as $l) {
			$lang[$l['code']] = $l['id'];
		}
		foreach($demo_content as $content) {
			if(empty($lang[$content['lang_code']])) {
				continue;
			}
			// Replace language code with ID
			$content['lang_id'] = $lang[$content['lang_code']];
			unset($content['lang_code']);
			$this->CI->Content_model->save_page(null, $content);
		}
		return true;
	}

	public function _arbitrary_lang_install($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('content', 'demo', $langs_ids);

		if(!$langs_file) {
			log_message('info', 'Empty arbitrary langs data');
			return false;
		}

		$this->CI->load->model("content/models/Content_promo_model");
		foreach($langs_ids as $lang_id){
			$promo["promo_text"] = $langs_file["content"][$lang_id];
			$this->CI->Content_promo_model->save_promo($lang_id, $promo);
		}
		
		$langs_file = $this->CI->Install_model->language_file_read("content", "arbitrary", $langs_ids);
		if(!$langs_file){log_message("info", "Empty content arbitrary langs data"); return false;}
		
		$post_data = array(
			"title" => $langs_file["seo_tags_index_title"],
			"keyword" => $langs_file["seo_tags_index_keyword"],
			"description" => $langs_file["seo_tags_index_description"],
			"header" => $langs_file["seo_tags_index_header"],
			"og_title" => $langs_file["seo_tags_index_og_title"],
			"og_type" => $langs_file["seo_tags_index_og_type"],
			"og_description" => $langs_file["seo_tags_index_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "content", "index", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_view_title"],
			"keyword" => $langs_file["seo_tags_view_keyword"],
			"description" => $langs_file["seo_tags_view_description"],
			"header" => $langs_file["seo_tags_view_header"],
			"og_title" => $langs_file["seo_tags_view_og_title"],
			"og_type" => $langs_file["seo_tags_view_og_type"],
			"og_description" => $langs_file["seo_tags_view_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "content", "view", $post_data);
		
		return;
	}

	public function _arbitrary_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model("content/models/Content_promo_model");

		foreach($langs_ids as $lang_id){
			$promo = $this->CI->Content_promo_model->get_promo($lang_id);
			$langs["content"][$lang_id] = $promo["promo_text"];
		}
		
		$settings = $this->CI->pg_seo->get_settings("user", "contact_us", "index", $langs_ids);
		$arbitrary_return["seo_tags_index_title"] = $settings["title"];
		$arbitrary_return["seo_tags_index_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_index_description"] = $settings["description"];
		$arbitrary_return["seo_tags_index_header"] = $settings["header"];
		$arbitrary_return["seo_tags_index_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_index_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_index_og_description"] = $settings["og_description"];
		
		return array('demo' => $langs, "arbitrary" => $arbitrary_return);
	}


	public function deinstall_site_map() {
		$this->CI->load->model('Site_map_model');
		$this->CI->Site_map_model->delete_sitemap_module('content');
	}

	public function deinstall_banners() {
		///// delete banners module
		$this->CI->load->model("banners/models/Banner_group_model");
		$this->CI->Banner_group_model->delete_module("content");
	}

	public function deinstall_uploads() {
		///// remove upload config
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = $this->CI->Uploads_config_model->get_config_by_gid('promo-content-img');
		if(!empty($config_data["id"])){
			$this->CI->Uploads_config_model->delete_config($config_data["id"]);
		}
	}

	public function deinstall_file_uploads() {
		$this->CI->load->model('file_uploads/models/File_uploads_config_model');
		$config_data = $this->CI->File_uploads_config_model->get_config_by_gid('promo-content-flash');
		if(!empty($config_data["id"])){
			$this->CI->File_uploads_config_model->delete_config($config_data["id"]);
		}
	}

	public function deinstall_social_networking() {
		///// delete social netorking page
		$this->CI->load->model('social_networking/models/Social_networking_pages_model');
		$this->CI->Social_networking_pages_model->delete_pages_by_controller('content');
	}

	function _arbitrary_deinstalling(){
		$this->CI->pg_seo->delete_seo_module('content');

		/// delete entries in dedicate modules
		$lang_dm_data['where'] = array(
			'module' => 'content',
			'model' => 'Content_promo_model',
		);
		$this->CI->pg_language->delete_dedicate_modules_entry($lang_dm_data);
	}

	public function install_dynamic_blocks() {
		$this->CI->load->model('Dynamic_blocks_model');
		foreach($this->dynamic_blocks as $block_data){
			$validate_data = $this->CI->Dynamic_blocks_model->validate_block(null, $block_data);
			if(!empty($validate_data['errors'])) {
				continue;
			}
			$id_block = $this->CI->Dynamic_blocks_model->save_block(null, $validate_data['data']);

			if(!empty($block_data['area'])) {
				foreach($block_data['area'] as $block_area) {
					$area = $this->CI->Dynamic_blocks_model->get_area_by_gid($block_area['gid']);
					$area_data = array(
						'id_area' => $area['id'],
						'id_block' => $id_block,
						'params' => $block_area['params'],
						'view_str' => $block_area['view_str'],
						'cache_time' => $block_area['cache_time'],
						'width' => $block_area['width'],
					);
					if(isset($block_area['sorter'])){
						$area_data['sorter'] = $block_area['sorter'];
					}
					$validate_data = $this->CI->Dynamic_blocks_model->validate_area_block($area_data, true);
					if(!empty($validate_data['errors'])) {
						continue;
					}
					$this->CI->Dynamic_blocks_model->save_area_block(null, $validate_data['data']);
				}
			}
		}
	}

	public function install_dynamic_blocks_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('content', 'dynamic_blocks', $langs_ids);
		if(!$langs_file) { log_message('info', 'Empty dynamic_blocks langs data'); return false; }
		$this->CI->load->model('Dynamic_blocks_model');
		$data = array();
		foreach($this->dynamic_blocks as $block_data){
			if($block = $this->CI->Dynamic_blocks_model->get_block_by_gid($block_data['gid'])){
				$data[] = $block;
			}
		}
		$this->CI->Dynamic_blocks_model->update_langs($data, $langs_file, $langs_ids);
	}

	public function install_dynamic_blocks_lang_export($langs_ids = null) {
		$this->CI->load->model('Dynamic_blocks_model');
		$data = array();
		foreach($this->dynamic_blocks as $block_data){
			if($block = $this->CI->Dynamic_blocks_model->get_block_by_gid($block_data['gid'])){
				$data[] = $block;
			}
		}
		$langs = $this->CI->Dynamic_blocks_model->export_langs($data, $langs_ids);
		return array('dynamic_blocks' => $langs);
	}

	public function deinstall_dynamic_blocks(){
		$this->CI->load->model('Dynamic_blocks_model');
		foreach($this->dynamic_blocks as $block) {
			$this->CI->Dynamic_blocks_model->delete_block_by_gid($block['gid']);
		}
	}

}
