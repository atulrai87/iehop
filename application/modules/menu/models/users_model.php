<?php
/**
* Users main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

define('USERS_TABLE', DB_PREFIX . 'users');

class Users_model extends Model {

	public $CI;
	public $DB;
	public $fields = array(
		'account',
		'activated_end_date',
		'activity',
		'address',
		'age',
		'age_min',
		'age_max',
		'approved',
		'birth_date',
		'confirm',
		'date_created',
		'date_last_activity',
		'date_modified',
		'email',
		'featured_end_date',
		'fname',
		'group_id',
		'hide_on_site_end_date',
		'highlight_in_search_end_date',
		'id',
		'id_country',
		'id_region',
		'id_city',
		'lang_id',
		'leader_bid',
		'leader_text',
		'leader_write_date',
		'logo_comments_count',
		'looking_user_type',
		'nickname',
		'online_status',
		'password',
		'phone',
		'profile_completion',
		'postal_code',
		'search_field',
		'show_adult',
		'site_status',
		'sname',
		'user_logo',
		'user_logo_moderation',
		'user_open_id',
		'user_type',
		'up_in_search_end_date',
		'views_count',
		'id_seo_settings',
	);
	public $fields_all = array();
	public $safe_fields = array(
		'id',
		'nickname',
		'age',
		'user_logo',
		'logo_comments_count',
		'views_count',
		'online_status',
		'site_status',
		'date_last_activity',
		'user_type',
		'user_type_str',
		'looking_user_type',
		'looking_user_type_str',
		'output_name',
		'media',
		'statuses',
		'link',
		'is_activated',
		'is_featured',
		'is_hide_on_site',
		'is_highlight_in_search',
		'is_up_in_search',
	);

	public $fields_register = array(
		'email',
		'nickname',
		'user_type',
		'looking_user_type',
		'password',
		'birth_date'
	);

	public $fields_not_editable = array(
		'user_type',
		'birth_date',
	);

	public $fields_for_activation = array(
		'user_type',
		'looking_user_type',
		'nickname',
		'birth_date',
		'id_region',
		'id_country',
		'user_logo'
	);

	public $fields_completion = array(
		'email',
		'nickname',
		'user_type',
		'looking_user_type',
		'fname',
		'sname',
		'user_logo',
		'id_country',
		'id_region',
		'id_city',
		'birth_date',
		'age_min',
		'age_max',
	);

	public $services_buy_gids = array(
		'users_featured',
		'admin_approve',
		'hide_on_site',
		'highlight_in_search',
		'up_in_search',
	);

	public $upload_config_id = "user-logo";
	public $moderation_type = 'user_logo';
	public $form_editor_type = "users";
	public $advanced_search_form_gid = "advanced_search";
	private $dop_fields = array();
	/**
	 * Constructor
	 *
	 * @return users object
	 */
	public function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_all = $this->fields;
	}

	public function set_additional_fields($fields){
		$this->dop_fields = $fields;
		$this->fields_all = (!empty($this->dop_fields))?array_merge($this->fields, $this->dop_fields):$this->fields;
		return;
	}

	public function get_user_by_id($user_id, $formatted = false, $safe_format = false) {
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(USERS_TABLE)->where("id", $user_id)->get()->result_array();
		if(empty($result)) {
			return false;
		} else if($formatted) {
			return $this->format_user($result[0], $safe_format);
		} else {
			return $result[0];
		}
	}

	public function get_user_by_login_password($login, $password) {
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(USERS_TABLE)->where("nickname", $login)->where("password", $password)->get()->result_array();
		return empty($result) ? false : $result[0];
	}

	public function get_user_by_login($login) {
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(USERS_TABLE)->where("nickname", $login)->get()->result_array();
		return empty($result) ? false : $result[0];
	}

	public function get_user_by_email_password($email, $password) {
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(USERS_TABLE)->where("email", $email)->where("password", $password)->get()->result_array();
		return empty($result) ? false : $result[0];
	}

	public function get_user_by_email($email) {
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(USERS_TABLE)->where("email", $email)->get()->result_array();
		return empty($result) ? false : $result[0];
	}

	public function get_user_by_confirm_code($code) {
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(USERS_TABLE)->where("confirm_code", $code)->get()->result_array();
		return empty($result) ? false : $result[0];
	}

	public function get_user_by_open_id($open_id) {
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(USERS_TABLE)->where("user_open_id", $open_id)->get()->result_array();
		return empty($result) ? false : $result[0];
	}

	public function get_all_users_id(){
		$result = $this->DB->select('id')->from(USERS_TABLE)->get()->result_array();
		$return = array();
		foreach($result as $row){
			$return[] = $row['id'];
		}
		return $return;
	}

	public function get_users_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = array(), $formatted = true, $safe_format = false) {
	
		if(isset($params["fields"]) && is_array($params["fields"]) && count($params["fields"])){
			$this->set_additional_fields($params["fields"]);
		}
		
		$this->DB->select(implode(", ", $this->fields_all));
		$this->DB->from(USERS_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}

		if (isset($params["like"]) && is_array($params["like"]) && count($params["like"])) {
			foreach ($params["like"] as $field => $value) {
				$this->DB->like($field, $value);
			}
		}

		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}

		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}

		if (!empty($filter_object_ids) && is_array($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}

		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->fields_all) || $field == 'fields') {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}

		if (!is_null($page)) {
			$page = intval($page) ? intval($page) : 1;
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			if($formatted) $results = $this->format_users($results, $safe_format);
			return $results;
		}
		return array();
	}

	public function get_users_list_by_key($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = array(), $formatted = true, $safe_format = false) {
		$list = $this->get_users_list($page, $items_on_page, $order_by, $params, $filter_object_ids, $formatted, $safe_format);
		if (!empty($list)) {
			foreach ($list as $l) {
				$data[$l["id"]] = $l;
			}
			return $data;
		} else {
			return array();
		}
	}

	public function get_featured_users($count = 20, $user_type = 0){
		$params['where']['featured_end_date !='] = '0000-00-00 00:00:00';
		//$params['where']['featured_end_date >'] = date("Y-m-d H:i:s");
		//$params['where']['confirm'] = '1';
		//$params['where']['approved'] = '1';
		//$params['where']['activity'] = '1';
		if($user_type){
			$params['where']['user_type'] = intval($user_type);
		}
		$order_by['featured_end_date'] = 'DESC';
		return $this->get_users_list(1, $count, $order_by, $params);
	}

	public function get_active_users($count = 20, $user_type = 0, $params=array()){
		$params['where']['confirm'] = '1';
		$params['where']['approved'] = '1';
		$params['where']['activity'] = '1';
		if($user_type){
			$params['where']['user_type'] = intval($user_type);
		}
		$order_by['date_last_activity'] = 'DESC';
		return $this->get_users_list(1, $count, $order_by, $params);
	}

	public function get_new_users($count = 20, $user_type = 0){
		$params['where']['confirm'] = '1';
		$params['where']['approved'] = '1';
		$params['where']['activity'] = '1';
		if($user_type){
			$params['where']['user_type'] = intval($user_type);
		}
		$order_by['date_created'] = 'DESC';
		return $this->get_users_list(1, $count, $order_by, $params, array(), true);
	}

	public function get_users_count($params = array(), $filter_object_ids = null) {
		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
		}

		if (isset($params["like"]) && is_array($params["like"]) && count($params["like"])) {
			foreach ($params["like"] as $field => $value) {
				$this->DB->like($field, $value);
			}
		}

		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}

		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}

		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}
		$result = $this->DB->count_all_results(USERS_TABLE);

		return $result;
	}

	public function get_active_users_list($page = null, $items_on_page = null, $order_by = null, $params = array(), $filter_object_ids = null, $formatted = true) {
		$params["where"]['approved'] = 1;
		$params["where"]['confirm'] = 1;
		return $this->get_users_list($page, $items_on_page, $order_by, $params, $filter_object_ids, $formatted);
	}

	public function get_active_users_count($params = array(), $filter_object_ids = null) {
		$params["where"]['approved'] = 1;
		$params["where"]['confirm'] = 1;
		return $this->get_users_count($params, $filter_object_ids);
	}

	public function get_active_users_types_count($params = array(), $filter_object_ids = null) {
		$params["where"]['approved'] = 1;
		$params["where"]['confirm'] = 1;
		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			$this->DB->where($params["where"]);
		}
		if (isset($params["like"]) && is_array($params["like"]) && count($params["like"])) {
			foreach ($params["like"] as $field => $value) {
				$this->DB->like($field, $value);
			}
		}
		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value, null, false);
			}
		}
		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}
		$result = $this->DB->select('user_type, COUNT(user_type) AS count')->from(USERS_TABLE)->group_by('user_type')->get()->result_array();
		return $result;
	}

	public function simply_update_user($user_id, $attrs){
		$fe_settings = $this->config->item('editor_type', 'field_editor');
		$fe_prefix = !empty($fe_settings['users']['field_prefix']) ? $fe_settings['users']['field_prefix'] : '';
		foreach((array)$this->fields_not_editable as $field_not_editable){
			unset($attrs[$field_not_editable], $attrs[$fe_prefix.$field_not_editable]);
		}
		$this->DB->where('id', $user_id);
		$this->DB->update(USERS_TABLE, $attrs);
		return $this->DB->affected_rows();
	}

	public function save_user($user_id = null, $attrs = array(), $file_name = "", $use_icon_moderation=true) {
		if (is_null($user_id)) {
			if(empty($attrs["date_created"])) {
				$attrs["date_created"] = $attrs["date_modified"] = date("Y-m-d H:i:s");
			}
			$this->DB->insert(USERS_TABLE, $attrs);
			$user_id = $this->DB->insert_id();
		}else {
			$attrs["date_modified"] = date("Y-m-d H:i:s");
			$fe_settings = $this->config->item('editor_type', 'field_editor');
			$fe_prefix = !empty($fe_settings['users']['field_prefix']) ? $fe_settings['users']['field_prefix'] : '';
			foreach((array)$this->fields_not_editable as $field_not_editable){
				unset($attrs[$field_not_editable], $attrs[$fe_prefix.$field_not_editable]);
			}
			$this->DB->where('id', $user_id);
			$this->DB->update(USERS_TABLE, $attrs);
		}
		$this->update_age(array($user_id));

		if (!empty($file_name) && !empty($user_id) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])) {
			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->upload($this->upload_config_id, $user_id . "/", $file_name);

			if (empty($img_return["errors"])) {
				$this->CI->load->model('Moderation_model');
				$mstatus = intval($this->CI->Moderation_model->get_moderation_type_status($this->moderation_type));

				if ($mstatus != 2 && $use_icon_moderation) {
					$this->CI->Moderation_model->add_moderation_item($this->moderation_type, $user_id);
					if($mstatus == 1){
						$img_data['user_logo'] = $img_return["file"];
						$img_data['user_logo_moderation'] = '';
					}else{
						$img_data['user_logo_moderation'] = $img_return["file"];
					}
				}else{
					$img_data["user_logo"] = $img_return["file"];
				}

				$this->save_user($user_id, $img_data);
			}
		}

		$available_activation = $this->check_available_user_activation($user_id);
		if(!$available_activation['status']){
			$this->DB->set('activity', 0)->where('id', $user_id)->update(USERS_TABLE);
		}

		$this->update_profile_completion($user_id);

		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize($this->form_editor_type);
		$fields_for_select = $this->CI->Field_editor_model->get_fields_for_select();
		$this->CI->Field_editor_model->update_fulltext_field($user_id);

		$this->CI->load->model('users/models/Users_perfect_match_model');
		$this->CI->Users_perfect_match_model->set_additional_fields($fields_for_select);
		$this->CI->Users_perfect_match_model->save_perfect_match($user_id, $attrs);

		return $user_id;
	}

	public function check_available_user_activation($user_id){
		$user = $this->get_user_by_id($user_id);
		$result['status'] = 0;
		$result['fields'] = array();
		if($user){
			foreach($this->fields_for_activation as $attr){
				if(!(isset($user[$attr]) && $user[$attr])){
					if($attr == 'user_logo' && isset($user['user_logo_moderation']) && $user['user_logo_moderation']){
						$result['fields'][] = 'user_logo_moderation';
					}else{
						$result['fields'][] = $attr;
					}
				}
			}
			$result['status'] = ($result['fields']) ? 0 : 1;
			return $result;
		}
		return $result;
	}

	public function set_user_confirm($user_id, $status = 1) {
		$attrs["confirm"] = intval($status);
		return is_null($user_id) ? false : $this->save_user($user_id, $attrs);
	}

	public function set_user_approve($user_id, $status = 1) {
		$attrs["approved"] = intval($status);
		return is_null($user_id) ? false : $this->save_user($user_id, $attrs);
	}

	public function set_user_activity($user_id, $status = 1) {
		$attrs["activity"] = intval($status);
		return is_null($user_id) ? false : $this->save_user($user_id, $attrs);
	}

	public function activate_user($user_id, $status = 1) {
		return $this->set_user_approve($user_id, $status);
	}

	public function validate($user_id = null, $data = array(), $file_name = "", $section_gid = null, $type = 'select') {
		$return = array("errors" => array(), "data" => array());

		if (isset($data["user_logo"])) {
			$return["data"]["user_logo"] = strip_tags($data["user_logo"]);
		}

		if (isset($data["user_logo_moderation"])) {
			$return["data"]["user_logo_moderation"] = strip_tags($data["user_logo_moderation"]);
		}

		if (isset($data["fname"])) {
			$return["data"]["fname"] = strip_tags($data["fname"]);
		}

		if (isset($data["sname"])) {
			$return["data"]["sname"] = strip_tags($data["sname"]);
		}

		if (isset($data["user_type"])) {
			$return["data"]["user_type"] = intval($data["user_type"]);
		}

		if (isset($data["looking_user_type"])) {
			$return["data"]["looking_user_type"] = intval($data["looking_user_type"]);
		}

		$age_min = $this->pg_module->get_module_config('users', 'age_min');
		$age_max = $this->pg_module->get_module_config('users', 'age_max');
		if(isset($data['age_min'])){
			$return["data"]["age_min"] = intval($data['age_min']);
			if($return["data"]["age_min"] < $age_min || $return["data"]["age_min"] > $age_max){
				$return["data"]["age_min"] = $age_min;
			}
		}
		if(isset($data['age_max'])){
			$return["data"]["age_max"] = intval($data['age_max']);
			if($return["data"]["age_max"] < $age_min || $return["data"]["age_max"] > $age_max){
				$return["data"]["age_max"] = $age_max;
			}
			if(!empty($return["data"]["age_min"]) && $return["data"]["age_min"] > $return["data"]["age_max"]){
				$return["data"]["age_min"] = $age_min;
			}
		}

		$this->CI->config->load('reg_exps', TRUE);
		if (isset($data["nickname"])) {
			$login_expr = $this->CI->config->item('nickname', 'reg_exps');
			$return["data"]["nickname"] = strip_tags($data["nickname"]);
			if (empty($return["data"]["nickname"]) || !preg_match($login_expr, $return["data"]["nickname"])) {
				$return["errors"]["nickname"] = l('error_nickname_incorrect', 'users');
			}
			$params = array();
			$params["where"]["nickname"] = $return["data"]["nickname"];
			if ($user_id){
				$params["where"]["id <>"] = $user_id;
			}
			$count = $this->get_users_count($params);
			if ($count > 0) {
				$return["errors"]["nickname"] = l('error_nickname_already_exists', 'users');
			}
			if(empty($return["errors"]["nickname"])) {
				$return["success"]["nickname"] = "";
			}
		}

		if (isset($data["user_open_id"])) {
			$return["data"]["user_open_id"] = trim($data["user_open_id"]);
		}

		if (isset($data["id_country"])) {
			$return["data"]["id_country"] = $data["id_country"];
		}

		if (isset($data["id_region"])) {
			$return["data"]["id_region"] = intval($data["id_region"]);
		}

		if (isset($data["id_city"])) {
			$return["data"]["id_city"] = intval($data["id_city"]);
		}

		if (isset($data["phone"])) {
			$return["data"]["phone"] = trim(strip_tags($data["phone"]));
		}

		if (isset($data["address"])) {
			$return["data"]["address"] = trim(strip_tags($data["address"]));
		}

		if (!empty($data["birth_date"])) {
			$return["data"]["birth_date"] = trim(strip_tags($data["birth_date"]));
			$user_age = date_create($return["data"]["birth_date"])->diff(date_create('today'))->y;
			if ($user_age < $age_min) {
				$return["errors"]["birth_date"] = str_replace("[age]", $age_min, l("error_user_too_young", "users"));
			} else if($user_age > $age_max) {
				$return["errors"]["birth_date"] = str_replace("[age]", $age_max, l("error_user_too_old", "users"));
			}
		}

		if (isset($data["age"])) {
			$return["data"]["age"] = intval($data["age"]);
		}

		if (isset($data["show_adult"])) {
			$return["data"]["show_adult"] = intval($data["show_adult"]);
		}

		if (isset($data["profile_completion"])) {
			$return["data"]["profile_completion"] = intval($data["profile_completion"]);
		}

		if (isset($data["postal_code"])) {
			$return["data"]["postal_code"] = trim(strip_tags($data["postal_code"]));
		}

		if (empty($user_id) && !isset($data["group_id"])) {
			$this->CI->load->model('users/models/Groups_model');
			$return["data"]["group_id"] = $this->CI->Groups_model->get_default_group_id();
		} elseif (isset($data["group_id"])) {
			$return["data"]["group_id"] = intval($data["group_id"]);
		}

		if (isset($data["email"])) {
			$email_expr = $this->CI->config->item('email', 'reg_exps');
			$return["data"]["email"] = strip_tags($data["email"]);
			if (empty($return["data"]["email"]) || !preg_match($email_expr, $return["data"]["email"])) {
				$return["errors"]["email"] = l('error_email_incorrect', 'users');
			} else {
				unset($params);
				$params["where"]["email"] = $return["data"]["email"];
				if ($user_id){
					$params["where"]["id <>"] = $user_id;
				}
				$count = $this->get_users_count($params);
				if ($count > 0) {
					$return["errors"]["email"] = l('error_email_already_exists', 'users');
				}
			}
			if(empty($return["errors"]["email"])) {
				$return["success"]["email"] = "";
			}
		}
		if (isset($data["password"]) && isset($data["repassword"])) {
			if (empty($data["password"]) || empty($data["repassword"])) {
				$return["errors"]["password"] = l('error_password_empty', 'users');
			} elseif ($data["password"] != $data["repassword"]) {
				$return["errors"]["repassword"] = l('error_pass_repass_not_equal', 'users');
			} else {
				$password_expr = $this->CI->config->item('password', 'reg_exps');
				$data["password"] = trim(strip_tags($data["password"]));
				if (!preg_match($password_expr, $data["password"])) {
					$return["errors"]["password"] = l('error_password_incorrect', 'users');
				} else {
					$return["data"]["password"] = $data["password"];
				}
			}
		}

		if (!empty($file_name) && isset($_FILES[$file_name]) && is_array($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]["tmp_name"])) {
			$this->CI->load->model("Uploads_model");
			$img_return = $this->CI->Uploads_model->validate_upload($this->upload_config_id, $file_name);
			if (!empty($img_return["error"])) {
				$return["errors"][] = implode("<br>", $img_return["error"]);
			}
		}

		if (isset($data["confirm"])) {
			$return["data"]["confirm"] = intval($data["confirm"]);
		}

		if (isset($data["approved"])) {
			$return["data"]["approved"] = intval($data["approved"]);
		}

		if (isset($data["activity"])) {
			$return["data"]["activity"] = intval($data["activity"]);
		}

		if(!is_null($section_gid)){
			$this->CI->load->model('Field_editor_model');
			$params = array();
			if(!empty($section_gid)){
				$params["where"]["section_gid"] = $section_gid;
			}
			if($type == 'save'){
				$validate_data = $this->CI->Field_editor_model->validate_fields_for_save($params, $data);
			}else{
				$validate_data = $this->CI->Field_editor_model->validate_fields_for_select($params, $data);
			}
			$return["data"] = array_merge($return["data"], $validate_data["data"]);
			if(!empty($validate_data["errors"])){
				$return["errors"] = array_merge($return["errors"], $validate_data["errors"]);
			}
		}

		if(!$user_id){
			foreach($this->fields_register as $field){
				if(!(isset($return["data"][$field]) && $return["data"][$field])){
					$return["errors"][] = l('error_empty_fields', 'users');
					break;
				}
			}
		}

		return $return;
	}


	/**
	 * Deletes user
	 *
	 * @param int $user_id
	 */
	public function delete_user($user_id) {
		$data = $this->get_user_by_id($user_id);

		$this->DB->where('id', $user_id);
		$this->DB->delete(USERS_TABLE);

		$this->CI->load->model('users/models/Users_perfect_match_model');
		$this->CI->Users_perfect_match_model->delete_user_perfect_match_params($user_id);

		/// delete uploads
		$this->CI->load->model("Uploads_model");
		if($data['user_logo_moderation']){
			$this->CI->Uploads_model->delete_upload($this->upload_config_id, $user_id . "/", $data['user_logo_moderation']);
		}
		if($data['user_logo']){
			$this->CI->Uploads_model->delete_upload($this->upload_config_id, $user_id . "/", $data['user_logo']);
		}

		/// delete moderation
		$this->CI->load->model('Moderation_model');
		$this->CI->Moderation_model->delete_moderation_item_by_obj($this->moderation_type, $user_id);

		/// delete links
		$this->delete_user_contacts($user_id);

		/// delete user connections
		if($this->pg_module->is_module_installed('users_connections')){
			$this->CI->load->model('Users_connections_model');
			$this->CI->Users_connections_model->delete_user_connections($user_id);
		}

		/// delete user albums
		if($this->pg_module->is_module_installed('media')){
			$this->CI->load->model('media/models/Albums_model');
			$this->CI->load->model('media/models/Media_album_model');
			$this->CI->load->model('media/models/Media_model');
			$this->CI->Albums_model->delete_user_albums($user_id);
			$params = array();
			$params['where']['id_user'] = $user_id;
			$user_media = $this->CI->Media_model->get_media_ids(null, null, null, $params);
			if($user_media){
				$user_media_arrays = array_chunk($user_media, 5000);
				foreach($user_media_arrays as $user_media_arr){
					$this->CI->Media_album_model->delete_media_from_all_albums($user_media_arr);
				}
			}
		}

		return;
	}

	public function set_user_output_name(&$user){
		$hide_user_names = $this->pg_module->get_module_config('users', 'hide_user_names');
		if($hide_user_names && !(!empty($user['id']) && $this->session->userdata('user_id') == $user['id'])){
			$user['fname'] = $user['sname'] = '';
		}
		if(!(empty($user['fname']) && empty($user['sname'])) && !$hide_user_names){
			$user['output_name'] = trim($user['fname'].' '.$user['sname']);
		}else{
			$user['output_name'] = $user["nickname"];
		}
		return $user['output_name'];
	}

	public function format_users($data, $safe_format = false, $lang_id = ''){
		$user_for_location = array();

		$this->CI->load->model('Uploads_model');
		$this->CI->load->model('Properties_model');
		$this->CI->load->model('users/models/Users_statuses_model');
		$user_types = $this->CI->Properties_model->get_property('user_type', $lang_id);

		foreach($data as $key=>$user){
			if(!empty($user["account"])){
				$user["account"] = (double)$user["account"];
			}

			if(!empty($user["id"])){
				$user["postfix"] = $user["id"];
			}

			$cur_time = time();
			$user['is_activated'] = 0;
			if(!empty($user['activated_end_date'])){
				$user['unix_activated_end_date'] = intval(strtotime($user['activated_end_date']));
				if($user['unix_activated_end_date'] > $cur_time){
					$user['is_activated'] = 1;
				}
			}
			$user['is_featured'] = 0;
			if(!empty($user['featured_end_date'])){
				$user['unix_featured_end_date'] = intval(strtotime($user['featured_end_date']));
				if($user['unix_featured_end_date'] > $cur_time){
					$user['is_featured'] = 1;
				}
			}
			$user['is_hide_on_site'] = 0;
			if(!empty($user['hide_on_site_end_date'])){
				$user['unix_hide_on_site_end_date'] = intval(strtotime($user['hide_on_site_end_date']));
				if($user['unix_hide_on_site_end_date'] > $cur_time){
					$user['is_hide_on_site'] = 1;
				}
			}
			$user['is_highlight_in_search'] = 0;
			if(!empty($user['highlight_in_search_end_date'])){
				$user['unix_highlight_in_search_end_date'] = intval(strtotime($user['highlight_in_search_end_date']));
				if($user['unix_highlight_in_search_end_date'] > $cur_time){
					$user['is_highlight_in_search'] = 1;
				}
			}
			$user['is_up_in_search'] = 0;
			if(!empty($user['up_in_search_end_date'])){
				$user['unix_up_in_search_end_date'] = intval(strtotime($user['up_in_search_end_date']));
				if($user['unix_up_in_search_end_date'] > $cur_time){
					$user['is_up_in_search'] = 1;
				}
			}

			if(!empty($user['user_type'])){
				$user['user_type_str'] = !empty($user_types['option'][$user['user_type']]) ? $user_types['option'][$user['user_type']] : '';
			}
			if(!empty($user['looking_user_type'])){
				$user['looking_user_type_str'] = !empty($user_types['option'][$user['looking_user_type']]) ? $user_types['option'][$user['looking_user_type']] : '';
			}

			$this->set_user_output_name($user);

			$age_min = $this->pg_module->get_module_config('users', 'age_min');
			$age_max = $this->pg_module->get_module_config('users', 'age_max');
			if(isset($user['age_min']) && ($user['age_min'] < $age_min || $user['age_min'] > $age_max)){
				$user['age_min'] = $age_min;
			}
			if(isset($user['age_max'])){
				if($user['age_max'] < $age_min || $user['age_max'] > $age_max){
					$user['age_max'] = $age_max;
				}
				if(!empty($user['age_min']) && $user['age_min'] > $user['age_max']){
					$user['age_min'] = $age_min;
				}
			}

			if (!empty($user["user_logo"])) {
				$user["media"]["user_logo"] = $this->CI->Uploads_model->format_upload($this->upload_config_id, $user["postfix"], $user["user_logo"]);
			} else {
				$user["media"]["user_logo"] = $this->CI->Uploads_model->format_default_upload($this->upload_config_id);
			}

			if (!empty($user["user_logo_moderation"])) {
				$user["media"]["user_logo_moderation"] = $this->CI->Uploads_model->format_upload($this->upload_config_id, $user["postfix"], $user["user_logo_moderation"]);
			} else {
				$user["media"]["user_logo_moderation"] = $this->CI->Uploads_model->format_default_upload($this->upload_config_id);
			}

			$user_for_location[$user["id"]] = array($user["id_country"], $user["id_region"], $user["id_city"]);

			$user['statuses'] = $this->CI->Users_statuses_model->format_status($user['online_status'], $user['site_status']);

			$this->CI->load->helper('seo');
			$user['link'] = rewrite_link('users', 'view', $user);

			$data[$key] = $user;
		}

		if(!empty($user_for_location)){
			$this->CI->load->helper('countries');
			$user_locations = cities_output_format($user_for_location);
			foreach($data as $key=>$user){
				$data[$key]["location"] = (isset($user_locations[$user["id"]])) ? $user_locations[$user["id"]] : '';
			}
		}

		if($safe_format){
			foreach($data as $key=>$user){
				$data[$key] = array_intersect_key($data[$key], array_flip($this->safe_fields));
			}
		}

		return $data;
	}

	public function format_default_user($id = null, $lang_id = ''){
		$this->CI->load->model('Uploads_model');
		$data["postfix"] = $id ? $id : '';
		$data['link'] = site_url() . "users/untitled";
		$data["output_name"] = $id ? l('user_deleted', 'users', $lang_id) : l('guest', 'users', $lang_id);
		$data["media"]["user_logo"] = $this->CI->Uploads_model->format_default_upload($this->upload_config_id);
		return $data;
	}

	public function format_user($data, $safe_format = false, $lang_id = ''){
		if($data){
			$return = $this->format_users(array(0=>$data), $safe_format, $lang_id);
			return $return[0];
		}
		return array();
	}

	/**
	 * Don't delete (openid)
	 *
	 */
	/*function validate_open_id_data($open_id_data) {
		$attrs["email"] = $open_id_data['email'];
		$attrs["user_open_id"] = $open_id_data['openid'];

		if (!empty($open_id_data['fullname'])) {
			$name_arr = explode(" ", $open_id_data['fullname']);
			if (isset($name_arr[0]))
				$attrs["fname"] = $name_arr[0];
			if (isset($name_arr[1]))
				$attrs["sname"] = $name_arr[1];
		}

		if (!empty($open_id_data['dob'])) {
			$attrs["date_birthdate"] = $open_id_data['dob'] . " 00:00:00";
		}

		$attrs["status"] = "1";
		$attrs["confirm"] = "1";
		$attrs["lang_id"] = $this->CI->pg_language->get_default_lang_id();

		$this->CI->load->model('users/models/Groups_model');
		$attrs["group_id"] = $this->CI->Groups_model->get_default_group_id();

		if (empty($open_id_data["nickname"])) {
			$open_id_data["nickname"] = $this->create_nickname_by_open_id_email($open_id_data["email"]);
		}

		$get_nickname_user = $this->get_user_by_login($open_id_data["nickname"]);
		if (!empty($get_nickname_user)) {
			$attrs["nickname"] = $open_id_data["nickname"] . rand(10000, 99999);
		} else {
			$attrs["nickname"] = $open_id_data["nickname"];
		}

		$attrs["user_gender"] = ($open_id_data["gender"] == "F") ? 'female' : 'male';

		return $attrs;
	}*/

	/**
	 * Don't delete (openid)
	 *
	 */
	/*function create_nickname_by_open_id_email($email) {
		$email_arr = explode("@", $email);
		$nickname = $email_arr[0];
		$nickname = preg_replace("/[^a-z0-9\.\-_]/i", "", $nickname);
		return $nickname;
	}*/

	////// seo
	public function get_seo_settings($method = '', $lang_id = '') {
		if (!empty($method)) {
			return $this->_get_seo_settings($method, $lang_id);
		} else {
			$actions = array('account', 'account_delete', 'settings', 'login_form', 'restore', 'profile', 'view', 'registration', 'confirm', 'search', 'perfect_match', 'my_visits', 'my_guests');
			$return = array();
			foreach ($actions as $action) {
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	private function _get_seo_settings($method, $lang_id = '') {
		if ($method == "account") {
			return array(
				"templates" => array('nickname', 'fname', 'sname', 'email', 'output_name'),
				"url_vars" => array()
			);
		} elseif ($method == "account_delete") {
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		} elseif ($method == "settings") {
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		} elseif ($method == "login_form") {
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		} elseif ($method == "restore") {
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		} elseif ($method == "profile") {
			return array(
				"templates" => array('nickname', 'fname', 'sname', 'email', 'user_type', 'output_name'),
				"url_vars" => array()
			);
		} elseif ($method == "view") {
			return array(
				"templates" => array('nickname', 'fname', 'sname', 'email', 'output_name'),
				"url_vars" => array(
					"id" => array("nickname" => 'literal')
				)
			);
		} elseif ($method == "registration") {
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		} elseif ($method == "confirm") {
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		} elseif ($method == "search") {
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		} elseif ($method == "perfect_match") {
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		} elseif ($method == "my_visits") {
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		} elseif ($method == "my_guests") {
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		}
	}

	public function request_seo_rewrite($var_name_from, $var_name_to, $value) {
		$user_data = array();

		if ($var_name_from == $var_name_to) {
			return $value;
		}

		if ($var_name_from == "nickname") {
			$user_data = $this->get_user_by_login($value);
		}

		if ($var_name_to == "id") {
			return $user_data["id"];
		}
	}

	public function get_sitemap_xml_urls() {
		$this->CI->load->helper('seo');
		$return = array(
			array(
				"url" => rewrite_link('users', 'account'),
				"priority" => 0.1
			),
			array(
				"url" => rewrite_link('users', 'login_form'),
				"priority" => 0.3
			),
			array(
				"url" => rewrite_link('users', 'profile'),
				"priority" => 0.1
			),
			array(
				"url" => rewrite_link('users', 'register'),
				"priority" => 0.1
			),
			array(
				"url" => rewrite_link('users', 'settings'),
				"priority" => 0.1
			),
			array(
				"url" => rewrite_link('users', 'my_visits'),
				"priority" => 0.1
			),
			array(
				"url" => rewrite_link('users', 'my_guests'),
				"priority" => 0.1
			),
			array(
				"url" => rewrite_link('users', 'perfect_match'),
				"priority" => 0.1
			),
			array(
				"url" => rewrite_link('users', 'search'),
				"priority" => 0.1
			),
		);
		return $return;
	}

	public function get_sitemap_urls() {
		$this->CI->load->helper('seo');
		$auth = $this->CI->session->userdata("auth_type");
		$block = array();

		$users_block = array(
			"name" => l('header_profile', 'users'),
			"link" => rewrite_link('users', 'profile', 'view'),
			"clickable" => ($auth == "user"),
			"items" => array(
				array(
					"name" => l('header_login', 'users'),
					"link" => rewrite_link('users', 'login_form'),
					"clickable" => !($auth == "user"),
				),
				array(
					"name" => l('link_edit_account', 'users'),
					"link" => rewrite_link('users', 'account'),
					"clickable" => ($auth == "user"),
				),
				array(
					"name" => l('link_edit_profile', 'users'),
					"link" => rewrite_link('users', 'profile'),
					"clickable" => ($auth == "user"),
				),
				array(
					"name" => l('link_logout', 'users'),
					"link" => site_url() . "users/logout",
					"clickable" => ($auth == "user"),
				),
				array(
					"name" => l('header_account_settings', 'users'),
					"link" => rewrite_link('users', 'settings'),
					"clickable" => ($auth == "user"),
				),
				array(
					"name" => l('header_my_visits', 'users'),
					"link" => rewrite_link('users', 'my_visits'),
					"clickable" => ($auth == "user"),
				),
				array(
					"name" => l('header_my_guests', 'users'),
					"link" => rewrite_link('users', 'my_guests'),
					"clickable" => ($auth == "user"),
				),
				array(
					"name" => l('header_perfect_match_results', 'users'),
					"link" => rewrite_link('users', 'perfect_match'),
					"clickable" => ($auth == "user"),
				),
				array(
					"name" => l('header_find_people', 'users'),
					"link" => rewrite_link('users', 'search'),
					"clickable" => ($auth == "user"),
				),
			)
		);

		if($this->CI->pg_module->is_module_installed('banners')){
			$users_block['items'][] = array(
				"name" => l('header_my_banners', 'banners'),
				"link" => site_url().'users/account/banners',
				"clickable" => ($auth == "user"),
			);
		}

		$block[] = $users_block;

		return $block;
	}

	////// banners callback method
	public function _banner_available_pages() {
		$return[] = array("link" => "users/profile", "name" => l('header_profile', 'users'));
		$return[] = array("link" => "users/login_form", "name" => l('header_login', 'users'));
		$return[] = array("link" => "users/register", "name" => l('header_register', 'users'));
		$return[] = array("link" => "users/account", "name" => l('link_edit_account', 'users'));
		$return[] = array("link" => "users/view", "name" => l('header_view_profile', 'users'));
		return $return;
	}

	public function _dynamic_block_last_registered($params, $view = '') {
		$count = $params["count"];
		if ($params["with_logo"] == 'yes') {
			$attrs["where"]["user_logo !="] = '';
		} else {
			$attrs = array();
		}
		$users = $this->get_active_users_list(1, $count, array("date_created" => "DESC"), $attrs);
		$this->CI->template_lite->assign('users', $users);
		return $this->CI->template_lite->fetch('helper_last_registered', 'user', 'users');
	}

	///// moderation functions
	public function _moder_get_list($object_ids) {
		$params["where_in"]["id"] = $object_ids;

		$users = $this->get_users_list(null, null, null, $params);

		if (!empty($users)) {
			foreach ($users as $user) {
				$return[$user["id"]] = $user;
			}
			return $return;
		} else {
			return array();
		}
	}

	public function _moder_set_status($object_id, $status) {
		$user = $this->get_user_by_id($object_id);
		$backup_user = array();
		switch ($status) {
			case 0:
				if ($user['user_logo_moderation']) {
					$backup_user['user_logo_moderation'] = '';
				}else{
					$backup_user['user_logo'] = '';
				}
				break;
			case 1:
				if ($user['user_logo_moderation']) {
					$backup_user['user_logo'] = $user['user_logo_moderation'];
					$backup_user['user_logo_moderation'] = '';
				}
				break;
		}
		$this->save_user($object_id, $backup_user);
	}

	public function add_contact($id_user, $id_contact){
		$this->CI->load->model('Linker_model');
		$this->CI->Linker_model->add_link('users_contacts', $id_user, $id_contact);
		$this->CI->Linker_model->add_link('users_contacts', $id_contact, $id_user);
		return;
	}

	public function delete_contact($id_user, $id_contact){
		$this->CI->load->model('Linker_model');
		$params["where_in"]['id_link_2'] = $params["where_in"]['id_link_1'] = array($id_user, $id_contact);
		$this->CI->Linker_model->delete_links('users_contacts', $params);
		return;
	}

	public function delete_user_contacts($id_user){
		$this->CI->load->model('Linker_model');
		$params["where"]['id_link_2'] = $params["where"]['id_link_1'] = $id_user;
		$this->CI->Linker_model->delete_links('users_contacts', $params);
		return;
	}

	public function get_fulltext_data($id, $fields){
		$return = array('main_fields'=>array(), 'fe_fields'=>array(), 'default_lang_id'=>$this->CI->pg_language->get_default_lang_id(), 'object_lang_id'=> 1);
		$this->set_additional_fields($fields);
		$data = $this->get_user_by_id($id);
		$hide_user_names = $this->pg_module->get_module_config('users', 'hide_user_names');
		$return['object_lang_id'] = $data["lang_id"];
		$return['main_fields'] = array(
			'fname' => $hide_user_names ? '' : $data['fname'],
			'sname' => $hide_user_names ? '' : $data['sname'],
			'nickname' => $data['nickname'],
			'phone' => $data['phone'],
			'address' => $data['address'],
			'postal_code' => $data['postal_code'],
			'birth_date' => $data['birth_date'],
		);
		$user_for_location[$data["id"]] = array($data["id_country"], $data["id_region"], $data["id_city"]);
		$this->CI->load->helper('countries');
		$user_locations = cities_output_format($user_for_location);
		$return['main_fields']["location"] = (isset($user_locations[$data["id"]])) ? $user_locations[$data["id"]] : '';

		foreach($fields as $field){
			$return['fe_fields'][$field] = $data[$field];
		}
		return $return;
	}

	public function update_age($filter_object_ids = array()){
		if (is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in('id', $filter_object_ids);
		}
		$now_date = date('Y-m-d');
		$this->DB->set('age', "FLOOR(DATEDIFF('$now_date', birth_date) / 365)", FALSE)
				->update(USERS_TABLE);
		return $this->DB->affected_rows();
	}

	public function update_profile_completion($filter_object_ids = array()){
		$filter_object_ids = (array)$filter_object_ids;
		$table_fields = $this->DB->list_fields(USERS_TABLE);
		$this->CI->load->model('Field_editor_model');
		$this->CI->Field_editor_model->initialize($this->form_editor_type);
		$fe_fields = $this->CI->Field_editor_model->get_fields_list();
		foreach($fe_fields as $fe_field){
			if(in_array($fe_field['field_name'], $table_fields)){
				$this->fields_completion[] = $fe_field['field_name'];
			}
		}

		$fields_count = count($this->fields_completion);
		$fields_sql = array();
		foreach($this->fields_completion as $field){
			$fields_sql[] = "IF(ISNULL({$field}), 0 , ({$field}>''))";
		}

		if (count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}
		if($fields_sql){
			$this->DB->set('profile_completion', "ROUND((".implode('+', $fields_sql).")/{$fields_count}*100)", FALSE)->update(USERS_TABLE);
		}
	}

	public function service_set_user_activate_in_search($id_user, $period = null){
		$user = $this->get_user_by_id($id_user);
		if(strtotime($user["activated_end_date"]) > 86400){
			$uts = strtotime($user["activated_end_date"]);
		}else{
			$uts = time();
		}
		if(!is_null($period)){
			$data['activated_end_date'] =  date('Y-m-d H:i:s', $uts+$period*60*60*24);
		} else {
			$data['activated_end_date'] = '0000-00-00 00:00:00';
		}
		$data['activity'] = '1';
		$this->save_user($id_user, $data);
		return;
	}

	public function service_set_users_featured($id_user, $period = ''){
		$user = $this->get_user_by_id($id_user);
		if(strtotime($user["featured_end_date"]) > time()){
			$uts = strtotime($user["featured_end_date"]);
		}else{
			$uts = time();
		}
		if($period){
			$data['featured_end_date'] =  date('Y-m-d H:i:s', $uts+$period*60*60*24);
		} else {
			$data['featured_end_date'] = '0000-00-00 00:00:00';
		}
		$this->save_user($id_user, $data);
		return;
	}

	public function is_user_featured($user_id) {
		$this->DB->select('featured_end_date')
				->from(USERS_TABLE)
				->where('id', $user_id)
				->where('featured_end_date >', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(featured_end_date) >', '86400');
		$results = $this->DB->get()->result_array();
		$return = array('is_featured' => 0);
		if (isset($results[0]['featured_end_date'])){
			$return['is_featured'] = 1;
			$return['featured_end_date'] = $results[0]['featured_end_date'];
		}

		return $return;
	}

	public function is_user_activated($user_id) {
		$this->DB->select('activated_end_date, (UNIX_TIMESTAMP(activated_end_date) - UNIX_TIMESTAMP())/86400 AS period')
				->from(USERS_TABLE)
				->where('id', $user_id)
				->where('activated_end_date >', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(activated_end_date) >', '86400');
		$results = $this->DB->get()->row_array();
		$return = array('is_activity' => 0);
		if (isset($results['activated_end_date'])){
			$return['is_activity'] = 1;
			$return['activated_end_date'] = $results['activated_end_date'];
			$return['period'] = $results['period'];
		}

		return $return;
	}

	public function service_set_admin_approve($user_id){
		$data['approved'] = '1';
		$this->save_user($user_id, $data);
		return;
	}

	public function service_set_hide_on_site($id_user, $period = ''){
		$user = $this->get_user_by_id($id_user);
		if(strtotime($user["hide_on_site_end_date"]) > 86400){
			$uts = strtotime($user["hide_on_site_end_date"]);
		}else{
			$uts = time();
		}
		if($period){
			$data['hide_on_site_end_date'] =  date('Y-m-d H:i:s', $uts+$period*60*60*24);
		} else {
			$data['hide_on_site_end_date'] = '0000-00-00 00:00:00';
		}
		$this->save_user($id_user, $data);
		return;
	}

	public function service_set_highlight_in_search($id_user, $period = ''){
		$user = $this->get_user_by_id($id_user);
		if(strtotime($user["highlight_in_search_end_date"]) > time()){
			$uts = strtotime($user["highlight_in_search_end_date"]);
		}else{
			$uts = time();
		}
		if($period){
			$data['highlight_in_search_end_date'] =  date('Y-m-d H:i:s', $uts+$period*60*60*24);
		} else {
			$data['highlight_in_search_end_date'] = '0000-00-00 00:00:00';
		}
		$this->save_user($id_user, $data);
		return;
	}

	public function service_set_up_in_search($id_user, $period = ''){
		$user = $this->get_user_by_id($id_user);
		if(strtotime($user["up_in_search_end_date"]) > 86400){
			$uts = strtotime($user["up_in_search_end_date"]);
		}else{
			$uts = time();
		}
		if($period){
			$data['up_in_search_end_date'] =  date('Y-m-d H:i:s', $uts+$period*60*60*24);
		} else {
			$data['up_in_search_end_date'] = '0000-00-00 00:00:00';
		}
		$this->save_user($id_user, $data);
		return;
	}

	public function service_cron_user_activate_in_search(){
		$this->DB->select('COUNT(*) AS cnt')->from(USERS_TABLE)
				->where('activated_end_date <', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(activated_end_date) >', '86400');
		$results = $this->DB->get()->result_array();
		$clean = 0;
		if(!empty($results) && is_array($results) && $results[0]["cnt"] > 0){
			$data["activated_end_date"] = '0000-00-00 00:00:00';
			$data["activity"] = '0';
			$this->DB->where('activated_end_date <', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(activated_end_date) >', '86400')
				->update(USERS_TABLE, $data);
			$clean = $results[0]["cnt"];
		}
		echo "Make clean(Users activated): ".$clean." Users was deactivated";
	}

	public function service_cron_users_featured(){
		$this->DB->select('COUNT(*) AS cnt')->from(USERS_TABLE)
				->where('featured_end_date <', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(featured_end_date) >', '86400');
		$results = $this->DB->get()->result_array();
		$clean = 0;
		if(!empty($results) && is_array($results) && $results[0]["cnt"] > 0){
			$data["featured_end_date"] = '0000-00-00 00:00:00';
			$this->DB->where('featured_end_date <', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(featured_end_date) >', '86400')
				->update(USERS_TABLE, $data);
			$clean = $results[0]["cnt"];
		}
		echo "Make clean(Users featured): ".$clean." Users was removed";
	}

	public function service_cron_hide_on_site(){
		$this->DB->select('COUNT(*) AS cnt')->from(USERS_TABLE)
				->where('hide_on_site_end_date <', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(hide_on_site_end_date) >', '86400');
		$results = $this->DB->get()->result_array();
		$clean = 0;
		if(!empty($results) && is_array($results) && $results[0]["cnt"] > 0){
			$data["hide_on_site_end_date"] = '0000-00-00 00:00:00';
			$this->DB->where('hide_on_site_end_date <', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(hide_on_site_end_date) >', '86400')
				->update(USERS_TABLE, $data);
			$clean = $results[0]["cnt"];
		}
		echo "Make clean(hide on site): ".$clean." Users was removed";
	}

	public function service_cron_highlight_in_search(){
		$this->DB->select('COUNT(*) AS cnt')->from(USERS_TABLE)
				->where('highlight_in_search_end_date <', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(highlight_in_search_end_date) >', '86400');
		$results = $this->DB->get()->result_array();
		$clean = 0;
		if(!empty($results) && is_array($results) && $results[0]["cnt"] > 0){
			$data["hide_on_site_end_date"] = '0000-00-00 00:00:00';
			$this->DB->where('highlight_in_search_end_date <', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(highlight_in_search_end_date) >', '86400')
				->update(USERS_TABLE, $data);
			$clean = $results[0]["cnt"];
		}
		echo "Make clean(highlight in search): ".$clean." Users was removed";
	}

	public function service_cron_up_in_search(){
		$this->DB->select('COUNT(*) AS cnt')->from(USERS_TABLE)
				->where('up_in_search_end_date <', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(up_in_search_end_date) >', '86400');
		$results = $this->DB->get()->result_array();
		$clean = 0;
		if(!empty($results) && is_array($results) && $results[0]["cnt"] > 0){
			$data["up_in_search_end_date"] = '0000-00-00 00:00:00';
			$this->DB->where('up_in_search_end_date <', date('Y-m-d H:i:s'))
				->where('UNIX_TIMESTAMP(up_in_search_end_date) >', '86400')
				->update(USERS_TABLE, $data);
			$clean = $results[0]["cnt"];
		}
		echo "Make clean(up in search): ".$clean." Users was removed";
	}

	public function service_cron_region_leader(){
		$this->CI->load->model('Services_model');
		$service_data = $this->CI->Services_model->get_service_by_gid('region_leader');
		$write_period = $service_data["data_admin_array"]["write_off_period"]*60*60;
		$write_amount = $service_data["data_admin_array"]["write_off_amount"];

		$this->DB->select('COUNT(*) AS cnt')->from(USERS_TABLE)->where("leader_bid > 0 AND ( UNIX_TIMESTAMP()-UNIX_TIMESTAMP(leader_write_date)>'".$write_period."' )");
		$results = $this->DB->get()->result_array();
		$clean = 0;
		if(!empty($results) && is_array($results) && $results[0]["cnt"] > 0){
			$this->DB->set('leader_bid', 'leader_bid-'.$write_amount, FALSE);
			$this->DB->set('leader_write_date', date("Y-m-d H:i:s"));
			$this->DB->where("leader_bid > 0 AND ( UNIX_TIMESTAMP()-UNIX_TIMESTAMP(leader_write_date)>'".$write_period."' )");
			$this->DB->update(USERS_TABLE);
			$clean = $results[0]["cnt"];
		}

		$this->DB->where("leader_bid < 0");
		$this->DB->update(USERS_TABLE, array('leader_bid' => 0));

		echo "Reculc bids(Users region leader): ".$clean." users was updated";
	}

	public function get_common_criteria($data){
		$criteria["where"]["approved"] = '1';
		$criteria["where"]["confirm"] = '1';
		$criteria["where"]["activity"] = '1';
		$criteria["where"]["hide_on_site_end_date <"] = date('Y-m-d H:i:s');

		if(!empty($data['age_min'])){
			$criteria["where"]["age >="] = intval($data["age_min"]);
		}
		if(!empty($data['age_max'])){
			$criteria["where"]["age <="] = intval($data["age_max"]);
		}
		if(!empty($data['user_type'])){
			if(is_array($data["user_type"])) {
				$criteria["where_in"]["user_type"] = $data["user_type"];
			} else {
				$criteria["where"]["user_type"] = intval($data["user_type"]);
			}
		}
		if(!empty($data['looking_user_type'])){
			$criteria["where"]["looking_user_type"] = intval($data["looking_user_type"]);
		}
		if(!empty($data['online_status']) && $data['online_status']){
			$criteria["where"]["online_status"] = 1;
		}

		if($this->session->userdata('auth_type') == 'user'){
			$criteria["where"]["id !="] = $this->session->userdata('user_id');
		}

		if(!empty($data["id_country"])){
			$data["id_country"] = $data["id_country"];
			$criteria["where"]["id_country"] = $data["id_country"];
		}
		if(!empty($data["id_region"])){
			$data["id_region"] = intval($data["id_region"]);
			$criteria["where"]["id_region"] = $data["id_region"];
		}
		if(!empty($data["id_city"])){
			$data["id_city"] = intval($data["id_city"]);
			$criteria["where"]["id_city"] = $data["id_city"];
		}
		return $criteria;
	}

	public function get_advanced_search_criteria($data){
		$criteria = array();
		if($this->pg_module->get_module_config('users', 'hide_user_names')){
			unset($data['fname'], $data['sname']);
		}

		if(!empty($data['fname'])){
			$criteria["like"]["fname"] = trim(strip_tags($data["fname"]));
		}
		if(!empty($data['sname'])){
			$criteria["like"]["sname"] = trim(strip_tags($data["sname"]));
		}
		if(!empty($data['nickname'])){
			$criteria["like"]["nickname"] = trim(strip_tags($data["nickname"]));
		}

		if(!empty($data["id"])){
			if ( is_array($data["id"]) ) {
				$criteria["where_in"]["id"] = $data["id"];
			}else{
				$criteria["where"]["id"] = $data["id"];
			}
		}

		return $criteria;
	}

	public function get_default_search_data(){
		$this->CI->load->model('Properties_model');
		$user_type = $this->CI->session->userdata('user_type');
		$user_types = $this->CI->Properties_model->get_property('user_type');
		if($user_type !== false && isset($user_types['option'][$user_type])){
			unset($user_types['option'][$user_type]);
		}
		if(!empty($user_types['option'])){
			$data['user_type'] = array_shift(array_keys($user_types['option']));
		}
		$data['age_min'] = $this->pg_module->get_module_config('users', 'age_min');
		$data['age_max'] = $this->pg_module->get_module_config('users', 'age_max');

		$this->CI->load->model('Field_editor_model');
		$this->CI->load->model('field_editor/models/Field_editor_forms_model');
		$this->CI->Field_editor_model->initialize($this->form_editor_type);
		$form = $this->CI->Field_editor_forms_model->get_form_by_gid($this->advanced_search_form_gid, $this->form_editor_type);
		$form = $this->CI->Field_editor_forms_model->format_output_form($form, array(), true);
		if($form['field_data']){
			foreach($form['field_data'] as $field_data){
				if(isset($field_data['field_content']['value'])){
					$data[$field_data['field_content']['field_name']] = $field_data['field_content']['value'];
				}
			}
		}

		return $data;
	}

	public function get_minimum_search_data(){
		$data['age_min'] = $this->pg_module->get_module_config('users', 'age_min');
		$data['age_max'] = $this->pg_module->get_module_config('users', 'age_max');
		return $data;
	}

	public function update_online_status($user_id, $status){
		$status = intval($status);
		if($status){
			$this->DB->set('date_last_activity', date('Y-m-d H:i:s'))->where('id', $user_id)->update(USERS_TABLE);
		}

		if($user_id == $this->CI->session->userdata('user_id')){
			$user_site_status = $this->CI->session->userdata('site_status');
		}else{
			$user = $this->get_user_by_id($user_id);
			$user_site_status = !empty($user['site_status']) ? $user['site_status'] : 0;
		}
		if(!$status){
			$user_site_status = 0;
		}

		$this->DB->set('online_status', $status)->where('id', $user_id)->update(USERS_TABLE);
		$is_updated = $this->DB->affected_rows();
		if($is_updated){
			$this->CI->load->model('users/models/Users_statuses_model');
			$site_statuses = $this->CI->Users_statuses_model->statuses;
			$event_status = isset($site_statuses[$user_site_status]) ? $site_statuses[$user_site_status] : 0;
			if($event_status){
				$this->CI->Users_statuses_model->execute_callbacks($event_status, $user_id);
			}
		}
	}

	public function cron_set_offline_status(){
		$where['online_status'] = '1';
		$where['date_last_activity <'] = date('Y-m-d H:i:s', time() - 600);
		$where['date_last_activity !='] = '0000-00-00 00:00:00';
		$users = $this->get_users_list(null, null, null, array('where'=>$where), null, false);
		$users_ids = array();
		foreach($users as $user){
			$users_ids[] = $user['id'];
		}
		if($users_ids){
			$users_ids = array_slice($users_ids, 0, 10000); //на всякий случай, если вдруг выберется много ИДшников
			$this->DB->where_in('id', $users_ids)->set('online_status', '0')->update(USERS_TABLE);
			$this->CI->load->model('users/models/Users_statuses_model');
			$this->CI->Users_statuses_model->execute_callbacks(0, $users_ids);
		}
	}

	/* SERVICES */
	private function _service_available_default_action($id_user, $service_gid){
		$return['available'] = 0;
		$return['content'] = '';
		$return['content_buy_block'] = false;
		$services_available = false;
		if($this->pg_module->is_module_installed('services')){
			$this->CI->load->model('services/models/Services_users_model');
			$service_access = $this->CI->Services_users_model->is_service_access($id_user, $service_gid);
			$services_available = ($service_access['use_status']) ? true : false;
		}
		$return['services_available'] = $services_available;
		if ($services_available){
			$return['content_buy_block'] = true;
		} else {
			$return['content'] = l('service_not_found', 'services');
			$return['available'] = 1;
		}
		return $return;
	}

	public function service_available_hide_on_site_action($id_user){
		return $this->_service_available_default_action($id_user, 'hide_on_site');
	}

	public function service_available_highlight_in_search_action($id_user){
		return $this->_service_available_default_action($id_user, 'highlight_in_search');
	}

	public function service_available_up_in_search_action($id_user){
		return $this->_service_available_default_action($id_user, 'up_in_search');
	}

	public function service_available_ability_delete_action($id_user){
		$result = $this->_service_available_default_action($id_user, 'ability_delete');
		if($result['services_available']){
			$result['content_buy_block'] = true;
			$result['content'] = $this->CI->Services_users_model->available_service_block($id_user, 'ability_delete');
		}else{
			$result['content_buy_block'] = false;
			$result['content'] = '<script>locationHref("'.site_url().'users/account_delete")</script>';
		}
		$result['available'] = 0;
		return $result;
	}

	public function service_available_user_activate_in_search_action($id_user){
		$return['available'] = 0;
		$return['content'] = '';
		$return['content_buy_block'] = false;

		$activated = $this->is_user_activated($id_user);
		$this->CI->load->model('Services_model');
		$service = $this->CI->Services_model->get_service_by_gid('user_activate_in_search');
		if (!empty($service['status']) && !$activated['is_activity']){
			$return['content_buy_block'] = true;
		} else {
			$this->service_set_user_activate_in_search($id_user, 0);
			$return['available'] = 1;
		}

		return $return;
	}

	public function service_available_users_featured_action($id_user){
		$return['available'] = 0;
		$return['content'] = '';
		$return['content_buy_block'] = false;

		$this->CI->load->model('Services_model');
		$services_params = array();
		$services_params['where']['gid'] = 'users_featured';
		$services_params['where']["status"] = 1;
		$services_count = $this->CI->Services_model->get_service_count($services_params);
		if($services_count){
			$return['content_buy_block'] = true;
		}else{
			$return['content'] = l('service_not_found', 'services');
			$return['available'] = 1;
		}

		return $return;
	}

	public function service_available_admin_approve_action($id_user){
		$return['available'] = 0;
		$return['content'] = '';
		$return['content_buy_block'] = false;

		$this->CI->load->model('Services_model');
		$services_params = array();
		$services_params['where']['gid'] = 'admin_approve';
		$services_params['where']["status"] = 1;
		$services_count = $this->CI->Services_model->get_service_count($services_params);
		if ($services_count){
			$return['content_buy_block'] = true;
		} else {
			$this->service_set_admin_approve($id_user);
			$return['available'] = 1;
		}

		return $return;
	}

	public function service_validate_user_activate_in_search($user_id, $data, $service_data=array(), $price=''){
		return $this->_service_validate('user_activate_in_search', $user_id, $data, $service_data, $price);
	}

	public function service_validate_users_featured($user_id, $data, $service_data=array(), $price=''){
		return $this->_service_validate('users_featured', $user_id, $data, $service_data, $price);
	}

	public function service_validate_admin_approve($user_id, $data, $service_data=array(), $price=''){
		return $this->_service_validate('admin_approve', $user_id, $data, $service_data, $price);
	}

	public function service_validate_hide_on_site($user_id, $data, $service_data=array(), $price=''){
		return $this->_service_validate('hide_on_site', $user_id, $data, $service_data, $price);
	}

	public function service_validate_highlight_in_search($user_id, $data, $service_data=array(), $price=''){
		return $this->_service_validate('highlight_in_search', $user_id, $data, $service_data, $price);
	}

	public function service_validate_up_in_search($user_id, $data, $service_data=array(), $price=''){
		return $this->_service_validate('up_in_search', $user_id, $data, $service_data, $price);
	}

	public function service_validate_ability_delete($user_id, $data, $service_data=array(), $price=''){
		return $this->_service_validate('ability_delete', $user_id, $data, $service_data, $price);
	}

	public function service_buy_region_leader($id_user, $price, $service, $template, $payment_data, $users_package_id = 0, $count = 1){
		$user = $this->get_user_by_id($id_user);
		if($user["leader_bid"] > 0){
			$bid = $user["leader_bid"]+$price;
		}else{
			$bid = $price;
		}
		$text = $payment_data['user_data']['text'];

		$data = array(
			'leader_bid' => $bid,
			'leader_text' => $text,
			'leader_write_date' => date("Y-m-d H:i:s"),
		);
		$user = $this->save_user($id_user, $data);

		$this->service_buy($id_user, $price, $service, $template, $payment_data, $users_package_id, 0, 0);

		$return['status'] = 1;
		$return['message'] = l('success_service_activating', 'services');
		return $return;
	}

	public function service_validate_region_leader($user_id, $data, $service_data, $price){
		$return = array("errors"=> array(), "data" => $data);
		if($service_data["data_admin_array"]["min_bid"] > floatval($price)){
			$return["errors"][] = l('error_service_leader_min_bid_error', 'users');
		}
		if(empty($data["text"])){
			$return["errors"][] = l('error_leader_text_is_empty', 'users');
		}

		return $return;
	}

	public function service_buy($id_user, $price, $service, $template, $payment_data, $users_package_id = 0, $count = 1, $status = 1){
		$service_data = array(
			'id_user' => $id_user,
			'service_gid' => $service['gid'],
			'template_gid' => $template['gid'],
			'service' => $service,
			'template' => $template,
			'payment_data' => $payment_data,
			'id_users_package' => $users_package_id,
			'status' => $status,
			'count' => $count,
		);

		$this->CI->load->model('services/models/Services_users_model');
		return $this->CI->Services_users_model->save_service(null, $service_data);
	}

	private function _service_validate($service_gid, $user_id, $data, $service_data=array(), $price=''){
		$return = array("errors"=> array(), "data" => $data);
		return $return;
	}

	public function service_activate_ability_delete($id_user, $id_user_service, $is_ajax = 0){
		$id_user_service = intval($id_user_service);
		$return = array('status' => 0, 'message' => '');

		$this->CI->load->model('services/models/Services_users_model');
		$user_service = $this->CI->Services_users_model->get_user_service_by_id($id_user, $id_user_service);
		if (empty($user_service) || !$user_service["status"]){
			$return['status'] = 0;
			$return['message'] = l('error_service_activating', 'services');
		} else {
			$this->delete_user($id_user);
			$user_service['count']--;
			if ($user_service['count'] < 1){
				$user_service["status"] = 0;
			}
			$this->CI->Services_users_model->save_service($id_user_service, $user_service);
			$return['status'] = 1;
			$return['message'] = l('success_service_activating', 'services');

			$auth_type = $this->CI->session->userdata("auth_type");
			if($auth_type != 'admin'){
				$this->CI->load->model("users/models/Auth_model");
				$this->CI->Auth_model->logoff();
				if(!$is_ajax){
					redirect();
				}
			}
		}
		return $return;
	}

	public function service_activate_admin_approve($id_user, $id_user_service) {
		$id_user_service = intval($id_user_service);
		$return = array('status' => 0, 'message' => '');

		$this->CI->load->model('services/models/Services_users_model');
		$user_service = $this->CI->Services_users_model->get_user_service_by_id($id_user, $id_user_service);
		if (empty($user_service) || !$user_service["status"] || $user_service['count'] < 1){
			$return['status'] = 0;
			$return['message'] = l('error_service_activating', 'services');
		} else {
			$this->service_set_admin_approve($id_user);
			$user_service['count']--;
			if ($user_service['count'] < 1){
				$user_service["status"] = 0;
			}
			$this->CI->Services_users_model->save_service($id_user_service, $user_service);
			$return['status'] = 1;
			$return['message'] = l('success_service_activating', 'services');
		}
		return $return;
	}

	public function service_activate_hide_on_site($id_user, $id_user_service) {
		$id_user_service = intval($id_user_service);
		$return = array('status' => 0, 'message' => '');

		$this->CI->load->model('services/models/Services_users_model');
		$user_service = $this->CI->Services_users_model->get_user_service_by_id($id_user, $id_user_service);
		if (empty($user_service) || !$user_service["status"] || $user_service['count'] < 1){
			$return['status'] = 0;
			$return['message'] = l('error_service_activating', 'services');
		} else {
			$this->service_set_hide_on_site($id_user, $user_service['service']['data_admin']['period']);
			$user_service['count']--;
			if ($user_service['count'] < 1){
				$user_service["status"] = 0;
			}
			$this->CI->Services_users_model->save_service($id_user_service, $user_service);
			$return['status'] = 1;
			$return['message'] = l('success_service_activating', 'services');
		}
		return $return;
	}

	public function service_activate_highlight_in_search($id_user, $id_user_service) {
		$id_user_service = intval($id_user_service);
		$return = array('status' => 0, 'message' => '');

		$this->CI->load->model('services/models/Services_users_model');
		$user_service = $this->CI->Services_users_model->get_user_service_by_id($id_user, $id_user_service);
		if (empty($user_service) || !$user_service["status"] || $user_service['count'] < 1){
			$return['status'] = 0;
			$return['message'] = l('error_service_activating', 'services');
		} else {
			$this->service_set_highlight_in_search($id_user, $user_service['service']['data_admin']['period']);
			$user_service['count']--;
			if ($user_service['count'] < 1){
				$user_service["status"] = 0;
			}
			$this->CI->Services_users_model->save_service($id_user_service, $user_service);
			$return['status'] = 1;
			$return['message'] = l('success_service_activating', 'services');
		}
		return $return;
	}

	public function service_activate_up_in_search($id_user, $id_user_service) {
		$id_user_service = intval($id_user_service);
		$return = array('status' => 0, 'message' => '');

		$this->CI->load->model('services/models/Services_users_model');
		$user_service = $this->CI->Services_users_model->get_user_service_by_id($id_user, $id_user_service);
		if (empty($user_service) || !$user_service["status"]){
			$return['status'] = 0;
			$return['message'] = l('error_service_activating', 'services');
		} else {
			$this->service_set_up_in_search($id_user, $user_service['service']['data_admin']['period']);
			$user_service['count']--;
			if ($user_service['count'] < 1){
				$user_service["status"] = 0;
			}
			$this->CI->Services_users_model->save_service($id_user_service, $user_service);
			$return['status'] = 1;
			$return['message'] = l('success_service_activating', 'services');
		}
		return $return;
	}

	public function service_activate_users_featured($id_user, $id_user_service) {
		$id_user_service = intval($id_user_service);
		$return = array('status' => 0, 'message' => '');

		$this->CI->load->model('services/models/Services_users_model');
		$user_service = $this->CI->Services_users_model->get_user_service_by_id($id_user, $id_user_service);
		if (empty($user_service) || !$user_service["status"]){
			$return['status'] = 0;
			$return['message'] = l('error_service_activating', 'services');
		} else {
			$this->service_set_users_featured($id_user, $user_service['service']['data_admin']['period']);
			$user_service['count']--;
			if ($user_service['count'] < 1){
				$user_service["status"] = 0;
			}
			$this->CI->Services_users_model->save_service($id_user_service, $user_service);
			$return['status'] = 1;
			$return['message'] = l('success_service_activating', 'services');
		}
		return $return;
	}

	public function service_activate_user_activate_in_search($id_user, $id_user_service) {
		$id_user_service = intval($id_user_service);
		$return = array('status' => 0, 'message' => '');

		$this->CI->load->model('services/models/Services_users_model');
		$user_service = $this->CI->Services_users_model->get_user_service_by_id($id_user, $id_user_service);
		if (empty($user_service) || !$user_service["status"]){
			$return['status'] = 0;
			$return['message'] = l('error_service_activating', 'services');
		} else {
			$this->service_set_user_activate_in_search($id_user, $user_service['service']['data_admin']['period']);
			$user_service['count']--;
			if ($user_service['count'] < 1){
				$user_service["status"] = 0;
			}
			$this->CI->Services_users_model->save_service($id_user_service, $user_service);
			$return['status'] = 1;
			$return['message'] = l('success_service_activating', 'services');
		}
		return $return;
	}

	public function service_status_highlight_in_search($user_data){
		$result['status'] = false;
		$result['service'] = array();
		$result['user_service'] = array();
		if(empty($user_data['is_highlight_in_search']) && !empty($user_data['confirm']) && $user_data['approved'] && $user_data['activity'] && $this->pg_module->is_module_installed('services')){
			$this->CI->load->model('Services_model');
			$this->CI->load->model('services/models/Services_users_model');
			$result['service'] = $this->CI->Services_model->format_service($this->CI->Services_model->get_service_by_gid('highlight_in_search'));
			$result['name'] = $result['service']['name'];
			$result['description'] = $result['service']['description'];
			$params = array();
			$params['where']['service_gid'] = 'highlight_in_search';
			$params['where']['id_user'] = $user_data['id'];
			$params['where']['status'] = 1;
			$params['where']['count >'] = 0;
			$result['user_service'] = $this->CI->Services_users_model->get_services_list($params);
			if($result['service']['status'] || $result['user_service']){
				$result['status'] = true;
			}
		}
		return $result;
	}

	public function service_status_up_in_search($user_data){
		$result['status'] = false;
		$result['service'] = array();
		$result['user_service'] = array();
		if(empty($user_data['is_up_in_search']) && !empty($user_data['confirm']) && $user_data['approved'] && $user_data['activity'] && $this->pg_module->is_module_installed('services')){
			$this->CI->load->model('Services_model');
			$this->CI->load->model('services/models/Services_users_model');
			$result['service'] = $this->CI->Services_model->format_service($this->CI->Services_model->get_service_by_gid('up_in_search'));
			$result['name'] = $result['service']['name'];
			$result['description'] = $result['service']['description'];
			$params = array();
			$params['where']['service_gid'] = 'up_in_search';
			$params['where']['id_user'] = $user_data['id'];
			$params['where']['status'] = 1;
			$params['where']['count >'] = 0;
			$result['user_service'] = $this->CI->Services_users_model->get_services_list($params);
			if($result['service']['status'] || $result['user_service']){
				$result['status'] = true;
			}
		}
		return $result;
	}

	public function service_status_hide_on_site($user_data){
		$result['status'] = false;
		$result['service'] = array();
		$result['user_service'] = array();
		if(!empty($user_data['id']) && empty($user_data['is_hide_on_site']) && $this->pg_module->is_module_installed('services')){
			$this->CI->load->model('Services_model');
			$this->CI->load->model('services/models/Services_users_model');
			$result['service'] = $this->CI->Services_model->format_service($this->CI->Services_model->get_service_by_gid('hide_on_site'));
			$result['name'] = $result['service']['name'];
			$result['description'] = $result['service']['description'];
			$params = array();
			$params['where']['service_gid'] = 'hide_on_site';
			$params['where']['id_user'] = $user_data['id'];
			$params['where']['status'] = 1;
			$params['where']['count >'] = 0;
			$result['user_service'] = $this->CI->Services_users_model->get_services_list($params);
			if($result['service']['status'] || $result['user_service']){
				$result['status'] = true;
			}
		}
		return $result;
	}

	public function service_status_users_featured($user_data){
		$result['status'] = false;
		$result['service'] = array();
		$result['user_service'] = array();
		if(/*!$user_data['is_featured'] && */!empty($user_data['confirm']) && $user_data['approved'] && $this->pg_module->is_module_installed('services')){
			$this->CI->load->model('Services_model');
			$this->CI->load->model('services/models/Services_users_model');
			$result['service'] = $this->CI->Services_model->format_service($this->CI->Services_model->get_service_by_gid('users_featured'));
			$result['name'] = $result['service']['name'];
			$result['description'] = $result['service']['description'];
			$params = array();
			$params['where']['service_gid'] = 'users_featured';
			$params['where']['id_user'] = $user_data['id'];
			$params['where']['status'] = 1;
			$params['where']['count >'] = 0;
			$result['user_service'] = $this->CI->Services_users_model->get_services_list($params);
			if($result['service']['status'] || $result['user_service']){
				$result['status'] = true;
			}
		}
		return $result;
	}

	public function services_status($user_data){
		if($this->pg_module->is_module_installed('services')){
			$this->CI->load->model('Services_model');
			$this->CI->Services_model->cache_all_services();
		}
		$result['highlight_in_search'] = $this->service_status_highlight_in_search($user_data);
		$result['up_in_search'] = $this->service_status_up_in_search($user_data);
		$result['hide_on_site'] = $this->service_status_hide_on_site($user_data);
		$result['users_featured'] = $this->service_status_users_featured($user_data);

		return $result;
	}

	public function _dynamic_block_get_registration_login_form($params, $view, $width){
		$data['rand'] = rand(1, 999999);
		$data['params'] = $params;
		$data['view'] = $view;
		$data['width'] = $width;
		$this->CI->template_lite->assign('dynamic_block_registration_login_form_data', $data);
		return $this->CI->template_lite->fetch('dynamic_block_registration_login_form', 'user', 'users');
	}

	private function _dynamic_block_get_users($type, $params, $view, $width){
		$user_type = !empty($params['user_type']) ? intval($params['user_type']) : 0;
		switch($type){
			case 'active':
				$users = $this->get_active_users(intval($params['count']), $user_type);
				break;
			case 'featured':
				$users = $this->get_featured_users(intval($params['count']), $user_type);
				break;
			case 'new':
			default:
				$users = $this->get_new_users(intval($params['count']), $user_type);
				break;
		}

		$this->CI->template_lite->assign('dynamic_block_users', $users);
		$this->CI->template_lite->assign('dynamic_block_users_params', $params);
		$this->CI->template_lite->assign('dynamic_block_users_view', $view);
		$this->CI->template_lite->assign('dynamic_block_users_width', $width);
		$this->CI->template_lite->assign('dynamic_block_users_title', $params['title_'.$this->pg_language->current_lang_id]);
		return $this->CI->template_lite->fetch('dynamic_block_users', 'user', 'users');
	}

	public function _dynamic_block_get_new_users($params, $view, $width){
		return $this->_dynamic_block_get_users('new', $params, $view, $width);
	}

	public function _dynamic_block_get_active_users($params, $view, $width){
		return $this->_dynamic_block_get_users('active', $params, $view, $width);
	}

	public function _dynamic_block_get_featured_users($params, $view, $width){
		return $this->_dynamic_block_get_users('featured', $params, $view, $width);
	}

	public function _dynamic_block_get_auth_links($params, $view, $width){
		$data['rand'] = rand(1, 999999);
		$data['params'] = $params;
		$this->CI->template_lite->assign('dynamic_block_auth_links_data', $data);
		return $this->CI->template_lite->fetch('dynamic_block_auth_links', 'user', 'users');
	}

	public function _dynamic_block_get_lang_select($params, $view, $width){
		$data['rand'] = rand(1, 999999);
		$data['params'] = $params;
		$data['lang_id'] = $this->CI->session->userdata("lang_id");
		if(!$data['lang_id']){
			$data['lang_id'] = $this->CI->pg_language->get_default_lang_id();
		}
		$data['languages'] = array();
		foreach($this->CI->pg_language->languages as $lang){
			if($lang['status']){
				$data['languages'][$lang['id']] = $lang['name'];
			}
		}
		$data['count_active'] = count($data['languages']);

		$this->CI->template_lite->assign('dynamic_block_lang_select_data', $data);
		return $this->CI->template_lite->fetch('dynamic_block_lang_select', 'user', 'users');
	}

	public function comments_count_callback($count, $id){
		$attrs['logo_comments_count'] = $count;
		$this->save_user($id, $attrs);
	}

	public function comments_object_callback($id = 0){
		$return = array();
		return $return;
	}

	public function insertUserLocation($postArr)
	{
	print_r($postArr); exit();
		$lat = '3977.44445';
		$lang = '3777.44445';
		$user_id = 2;
		$datetime = gmdate("Y-m-d H:i:s");
		$sql = "insert into pg_user_locations(user_id,location,country,state,city,lat,long,datetime) values(".$user_id.",'".$postArr['location']."','".$postArr['country']."','".$postArr['state']."','".$postArr['city']."','".$lat."','".$long."','".$datetime."')";
		$query = $this->db->query($sql);
		return $this->db->insert_id();
	}	

	/**
	 * Callback for spam module
	 * @param string $action action name
	 * @param integer $user_ids user identifiers
	 * @return string
	 */
	public function spam_callback($action, $data){
		switch($action){
			case "ban":
				$this->save_user((int)$data, array("banned"=>1));
				return "banned";

				//$this->delete_user((int)$data);
				//return "removed";
			break;
			case "unban":
				$this->save_user((int)$data, array("banned"=>0));
				return "unbanned";
			break;
			case "delete":
				$this->delete_user((int)$data);
				return "removed";
			break;
			case 'get_content':
				if(empty($data)) return array();
				$new_data = array();
				$return = array();
				foreach ($data as $id){
					if (($this->get_users_count(array('where_in'=>array('id'=>$id))))==0){
						$return[$id]["content"]["view"] = $return[$id]["content"]["list"] = "<span class='spam_object_delete'>".l("error_is_deleted_users_object", "spam")."</span>";
						$return[$id]["user_content"] = l("author_unknown", "spam");
					} else {
						$new_data[] = $id;
					}
				}
				$users = $this->get_users_list(null, null, null, null, (array)$new_data);
				foreach($users as $user){
					$return[$user['id']]["content"]["list"] = $return[$user['id']]["content"]["view"] = $user['output_name'].', '.$user['user_type_str'];
					$return[$user['id']]["user_content"] = $user['output_name'];
				}
				return $return;
			break;
			case 'get_subpost':
				return array();
			break;
			case 'get_link':
				if(empty($data)) return array();
				$users = $this->get_users_list(null, null, null, null, (array)$data);
				$return = array();
				foreach($users as $user){
					$return[$user['id']] = site_url().'admin/users/edit/personal/'.$user['id'];
				}
				return $return;
			break;
			case 'get_deletelink':
				return array();
			break;
			case 'get_object':
				if(empty($data)) return array();
				$users = $this->get_users_list_by_key(null, null, null, null, (array)$data);
				return $users;
			break;
		}
	}
}
