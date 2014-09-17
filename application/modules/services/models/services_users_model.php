<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Users services model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Alexander Batukhtin <abatukhtin@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2012-09-20 09:47:07 +0300 (Пт, 20 сент 2012) $ $Author: abatukhtin $
**/

if (!defined('SERVICES_USERS_TABLE')) define('SERVICES_USERS_TABLE', DB_PREFIX . 'services_users');

class Services_users_model extends Model {

	private $CI;
	private $DB;
	private $services_fields = array(
		'id',
		'id_user',
		'service_gid',
		'template_gid',
		'service',
		'template',
		'payment_data',
		'date_created',
		'date_modified',
		'status',
		'count',
		'id_users_package',
	);

	/**
	 * Constructor
	 *
	 * @return users object
	 */
	function __construct() {
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_count($user_id = 0, $count_name) {
		$count = 0;
		$services = $this->get_services_list(array('id_user' => $user_id, 'status' => '1'));
		foreach ($services as $id => $value) {
			$data = @unserialize($value['service_data']);
			if (isset($data[$count_name]))
				$count = $count + $data[$count_name];
		}
		return $count;
	}

	public function save_service($service_id = null, $attrs = array()) {
		if(!empty($attrs["service"])){
			$attrs["service"] = serialize($attrs["service"]);
		}
		if(!empty($attrs["template"])){
			$attrs["template"] = serialize($attrs["template"]);
		}
		if(isset($attrs["payment_data"]) && is_array($attrs["payment_data"])){
			$attrs["payment_data"] = serialize($attrs["payment_data"]);
		}
		foreach($attrs as $field => $attr) if(!in_array($field, $this->services_fields)){
			unset($attrs[$field]);
		}
		if (is_null($service_id)) {
			$attrs["date_created"] = $attrs["date_modified"] = date("Y-m-d H:i:s");
			$this->DB->insert(SERVICES_USERS_TABLE, $attrs);
			$service_id = $this->DB->insert_id();
		} else {
			$attrs["date_modified"] = date("Y-m-d H:i:s");
			$this->DB->where('id', $service_id);
			$this->DB->update(SERVICES_USERS_TABLE, $attrs);
		}

		return $service_id;
	}

	public function update_service($params, $attrs = array()) {
		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			$this->DB->where($params["where"]);
		}
		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value);
			}
		}

		if(isset($attrs["service_data"]) && is_array($attrs["service_data"])){
			$attrs["service_data"] = serialize($attrs["service_data"]);
		}
		if(!isset($attrs["date_modified"])){
			$attrs["date_modified"] = date("Y-m-d H:i:s");
		}
		$this->DB->update(SERVICES_USERS_TABLE, $attrs);

		return $this->DB->affected_rows();
	}

	public function get_services_list($params = array(), $order_by = null, $filter_object_ids = null, $lang_id = '') {
		$data = array();
		$this->DB->select(implode(", ", $this->services_fields))->from(SERVICES_USERS_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			$this->DB->where($params["where"]);
		}
		if (isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])) {
			foreach ($params["where_in"] as $field => $value) {
				$this->DB->where_in($field, $value);
			}
		}
		if (isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])) {
			foreach ($params["where_sql"] as $value) {
				$this->DB->where($value);
			}
		}
		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}
		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->services_fields)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}

		$results = $this->DB->get()->result_array();

		return $this->format_services($results, $lang_id);
	}

	public function format_services($services, $lang_id = ''){
		$result = array();
		foreach($services as $service){
			$id = $service['id'];

			$result[$id] = $service;
			if(!empty($service['service'])) {
				$result[$id]['service'] = @unserialize($service['service']);
				$result[$id] = $this->add_expire_data($result[$id]);
			}
			if(!empty($service['template'])) {
				$result[$id]['template'] = @unserialize($service['template']);
			}
			if(!empty($service['payment_data'])) {
				$result[$id]['payment_data'] = @unserialize($service['payment_data']);
			}
			$result[$id]['name'] = !(empty($result[$id]['service'])) ? l('service_name_'.$result[$id]['service']['id'], 'services', $lang_id) : $service['service_gid'];
			$result[$id]['description'] = !(empty($result[$id]['service'])) ? l('service_name_'.$result[$id]['service']['id'].'_description', 'services', $lang_id) : '';
			$result[$id]['is_active'] = $service['count'] && $service['status'];
		}
		return $result;
	}

	/**
	 * Add service expire data (date and number of days).
	 * @param type $service
	 * @return type
	 */
	private function add_expire_data($service) {
		if(!empty($service['date_modified']) && !empty($service['service']['data_admin']['period'])) {
			$now = new DateTime('now');
			$modified_date = new DateTime($service['date_modified']);
			$modified_date->add(new DateInterval('P' . $service['service']['data_admin']['period'] . 'D'));
			$days_left = (int)($service['service']['data_admin']['period'] - $now->diff($modified_date, true)->format('%a'));
			if($now > $modified_date){
				$days_left = -$days_left;
			}
			if($days_left <= 0) {
				$service['is_expired'] = true;
			}
			$service['date_expires'] = $modified_date->format('Y-m-d H:i:s');
			$service['days_left'] = $days_left;
		}
		return $service;
	}

	public function get_service_by_id($id){
		$this->DB->select(implode(", ", $this->services_fields))->from(SERVICES_USERS_TABLE)->where('id', $id);
		$results = $this->DB->get()->result_array();
		return array_shift($this->format_services($results));
	}

	public function get_user_service_by_id($id_user, $id){
		$this->DB->select(implode(", ", $this->services_fields))->from(SERVICES_USERS_TABLE)->where('id', $id)->where('id_user', $id_user);
		$results = $this->DB->get()->result_array();
		return array_shift($this->format_services($results));
	}

	public function available_service_block($id_user, $service_gid){
		$this->CI->load->model('Services_model');
		$service = $this->CI->Services_model->format_service($this->CI->Services_model->get_service_by_gid($service_gid));
		$service['is_free'] = ($service && !$service['price'] && $service['template']['price_type'] == 1);
		$service['is_free_status'] = (!empty($service['status']) && !$service['price'] && $service['template']['price_type'] == 1);

		$params = array();
		$params["where"]['id_user'] = $id_user;
		$params["where"]['service_gid'] = $service_gid;
		$params["where"]['status'] = '1';
		$params["where"]['count > '] = '0';

		$data['user_services'] = $this->get_services_list($params);

		if($this->pg_module->is_module_installed('packages')){
			$user_packages_ids = array();
			foreach($data['user_services'] as $user_service){
				if($user_service['id_users_package']){
					$user_packages_ids[$user_service['id_users_package']] = $user_service['id_users_package'];
				}
			}
			if($user_packages_ids){
				$this->CI->load->model('packages/models/Packages_users_model');
				$user_packages = $this->CI->Packages_users_model->get_user_packages_list(null, array(), $user_packages_ids);
			}
			foreach($data['user_services'] as &$user_service){
				if($user_service['id_users_package']){
					$user_service['package_name'] = !empty($user_packages[$user_service['id_users_package']]) ? $user_packages[$user_service['id_users_package']]['package_info']['name'] : '';
					$user_service['package_till_date'] = !empty($user_packages[$user_service['id_users_package']]) ? $user_packages[$user_service['id_users_package']]['till_date'] : '';
				}
			}
		}

		$data["id_user"] = $id_user;

		$data["date_format"] = $this->pg_date->get_format('date_literal', 'st');
		$data["date_time_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->CI->template_lite->assign('block_data', $data);
		$this->CI->template_lite->assign('service', $service);
		return $this->CI->template_lite->fetch('ajax_user_package_for_activate', 'user', 'services');
	}

	public function is_service_access($id_user, $service_gid){
		$this->CI->load->model('Services_model');
		$result['service'] = $this->CI->Services_model->format_service($this->CI->Services_model->get_service_by_gid($service_gid));
		$result['service']['is_free'] = ($result['service'] && !$result['service']['price'] && $result['service']['template']['price_type'] == 1);
		$result['service']['is_free_status'] = (!empty($result['service']['status']) && !$result['service']['price'] && $result['service']['template']['price_type'] == 1);
		$params['where']['id_user'] = $id_user;
		$params['where']['service_gid'] = $service_gid;
		$params['where']['status'] = '1';
		$params['where']['count > '] = '0';
		$result['user_services'] = $this->get_services_list($params);

		$result['service_status'] = !empty($result['service']['status']) ? true : false;
		$result['activate_status'] = ($result['service']['is_free_status'] || $result['user_services']);
		$result['buy_status'] = !empty($result['service']['status']);
		$result['use_status'] = ($result['activate_status'] || $result['buy_status']);
		return $result;
	}
}
