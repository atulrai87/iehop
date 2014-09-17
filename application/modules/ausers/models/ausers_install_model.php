<?php
/**
* Administrators install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/
class Ausers_install_model extends Model
{
	var $CI;

	private $menu = array(
		/// admin menu
		'admin_menu' => array(
			'name' => 'Admin area menu',
			"action" => "none",
			"items" => array(
				'main_items' => array(
					"action" => "none",
					"items" => array(
						'ausers_item' => array("action" => "create", 'link' => 'admin/ausers', 'status' => 1, 'sorter' => 2),
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
	function Ausers_install_model()
	{
		parent::Model();
		$this->CI = & get_instance();
		//// load langs
		$this->CI->load->model('Install_model');
	}

	function _validate_settings_form(){
		$errors = array();
		$data["name"] = $this->CI->input->post('name', true);
		$data["password"] = $this->CI->input->post('password', true);
		$data["email"] = $this->CI->input->post('email', true);
		$data["nickname"] = $this->CI->input->post('nickname', true);

		if(empty($data["name"])){
			$errors[] = $this->CI->pg_language->get_string('ausers', 'error_name_incorrect');
		}

		$this->CI->config->load('reg_exps', TRUE);
		$login_expr =  $this->CI->config->item('nickname', 'reg_exps');
		$password_expr =  $this->CI->config->item('password', 'reg_exps');
		$email_expr =  $this->CI->config->item('email', 'reg_exps');

		if(empty($data["nickname"]) || !preg_match($login_expr, $data["nickname"])){
			$errors[] = $this->CI->pg_language->get_string('ausers', 'error_nickname_incorrect');
		}

		if(empty($data["password"]) || !preg_match($password_expr, $data["password"])){
			$errors[] = $this->CI->pg_language->get_string('ausers', 'error_password_incorrect');
		}

		if(empty($data["email"]) || !preg_match($email_expr, $data["email"])){
			$errors[] = $this->CI->pg_language->get_string('ausers', 'error_email_incorrect');
		}

		$return = array(
			"data" => $data,
			"errors" => $errors,
		);
		return $return;
	}

	function _save_settings_form($data){
		$data["password"] = md5($data["password"]);
		$data["status"] = 1;
		$data["lang_id"] = 1;
		$data["user_type"] = "admin";

		$this->CI->load->model('Ausers_model');
		$this->CI->Ausers_model->save_user(null, $data);
		return;
	}

	function _get_settings_form($submit=false){
		$data = array(
			"nickname" => "admin",
			"name" => "Administrator",
		);
		if($submit){
			$validate = $this->_validate_settings_form();
			if(!empty($validate["errors"])){
				$this->CI->template_lite->assign('settings_errors', $validate["errors"]);
				$data = $validate["data"];
			}else{
				$this->_save_settings_form($validate["data"]);
				return false;
			}
		}

		$this->CI->template_lite->assign('settings_data', $data);
		$html = $this->CI->template_lite->fetch('install_settings_form', 'admin', 'ausers');
		return $html;
	}

	/*
	* Menu module methods
	*
	*/
	public function install_menu() {
		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('ausers', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( "menu" => $return );
	}

	public function deinstall_menu() {
		$this->CI->load->model('Menu_model');
		$this->CI->load->helper('menu');
		linked_install_delete_menu_items($menu_gid, $items);
		foreach($this->menu as $gid => $menu_data){
			if($menu_data["action"] == "create"){
				linked_install_set_menu($gid, "delete");
			}else{
				linked_install_delete_menu_items($gid, $this->menu[$gid]["items"]);
			}
		}
	}
}