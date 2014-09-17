<?php
/**
* Users lists install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:50:07 +0400 $
**/
class Users_lists_install_model extends Model
{
	private $CI;
	private $menu = array(
		'user_top_menu' => array(
			'action' => 'none',
			'items' => array(
				'user-menu-people' => array(
					'action' => 'none',
					'items' => array(
						'friendlist_item' => array('action' => 'create', 'link' => 'users_lists/friendlist', 'status' => 1, 'sorter' => 15),
						'blacklist_item' => array('action' => 'create', 'link' => 'users_lists/blacklist', 'status' => 1, 'sorter' => 16),
					)
				)
			)
		),
	);
	private $wall_events_types = array(
		'friend_add',
		'friend_del'	
	);
	private $notifications = array(
		'notifications' => array(
			array('gid' => 'friends_request', 'send_type' => 'simple'),
		),
		'templates' => array(
			array('gid' => 'friends_request', 'name' => 'Friends request', 'vars' => array('fname', 'sname', 'user', 'comment'), 'content_type' => 'text'),
		),
	);


	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
	}
	
	/**
	 * Check system requirements of module
	 */
	function _validate_requirements(){
		$result = array("data"=>array(), "result" => true);

		//check for Mbstring
		$good			= function_exists("mb_substr");
		$result["data"][] = array(
			"name" => "Mbstring extension (required for feeds parsing) is installed",
			"value" => $good?"Yes":"No",
			"result" => $good,
		);
		$result["result"] = $result["result"] && $good;

		return $result;
	}

	public function install_menu() {
		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data['action'], $menu_data['name']);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]['items']);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('users_lists', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]['items'], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( 'menu' => $return );
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
	
	public function install_wall_events(){
		$this->CI->load->model('Users_lists_model');
		$this->CI->load->model('wall_events/models/Wall_events_types_model');
		foreach($this->CI->Users_lists_model->wall_events as $wall_event){
			$attrs = array(
				'gid' => $wall_event['gid'],
				'status' => '1',
				'module' => 'users_lists',
				'model' => 'users_lists_model',
				'method_format_event' => '_format_wall_events',
				'date_add' => date("Y-m-d H:i:s"),
				'date_update' => date("Y-m-d H:i:s"),
				'settings' => $wall_event['settings']
			);
			$this->CI->Wall_events_types_model->add_wall_events_type($attrs);
		}

		return;
	}
	
	public function install_wall_events_lang_update($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('users_lists', 'wall_events', $langs_ids);

		if(!$langs_file) {
			log_message('info', 'Empty moderation langs data');
			return false;
		}
		$this->CI->load->model('wall_events/models/Wall_events_types_model');
		$this->CI->Wall_events_types_model->update_langs($this->wall_events_types, $langs_file);
	}

	public function install_wall_events_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('wall_events/models/Wall_events_types_model');
		return array('wall_events' => $this->CI->Wall_events_types_model->export_langs($this->wall_events_types, $langs_ids));
	}

	public function deinstall_wall_events(){
		$this->CI->load->model('Users_lists_model');
		$this->CI->load->model('wall_events/models/Wall_events_types_model');
		foreach($this->CI->Users_lists_model->wall_events as $wall_event){
			$this->CI->Wall_events_types_model->delete_wall_events_type($wall_event['gid']);
		}
	}
	
	public function install_site_map(){
		$this->CI->load->model('Site_map_model');
		$site_map_data = array(
			'module_gid' => 'users_lists',
			'model_name' => 'Users_lists_model',
			'get_urls_method' => 'get_sitemap_urls',
		);
		$this->CI->Site_map_model->set_sitemap_module('users_lists', $site_map_data);
	}

	public function deinstall_site_map(){
		$this->CI->load->model('Site_map_model');
		$this->CI->Site_map_model->delete_sitemap_module('users_lists');
	}

	public function install_banners(){
		$this->CI->load->model("banners/models/Banner_group_model");
		$this->CI->load->model('Users_lists_model');
		$this->CI->Banner_group_model->set_module("users_lists", "Users_lists_model", "_banner_available_pages");
		$group_id = $this->CI->Banner_group_model->get_group_id_by_gid('users_groups');
		$pages = $this->CI->Users_lists_model->_banner_available_pages();
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

	public function deinstall_banners(){
		$this->CI->load->model("banners/models/Banner_group_model");
		$this->CI->Banner_group_model->delete_module("users_lists");
		$group_id = $this->CI->Banner_group_model->get_group_id_by_gid('users_groups');
		$this->CI->Banner_group_model->delete($group_id);
	}
	
	public function install_notifications(){
		$this->CI->load->model('Notifications_model');
		$this->CI->load->model('notifications/models/Templates_model');

		foreach($this->notifications['templates'] as $tpl){
			$template_data = array(
				'gid' => $tpl['gid'],
				'name' => $tpl['name'],
				'vars' => serialize($tpl['vars']),
				'content_type' => $tpl['content_type'],
				'date_add' =>  date('Y-m-d H:i:s'),
				'date_update' => date('Y-m-d H:i:s'),
			);
			$tpl_ids[$tpl['gid']] = $this->CI->Templates_model->save_template(null, $template_data);
		}

		foreach($this->notifications['notifications'] as $notification){
			$notification_data = array(
				'gid' => $notification['gid'],
				'send_type' => $notification['send_type'],
				'id_template_default' => $tpl_ids[$notification['gid']],
				'date_add' => date("Y-m-d H:i:s"),
				'date_update' => date("Y-m-d H:i:s"),
			);
			$this->CI->Notifications_model->save_notification(null, $notification_data);
		}
	}
	
	public function install_notifications_lang_update($langs_ids = null){
		if(empty($langs_ids)) return false;
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Notifications_model');

		$langs_file = $this->CI->Install_model->language_file_read('users_lists', 'notifications', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty notifications langs data'); return false; }

		$this->CI->Notifications_model->update_langs($this->notifications, $langs_file, $langs_ids);
		return true;
	}
	
	public function install_notifications_lang_export($langs_ids = null){
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Notifications_model');
		$langs = $this->CI->Notifications_model->export_langs($this->notifications, $langs_ids);
		return array('notifications' => $langs);
	}
	
	public function deinstall_notifications(){
		$this->CI->load->model('Notifications_model');
		$this->CI->load->model('notifications/models/Templates_model');
		foreach($this->notifications['templates'] as $tpl){
			$this->CI->Templates_model->delete_template_by_gid($tpl['gid']);
		}
		foreach($this->notifications['notifications'] as $notification){
			$this->CI->Notifications_model->delete_notification_by_gid($notification['gid']);
		}
	}
	
	public function _arbitrary_installing(){
		///// seo
		$seo_data = array(
			'module_gid' => 'users_lists',
			'model_name' => 'Users_lists_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('users_lists', $seo_data);
	}
	
	/**
	 * Import module languages
	 * 
	 * @param array $langs_ids array languages identifiers
	 * @return void
	 */
	public function _arbitrary_lang_install($langs_ids=null){
		$langs_file = $this->CI->Install_model->language_file_read("users_lists", "arbitrary", $langs_ids);
		if(!$langs_file){log_message("info", "Empty users_lists arbitrary langs data"); return false;}
		
		$post_data = array(
			"title" => $langs_file["seo_tags_friendlist_title"],
			"keyword" => $langs_file["seo_tags_friendlist_keyword"],
			"description" => $langs_file["seo_tags_friendlist_description"],
			"header" => $langs_file["seo_tags_friendlist_header"],
			"og_title" => $langs_file["seo_tags_friendlist_og_title"],
			"og_type" => $langs_file["seo_tags_friendlist_og_type"],
			"og_description" => $langs_file["seo_tags_friendlist_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users_lists", "friendlist", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_blacklist_title"],
			"keyword" => $langs_file["seo_tags_blacklist_keyword"],
			"description" => $langs_file["seo_tags_blacklist_description"],
			"header" => $langs_file["seo_tags_blacklist_header"],
			"og_title" => $langs_file["seo_tags_blacklist_og_title"],
			"og_type" => $langs_file["seo_tags_blacklist_og_type"],
			"og_description" => $langs_file["seo_tags_blacklist_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users_lists", "blacklist", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_friends_requests_title"],
			"keyword" => $langs_file["seo_tags_friends_requests_keyword"],
			"description" => $langs_file["seo_tags_friends_requests_description"],
			"header" => $langs_file["seo_tags_friends_requests_header"],
			"og_title" => $langs_file["seo_tags_friends_requests_og_title"],
			"og_type" => $langs_file["seo_tags_friends_requests_og_type"],
			"og_description" => $langs_file["seo_tags_friends_requests_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users_lists", "friends_requests", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_friends_invites_title"],
			"keyword" => $langs_file["seo_tags_friends_invites_keyword"],
			"description" => $langs_file["seo_tags_friends_invites_description"],
			"header" => $langs_file["seo_tags_friends_invites_header"],
			"og_title" => $langs_file["seo_tags_friends_invites_og_title"],
			"og_type" => $langs_file["seo_tags_friends_invites_og_type"],
			"og_description" => $langs_file["seo_tags_friends_invites_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users_lists", "friends_invites", $post_data);
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
		$settings = $this->CI->pg_seo->get_settings("user", "users_lists", "friendlist", $langs_ids);
		$arbitrary_return["seo_tags_friendlist_title"] = $settings["title"];
		$arbitrary_return["seo_tags_friendlist_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_friendlist_description"] = $settings["description"];
		$arbitrary_return["seo_tags_friendlist_header"] = $settings["header"];
		$arbitrary_return["seo_tags_friendlist_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_friendlist_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_friendlist_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users_lists", "blacklist", $langs_ids);
		$arbitrary_return["seo_tags_blacklist_title"] = $settings["title"];
		$arbitrary_return["seo_tags_blacklist_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_blacklist_description"] = $settings["description"];
		$arbitrary_return["seo_tags_blacklist_header"] = $settings["header"];
		$arbitrary_return["seo_tags_blacklist_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_blacklist_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_blacklist_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users_lists", "friends_requests", $langs_ids);
		$arbitrary_return["seo_tags_friends_requests_title"] = $settings["title"];
		$arbitrary_return["seo_tags_friends_requests_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_friends_requests_description"] = $settings["description"];
		$arbitrary_return["seo_tags_friends_requests_header"] = $settings["header"];
		$arbitrary_return["seo_tags_friends_requests_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_friends_requests_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_friends_requests_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users_lists", "friends_invites", $langs_ids);
		$arbitrary_return["seo_tags_friends_invites_title"] = $settings["title"];
		$arbitrary_return["seo_tags_friends_invites_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_friends_invites_description"] = $settings["description"];
		$arbitrary_return["seo_tags_friends_invites_header"] = $settings["header"];
		$arbitrary_return["seo_tags_friends_invites_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_friends_invites_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_friends_invites_og_description"] = $settings["og_description"];

		return array("arbitrary" => $arbitrary_return);
	}

	public function _arbitrary_deinstalling(){
		
	}
}
