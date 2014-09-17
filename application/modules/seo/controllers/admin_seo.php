<?php

/**
 * Seo admin side controller
 *
 * @package PG_RealEstate
 * @subpackage Seo
 * @category	controllers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 **/
class Admin_Seo extends Controller
{
	/**
	 * Class constructor
	 * 
	 * @return Admin_Seo
	 */
	public function __construct(){
		parent::Controller();
		
		$this->load->model('Menu_model');
		$this->load->model('Seo_model');
		
		$this->Menu_model->set_menu_active_item('admin_menu', 'system-items');
		$this->system_messages->set_data('header', l('admin_header_list', 'seo'));
	}

	/**
	 * Render index action
	 * 
	 * @return void
	 */
	public function index(){
		$this->default_listing();
	}

	/**
	 * Render global settings manangement action
	 * 
	 * @return void
	 */
	public function default_listing(){

		$this->Menu_model->set_menu_active_item('admin_seo_menu', 'seo_default_list_item');
		$this->template_lite->view('default_list');
	}

	/**
	 * Render edit global settings action
	 * 
	 * @param string $controller user mode controller
	 * @return void
	 */
	public function default_edit($controller){
		$languages = $this->pg_language->languages;

		$user_settings = $this->pg_seo->get_settings($controller, '', '');
		
		if($this->input->post('btn_save')){
			$post_data = array(
				"title" => $this->input->post('title', true),
				"keyword" => $this->input->post('keyword', true),
				"description" => $this->input->post('description', true),
				"header" => $this->input->post('header', true),
				"og_title" => $this->input->post('og_title', true),
				"og_type" => $this->input->post('og_type', true),
				"og_description" => $this->input->post('og_description', true),
				"lang_in_url" => $this->input->post('lang_in_url', true)
			);

			$validate_data = $this->Seo_model->validate_seo_settings($controller, '', '', $post_data);
			if(!empty($validate_data['errors'])){
				$this->system_messages->add_message('error', $validate_data['errors']);
				
				foreach($languages as $lang_id => $lang_data){
					$user_settings['meta_'.$lang_id]['title'] = $post_data['title'][$lang_id];
					$user_settings['meta_'.$lang_id]['keyword'] = $post_data['keyword'][$lang_id];
					$user_settings['meta_'.$lang_id]['description'] = $post_data['description'][$lang_id];
					$user_settings['meta_'.$lang_id]['header'] = $post_data['header'][$lang_id];
					$user_settings['og_'.$lang_id]['og_title'] = $post_data['og_title'][$lang_id];
					$user_settings['og_'.$lang_id]['og_type'] = $post_data['og_type'][$lang_id];
					$user_settings['og_'.$lang_id]['og_description'] = $post_data['og_description'][$lang_id];
					$user_settings['lang_in_url'] = $post_data['lang_in_url'];
				}
			}else{
				$this->pg_seo->set_settings($controller, '', '', $validate_data['data']);
				$this->system_messages->add_message('success', l('success_settings_saved', 'seo'));
				
				foreach($languages as $lang_id => $lang_data){
					$user_settings['meta_'.$lang_id]['title'] = $validate_data['data']['title'][$lang_id];
					$user_settings['meta_'.$lang_id]['keyword'] = $validate_data['data']['keyword'][$lang_id];
					$user_settings['meta_'.$lang_id]['description'] = $validate_data['data']['description'][$lang_id];
					$user_settings['meta_'.$lang_id]['header'] = $validate_data['data']['header'][$lang_id];
					$user_settings['og_'.$lang_id]['og_title'] = $validate_data['data']['og_title'][$lang_id];
					$user_settings['og_'.$lang_id]['og_type'] = $validate_data['data']['og_type'][$lang_id];
					$user_settings['og_'.$lang_id]['og_description'] = $validate_data['data']['og_description'][$lang_id];
					$user_settings['lang_in_url'] = $validate_data['data']['lang_in_url'];
				}
				
				//$url = site_url()."admin/seo/default_listing/";
				$url = site_url()."admin/seo/default_edit/".$controller;
				redirect($url);
			}
		}

		$this->template_lite->assign("controller", $controller);
		$this->template_lite->assign("languages", $languages);
		$this->template_lite->assign("user_settings", $user_settings);
		
		$this->Menu_model->set_menu_active_item('admin_seo_menu', 'seo_default_list_item');
		$this->system_messages->set_data('header', l('admin_header_default_edit', 'seo'));
		$this->template_lite->view('default_edit_form');
	}

	/**
	 * Render modules management action
	 * 
	 * @param string $module_gid module guid
	 * @return void
	 */
	public function listing($module_gid=''){
		$seo_modules = $this->pg_seo->get_seo_modules();

		$modules = $this->pg_module->return_modules();
		foreach($modules as $module){
			if(isset($seo_modules[$module["module_gid"]])){
				$seo_modules[$module["module_gid"]]["module_name"] = $module["module_name"];
			}
		}
		$this->template_lite->assign("modules", $seo_modules);

		if(!$module_gid){
			$current_model = current($seo_modules);
			$module_gid = $current_model["module_gid"];
		}

		if($module_gid){
			$default_settings = $this->pg_seo->get_default_settings('user', $module_gid);
			if(!empty($default_settings)){
				foreach($default_settings as $method => $set_data){
					$default_settings[$method]["module_gid"] = $module_gid;
					$default_settings[$method]["default_title"] = true;
					$default_settings[$method]["default_description"] = true;
					$default_settings[$method]["default_keyword"] = true;
					$default_settings[$method]["default_header"] = true;
				}

				$user_settings = $this->pg_seo->get_all_settings('user', $module_gid);

				foreach($user_settings as $key=> $set_data){
					$default_settings[$set_data["method"]]["default_title"] = $set_data["default_title"];
					$default_settings[$set_data["method"]]["default_description"] = $set_data["default_description"];
					$default_settings[$set_data["method"]]["default_keyword"] = $set_data["default_keyword"];
					$default_settings[$set_data["method"]]["default_header"] = $set_data["default_header"];
					$default_settings[$set_data["method"]]["url"] = $this->pg_seo->url_template_transform($module_gid, $set_data["method"], $set_data["url_template"], 'base', 'scheme');
				}
			}
		
			$this->template_lite->assign("default_settings", $default_settings);
			$this->template_lite->assign("module_gid", $module_gid);
		}

		$this->Menu_model->set_menu_active_item('admin_seo_menu', 'seo_list_item');
		$this->template_lite->view('list');
	}

	/**
	 * Edit module settings action
	 * 
	 * @param string $module_gid module guid
	 * @param string $method method name
	 * @return void
	 */
	public function edit($module_gid, $method){
		$languages = $this->pg_language->languages;

		$default_settings = $this->pg_seo->get_default_settings('user', $module_gid, $method);
		$user_settings = $this->pg_seo->get_settings('user', $module_gid, $method);
		if($this->input->post('btn_save')){
			$url_template_data = json_decode($this->input->post('url_template_data', true), true);
			$validate_data = $this->pg_seo->validate_url_data($module_gid, $method, $url_template_data, $user_settings["url_vars"]);

			$post_data = array(
				"title" => $this->input->post('title', true),
				"keyword" => $this->input->post('keyword', true),
				"description" => $this->input->post('description', true),
				"header" => $this->input->post('header', true),
				"noindex" => intval($this->input->post('noindex')),
				"og_title" => $this->input->post('og_title', true),
				"og_type" => $this->input->post('og_type', true),
				"og_description" => $this->input->post('og_description', true),
			);
			$validate_settings = $this->Seo_model->validate_seo_settings('user', $module_gid, $method, $post_data);

			$validate_data['errors'] = array_merge($validate_data['errors'], $validate_settings['errors']);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
				
				foreach($languages as $lang_id => $lang_data){
					$user_settings['meta_'.$lang_id]['title'] = $post_data['title'][$lang_id];
					$user_settings['meta_'.$lang_id]['keyword'] = $post_data['keyword'][$lang_id];
					$user_settings['meta_'.$lang_id]['description'] = $post_data['description'][$lang_id];
					$user_settings['meta_'.$lang_id]['header'] = $post_data['header'][$lang_id];
					$user_settings['og_'.$lang_id]['og_title'] = $post_data['og_title'][$lang_id];
					$user_settings['og_'.$lang_id]['og_type'] = $post_data['og_type'][$lang_id];
					$user_settings['og_'.$lang_id]['og_description'] = $post_data['og_description'][$lang_id];
					$user_settings['noindex'] = $post_data['noindex'];
				}
			}else{
				$validate_settings['data']['url_template'] = $validate_data["data"]["url_template"];
				
				$this->pg_seo->set_settings('user', $module_gid, $method, $validate_settings['data']);

				$xml_data = $this->Seo_model->get_xml_route_file_content();
				$xml_data[$module_gid][$method] = $this->pg_seo->url_template_transform($module_gid, $method, $validate_data["data"]["url_template"], 'base', 'xml');

				$this->Seo_model->set_xml_route_file_content($xml_data);
				$this->Seo_model->rewrite_route_php_file();

				$this->system_messages->add_message('success', l('success_settings_saved', 'seo'));
				
				foreach($languages as $lang_id => $lang_data){
					$user_settings['meta_'.$lang_id]['title'] = $validate_settings['data']['title'][$lang_id];
					$user_settings['meta_'.$lang_id]['keyword'] = $validate_settings['data']['keyword'][$lang_id];
					$user_settings['meta_'.$lang_id]['description'] = $validate_settings['data']['description'][$lang_id];
					$user_settings['meta_'.$lang_id]['header'] = $validate_settings['data']['header'][$lang_id];
					$user_settings['og_'.$lang_id]['og_title'] = $validate_settings['data']['og_title'][$lang_id];
					$user_settings['og_'.$lang_id]['og_type'] = $validate_settings['data']['og_type'][$lang_id];
					$user_settings['og_'.$lang_id]['og_description'] = $validate_settings['data']['og_description'][$lang_id];
					$user_settings['noindex'] = $validate_settings['data']['noindex'];
				}
				
				//$url = site_url()."admin/seo/listing/".$module_gid;
				$url = site_url()."admin/seo/edit/".$module_gid."/".$method;
				redirect($url);
			}
		}

		if(!empty($user_settings["url_template"])){
			$user_settings["url_template_data"] = $this->pg_seo->url_template_transform($module_gid, $method, $user_settings["url_template"], "base", "js");
		}

		$this->template_lite->assign("languages", $languages);
		$this->template_lite->assign("default_settings", $default_settings);
		$this->template_lite->assign("user_settings", $user_settings);
		$this->template_lite->assign("module_gid", $module_gid);
		$this->template_lite->assign("method", $method);

		$this->pg_theme->add_js('seo-url-creator.js', 'seo');
		$this->Menu_model->set_menu_active_item('admin_seo_menu', 'seo_list_item');
		$this->system_messages->set_data('header', l('admin_header_edit', 'seo'));
		$this->template_lite->view('edit_form');
	}

	/**
	 * Tracker management action
	 * 
	 * @return void
	 */
	public function tracker(){

		$data = array(
			"seo_ga_default_activate" => $this->pg_module->get_module_config('seo', 'seo_ga_default_activate'),
			"seo_ga_default_account_id" => $this->pg_module->get_module_config('seo', 'seo_ga_default_account_id'),
			"seo_ga_default_placement" => $this->pg_module->get_module_config('seo', 'seo_ga_default_placement'),
			"seo_ga_manual_activate" => $this->pg_module->get_module_config('seo', 'seo_ga_manual_activate'),
			"seo_ga_manual_placement" => $this->pg_module->get_module_config('seo', 'seo_ga_manual_placement'),
			"seo_ga_manual_tracker_code" => $this->pg_module->get_module_config('seo', 'seo_ga_manual_tracker_code'),
		);
		if($this->input->post('btn_save')){
			$post_data = array(
				"seo_ga_default_activate" => $this->input->post('seo_ga_default_activate', true),
				"seo_ga_default_account_id" => $this->input->post('seo_ga_default_account_id', true),
				"seo_ga_default_placement" => $this->input->post('seo_ga_default_placement', true),
				"seo_ga_manual_activate" => $this->input->post('seo_ga_manual_activate', true),
				"seo_ga_manual_placement" => $this->input->post('seo_ga_manual_placement', true),
				"seo_ga_manual_tracker_code" => $this->input->post('seo_ga_manual_tracker_code', false),
			);

			$validate_data = $this->Seo_model->validate_tracker($post_data);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
			}else{
				foreach($validate_data["data"] as $setting_name => $value){
					$this->pg_module->set_module_config('seo', $setting_name, $value);
				}
				$this->system_messages->add_message('success', l('success_update_tracker', 'seo'));
			}
			$data = array_merge($data, $validate_data["data"]);
		}

		$this->template_lite->assign("data", $data);
		$this->Menu_model->set_menu_active_item('admin_seo_menu', 'seo_tracker');
		$this->template_lite->view('edit_tracker_form');
	}

	/**
	 * Analytics data management
	 * 
	 * @return void
	 */
	public function analytics(){
		$this->load->library('Whois');
		$this->load->library('Googlepr');
		$this->googlepr->cacheDir = TEMPPATH . 'cache';
		$this->load->helper('seo_analytics', 'seo');

		$url = Seo_analytics_helper::prepare_url($this->input->post('url') ? $this->input->post('url') : base_url());

		if (!$url){
			$this->system_messages->add_message('error', l('error_incorrect_url', 'seo'));
		}

		$whois = $this->whois->Lookup($url);
		$domain = $this->pg_module->get_module_config('seo', 'admin_seo_settings');
		if(!empty($domain)){
			$domain = unserialize($domain);
		}

		if ( (!$domain || $this->input->post('btn_save')) && $url){
			$domain	= array();
			$domain['registered']			= ('yes' === $whois['regrinfo']['registered']) ? true : false;
			$domain['created']				= isset($whois['regrinfo']['domain']['created']) ? $whois['regrinfo']['domain']['created'] : false;
			$domain['created_timestamp'] 	= strtotime($domain['created']);
			if ($domain['created_timestamp'])
			{
				$domain['age']['y']				= (int) date('Y') - date('Y', $domain['created_timestamp']);
				$domain['age']['m']				= (int) date('n') - date('n', $domain['created_timestamp']);
				$domain['age']['d']				= (int) date('j') - date('j', $domain['created_timestamp']);
				if ($domain['age']['m'] < 0)
				{
					$domain['age']['y']--;
					$domain['age']['m'] = 12 + $domain['age']['m'];
				}
			}

			// google page rank
			/*$domain['page_rank'] = $domain['registered'] ? $this->googlepr->get_pr($url) : 0;
			// alexa backlinks
			$domain['alexa_backlinks'] = Seo_analytics_helper::backlinks($url, 'alexa');
			// alexa traffic rank
			$domain['alexa_rank'] = Seo_analytics_helper::alexa_rank($url);
			// google backlinks
			$domain['google_backlinks'] = Seo_analytics_helper::backlinks($url, 'google');
			// yahoo backlinks
			$domain['yahoo_backlinks'] = Seo_analytics_helper::backlinks($url, 'yahoo');
			// technorati rank
			$domain['technorati_rank'] = Seo_analytics_helper::get_technorati_rank($url);
			// technorati authority
			$domain['technoraty_authority'] = Seo_analytics_helper::get_technorati_authority($url);
			// dmoz listed
			$domain['dmoz_listed'] = Seo_analytics_helper::dmoz_listed($url);
			// google directory listed
			$domain['google_listed'] = Seo_analytics_helper::google_listed($url);*/
			// google indexed
			$domain['google_indexed'] = Seo_analytics_helper::google_indexed($url);
			// yahoo indexed
			//$domain['yahoo_indexed'] = Seo_analytics_helper::yahoo_indexed($url);

			if ($url == Seo_analytics_helper::prepare_url(base_url())){
				$this->pg_module->set_module_config('seo', 'admin_seo_settings', serialize($domain));
			}
		}

		$check_links = array();
		$check_links['alexa_backlinks'] 		= 'http://www.alexa.com/site/linksin/'.urlencode($url);
		$check_links['alexa_rank']				= 'http://www.alexa.com/siteinfo/'.urlencode($url);
		$check_links['yahoo_indexed']			= 'http://search.yahoo.com/search?p=site%3A'.urlencode($url);
		$check_links['google_indexed']			= 'http://www.google.com/search?hl=en&lr=&ie=UTF-8&q=site%3A'.urlencode($url).'&filter=0';
		$check_links['google_listed']			= 'http://www.google.com/search?q='.urlencode($url).'&hl=en&cat=gwd%2FTop';
		$check_links['dmoz_listed']				= 'http://search.dmoz.org/cgi-bin/search?search=u:'.urlencode($url);
		$check_links['technoraty_authority']	= 'http://technorati.com/blogs/'.urlencode($url);
		$check_links['technorati_rank'] 		= 'http://technorati.com/blogs/'.urlencode($url);
		$check_links['yahoo_backlinks'] 		= 'http://search.yahoo.com/search?p=%22http%3A%2F%2F'.urlencode($url).'%22+%22http%3A%2F%2Fwww.'.urlencode($url).'%22+-site%3A'.urlencode($url).'+-site%3Awww.'.urlencode($url);
		$check_links['google_backlinks']		= 'http://www.google.com/search?hl=en&lr=&ie=UTF-8&q=link%3A'.urlencode($url).'&filter=0';

		$this->template_lite->assign('url', $url);
		$this->template_lite->assign('domain', $domain);
		$this->template_lite->assign('check_links', $check_links);

		$this->Menu_model->set_menu_active_item('admin_seo_menu', 'seo_analytics');
		$this->template_lite->view('list_analytics');
	}

	/**
	 * Robots.txt & sitemap management
	 *
	 * @return void 
	 */
	public function robots(){
		if($this->input->post('btn_save_robots')){
			$content = $this->input->post('content', true);
			$return = $this->Seo_model->set_robots_content($content);
			if(!empty($return["errors"])){
				$this->system_messages->add_message('error', $return["errors"]);
			}else{
				$this->system_messages->add_message('success', l('robots_txt_success_saved', 'seo'));
			}
		}

		$content = $this->Seo_model->get_robots_content();
		if(!empty($content["errors"])){
			$this->system_messages->add_message('error', $content["errors"]);
		}
		$this->template_lite->assign('content', $content["data"]);

		///// sitemap.xml
		if($this->input->post('btn_save_sitexml')){
			$params = array(
				"changefreq" => $this->input->post('changefreq', true),
				"lastmod" => intval($this->input->post('lastmod', true)),
				"lastmod_date" => $this->input->post('lastmod_date', true),
				"priority" => intval($this->input->post('priority', true)),
			);

			$this->pg_module->set_module_config('seo', 'sitemap_changefreq', $params['changefreq']);
			$this->pg_module->set_module_config('seo', 'sitemap_lastmod', $params['lastmod']);
			$this->pg_module->set_module_config('seo', 'sitemap_priority', $params['priority']);

			$generate_log = $this->Seo_model->generate_sitemap_xml($params);
			if(!empty($generate_log["errors"])){
				$this->system_messages->add_message('error', $generate_log["errors"]);
			}else{
				$this->system_messages->add_message('success', l('sitemap_xml_success_generated', 'seo'));
			}
		}

		$sitemap_data = $this->Seo_model->get_sitemap_data();
		if(!empty($sitemap_data["errors"])){
			$this->system_messages->add_message('error', $sitemap_data["errors"]);
		}
		$sitemap_data["data"]["current_date"] = date('Y-m-d H:i:s');
		$this->template_lite->assign('sitemap_data', $sitemap_data["data"]);
		$this->template_lite->assign('frequency_lang', ld('map_xml_frequency', 'seo'));
		
		$sitemap_changefreq = $this->pg_module->get_module_config('seo', 'sitemap_changefreq');
		$this->template_lite->assign('sitemap_changefreq', $sitemap_changefreq);
		
		$sitemap_lastmod = $this->pg_module->get_module_config('seo', 'sitemap_lastmod');
		$this->template_lite->assign('sitemap_lastmod', $sitemap_lastmod);
		
		$sitemap_priority = $this->pg_module->get_module_config('seo', 'sitemap_priority');
		$this->template_lite->assign('sitemap_priority', $sitemap_priority);
		
		$date_format = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('date_format', $date_format);

		///------
		$this->Menu_model->set_menu_active_item('admin_seo_menu', 'seo_robots');
		$this->template_lite->view('edit_robots_form');
	}
}
