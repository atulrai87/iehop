<?php
/**
* Administrators main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('AUSERS_TABLE', DB_PREFIX.'ausers');
define('AUSERS_MODERATE_METHODS_TABLE', DB_PREFIX.'ausers_moderate_methods');
class Ausers_model extends Model
{
	var $CI;
	var $DB;
	var $fields_all = array(
		'id',
		'date_created',
		'date_modified',
		'email',
		'nickname',
		'password',
		'name',
		'description',
		'status',
		'lang_id',
		'user_type',
		'permission_data'
	);

	/**
	 * Constructor
	 *
	 * @return Admin users object
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_user_by_id($user_id){
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(AUSERS_TABLE)->where("id", $user_id)->get()->result_array();
		if(empty($result)){
			return false;
		}else{
			$data = $this->format_user($result[0]);
			return $data;
		}
	}

	public function get_user_by_login_password($login, $password){
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(AUSERS_TABLE)->where("nickname", $login)->where("password", $password)->get()->result_array();
		if(empty($result)){
			return false;
		}else{
			$data = $this->format_user($result[0]);
			return $data;
		}
	}

	public function get_users_list($page=null, $items_on_page=null, $order_by=null, $params=array(), $filter_object_ids=null){
		$this->DB->select(implode(", ", $this->fields_all));
		$this->DB->from(AUSERS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		if(is_array($order_by) && count($order_by)>0){
			foreach($order_by as $field => $dir){
				if(in_array($field, $this->fields_all)){
					$this->DB->order_by($field." ".$dir);
				}
			}
		}

		if(!is_null($page) ){
			$page = intval($page)?intval($page):1;
			$this->DB->limit($items_on_page, $items_on_page*($page-1));
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$data[] = $this->format_user($r);
			}
			return $data;
		}
		return false;
	}

	public function format_user($data){
		$data["permission_data"] = unserialize($data["permission_data"]);
		return $data;
	}

	public function get_users_count($params=array(), $filter_object_ids=null){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(AUSERS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		if(isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)){
			$this->DB->where_in("id", $filter_object_ids);
		}

		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else{
			return 0;
		}
	}

	public function save_user($user_id=null, $attrs=array()){
		if (is_null($user_id)){
			$attrs["date_created"] = $attrs["date_modified"] = date("Y-m-d H:i:s");
			if(!isset($attrs["status"])) $attrs["status"] = 1;
			$this->DB->insert(AUSERS_TABLE, $attrs);
			$user_id = $this->DB->insert_id();
		}else{
			$attrs["date_modified"] = date("Y-m-d H:i:s");
			$this->DB->where('id', $user_id);
			$this->DB->update(AUSERS_TABLE, $attrs);
		}
		return $user_id;
	}

	public function activate_user($user_id, $status=1){
		$attrs["status"] = intval($status);
		$attrs["date_modified"] = date("Y-m-d H:i:s");
		$this->DB->where('id', $user_id);
		$this->DB->update(AUSERS_TABLE, $attrs);
	}

	public function validate_user($user_id=null, $data=array()){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["name"])){
			$return["data"]["name"] = strip_tags($data["name"]);
		}

		if(isset($data["description"])){
			$return["data"]["description"] = trim($data["description"]);
		}

		if(isset($data["user_type"])){
			$return["data"]["user_type"] = trim($data["user_type"]);
		}

		if(isset($data["lang_id"])){
			$return["data"]["lang_id"] = $data["lang_id"];
		}

		if(isset($data["permission_data"])){
			$return["data"]["permission_data"] = serialize($data["permission_data"]);
		}

		$this->CI->config->load('reg_exps', TRUE);

		if(isset($data["nickname"])){
			$login_expr =  $this->CI->config->item('nickname', 'reg_exps');
			$return["data"]["nickname"] = strip_tags($data["nickname"]);
			if(empty($return["data"]["nickname"]) || !preg_match($login_expr, $return["data"]["nickname"])){
				$return["errors"][] = l('error_nickname_incorrect', 'ausers');
			}
			$params["where"]["nickname"] = $return["data"]["nickname"];
			if($user_id) $params["where"]["id <>"] = $user_id;
			$count = $this->get_users_count($params);
			if($count > 0){
				$return["errors"][] = l('error_nickname_already_exists', 'ausers');
			}
		}

		if(isset($data["email"])){
			$email_expr =  $this->CI->config->item('email', 'reg_exps');
			$return["data"]["email"] = strip_tags($data["email"]);
			if(empty($return["data"]["email"]) || !preg_match($email_expr, $return["data"]["email"])){
				$return["errors"][] = l('error_email_incorrect', 'ausers');
			}
		}

		if((isset($data["update_password"]) && $data["update_password"]) || !$user_id){
			if(empty($data["password"]) || empty($data["repassword"])){
				$return["errors"][] = l('error_password_empty', 'ausers');
			}elseif($data["password"] != $data["repassword"]){
				$return["errors"][] = l('error_pass_repass_not_equal', 'ausers');
			}else{
				$password_expr =  $this->CI->config->item('password', 'reg_exps');
				$data["password"] = trim(strip_tags($data["password"]));
				if(!preg_match($password_expr, $data["password"])){
					$return["errors"][] = l('error_password_incorrect', 'ausers');
				}else{
					$return["data"]["password"] = md5($data["password"]);
				}
			}
		}
		return $return;
	}

	public function delete_user($user_id){
		$this->DB->where('id', $user_id);
		$this->DB->delete(AUSERS_TABLE);
		return;
	}

	/*
	 * Methods for moderated models
	 *
	 */

	public function get_methods($params=array()){
		$return = array();
		$this->DB->select('id, module, method, is_default')->from(AUSERS_MODERATE_METHODS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}
		$this->DB->order_by("module ASC");
		$this->DB->order_by("is_default DESC");
		$this->DB->order_by("id ASC");

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$r["name"] = l("method_name_".$r["id"], 'ausers_moderation');
				if($r["is_default"]){
					$return[$r["module"]]['main'] = $r;
				}else{
					$return[$r["module"]]["methods"][] = $r;
				}
			}
			foreach($return as $module_gid => $data){
				$return[$module_gid]['module'] = $this->CI->pg_module->get_module_by_gid($module_gid);
			}
		}
		return $return;
	}

	public function get_methods_lang_export($params=array(), $langs_ids=array()){
		$this->DB->select('id, module, method, is_default')->from(AUSERS_MODERATE_METHODS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}
		$this->DB->order_by("module ASC");
		$this->DB->order_by("is_default DESC");
		$this->DB->order_by("id ASC");

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as &$r){
				foreach($langs_ids as $lang_id){
					$r["langs"][$lang_id] = l("method_name_".$r["id"], 'ausers_moderation', $lang_id);
				}
			}
		}
		return $results;
	}

	public function get_module_methods($module){
		$return = array();
		$this->DB->select('id, module, method, is_default')->from(AUSERS_MODERATE_METHODS_TABLE)->where('module', $module);

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$return[] = $r["method"];
			}
		}
		return $return;
	}

	public function save_method($id=null, $attrs=array(), $langs=array()){
		if (empty($id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(AUSERS_MODERATE_METHODS_TABLE, $attrs);
			$id = $this->DB->insert_id();
		}elseif(!empty($attrs)){
			$this->DB->where('id', $id);
			$this->DB->update(AUSERS_MODERATE_METHODS_TABLE, $attrs);
		}

		if(!empty($langs)){
			$lang_ids = array_keys($langs);
			$this->CI->pg_language->pages->set_string_langs('ausers_moderation', "method_name_".$id, $langs, $lang_ids);
		}
		return $id;
	}

	public function delete_methods($params){
		$this->DB->select('id')->from(AUSERS_MODERATE_METHODS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value){
				$this->DB->where($field, $value);
			}
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value){
				$this->DB->where_in($field, $value);
			}
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value){
				$this->DB->where($value);
			}
		}

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$this->delete_method($r["id"]);
			}
		}
		return;
	}

	public function delete_method($id){
		$this->DB->where("id", $id);
		$this->DB->delete(AUSERS_MODERATE_METHODS_TABLE);
		$this->CI->pg_language->pages->delete_string('ausers_moderation', "method_name_".$id);
		return;
	}

	/**
	 * Returns items gids as they are in database
	 *
	 * @param string $module
	 * @param array $methods
	 * @return array
	 */
	private function get_lang_gids($module, $methods) {
		$gid = array();
		$gid['module'] = 'ausers_moderation';
		foreach($methods as $method) {
			$query['where'] = array('module' => $module,
									'method' => $method);
			$method = $this->CI->Ausers_model->get_methods($query);
			if(!empty($method[$module]['main'])) {
				$gid['items'][] = 'method_name_' . $method[$module]['main']['id'];
			} else {
				$gid['items'][] = 'method_name_' . $method[$module]['methods'][0]['id'];
			}
		}
		return $gid;
	}

	/**
	 * Returns langs data
	 *
	 * @param array $module_methods
	 * @param array $langs_ids
	 * @return array
	 */
	public function export_langs($module_methods, $langs_ids = null) {
		$lang_data = array();
		foreach($module_methods as $module => $methods) {
			$gids_db = $this->get_lang_gids($module, $methods);
			$langs_db = $this->CI->pg_language->export_langs($gids_db['module'], $gids_db['items'], $langs_ids);
			$lang_codes = array_keys($langs_db);
			foreach($lang_codes as $lang_code) {
				$lang_data[$lang_code][$module] = array_combine($module_methods[$module], $langs_db[$lang_code]);
			}
		}
		return $lang_data;
	}
}