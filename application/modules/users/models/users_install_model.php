<?php
/**
* Users install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Users_install_model extends Model
{
	public $CI;
	private $_menu = array(
		'guest_main_menu' => array(
			'action' => 'none',
			'items' => array(
				'main-menu-registration-item' => array('action' => 'create', 'link' => 'users/registration', 'status' => 1, 'sorter' => 1),
			)
		),
		'user_top_menu' => array(
			'action' => 'none',
			'items' => array(
				'user-menu-people' => array(
					'action' => 'none',
					'items' => array(
						'search_item' => array('action' => 'create', 'link' => 'users/search', 'status' => 1, 'sorter' => 10),
						'perfect_match_item' => array('action' => 'create', 'link' => 'users/perfect_match', 'status' => 1, 'sorter' => 20),
						'visits_item' => array('action' => 'create', 'link' => 'users/my_visits', 'status' => 1, 'sorter' => 30),
					)
				)
			)
		),
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'main_items' => array(
					'action' => 'none',
					'items' => array(
						'users_menu_item' => array('action' => 'create', 'link' => 'admin/users', 'status' => 1, 'sorter' => 2)
					)
				)
			)
		),
		'admin_users_menu' => array(
			'action' => 'create',
			'name' => 'Users section menu',
			'items' => array(
				'users_list_item' => array('action' => 'create', 'link' => 'admin/users', 'status' => 1),
				'groups_list_item' => array('action' => 'create', 'link' => 'admin/users/groups', 'status' => 1)
			)
		),
		'settings_menu'	=> array(
			'name' => 'Settings menu',
			"action" => "none",
			"items" => array(
				'my-profile-item' => array('action' => 'create', 'link' => 'users/profile', 'status' => 1, 'sorter' => 20),
				'account-item' => array("action" => "create", 'link' => 'users/account', 'status' => 1, 'sorter' => 30),
				'settings-item' => array("action" => "create", 'link' => 'users/settings', 'status' => 1, 'sorter' => 40),
				'logout-item' => array("action" => "create", 'link' => 'users/logout', 'status' => 1, 'sorter' => 50),
			),
		),
	);
	private $_ausers_methods = array(
		array('module' => 'users', 'method' => 'index', 'is_default' => 1),
	);
	private $_notifications = array(
		'notifications' => array(
			array('gid' => 'users_fogot_password', 'send_type' => 'simple'),
			array('gid' => 'users_change_password', 'send_type' => 'simple'),
			array('gid' => 'users_change_email', 'send_type' => 'simple'),
			array('gid' => 'users_registration', 'send_type' => 'simple')
		),
		'templates' => array(
			array('gid' => 'users_fogot_password', 'name' => 'Forgot password mail', 'vars' => array('password', 'email', 'fname', 'sname'), 'content_type' => 'text'),
			array('gid' => 'users_change_password', 'name' => 'Change password notification', 'vars' => array('password', 'email', 'fname', 'sname', 'nickname'), 'content_type' => 'text'),
			array('gid' => 'users_change_email', 'name' => 'Email changed', 'vars' => array('password', 'email', 'fname', 'sname', 'nickname'), 'content_type' => 'text'),
			array('gid' => 'users_registration', 'name' => 'User registration letter', 'vars' => array('password', 'email', 'fname', 'sname', 'nickname', 'confirm_code', 'confirm_block'), 'content_type' => 'text')
		),
	);
	private $_moderation_types = array(
		array(
			"name" => "user_logo",
			"mtype" => "0",
			"module" => "users",
			"model" => "Users_model",
			"check_badwords" => "0",
			"method_get_list" => "_moder_get_list",
			"method_set_status" => "_moder_set_status",
			"method_delete_object" => "",
			"allow_to_decline" => "1",
			"template_list_row" => "moder_block",
		)
	);
	private $_lang_services = array(
		'service' => array('user_activate_in_search', 'users_featured', 'admin_approve', 'hide_on_site', 'highlight_in_search', 'up_in_search', 'ability_delete', 'region_leader'),
		'service_description' => array('user_activate_in_search_description', 'users_featured_description', 'admin_approve_description', 'hide_on_site_description', 'highlight_in_search_description', 'up_in_search_description', 'ability_delete_description', 'region_leader_description'),
		'template' => array('user_activate_in_search_template', 'users_featured_template', 'admin_approve_template', 'hide_on_site_template', 'highlight_in_search_template', 'up_in_search_template', 'ability_delete_template', 'region_leader_template'),
		'param' => array(
			'user_activate_in_search_template' => array('period'),
			'users_featured_template' => array('period'),
			'hide_on_site_template' => array('period'),
			'highlight_in_search_template' => array('period'),
			'up_in_search_template' => array('period'),
			'region_leader_template' => array('min_bid', 'write_off_amount', 'write_off_period'),
		),
		'user_param' => array(
			'region_leader_template' => array('text')
		)
	);
	
	/**
	 * Spam configuration
	 * @var array
	 */
	private $spam = array(
		array("gid"=>"users_object", "form_type"=>"select_text", "send_mail"=>true, "status"=>true, "module"=>"users", "model"=>"Users_model", "callback"=>"spam_callback"),
	);
	
	private $_geomap = array(
		array(
			'map_settings' => array(
				'use_type_selector' => 1,
				'use_panorama' => 1,
				'use_router' => 0,
				'use_searchbox' => 1,
				'use_search_radius' => 1,
				'use_search_auto' => 1,
				'use_show_details' => 1,
				'use_amenities' => 1,
				'amenities' => array(),
			),
			'settings' => array(
				'map_gid' => 'googlemapsv3',
				'id_user' => 0,
				'id_object' => 0,
				'gid' => 'profile_view',
			)
		),
		array(
			'map_settings' => array(
				'use_type_selector' => 1,
				'use_router' => 0,
				'use_searchbox' => 1,
				'use_tools' => 1,
				'use_clusterer' => 0,
				'use_click_zoom' => 1,
			),
			'settings' => array(
				'map_gid' => 'yandexmapsv2',
				'id_user' => 0,
				'id_object' => 0,
				'gid' => 'profile_view',
			)
		),
		array(
			'map_settings' => array(
				'use_type_selector' => 1,
				'use_router' => 0,
				'use_searchbox' => 1,
			),
			'settings' => array(
				'map_gid' => 'bingmapsv7',
				'id_user' => 0,
				'id_object' => 0,
				'gid' => 'profile_view',
			)
		),
	);
	private $_dynamic_blocks = array(
		array(
			'gid' => 'registration_login_form',
			'module' => 'users',
			'model' => 'Users_model',
			'method' => '_dynamic_block_get_registration_login_form',
			'params' => array(),
			'views' => array(
				array('gid'=>'registration_form'),
				array('gid'=>'login_form'),
			),
			'area' => array(
				array(
					'gid' => 'mediumturquoise',
					'params' => 'b:0;',
					'view_str' => 'login_form',
					'width' => 30,
					'sorter' => 6,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lavender',
					'params' => 'b:0;',
					'view_str' => 'login_form',
					'width' => 30,
					'sorter' => 6,
					'cache_time' => 0,
				),
				array(
					'gid' => 'girls',
					'params' => 'b:0;',
					'view_str' => 'registration_form',
					'width' => 30,
					'sorter' => 6,
					'cache_time' => 0,
				),
				array(
					'gid' => 'neighbourhood',
					'params' => 'a:0:{}',
					'view_str' => 'registration_form',
					'width' => 30,
					'sorter' => 5,
					'cache_time' => 0,
				),
				array(
					'gid' => 'companions',
					'params' => 'a:0:{}',
					'view_str' => 'registration_form',
					'width' => 30,
					'sorter' => 6,
					'cache_time' => 0,
				),
			),
		),
		array(
			'gid' => 'new_users',
			'module' => 'users',
			'model' => 'Users_model',
			'method' => '_dynamic_block_get_new_users',
			'params' => array(
				'title' => array('gid'=>'title', 'type'=>'text', 'default'=>''),
				'count' => array('gid'=>'count', 'type'=>'int', 'default'=>8),
				'user_type' => array('gid'=>'user_type', 'type'=>'int', 'default'=>0),
			),
			'views' => array(
				array('gid'=>'big_thumbs'),
				array('gid'=>'medium_thumbs'),
				array('gid'=>'small_thumbs'),
				array('gid'=>'small_thumbs_w_descr'),
				array('gid'=>'carousel'),
				array('gid'=>'carousel_w_descr'),
			),
			'area' => array(
				array(
					'gid' => 'index-page',
					'params' => 'a:4:{s:7:"title_1";s:0:"";s:7:"title_2";s:0:"";s:5:"count";s:1:"4";s:9:"user_type";s:1:"0";}',
					'view_str' => 'medium_thumbs',
					'width' => 100,
					'sorter' => 10,
					'cache_time' => 0,
				),
				array(
					'gid' => 'girlfriends',
					'params' => 'a:4:{s:7:"title_1";s:0:"";s:7:"title_2";s:0:"";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'carousel',
					'width' => 100,
					'sorter' => 5,
					'cache_time' => 0,
				),
				array(
					'gid' => 'jewish',
					'params' => 'a:4:{s:7:"title_1";s:0:"";s:7:"title_2";s:0:"";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'small_thumbs',
					'width' => 100,
					'sorter' => 6,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lovers',
					'params' => 'a:4:{s:7:"title_1";s:9:"New Users";s:7:"title_2";s:35:"Новые пользователи";s:5:"count";s:1:"6";s:9:"user_type";s:1:"0";}',
					'view_str' => 'big_thumbs',
					'width' => 100,
					'sorter' => 7,
					'cache_time' => 0,
				),
				array(
					'gid' => 'blackonwhite',
					'params' => 'a:4:{s:7:"title_1";s:9:"New Users";s:7:"title_2";s:35:"Новые пользователи";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'medium_thumbs',
					'width' => 100,
					'sorter' => 9,
					'cache_time' => 0,
				),
				array(
					'gid' => 'deepblue',
					'params' => 'a:4:{s:7:"title_1";s:9:"New Users";s:7:"title_2";s:35:"Новые пользователи";s:5:"count";s:1:"3";s:9:"user_type";s:1:"0";}',
					'view_str' => 'big_thumbs',
					'width' => 100,
					'sorter' => 11,
					'cache_time' => 0,
				),
				array(
					'gid' => 'companions',
					'params' => 'a:4:{s:7:"title_1";s:9:"New Users";s:7:"title_2";s:35:"Новые пользователи";s:5:"count";s:1:"6";s:9:"user_type";s:1:"0";}',
					'view_str' => 'small_thumbs_w_descr',
					'width' => 30,
					'sorter' => 8,
					'cache_time' => 0,
				),
				array(
					'gid' => 'community',
					'params' => 'a:4:{s:7:"title_1";s:0:"";s:7:"title_2";s:0:"";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'carousel',
					'width' => 100,
					'sorter' => 5,
					'cache_time' => 0,
				),
				array(
					'gid' => 'christian',
					'params' => 'a:4:{s:7:"title_1";s:9:"New Users";s:7:"title_2";s:35:"Новые пользователи";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'small_thumbs',
					'width' => 100,
					'sorter' => 9,
					'cache_time' => 0,
				),			
			),
		),
		array(
			'gid' => 'active_users',
			'module' => 'users',
			'model' => 'Users_model',
			'method' => '_dynamic_block_get_active_users',
			'params' => array(
				'title' => array('gid'=>'title', 'type'=>'text', 'default'=>'Last active users'),
				'count' => array('gid'=>'count', 'type'=>'int', 'default'=>8),
				'user_type' => array('gid'=>'user_type', 'type'=>'int', 'default'=>0),
			),
			'views' => array(
				array('gid'=>'big_thumbs'),
				array('gid'=>'medium_thumbs'),
				array('gid'=>'small_thumbs'),
				array('gid'=>'small_thumbs_w_descr'),
				array('gid'=>'carousel'),
				array('gid'=>'carousel_w_descr'),
			),
			'area' => array(
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:4:{s:7:"title_1";s:19:"Latest active users";s:7:"title_2";s:60:"Последние активные пользователи";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'small_thumbs',
					'width' => 100,
					'sorter' => 16,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lavender',
					'params' => 'a:4:{s:7:"title_1";s:19:"Latest active users";s:7:"title_2";s:60:"Последние активные пользователи";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'small_thumbs',
					'width' => 100,
					'sorter' => 16,
					'cache_time' => 0,
				),
				array(
					'gid' => 'deepblue',
					'params' => 'a:4:{s:7:"title_1";s:13:"Latest Active";s:7:"title_2";s:35:"Последние активные";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'carousel_w_descr',
					'width' => 100,
					'sorter' => 7,
					'cache_time' => 0,
				),
			),
		),
		array(
			'gid' => 'featured_users',
			'module' => 'users',
			'model' => 'Users_model',
			'method' => '_dynamic_block_get_featured_users',
			'params' => array(
				'title' => array('gid'=>'title', 'type'=>'text', 'default'=>'Featured users'),
				'count' => array('gid'=>'count', 'type'=>'int', 'default'=>8),
				'user_type' => array('gid'=>'user_type', 'type'=>'int', 'default'=>0),
			),
			'views' => array(
				array('gid'=>'big_thumbs'),
				array('gid'=>'medium_thumbs'),
				array('gid'=>'small_thumbs'),
				array('gid'=>'small_thumbs_w_descr'),
				array('gid'=>'carousel'),
				array('gid'=>'carousel_w_descr'),
			),
			'area' => array(
				array(
					'gid' => 'index-page',
					'params' => 'a:4:{s:7:"title_1";s:14:"Featured users";s:7:"title_2";s:51:"Рекомендуемые пользователи";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'small_thumbs',
					'width' => 100,
					'sorter' => 13,
					'cache_time' => 0,
				),
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:4:{s:7:"title_1";s:14:"Featured users";s:7:"title_2";s:43:"Избранные пользователи";s:5:"count";s:1:"4";s:9:"user_type";s:1:"0";}',
					'view_str' => 'medium_thumbs',
					'width' => 100,
					'sorter' => 13,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lavender',
					'params' => 'a:4:{s:7:"title_1";s:14:"Featured users";s:7:"title_2";s:43:"Избранные пользователи";s:5:"count";s:1:"4";s:9:"user_type";s:1:"0";}',
					'view_str' => 'medium_thumbs',
					'width' => 100,
					'sorter' => 13,
					'cache_time' => 0,
				),
				array(
					'gid' => 'girls',
					'params' => 'a:4:{s:7:"title_1";s:14:"Featured users";s:7:"title_2";s:51:"Рекомендуемые пользователи";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'small_thumbs',
					'width' => 100,
					'sorter' => 11,
					'cache_time' => 0,
				),
				array(
					'gid' => 'neighbourhood',
					'params' => 'a:4:{s:7:"title_1";s:14:"Featured users";s:7:"title_2";s:51:"Рекомендуемые пользователи";s:5:"count";s:1:"8";s:9:"user_type";s:1:"0";}',
					'view_str' => 'medium_thumbs',
					'width' => 100,
					'sorter' => 8,
					'cache_time' => 0,
				),
			),
		),
		array(
			'gid' => 'auth_links',
			'module' => 'users',
			'model' => 'Users_model',
			'method' => '_dynamic_block_get_auth_links',
			'params' => array(
				'right_align' => array('gid'=>'right_align', 'type'=>'checkbox', 'default'=>'1'),
			),
			'views' => array(
				array('gid'=>'default'),
			),
			'area' => array(
				array(
					'gid' => 'index-page',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 4,
					'cache_time' => 0,
				),
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 4,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lavender',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 4,
					'cache_time' => 0,
				),
				array(
					'gid' => 'girlfriends',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'jewish',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 30,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lovers',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 30,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'blackonwhite',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 4,
					'cache_time' => 0,
				),
				array(
					'gid' => 'deepblue',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 30,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'community',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 30,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'christian',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
			),
		),
		array(
			'gid' => 'lang_select',
			'module' => 'users',
			'model' => 'Users_model',
			'method' => '_dynamic_block_get_lang_select',
			'params' => array(
				'right_align' => array('gid'=>'right_align', 'type'=>'checkbox', 'default'=>'1'),
			),
			'views' => array(
				array('gid'=>'default'),
			),
			'area' => array(
				array(
					'gid' => 'index-page',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 2,
					'cache_time' => 600,
				),
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lavender',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'girls',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'jewish',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lovers',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0,
				),
				array(
					'gid' => 'neighbourhood',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'blackonwhite',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'deepblue',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0,
				),
				array(
					'gid' => 'companions',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 30,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'community',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0,
				),
				array(
					'gid' => 'christian',
					'params' => 'a:1:{s:11:"right_align";s:1:"1";}',
					'view_str' => 'default',
					'width' => 100,
					'sorter' => 5,
					'cache_time' => 0,
				),
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

	public function install_menu () {
		$this->CI->load->helper('menu');
		foreach($this->_menu as $gid => $menu_data){
			$this->_menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->_menu, 'create', $gid, 0, $this->_menu[$gid]["items"]);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('users', 'menu', $langs_ids);
		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }
		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');

		foreach($this->_menu as $gid => $menu_data){
			linked_install_process_menu_items($this->_menu, 'update', $gid, 0, $this->_menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');
		$return = array();
		foreach($this->_menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->_menu, 'export', $gid, 0, $this->_menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( 'menu' => $return );
	}

	public function deinstall_menu() {
		$this->CI->load->helper('menu');
		foreach($this->_menu as $gid => $menu_data){
			if($menu_data['action'] == 'create'){
				linked_install_set_menu($gid, 'delete');
			}else{
				linked_install_delete_menu_items($gid, $this->_menu[$gid]['items']);
			}
		}
	}

	public function install_uploads () {
		// upload config
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = array(
			'gid' => 'user-logo',
			'name' => 'User icon',
			'max_height' => 5000,
			'max_width' => 5000,
			'max_size' => 10000000,
			'name_format' => 'generate',
			'file_formats' => array('jpg', 'jpeg', 'gif', 'png'),
			'default_img' => 'default-user-logo.png',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$config_data['file_formats'] = serialize($config_data['file_formats']);
		$config_id = $this->CI->Uploads_config_model->save_config(null, $config_data);
		$wm_data = $this->CI->Uploads_config_model->get_watermark_by_gid('image-wm');
		$wm_id = isset($wm_data["id"])?$wm_data["id"]:0;
		
		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'grand',
			'width' => 960,
			'height' => 720,
			'effect' => 'none',
			'watermark_id' => $wm_id,
			'crop_param' => 'resize',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);

		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'great',
			'width' => 305,
			'height' => 305,
			'effect' => 'none',
			'watermark_id' => $wm_id,
			'crop_param' => 'crop',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);

		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'big',
			'width' => 200,
			'height' => 200,
			'effect' => 'none',
			'watermark_id' => $wm_id,
			'crop_param' => 'crop',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);

		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'middle',
			'width' => 100,
			'height' => 100,
			'effect' => 'none',
			'watermark_id' => 0,
			'crop_param' => 'crop',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);

		$thumb_data = array(
			'config_id' => $config_id,
			'prefix' => 'small',
			'width' => 60,
			'height' => 60,
			'effect' => 'none',
			'watermark_id' => 0,
			'crop_param' => 'crop',
			'crop_color' => 'ffffff',
			'date_add' => date('Y-m-d H:i:s'),
		);
		$this->CI->Uploads_config_model->save_thumb(null, $thumb_data);
	}

	public function install_site_map () {
		// Site Map
		$this->CI->load->model('Site_map_model');
		$site_map_data = array(
			'module_gid' => 'users',
			'model_name' => 'Users_model',
			'get_urls_method' => 'get_sitemap_urls',
		);
		$this->CI->Site_map_model->set_sitemap_module('users', $site_map_data);
	}

	public function install_banners () {
		// add banners module
		$this->CI->load->model("banners/models/Banner_group_model");
		$this->CI->Banner_group_model->set_module("users", "Users_model", "_banner_available_pages");

		$this->add_banners();
	}

	public function add_banners(){
		$lang_id = $this->CI->pg_language->get_default_lang_id();
		$lang_ids = array_keys($this->CI->pg_language->languages);

		$this->CI->load->model('Users_model');
		$this->CI->load->model('banners/models/Banner_group_model');
		$this->CI->load->model('banners/models/Banner_place_model');

		$group_id = $this->CI->Banner_group_model->get_group_id_by_gid('users_groups');
		// add pages in group
		$pages = $this->CI->Users_model->_banner_available_pages();
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

	public function install_linker () {
		// add linker entry
		$this->CI->load->model('linker/models/linker_type_model');
		$this->CI->linker_type_model->create_type('users_contacts');
	}

	public function install_moderation () {
		// Moderation
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach($this->_moderation_types as $mtype) {
			$mtype['date_add'] = date("Y-m-d H:i:s");
			$this->CI->Moderation_type_model->save_type(null, $mtype);
		}
	}

	public function install_moderation_lang_update($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('users', 'moderation', $langs_ids);
		if(!$langs_file) {
			log_message('info', 'Empty moderation langs data');
			return false;
		}
		$this->CI->load->model('moderation/models/Moderation_type_model');
		$this->CI->Moderation_type_model->update_langs($this->_moderation_types, $langs_file);
	}

	public function install_moderation_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('moderation/models/Moderation_type_model');
		return array('moderation' => $this->CI->Moderation_type_model->export_langs($this->_moderation_types, $langs_ids));
	}

	public function deinstall_moderation () {
		// Moderation
		$this->CI->load->model('moderation/models/Moderation_type_model');
		foreach($this->_moderation_types as $mtype) {
			$type = $this->CI->Moderation_type_model->get_type_by_name($mtype["name"]);
			$this->CI->Moderation_type_model->delete_type($type['id']);
		}
	}

	/**
	 * Ausers module methods
	 */
	public function install_ausers() {
		// install ausers permissions
		$this->CI->load->model('ausers/models/Ausers_model');

		foreach($this->_ausers_methods as $method){
			$this->CI->Ausers_model->save_method(null, $method);
		}
	}

	public function install_ausers_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('users', 'ausers', $langs_ids);
		// install ausers permissions
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'users';
		$methods = $this->CI->Ausers_model->get_methods_lang_export($params);
		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Ausers_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_ausers_lang_export($langs_ids) {
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'users';
		$methods =  $this->CI->Ausers_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('ausers' => $return);
	}

	public function deinstall_ausers() {
		// delete moderation methods in ausers
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'users';
		$this->CI->Ausers_model->delete_methods($params);
	}

	public function install_notifications() {
		// add notification
		$this->CI->load->model('Notifications_model');
		$this->CI->load->model('notifications/models/Templates_model');

		foreach($this->_notifications['templates'] as $tpl){
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

		foreach($this->_notifications['notifications'] as $notification) {
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

	public function install_notifications_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Notifications_model');

		$langs_file = $this->CI->Install_model->language_file_read('users', 'notifications', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty notifications langs data'); return false; }

		$this->CI->Notifications_model->update_langs($this->_notifications, $langs_file, $langs_ids);
		return true;
	}

	public function install_notifications_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Notifications_model');
		$langs = $this->CI->Notifications_model->export_langs($this->_notifications, $langs_ids);
		return array('notifications' => $langs);
	}

	public function deinstall_notifications() {
		$this->CI->load->model('Notifications_model');
		$this->CI->load->model('notifications/models/Templates_model');
		foreach($this->_notifications['templates'] as $tpl){
			$this->CI->Templates_model->delete_template_by_gid($tpl['gid']);
		}
		foreach($this->_notifications['notifications'] as $notification){
			$this->CI->Notifications_model->delete_notification_by_gid($notification['gid']);
		}
	}

	public function install_social_networking() {
		// add social netorking page
		$this->CI->load->model('social_networking/models/Social_networking_pages_model');
		$page_data = array(
			'controller' => 'users',
			'method' => 'registration',
			'name' => 'Registration page',
			'data' => 'a:3:{s:4:"like";a:3:{s:8:"facebook";s:2:"on";s:9:"vkontakte";s:2:"on";s:6:"google";s:2:"on";}s:5:"share";a:4:{s:8:"facebook";s:2:"on";s:9:"vkontakte";s:2:"on";s:8:"linkedin";s:2:"on";s:7:"twitter";s:2:"on";}s:8:"comments";s:1:"1";}',
		);
		$this->CI->Social_networking_pages_model->save_page(null, $page_data);
	}

	public function deinstall_social_networking() {
		$this->CI->load->model('social_networking/models/Social_networking_pages_model');
		$this->CI->Social_networking_pages_model->delete_pages_by_controller('users');
	}

	public function _arbitrary_installing(){
		// create groups
		$this->CI->load->model("users/models/Groups_model");
		$data = array( 'gid' => 'default', 'is_default' => '1' );
		$this->CI->Groups_model->save_group(null, $data);

		$data = array( 'gid' => 'simple-users', 'is_default' => '0');
		$this->CI->Groups_model->save_group(null, $data);

		$data = array( 'gid' => 'vip-users', 'is_default' => '0' );
		$this->CI->Groups_model->save_group(null, $data);

		// SEO
		$seo_data = array(
			'module_gid' => 'users',
			'model_name' => 'Users_model',
			'get_settings_method' => 'get_seo_settings',
			'get_rewrite_vars_method' => 'request_seo_rewrite',
			'get_sitemap_urls_method' => 'get_sitemap_xml_urls',
		);
		$this->CI->pg_seo->set_seo_module('users', $seo_data);

		$this->CI->load->model('Users_model');
		$this->CI->Users_model->update_age();
		$this->CI->Users_model->update_profile_completion();

		$users = $this->CI->Users_model->get_users_list(null, null, null, array(), null, false);
		$this->CI->load->model('users/models/Users_perfect_match_model');
		$this->CI->Users_perfect_match_model->update_users_perfect_match($users);

		$this->add_demo_content();
		return;
	}

	public function add_demo_content() {
		$demo_content = include MODULEPATH . 'users/install/demo_content.php';
		// Associating languages id with codes
		foreach($this->CI->pg_language->languages as $l) {
			$lang[$l['code']] = $l['id'];
			if(!empty($l['is_default'])) {
				$default_lang = $l;
			}
		}
		// Users
		$this->CI->load->model('Users_model');
		foreach($demo_content['users'] as $user) {
			// Replace language code with ID
			if(empty($lang[$user['lang_code']])) {
				$user['lang_id'] = $default_lang['id'];
			} else {
				$user['lang_id'] = $lang[$user['lang_code']];
			}
			unset($user['lang_code']);
			$this->CI->Users_model->save_user(null, $user);
		}
		// Perfect match
		$this->CI->load->model("users/models/Users_perfect_match_model");
		foreach($demo_content['perfect_match'] as $match) {
			$this->CI->Users_perfect_match_model->save_perfect_match($match['id_user'], $match);
		}
		return true;
	}

	public function _arbitrary_lang_install($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('users', 'arbitrary');
		if(!$langs_file) {
			log_message('info', 'Empty arbitrary langs data');
			return false;
		}
		$this->CI->load->model("users/models/Groups_model");
		$group_gids = array('default', 'simple-users', 'vip-users');
		$this->CI->Groups_model->update_langs($group_gids, $langs_file, $langs_ids);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_account_title"],
			"keyword" => $langs_file["seo_tags_account_keyword"],
			"description" => $langs_file["seo_tags_account_description"],
			"header" => $langs_file["seo_tags_account_header"],
			"og_title" => $langs_file["seo_tags_account_og_title"],
			"og_type" => $langs_file["seo_tags_account_og_type"],
			"og_description" => $langs_file["seo_tags_account_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "account", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_account_delete_title"],
			"keyword" => $langs_file["seo_tags_account_delete_keyword"],
			"description" => $langs_file["seo_tags_account_delete_description"],
			"header" => $langs_file["seo_tags_account_delete_header"],
			"og_title" => $langs_file["seo_tags_account_delete_og_title"],
			"og_type" => $langs_file["seo_tags_account_delete_og_type"],
			"og_description" => $langs_file["seo_tags_account_delete_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "account_delete", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_settings_title"],
			"keyword" => $langs_file["seo_tags_settings_keyword"],
			"description" => $langs_file["seo_tags_settings_description"],
			"header" => $langs_file["seo_tags_settings_header"],
			"og_title" => $langs_file["seo_tags_settings_og_title"],
			"og_type" => $langs_file["seo_tags_settings_og_type"],
			"og_description" => $langs_file["seo_tags_settings_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "settings", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_login_form_title"],
			"keyword" => $langs_file["seo_tags_login_form_keyword"],
			"description" => $langs_file["seo_tags_login_form_description"],
			"header" => $langs_file["seo_tags_login_form_header"],
			"og_title" => $langs_file["seo_tags_login_form_og_title"],
			"og_type" => $langs_file["seo_tags_login_form_og_type"],
			"og_description" => $langs_file["seo_tags_login_form_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "login_form", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_restore_title"],
			"keyword" => $langs_file["seo_tags_restore_keyword"],
			"description" => $langs_file["seo_tags_restore_description"],
			"header" => $langs_file["seo_tags_restore_header"],
			"og_title" => $langs_file["seo_tags_restore_og_title"],
			"og_type" => $langs_file["seo_tags_restore_og_type"],
			"og_description" => $langs_file["seo_tags_restore_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "restore", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_profile_title"],
			"keyword" => $langs_file["seo_tags_profile_keyword"],
			"description" => $langs_file["seo_tags_profile_description"],
			"header" => $langs_file["seo_tags_profile_header"],
			"og_title" => $langs_file["seo_tags_profile_og_title"],
			"og_type" => $langs_file["seo_tags_profile_og_type"],
			"og_description" => $langs_file["seo_tags_profile_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "profile", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_view_title"],
			"keyword" => $langs_file["seo_tags_view_keyword"],
			"description" => $langs_file["seo_tags_view_description"],
			"header" => $langs_file["seo_tags_view_header"],
			"og_title" => $langs_file["seo_tags_view_og_title"],
			"og_type" => $langs_file["seo_tags_view_og_type"],
			"og_description" => $langs_file["seo_tags_view_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "view", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_registration_title"],
			"keyword" => $langs_file["seo_tags_registration_keyword"],
			"description" => $langs_file["seo_tags_registration_description"],
			"header" => $langs_file["seo_tags_registration_header"],
			"og_title" => $langs_file["seo_tags_registration_og_title"],
			"og_type" => $langs_file["seo_tags_registration_og_type"],
			"og_description" => $langs_file["seo_tags_registration_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "registration", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_confirm_title"],
			"keyword" => $langs_file["seo_tags_confirm_keyword"],
			"description" => $langs_file["seo_tags_confirm_description"],
			"header" => $langs_file["seo_tags_confirm_header"],
			"og_title" => $langs_file["seo_tags_confirm_og_title"],
			"og_type" => $langs_file["seo_tags_confirm_og_type"],
			"og_description" => $langs_file["seo_tags_confirm_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "confirm", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_search_title"],
			"keyword" => $langs_file["seo_tags_search_keyword"],
			"description" => $langs_file["seo_tags_search_description"],
			"header" => $langs_file["seo_tags_search_header"],
			"og_title" => $langs_file["seo_tags_search_og_title"],
			"og_type" => $langs_file["seo_tags_search_og_type"],
			"og_description" => $langs_file["seo_tags_search_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "search", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_perfect_match_title"],
			"keyword" => $langs_file["seo_tags_perfect_match_keyword"],
			"description" => $langs_file["seo_tags_perfect_match_description"],
			"header" => $langs_file["seo_tags_perfect_match_header"],
			"og_title" => $langs_file["seo_tags_perfect_match_og_title"],
			"og_type" => $langs_file["seo_tags_perfect_match_og_type"],
			"og_description" => $langs_file["seo_tags_perfect_match_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "perfect_match", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_my_visits_title"],
			"keyword" => $langs_file["seo_tags_my_visits_keyword"],
			"description" => $langs_file["seo_tags_my_visits_description"],
			"header" => $langs_file["seo_tags_my_visits_header"],
			"og_title" => $langs_file["seo_tags_my_visits_og_title"],
			"og_type" => $langs_file["seo_tags_my_visits_og_type"],
			"og_description" => $langs_file["seo_tags_my_visits_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "my_visits", $post_data);
		
		$post_data = array(
			"title" => $langs_file["seo_tags_my_guests_title"],
			"keyword" => $langs_file["seo_tags_my_guests_keyword"],
			"description" => $langs_file["seo_tags_my_guests_description"],
			"header" => $langs_file["seo_tags_my_guests_header"],
			"og_title" => $langs_file["seo_tags_my_guests_og_title"],
			"og_type" => $langs_file["seo_tags_my_guests_og_type"],
			"og_description" => $langs_file["seo_tags_my_guests_og_description"],
		);
		$this->CI->pg_seo->set_settings("user", "users", "my_guests", $post_data);
	}

	public function _arbitrary_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model("users/models/Groups_model");
		$group_gids = array('default', 'simple-users', 'vip-users');

		$langs = $this->CI->Groups_model->export_langs($group_gids, $langs_ids);
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "account", $langs_ids);
		$arbitrary_return["seo_tags_account_title"] = $settings["title"];
		$arbitrary_return["seo_tags_account_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_account_description"] = $settings["description"];
		$arbitrary_return["seo_tags_account_header"] = $settings["header"];
		$arbitrary_return["seo_tags_account_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_account_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_account_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "account_delete", $langs_ids);
		$arbitrary_return["seo_tags_account_delete_title"] = $settings["title"];
		$arbitrary_return["seo_tags_account_delete_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_account_delete_description"] = $settings["description"];
		$arbitrary_return["seo_tags_account_delete_header"] = $settings["header"];
		$arbitrary_return["seo_tags_account_delete_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_account_delete_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_account_delete_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "settings", $langs_ids);
		$arbitrary_return["seo_tags_settings_title"] = $settings["title"];
		$arbitrary_return["seo_tags_settings_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_settings_description"] = $settings["description"];
		$arbitrary_return["seo_tags_settings_header"] = $settings["header"];
		$arbitrary_return["seo_tags_settings_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_settings_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_settings_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "login_form", $langs_ids);
		$arbitrary_return["seo_tags_login_form_title"] = $settings["title"];
		$arbitrary_return["seo_tags_login_form_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_login_form_description"] = $settings["description"];
		$arbitrary_return["seo_tags_login_form_header"] = $settings["header"];
		$arbitrary_return["seo_tags_login_form_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_login_form_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_login_form_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "restore", $langs_ids);
		$arbitrary_return["seo_tags_restore_title"] = $settings["title"];
		$arbitrary_return["seo_tags_restore_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_restore_description"] = $settings["description"];
		$arbitrary_return["seo_tags_restore_header"] = $settings["header"];
		$arbitrary_return["seo_tags_restore_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_restore_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_restore_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "profile", $langs_ids);
		$arbitrary_return["seo_tags_profile_title"] = $settings["title"];
		$arbitrary_return["seo_tags_profile_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_profile_description"] = $settings["description"];
		$arbitrary_return["seo_tags_profile_header"] = $settings["header"];
		$arbitrary_return["seo_tags_profile_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_profile_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_profile_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "view", $langs_ids);
		$arbitrary_return["seo_tags_view_title"] = $settings["title"];
		$arbitrary_return["seo_tags_view_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_view_description"] = $settings["description"];
		$arbitrary_return["seo_tags_view_header"] = $settings["header"];
		$arbitrary_return["seo_tags_view_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_view_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_view_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "registration", $langs_ids);
		$arbitrary_return["seo_tags_registration_title"] = $settings["title"];
		$arbitrary_return["seo_tags_registration_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_registration_description"] = $settings["description"];
		$arbitrary_return["seo_tags_registration_header"] = $settings["header"];
		$arbitrary_return["seo_tags_registration_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_registration_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_registration_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "confirm", $langs_ids);
		$arbitrary_return["seo_tags_confirm_title"] = $settings["title"];
		$arbitrary_return["seo_tags_confirm_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_confirm_description"] = $settings["description"];
		$arbitrary_return["seo_tags_confirm_header"] = $settings["header"];
		$arbitrary_return["seo_tags_confirm_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_confirm_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_confirm_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "search", $langs_ids);
		$arbitrary_return["seo_tags_search_title"] = $settings["title"];
		$arbitrary_return["seo_tags_search_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_search_description"] = $settings["description"];
		$arbitrary_return["seo_tags_search_header"] = $settings["header"];
		$arbitrary_return["seo_tags_search_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_search_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_search_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "perfect_match", $langs_ids);
		$arbitrary_return["seo_tags_perfect_match_title"] = $settings["title"];
		$arbitrary_return["seo_tags_perfect_match_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_perfect_match_description"] = $settings["description"];
		$arbitrary_return["seo_tags_perfect_match_header"] = $settings["header"];
		$arbitrary_return["seo_tags_perfect_match_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_perfect_match_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_perfect_match_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "my_visits", $langs_ids);
		$arbitrary_return["seo_tags_my_visits_title"] = $settings["title"];
		$arbitrary_return["seo_tags_my_visits_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_my_visits_description"] = $settings["description"];
		$arbitrary_return["seo_tags_my_visits_header"] = $settings["header"];
		$arbitrary_return["seo_tags_my_visits_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_my_visits_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_my_visits_og_description"] = $settings["og_description"];
		
		$settings = $this->CI->pg_seo->get_settings("user", "users", "my_guests", $langs_ids);
		$arbitrary_return["seo_tags_my_guests_title"] = $settings["title"];
		$arbitrary_return["seo_tags_my_guests_keyword"] = $settings["keyword"];
		$arbitrary_return["seo_tags_my_guests_description"] = $settings["description"];
		$arbitrary_return["seo_tags_my_guests_header"] = $settings["header"];
		$arbitrary_return["seo_tags_my_guests_og_title"] = $settings["og_title"];
		$arbitrary_return["seo_tags_my_guests_og_type"] = $settings["og_type"];
		$arbitrary_return["seo_tags_my_guests_og_description"] = $settings["og_description"];
		
		return array('arbitrary' => array_merge($langs, $arbitrary_return));

	}

	public function deinstall_uploads () {
		$this->CI->load->model('uploads/models/Uploads_config_model');
		$config_data = $this->CI->Uploads_config_model->get_config_by_gid('user-logo');
		if(!empty($config_data['id'])){
			$this->CI->Uploads_config_model->delete_config($config_data['id']);
		}
	}

	public function deinstall_site_map () {
		$this->CI->load->model('Site_map_model');
		$this->CI->Site_map_model->delete_sitemap_module('users');
	}

	public function deinstall_banners () {
		// delete banners module
		$this->CI->load->model("banners/models/Banner_group_model");
		$this->CI->Banner_group_model->delete_module("users");
		$this->remove_banners();
	}

	public function remove_banners(){
		$this->CI->load->model('banners/models/Banner_group_model');
		$group_id = $this->CI->Banner_group_model->get_group_id_by_gid('users_groups');
		$this->CI->Banner_group_model->delete($group_id);
	}

	public function deinstall_linker () {
		$this->CI->load->model('linker/models/linker_type_model');
		$this->CI->linker_type_model->delete_type('users_contacts');
	}

	public function _arbitrary_deinstalling(){
		$this->CI->pg_seo->delete_seo_module('users');
	}

	// looks like not in use
	public function get_menu_lang_delete($langs, $menu_gid, $item_gid) {
		$lang_data = array();
		foreach ($this->CI->pg_language->languages as $lang) {
			$lang_data[$lang["id"]] = $langs[$lang["code"]][$menu_gid][$item_gid];
		}
		return $lang_data;
	}

	public function install_field_editor(){
		$this->CI->load->model('Users_model');
		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize('users');
		include MODULEPATH . 'users/install/user_fields_data.php';

		$this->CI->Field_editor_model->import_type_structure('users', $fe_sections, $fe_fields, $fe_forms);

		$users_id = $this->CI->Users_model->get_all_users_id();
		foreach($users_id as $uid){
			$this->CI->Field_editor_model->update_fulltext_field($uid);
		}
	}

	public function install_field_editor_lang_update() {
		$langs_file = $this->CI->Install_model->language_file_read('users', 'field_editor');

		if(!$langs_file) {
			log_message('info', 'Empty dynamic_blocks langs data');
			return false;
		}

		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize('users');
		include MODULEPATH . 'users/install/user_fields_data.php';

		$this->CI->Field_editor_model->update_sections_langs($fe_sections, $langs_file);
		$this->CI->Field_editor_model->update_fields_langs('users', $fe_fields, $langs_file);
		return true;
	}

	public function install_field_editor_lang_export($langs_ids = null) {
		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize('users');
		list($fe_sections, $fe_fields, $fe_forms) = $this->CI->Field_editor_model->export_type_structure('users', 'application/modules/users/install/user_fields_data.php');
		$sections = $this->CI->Field_editor_model->export_sections_langs($fe_sections, $langs_ids);
		$fields = $this->CI->Field_editor_model->export_fields_langs('users', $fe_fields, $langs_ids);

		return array('field_editor' => array_merge($sections, $fields));
	}

	public function deinstall_field_editor() {
		$this->CI->load->model('Field_editor_model');
		$this->CI->load->model('field_editor/models/Field_editor_forms_model');

		include MODULEPATH . 'users/install/user_fields_data.php';

		foreach($fe_fields as $field){
			$this->CI->Field_editor_model->delete_field_by_gid($field['data']['gid']);
		}
		$this->CI->Field_editor_model->initialize('users');
		foreach($fe_sections as $section){
			$this->CI->Field_editor_model->delete_section_by_gid($section['data']['gid']);
		}
		foreach($fe_forms as $form){
			$this->CI->Field_editor_forms_model->delete_form_by_gid($form['data']['gid']);
		}
		return;
	}

	public function install_cronjob() {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array(
			"name" => "Update users offline status",
			"module" => "users",
			"model" => "Users_model",
			"method" => "cron_set_offline_status",
			"cron_tab" => "*/5 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);

		/*$cron_data = array(
			"name" => "Users featured section clean",
			"module" => "users",
			"model" => "Users_model",
			"method" => "service_cron_users_featured",
			"cron_tab" => "0 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);*/

		$cron_data = array(
			"name" => "Users activated section clean",
			"module" => "users",
			"model" => "Users_model",
			"method" => "service_cron_user_activate_in_search",
			"cron_tab" => "0 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);

		$cron_data = array(
			"name" => "Users hide on site clean",
			"module" => "users",
			"model" => "Users_model",
			"method" => "service_cron_hide_on_site",
			"cron_tab" => "0 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);

		$cron_data = array(
			"name" => "Users highlight in search clean",
			"module" => "users",
			"model" => "Users_model",
			"method" => "service_cron_highlight_in_search",
			"cron_tab" => "0 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);

		$cron_data = array(
			"name" => "Users up in search clean",
			"module" => "users",
			"model" => "Users_model",
			"method" => "service_cron_up_in_search",
			"cron_tab" => "0 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);

		$cron_data = array(
			"name" => "Region leaders update",
			"module" => "users",
			"model" => "Users_model",
			"method" => "service_cron_region_leader",
			"cron_tab" => "*/5 * * * *",
			"status" => "1"
		);
		$this->CI->Cronjob_model->save_cron(null, $cron_data);
	}

	public function deinstall_cronjob() {
		$this->CI->load->model('Cronjob_model');
		$cron_data = array();
		$cron_data["where"]["module"] = "users";
		$this->CI->Cronjob_model->delete_cron_by_param($cron_data);
	}

	public function install_services () {
		$this->CI->load->model("Services_model");

		/* featured users */
		$template_data = array(
			'gid' => "users_featured_template",
			'callback_module' => "users",
			'callback_model' => "Users_model",
			'callback_buy_method' => "service_buy",
			'callback_activate_method' => "service_activate_users_featured",
			'callback_validate_method' => "service_validate_users_featured",
			'price_type' => 1,
			'data_admin' => serialize(array('period' => 'int')),
			'data_user'=> '',
			'date_add' => date("Y-m-d H:i:s"),
			'moveable' => 0,
			'alert_activate' => 0,
		);
		$id_tpl = $this->CI->Services_model->save_template(null, $template_data);

		$service_data = array(
			"gid" => "users_featured",
			"template_gid" => "users_featured_template",
			"pay_type" => 2,
			"status" => 1,
			"price" => 10,
			"data_admin" => serialize(array('period' => '30')),
			"date_add" => date("Y-m-d H:i:s")
		);
		$this->CI->Services_model->save_service(null, $service_data);

		/* activate in search */
		$template_data = array(
			'gid' => "user_activate_in_search_template",
			'callback_module' => "users",
			'callback_model' => "Users_model",
			'callback_buy_method' => "service_buy",
			'callback_activate_method' => "service_activate_user_activate_in_search",
			'callback_validate_method' => "service_validate_user_activate_in_search",
			'price_type' => 1,
			'data_admin' => serialize(array('period' => 'int')),
			'data_user'=> "",
			'date_add' => date("Y-m-d H:i:s"),
			'moveable' => 0,
			'alert_activate' => 0,
		);
		$id_tpl = $this->CI->Services_model->save_template(null, $template_data);

		$service_data = array(
			"gid" => "user_activate_in_search",
			"template_gid" => "user_activate_in_search_template",
			"pay_type" => 2,
			"status" => 1,
			"price" => 10,
			"data_admin" => serialize(array('period' => '30')),
			"date_add" => date("Y-m-d H:i:s")
		);
		$this->CI->Services_model->save_service(null, $service_data);

		/*get admin approve*/
		$template_data = array(
			'gid' => "admin_approve_template",
			'callback_module' => "users",
			'callback_model' => "Users_model",
			'callback_buy_method' => "service_buy",
			'callback_activate_method' => "service_activate_admin_approve",
			'callback_validate_method' => "service_validate_admin_approve",
			'price_type' => 1,
			'data_admin' => '',
			'data_user'=> '',
			'date_add' => date("Y-m-d H:i:s"),
			'moveable' => 0,
			'alert_activate' => 0,
		);
		$id_tpl = $this->CI->Services_model->save_template(null, $template_data);

		$service_data = array(
			"gid" => "admin_approve",
			"template_gid" => "admin_approve_template",
			"pay_type" => 2,
			"status" => 1,
			"price" => 10,
			"data_admin" => '',
			"date_add" => date("Y-m-d H:i:s")
		);
		$this->CI->Services_model->save_service(null, $service_data);

		/* hide on site */
		$template_data = array(
			'gid' => "hide_on_site_template",
			'callback_module' => "users",
			'callback_model' => "Users_model",
			'callback_buy_method' => "service_buy",
			'callback_activate_method' => "service_activate_hide_on_site",
			'callback_validate_method' => "service_validate_hide_on_site",
			'price_type' => 1,
			'data_admin' => serialize(array('period' => 'int')),
			'data_user'=> "",
			'date_add' => date("Y-m-d H:i:s"),
			'moveable' => 0,
			'alert_activate' => 0,
		);
		$id_tpl = $this->CI->Services_model->save_template(null, $template_data);

		$service_data = array(
			"gid" => "hide_on_site",
			"template_gid" => "hide_on_site_template",
			"pay_type" => 2,
			"status" => 1,
			"price" => 10,
			"data_admin" => serialize(array('period' => '30')),
			"date_add" => date("Y-m-d H:i:s")
		);
		$this->CI->Services_model->save_service(null, $service_data);

		/* highlight in search */
		$template_data = array(
			'gid' => "highlight_in_search_template",
			'callback_module' => "users",
			'callback_model' => "Users_model",
			'callback_buy_method' => "service_buy",
			'callback_activate_method' => "service_activate_highlight_in_search",
			'callback_validate_method' => "service_validate_highlight_in_search",
			'price_type' => 1,
			'data_admin' => serialize(array('period' => 'int')),
			'data_user'=> "",
			'date_add' => date("Y-m-d H:i:s"),
			'moveable' => 0,
			'alert_activate' => 0,
		);
		$id_tpl = $this->CI->Services_model->save_template(null, $template_data);

		$service_data = array(
			"gid" => "highlight_in_search",
			"template_gid" => "highlight_in_search_template",
			"pay_type" => 2,
			"status" => 1,
			"price" => 10,
			"data_admin" => serialize(array('period' => '30')),
			"date_add" => date("Y-m-d H:i:s")
		);
		$this->CI->Services_model->save_service(null, $service_data);

		/* up in search */
		$template_data = array(
			'gid' => "up_in_search_template",
			'callback_module' => "users",
			'callback_model' => "Users_model",
			'callback_buy_method' => "service_buy",
			'callback_activate_method' => "service_activate_up_in_search",
			'callback_validate_method' => "service_validate_up_in_search",
			'price_type' => 1,
			'data_admin' => serialize(array('period' => 'int')),
			'data_user'=> "",
			'date_add' => date("Y-m-d H:i:s"),
			'moveable' => 0,
			'alert_activate' => 0,
		);
		$id_tpl = $this->CI->Services_model->save_template(null, $template_data);

		$service_data = array(
			"gid" => "up_in_search",
			"template_gid" => "up_in_search_template",
			"pay_type" => 2,
			"status" => 1,
			"price" => 10,
			"data_admin" => serialize(array('period' => '30')),
			"date_add" => date("Y-m-d H:i:s")
		);
		$this->CI->Services_model->save_service(null, $service_data);

		/*ability_delete*/
		$template_data = array(
			'gid' => "ability_delete_template",
			'callback_module' => "users",
			'callback_model' => "Users_model",
			'callback_buy_method' => "service_buy",
			'callback_activate_method' => "service_activate_ability_delete",
			'callback_validate_method' => "service_validate_ability_delete",
			'price_type' => 1,
			'data_admin' => '',
			'data_user'=> '',
			'date_add' => date("Y-m-d H:i:s"),
			'moveable' => 0,
			'alert_activate' => 1,
		);
		$id_tpl = $this->CI->Services_model->save_template(null, $template_data);

		$service_data = array(
			"gid" => "ability_delete",
			"template_gid" => "ability_delete_template",
			"pay_type" => 2,
			"status" => 1,
			"price" => 10,
			"data_admin" => '',
			"date_add" => date("Y-m-d H:i:s")
		);
		$this->CI->Services_model->save_service(null, $service_data);

		/////// region_leader
		/*$template_data = array(
			'gid' => "region_leader_template",
			'callback_module' => "users",
			'callback_model' => "Users_model",
			'callback_buy_method' => "service_buy_region_leader",
			'callback_activate_method' => "service_activate_region_leader",
			'callback_validate_method' => "service_validate_region_leader",
			'price_type' => 2,
			'data_admin' => 'a:3:{s:7:"min_bid";s:5:"price";s:16:"write_off_amount";s:5:"price";s:16:"write_off_period";s:3:"int";}',
			'data_user'=> 'a:1:{s:4:"text";s:6:"string";}',
			'date_add' => date("Y-m-d H:i:s"),
			'moveable' => 0,
			'alert_activate' => 0,
		);

		$id_tpl = $this->CI->Services_model->save_template(null, $template_data);

		$service_data = array(
			"gid" => "region_leader",
			"template_gid" => "region_leader_template",
			"pay_type" => 2,
			"status" => 1,
			"price" => 0,
			"data_admin" => 'a:3:{s:7:"min_bid";s:4:"0.75";s:16:"write_off_amount";s:4:"0.20";s:16:"write_off_period";i:1;}',
			"date_add" => date("Y-m-d H:i:s")
		);

		$this->CI->Services_model->save_service(null, $service_data);*/
	}

	public function install_services_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('users', 'services', $langs_ids);
		if(!$langs_file) {
			log_message('info', 'Empty services langs data');
			return false;
		}
		$this->CI->load->model('Services_model');
		$this->CI->Services_model->update_langs($this->_lang_services, $langs_file);
		return true;
	}

	public function install_services_lang_export($langs_ids = null) {
		$this->CI->load->model('Services_model');
		return array('services' => $this->CI->Services_model->export_langs($this->_lang_services, $langs_ids));
	}

	public function deinstall_services () {
		$this->CI->load->model("Services_model");

		$this->CI->Services_model->delete_template_by_gid('users_featured_template');
		$this->CI->Services_model->delete_template_by_gid('user_activate_in_search_template');
		$this->CI->Services_model->delete_template_by_gid('admin_approve_template');
		$this->CI->Services_model->delete_template_by_gid('hide_on_site_template');
		$this->CI->Services_model->delete_template_by_gid('highlight_in_search_template');
		$this->CI->Services_model->delete_template_by_gid('up_in_search_template');
		$this->CI->Services_model->delete_template_by_gid('ability_delete_template');
		$this->CI->Services_model->delete_template_by_gid('region_leader_template');

		$this->CI->Services_model->delete_service_by_gid('users_featured');
		$this->CI->Services_model->delete_service_by_gid('user_activate_in_search');
		$this->CI->Services_model->delete_service_by_gid('admin_approve');
		$this->CI->Services_model->delete_service_by_gid('hide_on_site');
		$this->CI->Services_model->delete_service_by_gid('highlight_in_search');
		$this->CI->Services_model->delete_service_by_gid('up_in_search');
		$this->CI->Services_model->delete_service_by_gid('ability_delete');
		$this->CI->Services_model->delete_service_by_gid('region_leader');
	}

	/**
	 * Install geomap links
	 */
	public function install_geomap(){
		//add geomap settings
		$this->CI->load->model('geomap/models/Geomap_settings_model');
		foreach($this->_geomap as $geomap){
			$validate_data = $this->CI->Geomap_settings_model->validate_settings($geomap['map_settings']);
			if(!empty($validate_data['errors'])) continue;
			$this->CI->Geomap_settings_model->save_settings($geomap['settings']['map_gid'], $geomap['settings']['id_user'], $geomap['settings']['id_object'], $geomap['settings']['gid'], $validate_data['data']);
		}
	}

	/**
	 * Install languages
	 * @param array $langs_ids
	 */
	public function install_geomap_lang_update($langs_ids=null){
		if(empty($langs_ids)) return false;

		$langs_file = $this->CI->Install_model->language_file_read('users', 'geomap', $langs_ids);
		if(!$langs_file){log_message('info', 'Empty geomap langs data');return false;}

		$this->CI->load->model('geomap/models/Geomap_settings_model');

		$gids = array();
		foreach($this->_geomap as $geomap){
			$gids[$geomap['settings']['gid']] = 'map_'.$geomap['settings']['gid'];
		}
		$this->CI->Geomap_settings_model->update_lang($gids, $langs_file, $langs_ids);
	}

	/**
	 * Import languages
	 * @param array $langs_ids
	 */
	public function install_geomap_lang_export($langs_ids=null){
		$this->CI->load->model('geomap/models/Geomap_settings_model');

		$gids = array();
		foreach($this->_geomap as $geomap){
			$gids[$geomap['settings']['gid']] = 'map_'.$geomap['settings']['gid'];
		}
		$langs = $this->CI->Geomap_settings_model->export_lang($gids, $langs_ids);
		return array('geomap' => $langs);
	}

	/**
	 * Uninstall geomap links
	 */
	public function deinstall_geomap(){
		$this->CI->load->model("geomap/models/Geomap_settings_model");
		foreach($this->_geomap as $geomap){
			$this->CI->Geomap_settings_model->delete_settings($geomap['settings']['map_gid'], $geomap['settings']['id_user'], $geomap['settings']['id_object'], $geomap['settings']['gid']);
		}
	}

	public function install_dynamic_blocks() {
		$this->CI->load->model('Dynamic_blocks_model');

		foreach($this->_dynamic_blocks as $block_data){
			$validate_data = $this->CI->Dynamic_blocks_model->validate_block(null, $block_data);
			if(!empty($validate_data['errors'])) continue;
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
						'sorter' => $block_area['sorter'],
						'width' => $block_area['width'],
					);
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
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('users', 'dynamic_blocks', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty dynamic_blocks langs data'); return false; }

		$this->CI->load->model('Dynamic_blocks_model');
		$data = array();
		foreach($this->_dynamic_blocks as $block_data){
			if($block = $this->CI->Dynamic_blocks_model->get_block_by_gid($block_data['gid'])){
				$data[] = $block;
			}
		}
		$this->CI->Dynamic_blocks_model->update_langs($data, $langs_file, $langs_ids);
	}

	public function install_dynamic_blocks_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Dynamic_blocks_model');
		$data = array();
		foreach($this->_dynamic_blocks as $block_data){
			if($block = $this->CI->Dynamic_blocks_model->get_block_by_gid($block_data["gid"])){
				$data[] = $block;
			}
		}
		$langs = $this->CI->Dynamic_blocks_model->export_langs($data, $langs_ids);
		return array("dynamic_blocks" => $langs);
	}

	public function deinstall_dynamic_blocks(){
		$this->CI->load->model('Dynamic_blocks_model');
		foreach($this->_dynamic_blocks as $block) {
			$this->CI->Dynamic_blocks_model->delete_block_by_gid($block['gid']);
		}
	}

	public function install_comments(){
		$this->CI->load->model('comments/models/Comments_types_model');
		$comment_type = array(
			'gid' => 'user_avatar',
			'module' => 'users',
			'model' => 'Users_model',
			'method_count' => 'comments_count_callback',
			'method_object' => 'comments_object_callback'
		);
		$this->CI->Comments_types_model->add_comments_type($comment_type);
	}

	public function install_comments_lang_update($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('users', 'comments', $langs_ids);

		if(!$langs_file) {
			log_message('info', 'Empty moderation langs data');
			return false;
		}
		$this->CI->load->model('comments/models/Comments_types_model');
		$this->CI->Comments_types_model->update_langs(array('user_avatar'), $langs_file);

	}

	public function install_comments_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('comments/models/Comments_types_model');
		return array('comments' => $this->CI->Comments_types_model->export_langs(array('user_avatar'), $langs_ids));
	}

	public function deinstall_comments(){
		$this->CI->load->model('comments/models/Comments_types_model');
		$this->CI->Comments_types_model->delete_comments_type('user_avatar');
	}

	/**
	 * Install spam links
	 */
	public function install_spam(){
		// add spam type
		$this->CI->load->model("spam/models/Spam_type_model");

		foreach((array)$this->spam as $spam_data){
			$validate_data = $this->CI->Spam_type_model->validate_type(null, $spam_data);
			if(!empty($validate_data["errors"])) continue;
			$this->CI->Spam_type_model->save_type(null, $validate_data["data"]);
		}
	}
	
	/**
	 * Import spam languages
	 * @param array $langs_ids
	 */
	public function install_spam_lang_update($langs_ids=null){
		if(empty($langs_ids)) return false;
		
		$this->CI->load->model("spam/models/Spam_type_model");
		
		$langs_file = $this->CI->Install_model->language_file_read("users", "spam", $langs_ids);
		if(!$langs_file){log_message("info", "Empty spam langs data");return false;}
	
		$this->CI->Spam_type_model->update_langs($this->spam, $langs_file, $langs_ids);
		return true;
	}
	
	/**
	 * Export spam languages
	 * @param array $langs_ids
	 */
	public function install_spam_lang_export($langs_ids=null){
		$this->CI->load->model("spam/models/Spam_type_model");
		$langs = $this->CI->Spam_type_model->export_langs((array)$this->spam, $langs_ids);
		return array("spam" => $langs);
	}
	
	/**
	 * Uninstall spam links
	 */
	public function deinstall_spam(){
		//add spam type
		$this->CI->load->model("spam/models/Spam_type_model");

		foreach((array)$this->spam as $spam_data){
			$this->CI->Spam_type_model->delete_type($spam_data["gid"]);
		}
	}
}
