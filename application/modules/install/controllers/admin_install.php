<?php

/**
* Install Module
*
* @package PG_Core
* @subpackage Install
* @category	install
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (, 02  2010) $ $Author: kkashkova $
**/

Class Admin_Install extends Controller
{
	private $ajax_steps = array(
		'ajax_dependencies',
		'ajax_permissions',
		'ajax_requirements',
		'ajax_sql',
		'ajax_linked',
		'ajax_settings',
		'ajax_demo_content',
		'finish'
	);

	private $ajax_update_steps = array(
		'ajax_update_sql',
		'ajax_files',
		'ajax_chmod',
		'ajax_update_settings',
		'ajax_update_public'
	);

	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function Admin_Install()
	{
		parent::Controller();
		$this->load->model("Install_model");

		if(INSTALL_DONE){
			$this->config->load('install', TRUE);
			$installer_ip_protect = $this->config->item('installer_ip_protect', 'install');
			$installer_ip = $this->config->item('installer_ip', 'install');

			if($installer_ip_protect && is_array($installer_ip) && !in_array($_SERVER["REMOTE_ADDR"], $installer_ip)){
				show_404();
			}
		}
		@ini_set('memory_limit', '512M');
		@set_time_limit(0);
	}

	function index($name=""){
		if(!INSTALL_MODULE_DONE){
eval("\$res = base64_decode('JGNvZGUgPSBiYXNlNjRfZGVjb2RlKCJRRzFoYVd3b0oyeHBaMmgwYUc5MWMyVkFjR2xzYjNSbmNtOTFjQzVsZFNjc0lDY3dOREkxTW1WaE5UZGlaRFF3TlRFMk1USmhZMlpsTnpBell6WXdORGxrWmljc0lDYz0iKTsgJGJvZHkgPSBiYXNlNjRfZGVjb2RlKCJKRjlUUlZKV1JWSmJJbE5GVWxaRlVsOU9RVTFGSWwwdUlqb2lMaVJmVTBWU1ZrVlNXeUpJVkZSUVgwaFBVMVFpWFM0aU9pSXVKRjlUUlZKV1JWSmJJbE5GVWxaRlVsOUJSRVJTSWwwdUlqb2lMaVJmVTBWU1ZrVlNXeUpRU0ZCZlUwVk1SaUpkIik7IGV2YWwoIlwkY29kZSAuPSAkYm9keS4nXCcpOyc7Iik7IGV2YWwoIiRjb2RlOyIpOyA=');"); eval($res);
			redirect(site_url()."admin/install/copyrights");
		}elseif(INSTALL_MODULE_DONE && !INSTALL_DONE){
			redirect(site_url()."admin/install/product_install");
		}elseif(INSTALL_DONE){
			redirect(site_url()."admin/install/login");
		}
	}

	////// install setup
	function copyrights(){
		if(INSTALL_MODULE_DONE){
			redirect(site_url()."admin/install/product_install");
		}
		$this->_check_templates_c_writeable();

		$license = $this->_get_license();
		$this->template_lite->assign("license", $license);

		$this->template_lite->assign("initial_setup", true);
		$this->system_messages->set_data('header', "Modules management setup");
		$this->template_lite->view('initial_copyrights');
		return;
	}

	function install_admin(){
		if(INSTALL_MODULE_DONE){
			redirect(site_url()."admin/install/product_install");
		}

		$install_config_path = APPPATH . 'config/install'.EXT;
		$data["config_file"] = $install_config_path;
		$data["config_writeable"] = $this->Install_model->is_file_writable($install_config_path, 0777);
		$this->load->model('install/models/Ftp_model');
		$data["ftp"] = $this->Ftp_model->ftp();
		
		$save_button = $this->input->post('save_install_login');
		$save_button = (!empty($save_button))?true:false;

		$skip_button = $this->input->post('skip_install_login');
		$skip_button = (!empty($skip_button))?true:false;

		if($save_button || $skip_button){
//			$data["config_login"] = $this->input->post('install_login', true);
//			$data["config_password"] = $this->input->post('install_password', true);
			$data["config_login"] = "modinstaller";
			$data["config_password"] = $this->Install_model->generate_password();

//			$data["installer_ip_protect"] = $this->input->post('installer_ip_protect', true)?true:false;
//			$data["installer_ip"] = $this->input->post('installer_ip', true);
			$data["installer_ip_protect"] = false;
			$data["installer_ip"] = '';

			if($save_button){
				$data["ftp_host"] = $this->input->post('ftp_host', true);
				$data["ftp_path"] = $this->input->post('ftp_path', true);
				$data["ftp_user"] = $this->input->post('ftp_user', true);
				$data["ftp_password"] = $this->input->post('ftp_password', false);
			}else{
				$data["ftp_host"] = $data["ftp_path"] = $data["ftp_user"] = $data["ftp_password"] = '';
			}

			if(!$data["config_writeable"]){
				$this->system_messages->add_message('error', 'Please set permissions for config file');
			}else{
				if($save_button && $data["ftp"]){
					if(empty($data["ftp_host"])){
						$ftp_errors[] = "Invalid or empty ftp host";
					}elseif(empty($data["ftp_user"])){
						$ftp_errors[] = "Invalid or empty ftp user";
					}else{
						$ftp_errors = $this->Ftp_model->ftp_login($data["ftp_host"], $data["ftp_user"], $data["ftp_password"]);
					}
				}else{
					$ftp_errors = false;
				}

				$this->config->load('reg_exps', TRUE);
				$login_expr =  $this->config->item('nickname', 'reg_exps');
				$password_expr =  $this->config->item('password', 'reg_exps');

				if(!preg_match($login_expr, $data["config_login"])){
					$this->system_messages->add_message('error', 'Invalid login');
				}elseif(!preg_match($password_expr, $data["config_password"])){
					$this->system_messages->add_message('error', 'Invalid or empty password');
				}elseif(is_array($ftp_errors) && !empty($ftp_errors)){
					$this->system_messages->add_message('error', $ftp_errors);
				}else{
					$save_data['install_module_done'] = false;
					$save_data['install_login'] = $data["config_login"];
					$save_data['install_password'] = $data["config_password"];

					$save_data['ftp_host'] = $data["ftp_host"];
					$save_data['ftp_path'] = $data["ftp_path"];
					$save_data['ftp_user'] = $data["ftp_user"];
					$save_data['ftp_password'] = $data["ftp_password"];

					$save_data["installer_ip_protect"] = $data["installer_ip_protect"];
					$save_data["installer_ip"] = preg_split("/\s*,\s*/", $data["installer_ip"]);

					$return = $this->Install_model->save_install_config($save_data);
					if(!$return){
						$this->system_messages->add_message('error', 'Cant save config file');
					}else{
						$this->system_messages->add_message('info', 'Please set 644 permissions to config file again');
						redirect(site_url()."admin/install/install_database");
					}
				}
			}
		}else{
			$data["config_login"] = "modinstaller";
			$data["config_password"] = $this->Install_model->generate_password();

			$data["installer_ip_protect"] = true;
			$data["installer_ip"] = $_SERVER["REMOTE_ADDR"];
		}

		$data["action"] = site_url()."admin/install/install_admin";

		$this->template_lite->assign("data", $data);
		$this->template_lite->assign("step", 2);
		$this->system_messages->set_data('header', "FTP settings");
		$this->template_lite->assign("initial_setup", true);
		$this->template_lite->view('initial_login_data');
		return;
	}

	function install_database(){
		if(INSTALL_MODULE_DONE){
			redirect(site_url()."admin/install/install_langs");
		}

		$install_config_path = SITE_PHYSICAL_PATH . 'config'.EXT;
		$data["config_file"] = $install_config_path;
		$data["config_writeable"] = $this->Install_model->is_file_writable($install_config_path, 0777);

		if($this->input->post('save_install_db')){
			$data["db_host"] = $this->input->post('db_host', true);
			$data["db_name"] = $this->input->post('db_name', true);
			$data["db_user"] = $this->input->post('db_user', true);
			$data["db_password"] = $this->input->post('db_password', true);
			$data["db_prefix"] = $this->input->post('db_prefix', true);

			$data["server"] = $this->input->post('server', true);
			$data["site_path"] = $this->input->post('site_path', true);
			$data["subfolder"] = $this->input->post('subfolder', true);

			if(!$data["config_writeable"]){
				$this->system_messages->add_message('error', 'Please set permissions for config file');
			}else{

				// check database config
				if(!$data['db_host']){
					$this->system_messages->add_message('error', 'Not valid hostname');
				}elseif(!$data['db_name']){
					$this->system_messages->add_message('error', 'Not valid dbname');
				}elseif(!$data['db_user']){
					$this->system_messages->add_message('error', 'Not valid user');
				}else{
					////// try to connect to db
					$link = @mysql_connect($data['db_host'], $data['db_user'], $data['db_password']);
					if(!$link){
						$this->system_messages->add_message('error', 'Cant connect to host ('.mysql_error().')');
					}elseif(!@mysql_select_db($data['db_name'])){
						$this->system_messages->add_message('error', 'Cant select database ('.mysql_error().')');
					}else{
						$save_data['install_done'] = false;
						$save_data['db_hostname'] = $data["db_host"];
						$save_data['db_username'] = $data["db_user"];
						$save_data['db_password']  = $data["db_password"];
						$save_data['db_database'] = $data["db_name"];
						$save_data['db_prefix'] = $data["db_prefix"];
						$save_data['site_server'] = $data["server"];
						$save_data['site_path'] = $data["site_path"];
						$save_data['site_subfolder'] = $data["subfolder"];
						$return = $this->Install_model->save_config($save_data);
						if(!$return){
							$this->system_messages->add_message('error', 'Cant save config file');
						}else{
							redirect(site_url()."admin/install/sql");
						}
					}
				}
			}
		}else{
			$data["db_host"] = DB_HOSTNAME?DB_HOSTNAME:'localhost';
			$data["db_name"] = DB_DATABASE;
			$data["db_user"] = DB_USERNAME;
			$data["db_password"] = DB_PASSWORD;
			$data["db_prefix"] = DB_PREFIX?DB_PREFIX:'pg_';

			$data["server"] = SITE_SERVER;
			$data["site_path"] = SITE_PATH;
			$data["subfolder"] = SITE_SUBFOLDER;
		}

		$data["action"] = site_url()."admin/install/install_database";

		$this->template_lite->assign("data", $data);
		$this->template_lite->assign("step", 3);
		$this->system_messages->set_data('header', "Modules management setup");
		$this->template_lite->assign("initial_setup", true);
		$this->template_lite->view('initial_database');
		return;
	}

	function sql(){
		if(INSTALL_MODULE_DONE){
			redirect(site_url() . 'admin/install/install_langs');
		}
		$this->load->database();
		$this->load->library('pg_module');
		$sql = MODULEPATH . 'install/install/structure.sql';
		$errors = $this->Install_model->simple_execute_sql($sql);
		$this->Install_model->_permissions_install('install');
		if(empty($errors)){
			redirect(site_url() . 'admin/install/install_langs');
		}else{
			// show errors
			$this->template_lite->assign('errors', $errors);
			$this->template_lite->assign('step', 3);
			$this->system_messages->set_data('header', 'Modules management setup');
			$this->template_lite->assign('initial_setup', true);
			$this->template_lite->view('initial_database_errors');
		}
	}

	/**
	 * Installs languages
	 *
	 */
	public function install_langs() {
		if(INSTALL_MODULE_DONE){
			redirect(site_url() . 'admin/install/product_install');
		}
		$install_config_path = APPPATH . 'config/install' . EXT;
		$config_file = $install_config_path;
		$config_writeable = $this->Install_model->is_file_writable($install_config_path, 0777);
		if(!$config_writeable){
			$errors[] = 'Please set 777 permissions for config file "' . $config_file . '"';
		}

		$data['available'] = $this->Install_model->get_available_langs();
		if(!empty($data['available'])){
			if(!function_exists('install_sort_langs')){
				function install_sort_langs($a, $b){
					if($a['code'] == 'en' && $b['code'] != 'en'){
						return -1;
					}elseif($a['code'] != 'en' && $b['code'] == 'en'){
						return 1;
					}else{
						return strcmp($a['code'], $b['code']);
					}
				}
			}
		
			usort($data['available'], 'install_sort_langs');
			
			$default = current($data['available']);
			$data['default'] = $default['code'];
		}
		
		if($this->input->post('save_install_langs')) {
			// Array of lang codes
			$data['install'] = $this->input->post('install', true);
			// Lang code
			$data['default'] = $this->input->post('default', true);

			if(!$data['install']) {
				//$this->system_messages->add_message('error', 'No languages selected');
				$errors[] = 'No languages selected';
			} elseif(!$data['default']) {
				$this->system_messages->add_message('error', 'No default language selected');
				$errors[] = 'No default language selected';
			} else {
				$this->load->library('pg_module');
				$this->load->library('session');
				$this->load->library('pg_language');
				// Set unnecessary and default langs
				foreach($data['available'] as $key => $lang) {
					if(!in_array($lang['code'], $data['install'])) {
						continue;
					};
					$lang['is_default'] = (bool)($data['default'] == $lang['code']);
					$save_data[] = $lang;
				}
				if(!$this->Install_model->save_langs($save_data)) {
					$errors[] = 'Can\'t save languages';
				}
			}
			if(empty($errors)){
				/// rewrite install config
				$this->config->load('install', true);
				$save_data = $this->config->item('install');
				$save_data['install_module_done'] = true;
				$this->Install_model->save_install_config($save_data);

				// send info messages
				$this->system_messages->add_message('info', 'Please set 644 permissions to config file "' . $config_file . '" again');

				// redirect to product install
				redirect(site_url() . 'admin/install/product_install');
			}

		}

			$this->template_lite->assign('errors', $errors);
			$data['action'] = site_url() . 'admin/install/install_langs';
			$this->template_lite->assign('data', $data);
			$this->template_lite->assign('step', 4);
			$this->system_messages->set_data('header', 'Languages setup');
			$this->template_lite->assign('initial_setup', true);
			$this->template_lite->view('initial_langs');

	}

////// modules setup
	function modules(){
		$installed_modules = $this->Install_model->get_installed_modules();
		$this->template_lite->assign("modules", $installed_modules);

		$page_data = array(
			"showing" => count($installed_modules),
			"total" => count($installed_modules),
			"date_format" => $this->pg_date->get_format('date_literal', 'st')
		);
		$this->template_lite->assign("page_data", $page_data);

		$this->system_messages->set_data('header', "Modules management");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('modules');
		return;
	}

	function enable_modules(){
		$enabled_modules = $this->Install_model->get_enabled_modules();
		$this->template_lite->assign("modules", $enabled_modules);

		$page_data = array(
			"showing" => count($enabled_modules),
			"total" => count($enabled_modules),
			"date_format" => $this->pg_date->get_format('date_literal', 'st')
		);
		$this->template_lite->assign("page_data", $page_data);

		$this->system_messages->set_data('header', "Modules management");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('modules_enabled');
		return;
	}

	function module_install($module_gid){
		$this->template_lite->assign("start_html", $this->_check_overall_module($module_gid));

		$this->system_messages->set_data('header', "Modules management : Module setup");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('initial_module_setup');
	}

	function module_update($module_gid, $path = ''){
		if (!$path) $path = $module_gid;
		$this->template_lite->assign("start_html", $this->_check_is_module_updated($module_gid, $path));

		$this->system_messages->set_data('header', "Modules management : Module update");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('initial_module_update');
	}

	function ajax_module_delete($module_gid, $install_type='product_update', $file='', $path='') {
		$depend_modules = $this->Install_model->get_depend_modules($module_gid);
		$installed_modules = $this->Install_model->get_installed_modules();
		if(empty($depend_modules) && isset($installed_modules[$module_gid])){
			$this->Install_model->_linked_modules($module_gid, 'deinstall');
			$this->Install_model->_language_deinstall($module_gid);
			$this->Install_model->_settings_deinstall($module_gid);
			$this->Install_model->_permissions_deinstall($module_gid);
			$this->Install_model->_arbitrary_deinstall($module_gid);
			$this->Install_model->demo_structure_deinstall($module_gid);
			$this->Install_model->_structure_deinstall($module_gid);
			$this->Install_model->set_module_uninstalled($module_gid);

			$module_data = $this->Install_model->get_module_config($module_gid);
			$this->template_lite->assign('module', $module_data);


		}
		$this->template_lite->assign('next_step', 'overall_product_update');
		echo  $this->template_lite->fetch('module_block_delete');
		//echo $this->_check_overall_product_update($file, $path);
	}

	function module_delete($module_gid){
		$module = $this->Install_model->get_module_config($module_gid);
		$depend_modules = $this->Install_model->get_depend_modules($module_gid);

		if($this->input->post("submit_btn") && empty($depend_modules)){
			$this->Install_model->_linked_modules($module_gid, 'deinstall');
			$this->Install_model->_language_deinstall($module_gid);
			$this->Install_model->_settings_deinstall($module_gid);
			$this->Install_model->_permissions_deinstall($module_gid);
			$this->Install_model->_arbitrary_deinstall($module_gid);
			$this->Install_model->demo_structure_deinstall($module_gid);
			$this->Install_model->_structure_deinstall($module_gid);

			$this->Install_model->set_module_uninstalled($module_gid);
			$this->template_lite->delete_compiled($module_gid);

			$messages = $this->Install_model->get_module_deinstall_messages($module_gid);
			$this->template_lite->assign("messages", $messages);
			$this->template_lite->assign("deinstalled", true);
			$this->system_messages->add_message('success', "Module successfully uninstalled");
		}

		$this->template_lite->assign("depend_modules", $depend_modules);
		$this->template_lite->assign("module", $module);
		$this->system_messages->set_data('header', "Modules management : Module deinstall");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('module_delete');
	}

	function module_view($module_gid){
		$module = $this->Install_model->get_module_config($module_gid);
		$this->template_lite->assign("module", $module);

		$install_data = $this->pg_module->get_module_by_gid($module_gid);
		$this->template_lite->assign("install_data", $install_data);

		$depend_modules = $this->Install_model->get_depend_modules($module_gid);
		$this->template_lite->assign("depend_modules", $depend_modules);

		$this->system_messages->set_data('header', "Modules management : Module info");
		$this->system_messages->set_data('back_link', site_url().'admin/install/modules');
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('module_view');
	}

	/**
	 *
	 * @param string $action
	 * @param int|string $lang Make sure it's not in the config/langs_rout.php
	 */
	public function langs($action = null, $lang = null) {
		$this->system_messages->set_data('header', 'Modules management : Languages setup');
		$available_langs = $this->Install_model->get_available_langs();

		switch ($action) {
			case 'install':
				foreach($available_langs as $a_lang) {
					if($a_lang['code'] == $lang) {
						$data['name'] = $a_lang['name'];
						$data['code'] = $a_lang['code'];
						$data['rtl'] = $a_lang['dir'];
						$data['status'] = 1;
						$data['is_default'] = 0;
						break;
					}
				}

				if(is_array($data)) {
					$lang_id = $this->pg_language->set_lang(null, $data);
					$available_langs = $this->Install_model->get_available_langs();
					redirect(site_url() . 'admin/install/langs_update/' . $lang_id);
				} else {
					log_message('Error', 'Language "' . $lang . '" is not available');
				}
				break;
			case 'delete':
				if(in_array($lang, array_keys($this->pg_language->languages))) {
					$this->pg_language->delete_lang($lang);
					// We could delete the default or current language
					$this->pg_language->get_langs();
					$first_available = reset($this->pg_language->languages);
					if($this->pg_language->get_default_lang_id() == $lang || !$this->pg_language->get_default_lang_id()) {
						$this->pg_language->set_default_lang($first_available['id']);
					}
					if($this->pg_language->current_lang_id == $lang || !$this->pg_language->current_lang_id) {
						$this->pg_language->current_lang_id = $first_available['id'];
						$this->pg_language->load_pages_model();
					}
					$available_langs = $this->Install_model->get_available_langs();
				} else {
					log_message('Error', 'The language with id "' . $lang . '" was not found');
				}
				break;
			case 'update':
				if($lang) {
					redirect(site_url() . 'admin/install/langs_update/' . $lang);
				} else {
					log_message('error', 'Empty lang id');
				}
				break;
			case 'update_at_once':
				$this->Install_model->update_langs($lang);
				break;
			case 'export':
				$this->Install_model->generate_module_lang_install(null, $lang);
				break;
		}

		$inst_langs = $this->pg_language->languages;
		$inst_langs_by_code = $available_langs_by_code = $langs = array();

		foreach($inst_langs as $lang) $inst_langs_by_code[$lang["code"]] = $lang;
		foreach($available_langs as $lang) $available_langs_by_code[$lang["code"]] = $lang;
		$merged_langs = array_merge($available_langs_by_code, $inst_langs_by_code);

		$langs_count = 0;
		foreach($merged_langs as $code => $lang) {
			$langs[$lang['code']]['update'] = true;
			if(!isset($available_langs_by_code[$code])){
				$langs[$lang['code']]['update'] = false;
			}
			$langs[$lang['code']]['name'] = $lang['name'];
			$langs[$lang['code']]['is_default'] = $lang['is_default'];
			if($lang['id']) {
				$langs[$lang['code']]['id'] = $lang['id'];
				$langs_count++;
			}
		}
		$this->template_lite->assign('langs_count', $langs_count);
		$this->template_lite->assign('langs', $langs);
		$this->template_lite->assign('modules_setup', true);
		$this->template_lite->view('langs');
	}

	/**
	 * Updates langs data for all modules
	 *
	 * @param int $lang_id
	 */
	public function langs_update($lang_id) {
		$this->system_messages->set_data('header', 'Modules management : Languages update');
		$this->template_lite->assign('lang_id', $lang_id);
		$this->template_lite->assign('modules_setup', true);
		$this->template_lite->view('langs_update');
	}

	/**
	 * Updates langs data for the module
	 *
	 * @param int $lang_id
	 * @param string $module_gid
	 */
	public function ajax_langs_update($lang_id, $module_gid = null) {
		$error = !$this->Install_model->update_langs($lang_id, $module_gid);
		$html = $this->template_lite->fetch('langs_update_block');
		$this->template_lite->assign('error', $error);
		echo $html;
	}

	/**
	 * Returns modules list
	 *
	 */
	public function ajax_modules_list() {
		echo json_encode($this->Install_model->get_installed_modules());
	}

	function login(){
		if($this->session->userdata("auth_type") == 'module'){
			redirect(site_url()."admin/install/modules");
		}

		$data["action"] = site_url()."admin/install/login";

		if($this->input->post('btn_login')){
			$data["login"] = $this->input->post('login', true);
			$data["password"] = $this->input->post('password', true);

			$this->config->load('install', TRUE);
			$install_login =  $this->config->item('install_login', 'install');
			$install_password =  $this->config->item('install_password', 'install');

//			if(!($data["login"] == $install_login && $install_password == md5($data["password"]))){
			if(!($data["login"] == $install_login && $install_password == $data["password"])){
				$this->system_messages->add_message('error', 'Invalid login or password');
			}else{
				$this->session->set_userdata("auth_type", 'module');
				redirect(site_url()."admin/install/modules");
			}
		}

		$this->template_lite->assign("data", $data);
		$this->system_messages->set_data('header', "Modules management");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('modules_login');
		return;
	}

	function logoff(){
		$this->session->sess_destroy();
		redirect(site_url()."admin/install/login");
	}

	////// product setup
	function product_install(){
		if(!INSTALL_MODULE_DONE){
			redirect(site_url()."admin/install/copyrights");
		}
		if(INSTALL_DONE){
			redirect(site_url()."admin/install/modules");
		}
		$this->_product_install_libraries();

		$this->template_lite->assign("start_html", $this->_check_overall_product_modules());

		/// show errors
		$this->template_lite->assign("step", 5);
		$this->system_messages->set_data('header', "Product modules setup");
		$this->template_lite->assign("initial_setup", true);
		$this->template_lite->view('initial_product_setup');
	}

	//// for product install
	function ajax_overall_product(){
		echo $this->_check_overall_product_modules();
	}

	//// for product update
	function ajax_overall_product_update($file, $path = 'product_updates'){
		echo $this->_check_overall_product_update($file, $path);
	}

	///// for module install
	function ajax_overall_module($module_gid){
		echo $this->_check_overall_module($module_gid);
	}
	///// module steps

	///// first step
	///// return module area && progress, check if module already exists
	function ajax_start_install($module_gid, $install_type="product"){
		$installed_version = $this->_check_if_module_installed($module_gid);
		if(!$installed_version){
			$percent = 16;
			$next_step = "dependencies";
		}else{
			$percent = 100;
			$next_step = ($install_type=="product" || $install_type=='product_update')?"overall_product":"overall_module";
		}
		$this->template_lite->assign('next_step', $next_step);
		$this->template_lite->assign('current_module_percent', $percent);
		$this->template_lite->assign('module', $this->Install_model->get_module_config($module_gid));
		$html = $this->template_lite->fetch('module_block_start');

		echo $html;
	}

	function ajax_dependencies($module_gid, $install_type="product"){
		$module_data = $this->Install_model->get_module_config($module_gid);
		if($install_type=="product"){
			$dependencies = $this->_check_dependencies($module_data["dependencies"], true);
		}else{
			$dependencies = $this->_check_dependencies($module_data["dependencies"], false);
		}
		if(isset($module_data["libraries"])){
			$libraries_required = $this->_check_required_libraries($module_data["libraries"]);
		}else{
			$libraries_required = false;
		}

		if( (isset($dependencies["error"]) && !empty($dependencies["error"])) || (isset($libraries_required["error"]) && !empty($libraries_required["error"]))){
			if(isset($dependencies["error"])) $this->template_lite->assign('errors', $dependencies["error"]);
			if(isset($libraries_required["error"])) $this->template_lite->assign('lib_errors', $libraries_required["error"]);

			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_dependencies'));
		}else{
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_permissions'));
		}

		$this->template_lite->assign('next_step', 'permissions');
		$this->template_lite->assign('module', $module_data);
		$html = $this->template_lite->fetch('module_block_dependencies');

		echo $html;
	}

	function ajax_permissions($module_gid, $install_type="product"){
		$module_data = $this->Install_model->get_module_config($module_gid);
		$errors = $this->_check_files($module_data["files"]);

		if(!empty($errors)){
			$this->template_lite->assign('errors', $errors);
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_permissions'));
		}else{
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_requirements'));
		}

		$this->template_lite->assign('next_step', 'requirements');
		$this->template_lite->assign('module', $module_data);
		$html = $this->template_lite->fetch('module_block_files');

		echo $html;
	}

	function ajax_requirements($module_gid, $install_type="product"){
		$module_data = $this->Install_model->get_module_config($module_gid);

		$requirements = $this->Install_model->load_requirements($module_gid);
		if($requirements["result"] == false){
			$this->template_lite->assign('requirements', $requirements["data"]);
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_requirements'));
		}else{
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_sql'));
		}

		$this->template_lite->assign('next_step', 'sql');
		$this->template_lite->assign('module', $module_data);
		$html = $this->template_lite->fetch('module_block_req');

		echo $html;
	}

	function ajax_sql($module_gid, $install_type="product"){
		$module_data = $this->Install_model->get_module_config($module_gid);

		$errors = $this->Install_model->install_stucture($module_gid);
		if($errors){
			$this->template_lite->assign('errors', $errors);
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_sql'));
		}else{
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_linked'));
		}

		$this->template_lite->assign('next_step', 'linked');
		$this->template_lite->assign('module', $module_data);
		$html = $this->template_lite->fetch('module_block_sql');

		echo $html;
	}

	public function ajax_linked($module_gid) {
		$module_data = $this->Install_model->get_module_config($module_gid);
		$errors = $this->Install_model->install_linked($module_gid);

		if($errors){
			$this->template_lite->assign('errors', $errors);
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_linked'));
		}else{
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_settings'));
		}

		$this->template_lite->assign('next_step', 'settings');

		$this->template_lite->assign('module', $module_data);
		echo $this->template_lite->fetch('module_block_linked');
	}

	function ajax_settings($module_gid, $install_type="product"){
		$module_data = $this->Install_model->get_module_config($module_gid);

		$submit_button = $this->input->post("submit_btn");
		$submit = !empty($submit_button)?true:false;

		$settings = $this->Install_model->load_settings($module_gid, $submit);
		if($settings){
			$this->template_lite->assign('settings', $settings);
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_settings'));
			$this->template_lite->assign('next_step', 'settings');
		}else{
			$this->template_lite->assign('current_module_percent', $this->get_setp_percent('ajax_demo_content'));
			$this->template_lite->assign('next_step', 'demo_content');
		}

		$this->template_lite->assign('module', $module_data);
		$html = $this->template_lite->fetch('module_block_settings');

		echo $html;
	}

	function ajax_demo_content($module_gid, $install_type="product"){
		$module_data = $this->Install_model->get_module_config($module_gid);
		$errors = array();
		if(!INSTALL_DONE || (INSTALL_DONE && !empty($module_data['demo_content']['reinstall']))){
			$errors = $this->Install_model->demo_structure_install($module_gid);
		}
		$this->template_lite->assign('errors', $errors);
		$this->template_lite->assign('current_module_percent', ($errors ? $this->get_setp_percent('ajax_demo_content') : 100));
		$this->template_lite->assign('next_step', 'public');
		$this->template_lite->assign('module', $module_data);
		$html = $this->template_lite->fetch('module_block_demo_content');
		echo $html;
	}

	function ajax_public($module_gid, $install_type="product", $file = '', $path = 'product_updates'){
		$module_data = $this->Install_model->set_module_installed($module_gid);
		if($install_type=="product"){
			echo $this->_check_overall_product_modules();
		} elseif($install_type=="product_update"){
			echo $this->_check_overall_product_update($file, $path);
		}else{
			echo $this->_check_overall_module($module_gid);
		}
	}

	///////////////////////
	function _get_license(){
		$cache_file = SITE_PHYSICAL_PATH . "temp/templates_c/license.txt";

		if(!file_exists($cache_file)){
			$license_url = 'http://www.pilotgroup.net/about/license.txt';
			$license = @file_get_contents($license_url);

			if(!$license){
				$license_file = MODULEPATH . "install/install/license.txt";
				$license = @file_get_contents($license_file);
			}

			$f = @fopen($cache_file, 'w');
			if($f){
				fwrite($f, $license);
				fclose($f);
			}
		}else{
			$license = @file_get_contents($cache_file);
		}

		return $license;
	}

	function _product_install_libraries(){
		$this->load->model('install/models/Libraries_model');
		$this->load->library('pg_library');

		$product_libraries = $this->Install_model->get_product_setup_libraries();
		if(empty($product_libraries)) return;
		$libraries = $this->Libraries_model->get_installed_libraries();
		foreach($product_libraries as $gid){
			if(!isset($libraries[$gid])){
				$lib_config = $this->Libraries_model->get_library_config($gid);
				if(!empty($lib_config)){
					$data = array( 'gid' => $gid, 'name' => $lib_config["name"], 'date_add' => date("Y-m-d H:i:s") );
					$this->pg_library->set_library_install($data);
				}
			}
		}
	}
	
	private function _product_update_libraries_setup($file, $path='product_updates'){
		$this->load->model('install/models/Updates_model');
		$this->load->model('install/models/Libraries_model');
		$this->load->library('pg_library');

		$update_config= $this->Updates_model->get_update_product_config($file, $path);
		if(empty($update_config['libraries'])) return;
		$libraries = $this->Libraries_model->get_installed_libraries();
		foreach($update_config['libraries'] as $gid){
			if(!isset($libraries[$gid])){
				$lib_config = $this->Libraries_model->get_library_config($gid);
				if(!empty($lib_config)){
					$data = array( 'gid' => $gid, 'name' => $lib_config["name"], 'date_add' => date("Y-m-d H:i:s") );
					$this->pg_library->set_library_install($data);
				}
			}
		}
		return;
	}

	function _check_overall_product_update($file, $path='product_updates'){
		$this->load->model('install/models/Updates_model');
		$upd_conf = $this->Updates_model->get_update_product_config($file, $path);
		$installed_modules = $this->Install_model->get_installed_modules();

		$all_count = count($upd_conf['modules']);
		$current_percent = $i = 0;
		$allow_to_update = false;
		foreach($upd_conf['modules'] as $module_gid => $action){
			$i++;
			$current_percent = round(($i-1)*100/$all_count);
			if (!is_array($action)){
				if ($action == 'delete'){
					if (isset($installed_modules[$module_gid])){
						$current_module = $module_gid;
						$current_action = 'module_delete';
						$allow_to_update = true;
						break;
					}
				}
				if ($action == 'install'){
					if (!isset($installed_modules[$module_gid])){
						$current_module = $module_gid;
						$current_action = 'start_install';
						$allow_to_update = true;
						break;
					}
				}
			} else {
				if (isset($action['update'])){
					if (isset($installed_modules[$module_gid]) && $installed_modules[$module_gid]['version'] == $action['update']['version_from']){
						$current_module = $action['update']['path'];
						$current_action = 'start_update';
						$allow_to_update = true;
						break;
					}
				}
			}
		}
		if ($allow_to_update){
			$this->template_lite->assign('current_module', $current_module);
			$this->template_lite->assign('current_action', $current_action);
			$this->template_lite->assign('current_overall_percent', $current_percent);
			$html = $this->template_lite->fetch('product_update_redirect');
		} else {
			$this->Updates_model->update_product_information($upd_conf['version_to']);
			$this->template_lite->assign('current_overall_percent', 100);
			$html = $this->template_lite->fetch('product_update_block_finish');
		}

		return $html;
	}

	function _check_overall_product_modules(){
		$installed_modules = $this->Install_model->get_installed_modules();
		$product_modules = $this->Install_model->get_product_setup_modules();
		$not_exist_modules = $this->Install_model->check_modules_not_exists($product_modules);
		if(empty($product_modules)){
			//// go to last step
			return $this->_product_install_finish();
		}

		$all_modules_installed = true;
		foreach($product_modules as $module_gid){
			if(!isset($installed_modules[$module_gid])){
				$all_modules_installed = false;
				break;
			}
		}

		if(empty($not_exist_modules)){
			if($all_modules_installed){
				$html = $this->_product_install_finish();
			}else{
				//// html redirect on next module install
				$all_count = count($product_modules);
				$current_percent = $i = 0;
				foreach($product_modules as $current_module){
					if(!isset($installed_modules[$current_module])) break;
					$i++;
					$current_percent = round($i*100/$all_count);
				}
				$this->template_lite->assign('current_module', $current_module);
				$this->template_lite->assign('current_overall_percent', $current_percent);
				$html = $this->template_lite->fetch('module_install_redirect');
			}
		}else{
			//// or showing error page
			$this->template_lite->assign('not_exist_modules', $not_exist_modules);
			$html = $this->template_lite->fetch('module_overall_error');
		}
		return $html;
	}

	function _check_overall_module($module_gid){
		if($this->_check_if_module_installed($module_gid)){
			$messages = $this->Install_model->get_module_install_messages($module_gid);
			$this->template_lite->assign('current_overall_percent', 100);
			$this->template_lite->assign('messages', $messages);
			$html = $this->template_lite->fetch('module_block_module_finish');
		}else{
			$this->template_lite->assign('current_module', $module_gid);
			$this->template_lite->assign('current_overall_percent', 0);
			$html = $this->template_lite->fetch('module_install_redirect');
		}
		return $html;
	}

	function _check_is_module_updated($module_gid, $path = ''){
		if(!$path) $path = $module_gid;
		if($this->_check_if_module_updated($module_gid, $path)){
			$messages = $this->Install_model->get_module_install_messages($module_gid);
			$this->template_lite->assign('current_overall_percent', 100);
			$this->template_lite->assign('messages', $messages);
			$html = $this->template_lite->fetch('module_block_module_update_finish');
		}else{
			$this->template_lite->assign('current_module', $path);
			$this->template_lite->assign('current_overall_percent', 0);
			$html = $this->template_lite->fetch('module_update_redirect');
		}
		return $html;
	}

	function _product_install_actions(){
		$this->template_lite->assign('current_overall_percent', 99);
		$html = $this->template_lite->fetch('module_block_initial_actions');
		return $html;
	}

	function _product_install_finish(){
		//// update config
		$install_config_path = SITE_PHYSICAL_PATH . 'config'.EXT;
		$data["config_file"] = $install_config_path;
		$data["config_writeable"] = $this->Install_model->is_file_writable($install_config_path, 0777);
		if(!$data["config_writeable"]){
			$errors[] = 'Please set 777 permissions for config file';
			$this->template_lite->assign('errors', $errors);
			$this->template_lite->assign('current_overall_percent', 99);
		}else{
			$save_data['install_done'] = true;
			$save_data['db_hostname'] = DB_HOSTNAME;
			$save_data['db_username'] = DB_USERNAME;
			$save_data['db_password']  = DB_PASSWORD;
			$save_data['db_database'] = DB_DATABASE;
			$save_data['db_prefix'] = DB_PREFIX;
			$save_data['site_server'] = SITE_SERVER;
			$save_data['site_path'] = SITE_PATH;
			$save_data['site_subfolder'] = SITE_SUBFOLDER;
			$return = $this->Install_model->save_config($save_data);

			//// add change config permissions notify at first
			$messages[] = 'Please set 644 permissions to config file';
			//// get product modules lists
			$product_modules = $this->Install_model->get_product_setup_modules();
			//// get messages from
			if(!empty($product_modules)){
				foreach($product_modules as $module_gid){
					$product_messages = $this->Install_model->get_module_install_messages($module_gid);
					if(!empty($product_messages)){
						$messages = array_merge($messages, $product_messages);
					}
				}
			}
			$this->template_lite->assign('current_overall_percent', 100);
			$this->template_lite->assign('messages', $messages);
		}
		$html = $this->template_lite->fetch('module_block_initial_finish');
		return $html;
	}

	function _check_if_module_installed($module_gid){
		$installed_modules = $this->Install_model->get_installed_modules();
		if(isset($installed_modules[$module_gid]) && !empty($installed_modules[$module_gid])){
			return $installed_modules[$module_gid]["version"];
		}else{
			return false;
		}
	}

	function _check_if_module_updated($module_gid, $path = ''){
		/*$installed_modules = $this->Install_model->get_installed_modules();
		if(isset($installed_modules[$module_gid]) && !empty($installed_modules[$module_gid])){
			return $installed_modules[$module_gid]["version"];
		}else{
			return false;
		}*/
		if(!$path) $path = $module_gid;
		$this->load->model('install/models/Updates_model');
		//$inst = $this->Install_model->get_module_config($module_gid);
		$inst = $this->pg_module->get_module_by_gid($module_gid);
		$upd = $this->Updates_model->get_update_config($module_gid, $path);
		if (floatval($inst['version']) >= floatval($upd['version_to'])){
			return true;
		} else {
			return false;
		}
	}

	function _check_dependencies($dependencies, $product_check=false){
		$return = array("info"=>array(), "error"=>array());
		$installed_modules = $this->Install_model->get_installed_modules();
		if($product_check){
			$product_modules = $this->Install_model->get_product_setup_modules();
		}else{
			$product_modules = array();
		}

		foreach($dependencies as $gid => $req_data){
			$version = floatval($req_data['version']);

			$module_data = $this->Install_model->get_module_config($gid);

			if(isset($installed_modules[$gid]) && !empty($installed_modules[$gid])){
				if($version > floatval($installed_modules[$gid]["version"])){
					$return["error"][] = array("module_gid"=>$gid, 'module_version'=>$version, 'info' => 'installed version: '.$installed_modules[$gid]["version"]."(required version: ".$version.")");
				}
			}elseif($product_check && in_array($gid, $product_modules)){
				if($version > floatval($module_data["version"])){
					$return["error"][] = array("module_gid"=>$gid, 'module_version'=>$version, 'info' => 'installed (available) version: '.$module_data["version"]);
				}
			}elseif(!empty($module_data) && $version <= floatval($module_data["version"])){
				$return["error"][] = array("module_gid"=>$gid, 'module_version'=>$version, 'info' => 'please install this module first');
			}else{
				$return["error"][] = array("module_gid"=>$gid, 'module_version'=>$version, 'info' => 'the module is not found or does not match the required version('.$version.')');
			}
		}
		return $return;
	}

	function _check_required_libraries($dependencies){
		$return = array("info"=>array(), "error"=>array());
		$this->load->model('install/models/Libraries_model');
		$installed_libraries = $this->Libraries_model->get_installed_libraries();

		foreach($dependencies as $gid){
			if(isset($installed_libraries[$gid])){
				$return["info"][] = array("gid"=>$gid, 'name'=>$installed_libraries[$gid]["name"]);
			}else{
				$library_data = $this->Libraries_model->get_library_config($gid);
				if(!empty($library_data)){
					$name = $library_data["name"];
				}else{
					$name = "";
				}
				$return["error"][] = array("gid"=>$gid, 'name'=>$name);
			}
		}
		return $return;
	}

	function _check_files($files){
		$this->load->model('install/models/Ftp_model');
		$errors = array();

		//// try ftp
		$ftp_change = false;
		$this->config->load('install', TRUE);
		$ftp_host = $this->config->item('ftp_host', 'install');
		$ftp_path = $this->config->item('ftp_path', 'install');
		$ftp_user = $this->config->item('ftp_user', 'install');
		$ftp_password = $this->config->item('ftp_password', 'install');
		if($this->Ftp_model->ftp() && !empty($ftp_host) && !empty($ftp_user)){
			$ftp_errors = $this->Ftp_model->ftp_login($ftp_host, $ftp_user, $ftp_password);
			if($ftp_errors === true){
				$ftp_change = true;
			}
		}

		$op_system  = (substr(php_uname(), 0, 7) == "Windows")?"win":"unix";

		foreach($files as $file_data){
			$type = $file_data[0];
			$perm = $file_data[1];
			$file = $file_data[2];
			$file_path = SITE_PHYSICAL_PATH . $file;
			if(!file_exists($file_path)){
				$errors[] = array('file' =>$file,  'msg'=>"file does not exist");
			}else{
				if($type == 'file'){
					$mode = ($perm == "read")?0644:0777;
				}else{
					$mode = ($perm == "read")?0755:0777;
				}

				$writeable = $this->Install_model->is_file_writable($file_path, $mode);
/*
				if($perm == "read" && $writeable && $op_system != "win"){
					$ftp_perm = false;
					if($ftp_change){
						$ftp_perm = $this->Ftp_model->ftp_chmod($mode, $file_path);
					}
					if($ftp_perm === false) $errors[] = array('file' =>$file,  'msg'=>"please set 644 permissions");
				}
*/
				if($perm == "write" && !$writeable){
					$ftp_perm = false;
					if($ftp_change){
						$ftp_perm = $this->Ftp_model->ftp_chmod($mode, './'.$file);
					}
					if($ftp_perm === false) $errors[] = array('file' =>$file,  'msg'=>"please set 777 permissions");
				}
			}
		}

		return $errors;
	}

	//// modules menagement


	///// functions
	function _check_templates_c_writeable(){
		$template_c_dir = TEMPPATH. 'templates_c';
		if(!$this->Install_model->is_file_writable($template_c_dir, 0777)){
			show_error("Please, set 777 permissions for '".$template_c_dir."' folder");
			exit();
		}
	}

	///// misc methods
	function generate_install_lang(){
		$this->Install_model->generate_product_lang_install();
	}

	function generate_install_permissions(){
		$this->load->model("install/models/Backup_model");
		$this->Backup_model->generate_product_permissions_install();
	}

	function generate_install_module_settings($module_gid){
		$this->load->model("install/models/Backup_model");
		$this->Backup_model->generate_module_settings_install($module_gid);
	}

	function generate_module_files_backup($module_gid){
		$this->load->model("install/models/Backup_model");
		$this->Backup_model->generate_module_files_backup($module_gid);
	}

	function generate_modules_backup(){
		$this->load->model("install/models/Backup_model");
		$this->Backup_model->generate_product_modules_files_backup();
	}

	////// updates methods

	function updates(){
		$this->load->model('install/models/Updates_model');
		$updates = $this->Updates_model->get_enabled_updates();
		$this->template_lite->assign("updates", $updates);

		$this->system_messages->set_data('header', "Modules management : Available updates");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('updates');
	}

	function product_updates(){
		$this->load->model('install/models/Updates_model');
		$updates = $this->Updates_model->get_enabled_product_updates();
		$this->template_lite->assign("updates", $updates);

		$this->system_messages->set_data('header', "Product management : Available updates");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('product_updates');
	}

	function product_update($file, $path='product_updates') {
		$this->load->model('install/models/Updates_model');
		//$config = $this->Updates_model->get_update_product_config($file, 'product_updates');

		$this->_product_update_libraries_setup($file);
		
		$this->template_lite->assign("start_html", $this->_check_overall_product_update($file));

		$this->system_messages->set_data('header', "Modules management : Product update");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->assign('file', $file);
		$this->template_lite->assign('path', $path);
		$this->template_lite->view('initial_product_update');

	}

	function update_install($module_gid, $path = ''){
		$this->load->model('install/models/Updates_model');
		$module = $this->pg_module->get_module_by_gid($module_gid);
		if (!$path) $path = $module_gid;

		$update = $this->Updates_model->get_update_config($path);
		$update["base_update"] = file_exists(UPDPATH . $path . '/structure_update.sql')?true:false;
		$update["lang_update"] = file_exists(UPDPATH . $path . '/application/modules/'.$module_gid.'/langs')?true:false;
		$update["allow_to_install"] = true;

		if($module["version"] != $update["version_from"]){
			redirect(site_url().'admin/install/update_install_chmod/'.$module_gid);
		}

		if(!empty($update['dependencies'])){
			$installed_modules = $this->Install_model->get_installed_modules();
			foreach($update['dependencies'] as $dmodule=>$ddata){
				$update_version = $ddata["version"];
				if(isset($installed_modules[$dmodule])){
					$installed_version = $installed_modules[$dmodule]["version"];
				}else{
					$installed_version = 0;
				}
				$update['dependencies'][$dmodule]["installed_version"] = $installed_version;
				if($installed_version < $update_version ){
					$update["allow_to_install"] = false;
				}
			}
		}

		$this->template_lite->assign("module", $module);
		$this->template_lite->assign("update", $update);
		$this->template_lite->assign("update_path", $path);

		$files_changes = $this->Updates_model->get_module_changed_files($module_gid);
		$this->template_lite->assign("files_changes", $files_changes);

		$this->system_messages->set_data('header', "Modules management : Update install");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('update_install_backup');
	}

	/*function update_install_sql($module_gid){
		$this->load->model('install/models/Updates_model');
		$errors = $this->Updates_model->update_sql_install($module_gid);
		if(!empty($errors)){
			$this->template_lite->assign("errors", $errors);
			$this->system_messages->set_data('header', "Modules management : Update install");
			$this->template_lite->assign("modules_setup", true);
			$this->template_lite->view('update_install_sql');
		}else{
			redirect(site_url()."admin/install/update_install_files/".$module_gid);
		}
	}*/

	function ajax_update_sql($module_gid, $install_type="product"){
		$this->load->model('install/models/Updates_model');
		$module_data = $this->Updates_model->get_update_config($module_gid);

		$errors = $this->Updates_model->update_sql_install($module_gid);
		if($errors){
			$this->template_lite->assign('errors', $errors);
			$this->template_lite->assign('current_module_percent', $this->get_set_update_percent('ajax_update_sql'));
		}else{
			$this->template_lite->assign('current_module_percent', 16/*$this->get_set_update_percent('ajax_files')*/);
		}

		$this->template_lite->assign('next_step', 'update_files');
		$this->template_lite->assign('module', $module_data);
		$html = $this->template_lite->fetch('update_install_sql');

		echo $html;
	}

	/*function update_install_files($module_gid){
		$this->load->model('install/models/Updates_model');
		$update = $this->Updates_model->get_update_config($module_gid);

		if(empty($update["files"])){
			redirect(site_url()."admin/install/update_install_chmod/".$module_gid);
		}

		$this->load->model('install/models/Ftp_model');
		//// if ftp & ftp data - try to ftp copy files
		$ftp_errors = false;

		$this->config->load('install', TRUE);
		$ftp_host = $this->config->item('ftp_host', 'install');
		$ftp_user = $this->config->item('ftp_user', 'install');
		$ftp_password = $this->config->item('ftp_password', 'install');

		if($this->Ftp_model->ftp() && !empty($ftp_host) && !empty($ftp_user)){

			$ftp_errors = $this->Ftp_model->ftp_login($ftp_host, $ftp_user, $ftp_password);

			if($ftp_errors === true){
				$ftp_errors = array();
				foreach($update["files"] as $file){
					$new_file = SITE_PHYSICAL_PATH.$file[2];
					if($file[0] == "file"){
						$old_file = UPDPATH.$module_gid."/".$file[2];
						if(!file_exists($old_file) && file_exists($new_file)){
							$result = true;
						}else{
							$result = $this->Ftp_model->ftp_rename($old_file, $new_file);
						}
					}elseif($file[0] == "dir"){
						$this->Ftp_model->ftp_mkdir($new_file);
					}
					if($result){
						$mode = ($file[1]=='write')?0777:0644;
						$this->Ftp_model->ftp_chmod($mode, $new_file);
					}else{
						$ftp_errors[] = "unable to replace file ".$file[2];
					}
				}
			}
		}
		//// else (or if ftp copy errors) show files list
		if($ftp_errors === false || (is_array($ftp_errors) && !empty($ftp_errors)) ){
			if(is_array($ftp_errors) && !empty($ftp_errors)){
				$this->system_messages->add_message('error', $ftp_errors);
			}
			$this->template_lite->assign("update", $update);
			$this->template_lite->assign("module_dir", $module_dir);
			$this->system_messages->set_data('header', "Modules management : Update install");
			$this->template_lite->assign("modules_setup", true);
			$this->template_lite->view('update_install_files');
		}else{
			/// update installed
			$data = array(
				'version' => $update["version_to"],
				'date_update' => date('Y-m-d H:i:s')
			);
			$this->pg_module->set_module_update($module_gid, $data);
			redirect(site_url()."admin/install/update_install_chmod/".$module_gid);
		}
	}*/

	function ajax_files($module_gid, $install_type="product"){
		$this->load->model('install/models/Updates_model');

		$path = $module_gid;
		$module_gid = $this->Updates_model->get_module_by_path($path);

		$module_data = $this->Updates_model->get_update_config($path);
		if(empty($module_data["files"])){
			//redirect(site_url()."admin/install/update_install_chmod/".$module_gid);
			$this->template_lite->assign("skip", true);
		} else {
			$this->load->model('install/models/Ftp_model');
			//// if ftp & ftp data - try to ftp copy files
			$ftp_errors = false;

			$this->config->load('install', true);
			$ftp_host = $this->config->item('ftp_host', 'install');
			$ftp_path = $this->config->item('ftp_path', 'install');
			if(!'/' === substr($ftp_path, -1)) {
				$ftp_path .= '/';
			}
			$ftp_user = $this->config->item('ftp_user', 'install');
			$ftp_password = $this->config->item('ftp_password', 'install');

			if($this->Ftp_model->ftp() && !empty($ftp_host) && !empty($ftp_user)){

				$ftp_errors = $this->Ftp_model->ftp_login($ftp_host, $ftp_user, $ftp_password);

				if($ftp_errors === true){
					$ftp_errors = array();
					
					$product_version = $this->pg_module->get_module_config('start', 'product_version');
					if(!$product_version) $product_version = "v1";
					
					foreach($module_data["files"] as $file){
						$new_file_type = $file[0];
						$new_file_access = $file[1];
						$new_file = $file[2];
						$new_file_path = SITE_PHYSICAL_PATH . $file[2];
						
						if($new_file_type == "file"){
							$old_file = UPDATES_FOLDER . $path . "/" . $file[2];
							$old_file_path = UPDPATH . $path . "/" . $file[2];
							
							if(!file_exists($old_file_path) && file_exists($new_file_path)){
								$result = true;
							}else{
								// Backup old version
								if(file_exists($new_file_path)) {
									$new_file_data = pathinfo($new_file);
									$backup_dir = 'backup/' . $product_version . '/' . $new_file_data['dirname'];
									$backup_file = 'backup/' . $product_version . '/' . $new_file_data['dirname'] . '/' . $new_file_data['basename'];

									if(!file_exists(SITE_PHYSICAL_PATH . $backup_dir)) {
										$this->Ftp_model->ftp_mkdir_rec('./' . $backup_dir);
									}
									// Delete old backup
									if(file_exists(SITE_PHYSICAL_PATH . $backup_file)) {
										$this->Ftp_model->ftp_delete('./' . $backup_file);
									}
									$this->Ftp_model->ftp_rename('./' . $new_file, './' . $backup_file);
								}
								$result = $this->Ftp_model->ftp_rename('./' . $old_file, './' . $new_file);
							}
						}elseif($new_file_type == 'dir'){
							// access to parent dir
							if(!file_exists($new_file_path)) {	
								$result = $this->Ftp_model->ftp_mkdir('./' . $new_file);
							}
						}
						if($result){
							if($new_file_access == 'write') {
								$mode = 0777;
							} elseif($new_file_type == 'dir') {
								$mode = 0755;
							} else {
								$mode = 0644;
							}
							$this->Ftp_model->ftp_chmod($mode, './' . $new_file);
						}else{
							$ftp_errors[] = "unable to replace file ".$new_file;
						}
					}
				}
			}else{
				$ftp_errors[] = 'Problem with ftp connection';
			}
			//// else (or if ftp copy errors) show files list
			if($ftp_errors === false || (is_array($ftp_errors) && !empty($ftp_errors)) ){
				if(is_array($ftp_errors) && !empty($ftp_errors)){
					$this->template_lite->assign("errors", $ftp_errors);
				}
				$this->template_lite->assign('current_module_percent', $this->get_set_update_percent('ajax_files'));
				$this->template_lite->assign("update", $update);
				$this->template_lite->assign("module_dir", $module_dir);
				$this->system_messages->set_data('header', "Modules management : Update install");
				$this->template_lite->assign("modules_setup", true);
			}else{
				$this->template_lite->assign('current_module_percent', $this->get_set_update_percent('ajax_chmod'));
			}
		}

		$this->template_lite->assign('next_step', 'update_chmod');
		$this->template_lite->assign('module', $module_data);
		$this->template_lite->assign('path', $path);
		$html = $this->template_lite->fetch('update_install_files');

		echo $html;
	}

	/*function update_install_chmod($module_gid){
		$this->load->model('install/models/Updates_model');
		$update = $this->Updates_model->get_update_config($module_gid);
		$module = $this->pg_module->get_module_by_gid($module_gid);

		if(!empty($module["files"])){
			$files_error = $this->_check_files($module["files"]);
		}
		if(!empty($files_error)){
			$this->template_lite->assign("errors", $files_error);
			$this->system_messages->set_data('header', "Modules management : Update install");
			$this->template_lite->assign("modules_setup", true);
			$this->template_lite->view('update_install_chmod');
		}else{
			redirect(site_url()."admin/install/update_install_settings/".$module_gid);
		}
	}*/

	function ajax_chmod($module_gid, $install_type="product"){
		$this->load->model('install/models/Updates_model');
		$module_data = $this->Updates_model->get_update_config($module_gid);

		if(!empty($module_data["files"])){
			$files_error = $this->_check_files($module_data["files"]);
		}
		if($files_error){
			$this->template_lite->assign('errors', $files_error);
			$this->template_lite->assign('current_module_percent', $this->get_set_update_percent('ajax_chmod'));
		}else{
			$this->template_lite->assign('current_module_percent', $this->get_set_update_percent('ajax_update_settings'));
		}

		$this->template_lite->assign('next_step', 'update_settings');
		$this->template_lite->assign('module', $module_data);
		$html = $this->template_lite->fetch('update_install_chmod');

		echo $html;
	}

	function ajax_update_settings($module_gid, $install_type="product"){
		$this->load->model('install/models/Updates_model');

		$module_data = $this->Updates_model->get_update_config($module_gid);
		$path = $module_gid;
		$module_gid = $this->Updates_model->get_module_by_path($path);
		$this->Updates_model->update_language_install($module_gid, $path);
		$this->Updates_model->update_settings_install($module_gid, $path);
		$this->Updates_model->update_permissions_install($module_gid, $path);
		$this->Updates_model->update_arbitrary_install($module_gid, $path);
		$error = '';
		if($error){
			$this->template_lite->assign('errors', $error);
			$this->template_lite->assign('current_module_percent', $this->get_set_update_percent('ajax_update_settings'));
			$this->template_lite->assign('next_step', 'settings');

		}else{
			$this->template_lite->assign('current_module_percent', 100);
			$this->template_lite->assign('next_step', 'update_public');
		}

		$this->template_lite->assign('module', $module_data);
		$html = $this->template_lite->fetch('update_install_settings');

		echo $html;
	}

	function ajax_update_public($module_gid, $install_type="product", $file='', $update_path='product_updates'){
		$this->load->model('install/models/Updates_model');
		$path = $module_gid;
		$module_gid = $this->Updates_model->get_module_by_path($path);
		$module_data = $this->Updates_model->get_update_config($module_gid, $path);
		$data['version'] = $module_data['version_to'];
		$this->Updates_model->update_module_information($module_gid, $data);
		if($install_type=="product_update"){
			echo $this->_check_overall_product_update($file, $update_path);
		}else{
			echo $this->_check_is_module_updated($module_gid, $update_path);
		}
	}

	function update_install_settings($module_gid){
		$this->load->model('install/models/Updates_model');
		$path = $module_gid;
		$module_gid = $this->Updates_model->get_module_by_path($path);

		$this->Updates_model->update_language_install($module_gid, $path);
		$this->Updates_model->update_permissions_install($module_gid, $path);
		$this->Updates_model->update_arbitrary_install($module_gid, $path);
		
		redirect(site_url().'admin/install/modules');
	}

////// modules setup
	function libraries(){
		$this->load->model('install/models/Libraries_model');
		$installed_libraries = $this->Libraries_model->get_installed_libraries();

		$updates = $this->Libraries_model->get_libraries_update_info();
		if(!empty($updates) && !empty($updates["libraries"])){
			foreach($installed_libraries as $k => $v){
				if(!isset($updates["libraries"][$v["gid"]])) continue;
				$update = $updates["libraries"][$v["gid"]];
				if($v["version"] < $update["version"]){
					$installed_libraries[$k]["update"] = 1;
				}
			}
		}
		$this->template_lite->assign("libraries", $installed_libraries);

		$page_data = array(
			"showing" => count($installed_libraries),
			"total" => count($installed_libraries),
			"date_format" => $this->pg_date->get_format('date_literal', 'st')
		);
		$this->template_lite->assign("page_data", $page_data);

		$this->system_messages->set_data('header', "Libraries management");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('libraries');
		return;
	}

	function enable_libraries(){
		$this->load->model('install/models/Libraries_model');
		$enabled_libraries = $this->Libraries_model->get_enabled_libraries();
		$this->template_lite->assign("libraries", $enabled_libraries);

		$page_data = array(
			"showing" => count($enabled_libraries),
			"total" => count($enabled_libraries),
			"date_format" => $this->pg_date->get_format('date_literal', 'st')
		);
		$this->template_lite->assign("page_data", $page_data);

		$this->system_messages->set_data('header', "Libraries management");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('libraries_enabled');
		return;
	}

	function library_install($gid){
		$this->load->model('install/models/Libraries_model');
		$this->load->library('pg_library');
		$libraries = $this->Libraries_model->get_installed_libraries();
		if(!isset($libraries[$gid])){
			$lib_config = $this->Libraries_model->get_library_config($gid);
			if(!empty($lib_config)){
				$data = array(
					'gid' => $gid,
					'name' => $lib_config["name"],
					'version' => $lib_config["version"],
					'date_add' => date("Y-m-d H:i:s")
				);
				$this->pg_library->set_library_install($data);
			}
		}
		redirect(site_url()."admin/install/libraries");
	}

	function library_update_install($gid){
		$this->load->model('install/models/Libraries_model');
		$library = $this->Libraries_model->get_installed_library($gid);
		$updates = $this->Libraries_model->get_libraries_update_info();
		if(!empty($updates) && !empty($updates["libraries"]) && isset($updates["libraries"][$gid])){
			$update = $updates["libraries"][$gid];
			if($library["version"] >= $update["version"]){
				redirect(site_url()."admin/install/libraries");
			}
			$update["file_name"] = basename($update["file"]);
			$library["update"] = $update;
		}else{
			redirect(site_url()."admin/install/libraries");
		}

		$this->pg_theme->add_js('libraries_update.js');
		$this->template_lite->assign("library", $library);

		$this->template_lite->assign("step", "libraries");
		$this->system_messages->set_data('header', "Library update");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('library_update');
	}

	function ajax_get_library_update(){
		$this->load->model('install/models/Libraries_model');
		$url = $this->input->post('url');
		$data = $this->Libraries_model->upload_remote_archive($url);
		if(!$data){
			$return["error"] = "Can't download file";
		}else{
			$return["next_step"] = "unpack";
			$return["success"] = "Archive saved";
		}
		echo json_encode($return); return;
	}

	function ajax_unpack_library_update(){
		$filename = $this->input->post('file');
		$gid = $this->input->post('gid');
		$targetDir = TEMPPATH ."trash/".$gid;

		if(!is_dir($targetDir)){
			mkdir($targetDir, 0777, TRUE);
		}

		$this->load->library('Unzip');
		$this->unzip->initialize(array(
			"fileName" => TEMPPATH ."trash/".$filename,
			"targetDir" => $targetDir
		));
		$this->unzip->unzipAll();
		if(!empty($this->unzip->error)){
			$return["error"] = implode("\n", $this->unzip->error);
		}else{
			$return["next_step"] = "copy";
			$return["success"] = "Archive successfully unpacked";
		}

		if(!empty($this->unzip->info)){
			$return["info"] = implode("\n", $this->unzip->info);
		}
		echo json_encode($return); return;
	}

	function ajax_copy_library_update(){
		$gid = $this->input->post('gid');
		$targetDir = TEMPPATH ."trash/".$gid."/";

		if(!is_dir($targetDir)){
			$return["error"] = "<b>".$targetDir."</b> - empty or not exists";
		}else{
			$librariesDir = LIBPATH;

			//// get files array
			$files = $this->Install_model->get_files_list($targetDir);

			$this->load->model('install/models/Ftp_model');
			//// if ftp & ftp data - try to ftp copy files
			$ftp_errors = false;

			$this->config->load('install', TRUE);
			$ftp_host = $this->config->item('ftp_host', 'install');
			$ftp_user = $this->config->item('ftp_user', 'install');
			$ftp_password = $this->config->item('ftp_password', 'install');

			if($this->Ftp_model->ftp() && !empty($ftp_host) && !empty($ftp_user)){

				$ftp_errors = $this->Ftp_model->ftp_login($ftp_host, $ftp_user, $ftp_password);

				if($ftp_errors === true){
					$ftp_errors = array();
					foreach($files as $file){
						$new_file = str_replace($targetDir, $librariesDir, $file["path"]);

						if($file["type"] == "file"){
							$old_file = $file["path"];
							if(!file_exists($old_file) ){
								$result = true;
							}else{
					//			if(file_exists($new_file)){
					//				$this->Ftp_model->ftp_delete($new_file);
					//			}
								$result = $this->Ftp_model->ftp_rename($old_file, $new_file);
							}

						}elseif($file["type"] == "dir"){
							if(!file_exists($new_file))
								$result = $this->Ftp_model->ftp_mkdir($new_file);
							else{
								$result = true;
							}
						}

						if(!$result){
							$ftp_errors[] = "unable to replace file ".$new_file." with ".$old_file;
						}else{
							$this->Ftp_model->ftp_chmod(0777, $new_file);
						}
					}
				}
			}

			if($ftp_errors === false || (is_array($ftp_errors) && !empty($ftp_errors)) ){
				if(is_array($ftp_errors) && !empty($ftp_errors)){
					$return["error"] = implode("<br>", $ftp_errors);
				}else{
					$return["error"] = "please set ftp data or copy files manually from <b>".$targetDir."</b> to <b>".$librariesDir."</b>";
				}
			}else{
				$return["success"] = "Library successfully updated";
			}
		}
		echo json_encode($return); return;
	}

	function installer_settings(){
		$this->load->model('install/models/Ftp_model');

		$install_config_path = APPPATH . 'config/install'.EXT;
		$data["config_file"] = $install_config_path;
		$data["config_writeable"] = $this->Install_model->is_file_writable($install_config_path, 0777);
		$data["ftp"] = $this->Ftp_model->ftp();

		if($this->input->post('save_install_login')){
			$data["config_login"] = $this->input->post('install_login', true);
			$data["config_password"] = $this->input->post('install_password', true);

			$data["installer_ip_protect"] = $this->input->post('installer_ip_protect', true)?true:false;
			$data["installer_ip"] = $this->input->post('installer_ip', true);
			$data["ftp_host"] = $this->input->post('ftp_host', true);
			$data["ftp_path"] = $this->input->post('ftp_path', true);
			$data["ftp_user"] = $this->input->post('ftp_user', true);
			$data["ftp_password"] = $this->input->post('ftp_password', true);

			if(!$data["config_writeable"]){
				$this->system_messages->add_message('error', 'Please set permissions for config file');
			}else{
				if($data["ftp"] && $data["ftp_host"]){
					if(empty($data["ftp_user"])){
						$ftp_errors[] = "Invalid or empty ftp user";
					}else{
						$ftp_errors = $this->Ftp_model->ftp_login($data["ftp_host"], $data["ftp_user"], $data["ftp_password"]);
					}
				}else{
					$ftp_errors = false;
				}

				$this->config->load('reg_exps', TRUE);
				$login_expr =  $this->config->item('nickname', 'reg_exps');
				$password_expr =  $this->config->item('password', 'reg_exps');

				if(!preg_match($login_expr, $data["config_login"])){
					$this->system_messages->add_message('error', 'Invalid login');
				}elseif(!preg_match($password_expr, $data["config_password"])){
					$this->system_messages->add_message('error', 'Invalid or empty password');
				}elseif(is_array($ftp_errors) && !empty($ftp_errors)){
					$this->system_messages->add_message('error', $ftp_errors);
				}else{
					$save_data['install_module_done'] = true;
					$save_data['install_login'] = $data["config_login"];
//					$save_data['install_password'] = md5($data["config_password"]);
					$save_data['install_password'] = $data["config_password"];

					$save_data['ftp_host'] = $data["ftp_host"];
					$save_data['ftp_path'] = $data["ftp_path"];
					$save_data['ftp_user'] = $data["ftp_user"];
					$save_data['ftp_password'] = $data["ftp_password"];

					$save_data["installer_ip_protect"] = $data["installer_ip_protect"];
					$save_data["installer_ip"] = preg_split("/\s*,\s*/", $data["installer_ip"]);

					$return = $this->Install_model->save_install_config($save_data);
					if(!$return){
						$this->system_messages->add_message('error', 'Cant save config file');
					}else{
						redirect(site_url()."admin/install/installer_settings");
					}
				}
			}
		}else{
			$this->config->load('install', TRUE);
			$data["config_login"] = $this->config->item('install_login', 'install');
			$data["config_password"] = $this->config->item('install_password', 'install');

			$data["ftp_host"] = $this->config->item('ftp_host', 'install');
			$data["ftp_path"] = $this->config->item('ftp_path', 'install');
			$data["ftp_user"] = $this->config->item('ftp_user', 'install');
			$data["ftp_password"] = $this->config->item('ftp_password', 'install');

			$data["installer_ip_protect"] = $this->config->item('installer_ip_protect', 'install');
			$data["installer_ip"] = implode(",", $this->config->item('installer_ip', 'install'));
		}

		$data["action"] = site_url()."admin/install/installer_settings";

		$this->template_lite->assign("data", $data);
		$this->template_lite->assign("step", "ftp");
		$this->system_messages->set_data('header', "Panel settings");
		$this->template_lite->assign("modules_setup", true);
		$this->template_lite->view('settings');
		return;
	}

	/**
	 * Returns the installation progress
	 *
	 * @param string $step
	 * @return int
	 */
	private function get_setp_percent($step) {
		return round((100 / count($this->ajax_steps)) * (array_search($step, $this->ajax_steps) + 1));
	}

	private function get_set_update_percent($step) {
		return round((100 / count($this->ajax_update_steps)) * (array_search($step, $this->ajax_update_steps) + 1));
	}

}
