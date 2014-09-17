<?php
/**
* Services main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('SERVICES_TEMPLATES_TABLE', DB_PREFIX.'services_templates');
define('SERVICES_TABLE', DB_PREFIX.'services');
define('SERVICES_LOG_TABLE', DB_PREFIX.'services_log');

class Services_model extends Model
{
	private $CI;
	private $DB;

	/*
	*  data_admin = array("key" => "type") type = text/string/int/price/checkbox
	*  data_user = array("key" => "type") type = text/string/int/price/checkbox/hidden (hidden - user form controller will be wait get or post data)
	*
	*/
	private $template_fields = array(
		'id',
		'gid',
		'callback_module',
		'callback_model',
		'callback_buy_method',
		'callback_activate_method',
		'callback_validate_method',
		'price_type',
		'data_admin',
		'data_user',
		'lds',
		'date_add',
		'moveable',
		'alert_activate',
	);

	private $service_fields = array(
		"id",
		"gid",
		"template_gid",
		"pay_type",
		"status",
		"price",
		"data_admin",
		"lds",
		"date_add"
	);

	private $cache_template_by_id = array();
	private $cache_template_by_gid = array();
	private $cache_service_by_id = array();
	private $cache_service_by_gid = array();
	public $is_services_cache_set = false;
	public $is_templates_cache_set = false;

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_template_by_id($id, $lang_id = ''){
		if(empty($this->cache_template_by_id[$id])){
			$result = $this->DB->select(implode(", ", $this->template_fields))->from(SERVICES_TEMPLATES_TABLE)->where("id", $id)->get()->result_array();
			$return = (!empty($result))?$this->format_template($result[0], $lang_id):array();
			$this->cache_template_by_id[$id] = $this->cache_template_by_gid[$return["gid"]] = $return;
		}
		return $this->cache_template_by_id[$id];
	}

	public function get_template_by_gid($gid, $lang_id = ''){
		if(empty($this->cache_template_by_gid[$gid])){
			$result = $this->DB->select(implode(", ", $this->template_fields))
					->from(SERVICES_TEMPLATES_TABLE)->where("gid", $gid)->get()->result_array();
			$return = (!empty($result))?$this->format_template($result[0], $lang_id):array();
			$this->cache_template_by_gid[$gid] = $this->cache_template_by_id[$return["id"]] = $return;
		}
		return $this->cache_template_by_gid[$gid];
	}

	public function format_template($data, $lang_id = ''){
		if(!empty($data["data_admin"])){
			$temp = unserialize($data["data_admin"]);
			if(!empty($temp)){
				foreach($temp as $param => $type){
					$data["data_admin_array"][$param] = array(
						"gid" => $param,
						"type" => $type,
						"name" => l('admin_param_name_'.$data["id"]."_".$param, 'services', $lang_id),
						"name_lang_gid" => 'admin_param_name_'.$data["id"]."_".$param
					);
				}
			}
		}
		if(!empty($data["data_user"])){
			$temp = unserialize($data["data_user"]);
			if(!empty($temp)){
				foreach($temp as $param=>$type){
					$data["data_user_array"][$param] = array(
						"gid" => $param,
						"type" => $type,
						"name" => l('user_param_name_'.$data["id"]."_".$param, 'services', $lang_id),
						"name_lang_gid" => 'user_param_name_'.$data["id"]."_".$param
					);
				}
			}
		}
		if(!empty($data["lds"])){
			$temp = unserialize($data["lds"]);
			if(!empty($temp)){
				foreach($temp as $id=>$ds){
					$data["lds_array"][] = array(
						"module" => $data['callback_module'],
						"ds" => $ds
					);
				}
			}
		}
		$data["name"] = l('template_name_'.$data["id"], 'services', $lang_id);
		return $data;
	}

	public function get_template_list($params=array(), $filter_object_ids=null, $order_by=null, $lang_id = ''){
		$this->DB->select(implode(", ", $this->template_fields))->from(SERVICES_TEMPLATES_TABLE);

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
				$this->DB->order_by($field." ".$dir);
			}
		}

		$results = $this->DB->get()->result_array();
		$templates = array();
		foreach($results as $r){
			$templates[$r["gid"]] = $r;
			$templates[$r["gid"]]['name'] = l('template_name_'.$r['id'], 'services');
			$this->cache_template_by_id[$r['id']] = $this->cache_template_by_gid[$r['gid']] = $this->format_template($templates[$r["gid"]], $lang_id);
		}
		
		return $templates;
	}

	public function get_template_count($params=array(), $filter_object_ids=null){
		$this->DB->select("COUNT(*) AS cnt")->from(SERVICES_TEMPLATES_TABLE);

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

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return intval($results[0]["cnt"]);
		}
		return 0;
	}

	public function validate_template($id, $data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["gid"])){
			$data["gid"] = strip_tags($data["gid"]);
			$data["gid"] = preg_replace("/[^a-z0-9\-_]+/i", '', $data["gid"]);

			$return["data"]["gid"] = $data["gid"];

			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_template_code_incorrect', 'services');
			}
		}

		if(isset($data["callback_module"])){
			$return["data"]["callback_module"] = $data["callback_module"];
		}

		if(isset($data["callback_model"])){
			$return["data"]["callback_model"] = $data["callback_model"];
		}

		if(isset($data["callback_buy_method"])){
			$return["data"]["callback_buy_method"] = $data["callback_buy_method"];
		}

		if(isset($data["callback_activate_method"])){
			$return["data"]["callback_activate_method"] = $data["callback_activate_method"];
		}

		if(isset($data["callback_validate_method"])){
			$return["data"]["callback_validate_method"] = $data["callback_validate_method"];
		}

		if(isset($data["price_type"])){
			$return["data"]["price_type"] = intval($data["price_type"]);
		}

		if(isset($data["moveable"])){
			$return["data"]["moveable"] = intval($data["moveable"]);
		}

		if(isset($data["data_admin"])){
			$return["data"]["data_admin"] = serialize($data["data_admin"]);
		}

		if(isset($data["data_user"])){
			$return["data"]["data_user"] = serialize($data["data_user"]);
		}
		if(isset($data["lds"])){
			$return["data"]["lds"] = serialize($data["lds"]);
		}

		return $return;
	}

	public function save_template($id, $data, $name = null){
		if (is_null($id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(SERVICES_TEMPLATES_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(SERVICES_TEMPLATES_TABLE, $data);
		}

		if(!empty($name)){
			$languages = $this->CI->pg_language->languages;
			if(!empty($languages)){
				$lang_ids = array_keys($languages);
				$this->CI->pg_language->pages->set_string_langs('services', "template_name_".$id, $name, $lang_ids);
			}
		}
		unset($this->cache_template_by_id[$id]);
		if(!empty($data["gid"])) unset($this->cache_template_by_gid[$data["gid"]]);
		return $id;
	}

	public function delete_template($id){
		$this->DB->where("id", $id);
		$this->DB->delete(SERVICES_TEMPLATES_TABLE);
		$this->CI->pg_language->pages->delete_string("services", "template_name_".$id);
		return;
	}

	public function delete_template_by_gid($gid){
		$template_data = $this->get_template_by_gid($gid);
		$this->delete_template($template_data["id"]);
		return;
	}

	public function get_service_by_id($id){
		if(empty($this->cache_service_by_id[$id])){
			$result = $this->DB->select(implode(", ", $this->service_fields))->from(SERVICES_TABLE)->where("id", $id)->get()->result_array();
			$return = (!empty($result)) ? $result[0] : array();
			if(!empty($return["data_admin"])){
				$return["data_admin_array"] = unserialize($return["data_admin"]);
			}
			if(!empty($return["lds"])){
				$return["lds_array"] = unserialize($return["lds"]);
			}
			$this->cache_service_by_id[$id] = $this->cache_service_by_gid[$return["gid"]] = $return;
		}
		return $this->cache_service_by_id[$id];
	}

	public function get_service_by_gid($gid){
		if(empty($this->cache_service_by_gid[$gid])){
			$result = $this->DB->select(implode(", ", $this->service_fields))->from(SERVICES_TABLE)->where("gid", $gid)->get()->result_array();
			$return = (!empty($result))?$result[0]:array();
			if(!empty($return["data_admin"])){
				$return["data_admin_array"] = unserialize($return["data_admin"]);
			}
			$this->cache_service_by_gid[$gid] = $this->cache_service_by_id[$return["id"]] = $return;
		}
		return $this->cache_service_by_gid[$gid];
	}

	public function get_service_list($params=array(), $filter_object_ids=null, $order_by=null, $lang_id = ''){
		$this->DB->select(implode(", ", $this->service_fields))->from(SERVICES_TABLE);

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
		
		if (isset($params["where_not_in"]) && is_array($params["where_not_in"]) && count($params["where_not_in"])) {
			foreach ($params["where_not_in"] as $field => $value) {
				$this->DB->where_not_in($field, $value);
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
				$this->DB->order_by($field." ".$dir);
			}
		}
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$results = $this->format_services($results, $lang_id);
		}

		foreach($results as $service){
			$this->cache_service_by_id[$service['id']] = $this->cache_service_by_gid[$service['gid']] = $service;
		}
		
		return $results;
	}
	
	public function cache_all_services(){
		$this->is_services_cache_set = true;
		return $this->get_service_list();
	}

	public function cache_all_templates($lang_id = ''){
		$this->is_templates_cache_set = true;
		return $this->get_template_list(array(), null, null, $lang_id);
	}

	public function format_service($data){
		if(!$data) return array();
		$result = $this->format_services(array($data));
		return $result ? array_shift($result) : array();
	}
	
	public function format_services($data, $lang_id = ''){
		if(!$this->is_templates_cache_set){
			$this->cache_all_templates($lang_id);
		}
		foreach($data as $k => $service){
			$data[$k]["template"] = $this->get_template_by_gid($service["template_gid"], $lang_id);
			if(!is_array($data[$k]["data_admin"])){
				$data[$k]["data_admin"] = unserialize($service["data_admin"]);
			}
			if(isset($service["id"])){
				$data[$k]["name"] = l('service_name_'.$service["id"], 'services', $lang_id);
				$data[$k]["description"] = l('service_name_'.$service["id"].'_description', 'services', $lang_id);
				$data[$k]["alert"] = l('service_name_'.$service["id"].'_alert', 'services', $lang_id);
				$data[$k]["name_lang_gid"] = 'service_name_'.$service["id"];
				$data[$k]["description_lang_gid"] = 'service_name_'.$service["id"].'_description';
				$data[$k]["alert_lang_gid"] = 'service_name_'.$service["id"].'_alert';
			}
		}

		return $data;
	}

	public function get_service_count($params=array(), $filter_object_ids=null){
		$this->DB->select("COUNT(*) AS cnt")->from(SERVICES_TABLE);

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
		
		if (isset($params["where_not_in"]) && is_array($params["where_not_in"]) && count($params["where_not_in"])) {
			foreach ($params["where_not_in"] as $field => $value) {
				$this->DB->where_not_in($field, $value);
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

		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return intval($results[0]["cnt"]);
		}
		return 0;
	}

	public function validate_service($id, $data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["gid"])){
			$data["gid"] = strip_tags($data["gid"]);
			$data["gid"] = preg_replace("/[^a-z0-9\-_]+/i", '', $data["gid"]);

			$return["data"]["gid"] = $data["gid"];

			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_service_code_incorrect', 'services');
			}else{
				$param["where"]["gid"] = $return["data"]["gid"];
				if($id) $param["where"]["id <>"] = $id;
				$gid_counts = $this->get_service_count($param);
				if($gid_counts > 0){
					$return["errors"][] = l('error_service_code_exists', 'services');
				}
			}
		}

		if(isset($data["template_gid"])){
			$return["data"]["template_gid"] = $data["template_gid"];
		}

		if(isset($data["pay_type"])){
			$return["data"]["pay_type"] = intval($data["pay_type"]);
		}

		if(isset($data["status"])){
			$return["data"]["status"] = intval($data["status"]);
		}

		if(isset($data["price"])){
			$return["data"]["price"] = floatval($data["price"]);
		}

		if(isset($data["data_admin"]) && !empty($data["data_admin"])){
			$template_data = $this->get_template_by_gid($data["template_gid"]);
			foreach($data["data_admin"] as $key => $value){
				switch($template_data["data_admin_array"][$key]["type"]){
					case "string": $value = trim(strip_tags($value)); break;
					case "int": $value = intval($value); break;
					case "price": $value = sprintf("%01.2f", floatval($value)); break;
					case "text": break;
					case "checkbox": $value = (intval($value)>0)?1:0; break;
				}
				$data["data_admin"][$key] = $value;
			}
			$return["data"]["data_admin"] = serialize($data["data_admin"]);
		}
		if(isset($data["lds"]) && !empty($data["lds"])){
			
			$return["data"]["lds"] = serialize($data["lds"]);
		}

		return $return;
	}

	public function save_service($id, $data, $name = array(), $description = array()){
		if (is_null($id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(SERVICES_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(SERVICES_TABLE, $data);
		}

		$languages = $this->CI->pg_language->languages;
		$lang_ids = !empty($languages) ? array_keys($languages) : array();
		if(!empty($languages)){
			if(!empty($name)){
				$this->CI->pg_language->pages->set_string_langs('services', 'service_name_'.$id, $name, $lang_ids);
			}
			if(!empty($description)){
				$this->CI->pg_language->pages->set_string_langs('services', 'service_name_'.$id.'_description', $description, $lang_ids);
			}
		}

		unset($this->cache_service_by_id[$id]);
		if(!empty($data["gid"])) unset($this->cache_service_by_gid[$data["gid"]]);

		return $id;
	}

	public function delete_service($id){
		$this->DB->where("id", $id);
		$this->DB->delete(SERVICES_TABLE);
		$this->CI->pg_language->pages->delete_string("services", "service_name_{$id}");
		$this->CI->pg_language->pages->delete_string("services", "service_name_{$id}_description");
		return;
	}

	public function delete_service_by_gid($gid){
		$template_data = $this->get_service_by_gid($gid);
		$this->delete_service($template_data["id"]);
		return;
	}

	public function add_service_log($id_user, $id_service, $user_data){
		$data = array(
			"id_user" => $id_user,
			"id_service" => $id_service,
			"user_data" => serialize($user_data),
			"date_add" => date("Y-m-d H:i:s"),
		);
		$this->DB->insert(SERVICES_LOG_TABLE, $data);
	}

	public function validate_service_payment($id_service, $user_data, $price){
		$return = array("errors"=> array(), "data" => array());
		$service_data = $this->get_service_by_id($id_service);
		$template_data = $this->get_template_by_gid($service_data["template_gid"]);

		$return["data"]["price"] = $price = floatval($price);

		if(!empty($template_data["data_user_array"])){
			foreach($template_data["data_user_array"] as $gid => $param){
				$value = isset($user_data[$gid])?$user_data[$gid]:"";
				switch($param["type"]){
					case "string": $value = trim(strip_tags($value)); break;
					case "int": $value = intval($value); break;
					case "price": $value = sprintf("%01.2f", floatval($value)); break;
					case "text": break;
					case "checkbox": $value = (intval($value)>0)?1:0; break;
					case "hidden":
						if(empty($value)){
							$return["errors"][] = l('error_parametr_incorrect', 'services').$param["name"];
						}
					break;
				}
				$return["data"]["data_user"][$gid] = $value;
			}
		}
		return $return;
	}


	public function validate_service_original_model($id_service, $user_data, $id_user, $price){
		$return = array("errors"=> array(), "data" => array());
		$service_data = $this->get_service_by_id($id_service);
		$template_data = $this->get_template_by_gid($service_data["template_gid"]);

		$model_name = ucfirst($template_data["callback_model"]);
		$model_path = strtolower($template_data["callback_module"]."/models/").$model_name;
		$this->CI->load->model($model_path);
		$return = $this->CI->$model_name->$template_data["callback_validate_method"]($id_user, $user_data, $service_data, $price);
		return $return;
	}
	
	public function account_payment($id_service, $id_user, $user_data, $price, $activate_immediately = false){
		if($this->pg_module->is_module_installed('users_payments')) {
			$this->CI->load->model("Users_payments_model");

			$service_data = $this->get_service_by_id($id_service);
			$t = $this->format_services(array($service_data)); $service_data = $t[0];
			$message = l('service_payment', 'services').$service_data["name"];

			$return = $this->CI->Users_payments_model->write_off_user_account($id_user, $price, $message);
			if($return === true){
				//// log info
				$this->add_service_log($id_user, $id_service, $user_data);
				$payment_data = array(
					"id_user" => $id_user,
					"amount" => $price,
					"payment_data" => array(
						"id_service" => $id_service,
						"user_data" => $user_data,
						"activate_immediately" => ($activate_immediately ? 1 : 0),
					),
				);
				$this->payment_service_status($payment_data, 1);
				return true;
			}
		}
		return false;
	}

	public function system_payment($system_gid, $id_user, $id_service, $user_data, $price, $activate_immediately = false){
		//// log info
		$this->add_service_log($id_user, $id_service, $user_data);

		$service_data = $this->get_service_by_id($id_service);
		$t = $this->format_services(array($service_data)); $service_data = $t[0];

		$this->CI->load->model("payments/models/Payment_currency_model");
		$currency_gid = $this->CI->Payment_currency_model->default_currency["gid"];
		$payment_data["name"] = l('service_payment', 'services').$service_data["name"];
		$payment_data["id_service"] = $id_service;
		$payment_data["user_data"] = $user_data;
		$payment_data["activate_immediately"] = $activate_immediately ? 1 : 0;

		$this->CI->load->helper('payments');
		send_payment('services', $id_user, $price, $currency_gid, $system_gid, $payment_data, true);
	}

	///// callback method for payment module
	public function payment_service_status($payment_data, $payment_status){
		if($payment_status == 1){
			$user_id = $payment_data['id_user'];
			$service_id = $payment_data['payment_data']['id_service'];
			$price = $payment_data['amount'];
			$count = $payment_data['payment_data']['count'] ? $payment_data['payment_data']['count'] : 1;
			$users_package_id = intval($payment_data['payment_data']['id_users_package']);
			$activate_immediately = !empty($payment_data['payment_data']['activate_immediately']);

			$service = $this->format_service($this->get_service_by_id($service_id));
			$template = $this->get_template_by_gid($service['template_gid']);
			
			$model_name = ucfirst($template['callback_model']);
			$model_path = strtolower($template['callback_module'].'/models/').$model_name;
			$this->CI->load->model($model_path);
			$id_user_service = $this->CI->$model_name->$template['callback_buy_method']($user_id, $price, $service, $template, $payment_data['payment_data'], $users_package_id, $count);
			if($activate_immediately && $id_user_service && method_exists($this->CI->$model_name, $template['callback_activate_method'])){
				$this->CI->$model_name->$template['callback_activate_method']($user_id, $id_user_service);
			}
		}
		return;
	}

	public function is_service_active($gid = '') {
		$s = $this->get_service_by_gid($gid);
		return intval($s['status']);
	}

	/**
	 * Returns langs data
	 *
	 * @param array $items
	 * @param array $langs_ids
	 * @return array
	 */
	public function export_langs($items, $langs_ids = null) {
		foreach($items as $type => $gids) {
			switch($type){
				case 'param':
				case 'user_param':{
					$method = 'get_template_by_gid';
					foreach($gids as $template => $param_gids) {
						$element = $this->$method($template);
						$prefix = $type == 'param' ? 'admin_' : 'user_';
						if (is_array($param_gids)){
							foreach ($param_gids as $k => $v) {
								$services[$template."_".$v] = $prefix.'param_name_'.$element['id'].'_'.$v;
							}
						} else {
							$services[$template."_".$param_gids] = $prefix.'param_name_'.$element['id'].'_'.$param_gids;
						}
					}
					break;
				}
				case 'service_description': break;
				default:{
					$method = 'get_' . $type . '_by_gid';
					foreach($gids as $gid) {
						$element = null;
						if(method_exists($this, $method)){
							$element = $this->$method($gid);
						}
						if($element){
							$services[$gid] = $type.'_name_'.$element['id'];
							if(!empty($langs_data[$gid.'_description'])){
								$services[$gid.'_description'] = $type.'_name_'.$element['id'].'_description';
							}
							if(!empty($langs_data[$gid.'_alert'])){
								$services[$gid.'_alert'] = $type.'_name_'.$element['id'].'_alert';
							}
						}
					}
				}
			}
		}
		$langs_db = $this->CI->pg_language->export_langs('services', $services, $langs_ids);

		$lang_codes = array_keys($langs_db);
		foreach($langs_ids as $lang_code) {
			//$lang_data[$lang_code] = array_combine(array_keys($services), $langs_db[$lang_code]);
			foreach ($services as $key => $value) {
				$lang_data[$key][$lang_code] = $langs_db[$value][$lang_code];
			}
		}
		return $lang_data;
	}

	/**
	 * Updates langs data
	 *
	 * @param array $services
	 * @param array $langs_data
	 * @return boolean
	 */
	public function update_langs($services, $langs_data) {
		foreach($services as $type => $gids) {
			switch($type){
				case 'param':
				case 'user_param':{
					$method = 'get_template_by_gid';
					foreach($gids as $template => $param_gids) {
						$element = $this->$method($template);
						$prefix = $type == 'param' ? 'admin_' : 'user_';
						if (is_array($param_gids)){
							foreach ($param_gids as $k => $v) {
								$lang_data = $langs_data[$template."_".$v];
								$this->CI->pg_language->pages->set_string_langs('services', $prefix.'param_name_'.$element['id'].'_'.$v,  $lang_data, array_keys($lang_data));
							}
						} else {
							$lang_data = $langs_data[$template."_".$param_gids];
							$this->CI->pg_language->pages->set_string_langs('services', $prefix.'param_name_'.$element['id'].'_'.$param_gids, $lang_data, array_keys($lang_data));
						}
					}
					break;
				}
				case 'service_description': break;
				default:{
					$method = 'get_' . $type . '_by_gid';
					foreach($gids as $gid) {
						$element = null;
						if(method_exists($this, $method)){
							$element = $this->$method($gid);
						}
						if($element){
							$lang_data = $langs_data[$gid];
							$this->CI->pg_language->pages->set_string_langs('services', $type.'_name_'.$element['id'], $lang_data, array_keys($lang_data));
							if(!empty($langs_data[$gid.'_description'])){
								$lang_data = $langs_data[$gid.'_description'];
								$this->CI->pg_language->pages->set_string_langs('services', $type.'_name_'.$element['id'].'_description', $lang_data, array_keys($lang_data));
							}
							if(!empty($langs_data[$gid.'_alert'])){
								$lang_data = $langs_data[$gid.'_alert'];
								$this->CI->pg_language->pages->set_string_langs('services', $type.'_name_'.$element['id'].'_alert', $lang_data, array_keys($lang_data));
							}
						}
					}
				}
			}
		}
		return true;
	}
	
	// seo
	function get_seo_settings($method = '', $lang_id = '') {
		if (!empty($method)) {
			return $this->_get_seo_settings($method, $lang_id);
		} else {
			$actions = array('services', 'my_services');
			$return = array();
			foreach ($actions as $action) {
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	function _get_seo_settings($method, $lang_id = '') {
		if ($method == "services") {
			return array(
				"templates" => array('nickname', 'fname', 'sname', 'email'),
				"url_vars" => array()
			);
		}
	}

	/**
	 * Return urls for site map
	 */
	function get_sitemap_xml_urls() {
		$this->CI->load->helper('seo');
		$return = array();
		return $return;
	}

	/**
	 * Return urls for site map
	 */
	function get_sitemap_urls() {
		$this->CI->load->helper("seo");
		$auth = $this->CI->session->userdata("auth_type");
		$user_type = $this->CI->session->userdata("user_type_str");
		$block = array();
		return $block;
	}
}
