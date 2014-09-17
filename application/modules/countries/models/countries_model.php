<?php
/**
* Countries main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('COUNTRIES_TABLE', DB_PREFIX . 'cnt_countries');
define('REGIONS_TABLE', DB_PREFIX . 'cnt_regions');
define('CITIES_TABLE', DB_PREFIX . 'cnt_cities');
define('CACHE_COUNTRIES_TABLE', DB_PREFIX . 'cnt_cache_countries');
define('CACHE_REGIONS_TABLE', DB_PREFIX . 'cnt_cache_regions');
define('GEOBASE_URL', 'http://download.pilotgroup.net/geobase/wget/');

class Countries_model extends Model {

	private $CI;
	private $DB;
	private $db_insert_step = 100;
	private $cache_attrs_country = array('id', 'code', 'name', 'areainsqkm', 'continent', 'currency', 'region_update_date');
	private $cache_attrs_region = array('id', 'country_code', 'code', 'id_region', 'name');
	private $attrs_country = array('id', 'code', 'name', 'areainsqkm', 'continent', 'currency', 'priority');
	private $attrs_region = array('id', 'country_code', 'code', 'name', 'priority');
	private $attrs_city = array('id', 'id_region', 'name', 'latitude', 'longitude', 'country_code', 'region_code');
	private $use_infile_city_install = true;
	private $city_install_step = 100;
	private $temp_server_city_id = 0;

	/**
	 * Constructor
	 *
	 * @return
	 */
	function __construct() {
	parent::Model();
	$this->CI = & get_instance();
	$this->DB = &$this->CI->db;

	$this->use_infile_city_install = $this->CI->pg_module->get_module_config('countries', 'use_infile_city_install');
	}

	/**
	 *
	 *  get country list from server
	 *  put it in base cache
	 *  return countries list
	 *
	 * @return array
	 */
	private function wget_countries() {
		$this->load->library('Snoopy');
		$res = $this->snoopy->fetch(GEOBASE_URL . 'get_countries');

		if (!$res or trim($this->snoopy->headers[0]) != 'HTTP/1.1 200 OK') {
			return false;
		}
		$temp_geo_data = $this->snoopy->results;

		$data = array();
		$temp_geo_array = preg_split('/\n/', $temp_geo_data);
		foreach ($temp_geo_array as $geo) {
			if (!strlen(trim($geo)))
			continue;
			$geo_array = preg_split("/\t/", $geo);
			$data[] = array(
			'code' => $geo_array[0],
			'name' => $geo_array[4],
			'areainsqkm' => $geo_array[6],
			'continent' => $geo_array[8],
			'currency' => $geo_array[10],
			'region_update_date' => '0000-00-00 00:00:00',
			);
		}
		return $data;
	}

	/**
	 *
	 * get region list from server
	 * put it in base cache
	 * return region list
	 *
	 * @return array
	 */
	private function wget_regions($country_code) {
		$this->load->library('Snoopy');
		$res = $this->snoopy->fetch(GEOBASE_URL . 'get_regions/' . $country_code);

		if (!$res or trim($this->snoopy->headers[0]) != 'HTTP/1.1 200 OK') {
			return false;
		}
		$temp_geo_data = $this->snoopy->results;

		$data = array();
		$temp_geo_array = preg_split('/\n/', $temp_geo_data);
		foreach ($temp_geo_array as $geo) {
			if (!strlen(trim($geo)))
			continue;
			$geo_array = preg_split("/\t/", $geo);
			$data[] = array(
			'country_code' => $geo_array[1],
			'code' => $geo_array[2],
			'id_region' => $geo_array[0],
			'name' => $geo_array[3],
			);
		}
		return $data;
	}

	private function wget_cities($country_code, $region_server_id, $region_data) {
		//// get cities from server and return cities list
		//// if returned data && infile == true - save file and return filepath
		//// if returned data && infile == false - return data array
		//// if returned install clear and return

		$this->load->library('Snoopy');
		$res = $this->snoopy->fetch(GEOBASE_URL . 'get_regions_cities/' . $country_code . "/" . $region_server_id . "/" . $this->temp_server_city_id);

		if (!$res or trim($this->snoopy->headers[0]) != 'HTTP/1.1 200 OK') {
			return false;
		}

		$temp_geo_data = $this->snoopy->results;

		if ('installed' === trim($temp_geo_data)) {
			$this->temp_server_city_id = 0;
			return true;
		}

		$data = array();
		$temp_geo_array = preg_split('/\n/', $temp_geo_data);
		foreach ($temp_geo_array as $geo) {
			if (!strlen(trim($geo)))
			continue;
			$geo_array = preg_split("/\t/", $geo);
			$data[] = array(
			'id' => NULL,
			'id_region' => $region_data["id"],
			'name' => $geo_array[2],
			'latitude' => $geo_array[5],
			'longitude' => $geo_array[6],
			'country_code' => $country_code,
			'region_code' => $region_data["code"],
			);
			$this->temp_server_city_id = $geo_array[0];
		}

		$return = array("data" => $data, "file" => '');
		if ($this->use_infile_city_install) {
			$infile = "";
			foreach ($data as $city) {
			$infile .= implode("\t", $city) . "\n";
			}
			$path_to_file = TEMPPATH . 'countries/regions_' . $country_code . '.txt';
			$this->load->helper('file');
			if (!write_file($path_to_file, $infile)) {
			$this->use_infile_city_install = false;
			return $return;
			} else {
			@chmod($path_to_file, 0777);
			$return["file"] = $path_to_file;
			return $return;
			}
		} else {
			return $return;
		}
	}

	/**
	 *
	 *  return country list from cache
	 *  if cache is empty or expiries - wget_countries
	 *  save update date in module settings
	 *
	 * @return array
	 */
	public function get_cache_countries() {
		$expiried_period = $this->CI->pg_module->get_module_config('countries', 'countries_update_period');
		$last_update = $this->CI->pg_module->get_module_config('countries', 'countries_last_update');

		$this->DB->select(implode(", ", $this->cache_attrs_country))->from(CACHE_COUNTRIES_TABLE)->order_by('name ASC');
		$results = $this->DB->get()->result_array();

		if (empty($results) || (!empty($last_update) && $last_update + $expiried_period < time()) || empty($last_update)) {
			$results = $this->wget_countries();
			if (empty($results))
			return array();

			$counter = 0;
			$data_count = count($results);
			$this->DB->query('TRUNCATE TABLE ' . CACHE_COUNTRIES_TABLE . '');

			$start_sql = "INSERT INTO " . CACHE_COUNTRIES_TABLE . " (code, name, areainsqkm, continent, currency, region_update_date) VALUES  ";

			while ($counter < $data_count) {
			unset($strings);
			$temp_geo = array_slice($results, $counter, $this->db_insert_step);
			foreach ($temp_geo as $data) {
				$strings[] = "( '" . $data["code"] . "', '" . addslashes($data["name"]) . "', '" . $data["areainsqkm"] . "', '" . $data["continent"] . "', '" . $data["currency"] . "', '" . $data["region_update_date"] . "')";
			}

			$query = $start_sql . implode(", ", $strings);
			$this->DB->query($query);
			$counter = $counter + $this->db_insert_step;
			}

			$this->CI->pg_module->set_module_config('countries', 'countries_last_update', time());
			return $results;
		} else {
			return $results;
		}
		return array();
	}

	public function get_cache_country_by_code($country_code) {
		$this->DB->select(implode(", ", $this->cache_attrs_country))->from(CACHE_COUNTRIES_TABLE)->where("code", $country_code);
		$data = $this->DB->get()->result_array();
		if (!empty($data)) {
			return $data[0];
		}
		return array();
	}

	/**
	 *
	 *  return country list from cache
	 *  if cache is empty or expiries - wget_regions
	 *  save update date in cache country table
	 *
	 * @return array
	 */
	public function get_cache_regions($country_code, $country_cache_data = array()) {
		$expiried_period = $this->CI->pg_module->get_module_config('countries', 'countries_update_period');

		if (empty($country_cache_data)) {
			$country_cache_data = $this->get_cache_country_by_code($country_code);
		}
		$last_update = (!empty($country_cache_data["region_update_date"])) ? $country_cache_data["region_update_date"] : "0000-00-00 00:00:00";
		$last_update = intval(strtotime($last_update));

		$this->DB->select(implode(", ", $this->cache_attrs_region))->from(CACHE_REGIONS_TABLE)->where('country_code', $country_code)->order_by('code ASC');
		$results = $this->DB->get()->result_array();

		if (empty($results) || (!empty($last_update) && $last_update + $expiried_period < time()) || empty($last_update)) {
			$results = $this->wget_regions($country_code);
			if (empty($results))
			return array();

			$counter = 0;
			$data_count = count($results);
			$this->DB->where('country_code', $country_code);
			$this->DB->delete(CACHE_REGIONS_TABLE);

			$start_sql = "INSERT INTO " . CACHE_REGIONS_TABLE . " (country_code, code, id_region, name) VALUES  ";

			while ($counter < $data_count) {
			unset($strings);
			$temp_geo = array_slice($results, $counter, $this->db_insert_step);
			foreach ($temp_geo as $data) {
				$strings[] = "('" . $data["country_code"] . "', '" . $data["code"] . "', '" . $data["id_region"] . "', '" . addslashes($data["name"]) . "')";
			}

			$query = $start_sql . implode(", ", $strings);
			$this->DB->query($query);
			$counter = $counter + $this->db_insert_step;
			}

			$cdata["region_update_date"] = date('Y-m-d H:i:s');
			$this->DB->where("code", $country_code);
			$this->DB->update(CACHE_COUNTRIES_TABLE, $cdata);
			return $results;
		} else {
			return $results;
		}
		return array();
	}

	public function get_cache_region_by_code($country_code, $region_code) {
		$this->DB->select(implode(", ", $this->cache_attrs_region))->from(CACHE_REGIONS_TABLE)->where("country_code", $country_code)->where("code", $region_code);
		$data = $this->DB->get()->result_array();
		if (!empty($data)) {
			return $data[0];
		}
		return array();
	}

	public function get_countries($order_by = array(), $params = array(), $filter_object_ids = array(), $lang_id = false) {
		/// return installed countries

		$select_attrs = $this->attrs_country;
		if ($lang_id) {
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}

		$this->DB->select(implode(", ", $select_attrs))->from(COUNTRIES_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
			$this->DB->where($field, $value);
			}
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
		if (is_array($order_by) && count($order_by) > 0){
			$fields = array();
			foreach($select_attrs as $attr){
				$fields[] = (strpos($attr, ' ') !== false) ? strstr($attr, ' ', true) : $attr;
			}
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $fields)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}

		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
			$data[$r["code"]] = $r;
			}
			return $data;
		}
		return array();
	}

	public function get_countries_by_code($filter_object_ids = array(), $lang_id = false) {
		/// return installed regions

		$select_attrs = $this->attrs_country;
		if ($lang_id){
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}

		$this->DB->select(implode(", ", $select_attrs))->from(COUNTRIES_TABLE);

		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("code", $filter_object_ids);
		}

		$data = array();
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
				$data[$r["code"]] = $r;
			}
			return $data;
		}
		return array();
	}

	public function get_countries_count($params = array()) {
		$this->DB->select('COUNT(*) AS cnt')->from(COUNTRIES_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
			$this->DB->where($field, $value);
			}
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

		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return intval($results[0]["cnt"]);
		}
		return 0;
	}

	public function get_regions($country_code, $order_by = array(), $params = array(), $filter_object_ids = array(), $lang_id = false) {
		/// return installed regions

		$select_attrs = $this->attrs_region;
		if ($lang_id){
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}

		$this->DB->select(implode(", ", $select_attrs))->from(REGIONS_TABLE)->where("country_code", $country_code);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
				$this->DB->where($field, $value);
			}
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

		if (is_array($order_by) && count($order_by) > 0){
			$fields = array();
			foreach($select_attrs as $attr){
				$fields[] = (strpos($attr, ' ') !== false) ? strstr($attr, ' ', true) : $attr;
			}
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $fields)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}

		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $results;
		}
		return array();
	}

	public function get_regions_by_id($filter_object_ids = array(), $lang_id = false) {
		/// return installed regions

		$select_attrs = $this->attrs_region;
		if ($lang_id){
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}

		$this->DB->select(implode(", ", $select_attrs))->from(REGIONS_TABLE);

		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}

		$data = array();
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
				$data[$r["id"]] = $r;
			}
			return $data;
		}
		return array();
	}

	public function get_regions_by_code($country_code, $order_by = array(), $params = array(), $filter_object_ids = array()) {
		$data = array();
		$regions = $this->get_regions($country_code, $order_by, $params, $filter_object_ids);
		if (!empty($regions) && is_array($regions)) {
			foreach ($regions as $r) {
				if (!empty($r["code"]))
					$data[$r["code"]] = $r;
			}
		}
		return $data;
	}

	public function get_cities($page = null, $items_on_page = null, $order_by = array(), $params = array(), $filter_object_ids = array(), $lang_id = false) {
		/// return installed cities

		$select_attrs = $this->attrs_city;
		if ($lang_id) {
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}

		$this->DB->select(implode(", ", $select_attrs))->from(CITIES_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
			$this->DB->where($field, $value);
			}
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

		if (is_array($order_by) && count($order_by) > 0){
			$fields = array();
			foreach($select_attrs as $attr){
				$fields[] = (strpos($attr, ' ') !== false) ? strstr($attr, ' ', true) : $attr;
			}
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $fields)) {
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
			return $results;
		}
		return array();
	}

	public function get_cities_count($params = array(), $filter_object_ids = array()) {
		/// return installed cities
		$this->DB->select("COUNT(*) AS cnt")->from(CITIES_TABLE);

		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
			$this->DB->where($field, $value);
			}
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

		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return intval($results[0]["cnt"]);
		}
		return 0;
	}

	public function get_cities_by_id($filter_object_ids = array(), $lang_id = false) {
		/// return installed regions

		$select_attrs = $this->attrs_city;
		if ($lang_id){
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}

		$this->DB->select(implode(", ", $select_attrs))->from(CITIES_TABLE);

		if (isset($filter_object_ids) && is_array($filter_object_ids) && count($filter_object_ids)) {
			$this->DB->where_in("id", $filter_object_ids);
		}

		$data = array();
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			foreach ($results as $r) {
				$data[$r["id"]] = $r;
			}
			return $data;
		}
		return array();
	}

	public function get_cities_by_radius($lat, $lon, $radius = 10, $radius_type = "km", $page = null, $items_on_page = null, $params = array(), 	$filter_object_ids = array()) {
		/// return installed cities
		$this->DB->select(implode(", ", $this->attrs_city))->from(CITIES_TABLE);

		$radius = ($radius_type == "mile") ? $radius : $radius * 0.6213712;
		$this->DB->where('(POW((69.1*(lon-"' . $lon . '")*cos(' . $lat . '/57.3)),"2")+POW((69.1*(lat-"' . $lat . '")),"2"))<(' . ($radius * $radius) . ')');


		if (isset($params["where"]) && is_array($params["where"]) && count($params["where"])) {
			foreach ($params["where"] as $field => $value) {
			$this->DB->where($field, $value);
			}
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


		if (!is_null($page)) {
			$page = intval($page) ? intval($page) : 1;
			$this->DB->limit($items_on_page, $items_on_page * ($page - 1));
		}

		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $results;
		}
		return array();
	}

	public function get_country($country_code, $lang_id = false, $languages = array()) {
		/// return installed country

		$select_attrs = $this->attrs_country;
		if ($lang_id) {
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}
		if (is_array($languages) && count($languages) > 0) {
			foreach ($languages as $id => $value)
			$select_attrs[] = 'lang_' . $id . ' as lang_' . $id;
		}

		$this->DB->select(implode(", ", $select_attrs))->from(COUNTRIES_TABLE)->where("code", $country_code);
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $results[0];
		}
		return array();
	}

	public function get_country_by_id($id) {
		/// return installed country
		$this->DB->select(implode(", ", $this->attrs_country))->from(COUNTRIES_TABLE)->where("id", $id);
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $results[0];
		}
		return array();
	}

	public function get_region($id_region, $lang_id = false, $languages = array()) {
		/// return installed region

		$select_attrs = $this->attrs_region;
		if ($lang_id) {
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}
		if (is_array($languages) && count($languages) > 0) {
			foreach ($languages as $id => $value)
			$select_attrs[] = 'lang_' . $id . ' as lang_' . $id;
		}

		$this->DB->select(implode(", ", $select_attrs))->from(REGIONS_TABLE)->where("id", $id_region);
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $results[0];
		}
		return array();
	}

	public function get_region_by_code($region_code, $country_code) {
		/// return installed region
		$this->DB->select(implode(", ", $this->attrs_region))->from(REGIONS_TABLE)->where("country_code", $country_code)->where("code", $region_code);
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $results[0];
		}
		return array();
	}

	public function get_city($id_city, $lang_id = false, $languages = array()) {
		/// return installed city

		$select_attrs = $this->attrs_city;
		if ($lang_id) {
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}
		if (is_array($languages) && count($languages) > 0) {
			foreach ($languages as $id => $value)
			$select_attrs[] = 'lang_' . $id . ' as lang_' . $id;
		}

		$this->DB->select(implode(", ", $select_attrs))->from(CITIES_TABLE)->where("id", $id_city);
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return $results[0];
		}
		return array();
	}

	public function get_locations($loc_name, $order_by = array(), $lang_id = false, $languages = array()) {
		$return = array();
		// Search in countries
		$select_attrs = $this->attrs_country;
		if ($lang_id) {
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}

		$this->DB->select(implode(", ", $select_attrs))->from(COUNTRIES_TABLE);
		$loc_names = explode(' ', $loc_name);
		$where_str = '';
		if (count($loc_names) > 0){
			foreach ($loc_names as $loc_name) {
			$where_str .= strlen($where_str) > 0 ? " OR name like '%" . $loc_name . "%' " : " name like '%" . $loc_name . "%' ";
			if (is_array($languages) && count($languages) > 0)
				foreach ($languages as $lang_id => $lang_value) {
					$where_str .= " OR lang_" . $lang_id . " like '%" . $loc_name . "%' ";
				}
			}
		}

		if ($where_str){
			$this->DB->where($where_str, null, false);
		}

		$this->DB->limit(3);
		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->attrs_country)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}

		$results = $this->DB->get()->result_array();
		$return['countries'] = $results ? $results : array();
		// Search in regions
		$select_attrs = $this->attrs_region;
		if ($lang_id) {
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}

		$this->DB->select(implode(", ", $select_attrs))->from(REGIONS_TABLE);
		if ($where_str){
			$this->DB->where($where_str, null, false);
		}
		$this->DB->limit(3);

		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->attrs_region)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}
		$results = $this->DB->get()->result_array();
		$return['regions'] = $results ? $results : array();
		// Search in cities
		$select_attrs = $this->attrs_city;
		if ($lang_id) {
			$select_attrs[] = 'lang_' . $lang_id . ' as name';
		}
		$this->DB->select(implode(", ", $select_attrs))->from(CITIES_TABLE);
		if ($where_str){
			$this->DB->where($where_str, null, false);
		}
		$this->DB->limit(3);
		if (is_array($order_by) && count($order_by) > 0) {
			foreach ($order_by as $field => $dir) {
				if (in_array($field, $this->attrs_city)) {
					$this->DB->order_by($field . " " . $dir);
				}
			}
		}
		$results = $this->DB->get()->result_array();
		$return['cities'] = $results ? $results : array();

		return $return;
	}

	public function install_cities($country_code, $region_code, $languages = array()) {
		/// set country installed
		/// set region installed
		/// get region data(new id and region code)
		/// while wget_cities !== true||false => wget_cities and install sities (use_infile_city_install)

		$country_data = $this->get_country($country_code);
		if (!$country_data) {
			$country_data = $this->get_cache_country_by_code($country_code);
			if (empty($country_data))
			return false;
			$insert_data = array(
			'code' => $country_code,
			'name' => $country_data["name"],
			'areainsqkm' => $country_data["areainsqkm"],
			'continent' => $country_data["continent"],
			'currency' => $country_data["currency"],
			"priority" => $this->get_country_max_priority() + 1,
			);
			if (is_array($languages) && count($languages) > 0)
			foreach ($languages as $id => $value)
				$insert_data['lang_' . $id] = $country_data["name"];
			$this->save_country($country_code, $insert_data, "add");
		}

		$cache_region_data = $this->get_cache_region_by_code($country_code, $region_code);
		if (!$cache_region_data)
			return false;
		$region_server_id = $cache_region_data["id_region"];

		$region_data = $this->get_region_by_code($region_code, $country_code);
		if (!$region_data) {
			$insert_data = array(
			'country_code' => $country_code,
			'code' => $region_code,
			'name' => $cache_region_data["name"],
			"priority" => $this->get_region_max_priority($country_code) + 1,
			);
			if (is_array($languages) && count($languages) > 0)
			foreach ($languages as $id => $value)
				$insert_data['lang_' . $id] = $cache_region_data["name"];
			$id_region = $this->save_region(null, $insert_data);
			$region_data = $this->get_region($id_region);
		}

		$this->DB->where("id_region", $region_data["id"]);
		$this->DB->delete(CITIES_TABLE);

		$install_data = 'start';
		$max_itaration = 20;
		$itaration_counter = 0;

		if ($this->use_infile_city_install && is_array($languages) && count($languages) > 0) {
			$sql_query = "UPDATE " . CITIES_TABLE . " SET ";
			$sql_set = "";
			$sql_where = "";
			foreach ($languages as $id => $value) {
				$sql_set .= strlen($sql_set) > 0 ? ", lang_" . $id . " = name" : "lang_" . $id . " = name";
				$sql_where .= strlen($sql_where) ? " AND lang_" . $id . " = '' " : " lang_" . $id . " = '' ";
			}
			$languages_query = $sql_query . $sql_set . " WHERE " . $sql_where;

			if (isset($region_data["id"])){
				$languages_query .= " AND id_region = " . $region_data["id"];
			}
		}

		while ($install_data !== true && $install_data !== false) {
			if ($itaration_counter > $max_itaration) break;
			$install_data = $this->wget_cities($country_code, $region_server_id, $region_data);
			$itaration_counter++;
			if ($install_data !== true && $install_data !== false) {
				if ($this->use_infile_city_install) {
					if (file_exists($install_data["file"])){
						$sql_result = $this->DB->simple_query("LOAD DATA INFILE '" . $install_data["file"] . "' INTO TABLE " . CITIES_TABLE . "  FIELDS TERMINATED BY '\t';");
						if ($sql_result === false){
							$this->use_infile_city_install = false;
						}
					}
				}

				if (!$this->use_infile_city_install) {
					$counter = 0;
					$data_count = count($install_data["data"]);
					$start_sql = "INSERT INTO " . CITIES_TABLE . " (id_region, name, latitude, longitude, country_code, region_code";
					if (is_array($languages) && count($languages) > 0){
						foreach ($languages as $id => $value){
							$start_sql .= ", lang_" . $id;
						}
					}
					$start_sql .= ") VALUES ";

					while ($counter < $data_count) {
						unset($strings);
						$temp_geo = array_slice($install_data["data"], $counter, $this->city_install_step);
						foreach ($temp_geo as $incity) {
							$city_name = mysql_escape_string(trim($incity["name"]));
							$string = "('" . $region_data["id"] . "', '" . $city_name . "', '" . $incity["latitude"] . "', '" . $incity["longitude"] . "', '" . $country_code . "', '" . $region_code . "'";
							if (is_array($languages) && count($languages) > 0){
								foreach ($languages as $id => $value){
									$string .= ", '" . $city_name . "'";
								}
							}
							$string .= ")";
							$strings[] = $string;
						}
						$query = $start_sql . implode(", ", $strings);
						$this->DB->query($query);
						$counter = $counter + $this->city_install_step;
					}
				}
			}
		}

		if ($this->use_infile_city_install && !empty($languages_query)){
			$this->CI->db->query($languages_query);
		}
		return true;
	}

	public function save_country($country_code, $data, $type = "add", $langs = array()) {
		if (is_array($langs) && count($langs) > 0)
			foreach ($langs as $id => $value)
			$data['lang_' . $id] = $value;
		if ($type == "add") {
			if ($country_code)
			$data["code"] = $country_code;
			$this->DB->insert(COUNTRIES_TABLE, $data);
		}else {
			$this->DB->where('code', $country_code);
			$this->DB->update(COUNTRIES_TABLE, $data);
		}
		return;
	}

	public function save_region($id_region, $data, $langs = array()) {
		if (is_array($langs) && count($langs) > 0){
			foreach ($langs as $id => $value){
				$data['lang_' . $id] = $value;
			}
		}
		if (empty($id_region)) {
			$this->DB->insert(REGIONS_TABLE, $data);
			$id_region = $this->DB->insert_id();
		} else {
			$this->DB->where('id', $id_region);
			$this->DB->update(REGIONS_TABLE, $data);
		}
		return $id_region;
	}

	public function save_city($id_city, $data, $langs = array()) {
		if (is_array($langs) && count($langs) > 0){
			foreach ($langs as $id => $value){
				$data['lang_' . $id] = $value;
			}
		}
		if (empty($id_city)) {
			$this->DB->insert(CITIES_TABLE, $data);
			$id_city = $this->DB->insert_id();
		} else {
			$this->DB->where('id', $id_city);
			$this->DB->update(CITIES_TABLE, $data);
		}
		return $id_city;
	}

	public function delete_country($country_code) {
		$this->DB->where('code', $country_code);
		$this->DB->delete(COUNTRIES_TABLE);

		$this->DB->where('country_code', $country_code);
		$this->DB->delete(REGIONS_TABLE);

		$this->DB->where('country_code', $country_code);
		$this->DB->delete(CITIES_TABLE);
		return;
	}

	public function delete_region($id_region) {
		$this->DB->where('id', $id_region);
		$this->DB->delete(REGIONS_TABLE);

		$this->DB->where('id_region', $id_region);
		$this->DB->delete(CITIES_TABLE);
		return;
	}

	public function delete_city($id_city) {
		$this->DB->where('id', $id_city);
		$this->DB->delete(CITIES_TABLE);
		return;
	}

	public function validate($type, $id, $data) {
		$return = array("errors" => array(), "data" => array());

		if ($type == "country") {
			if (empty($id) && empty($data["code"])) {
				$return["errors"][] = l('error_code_empty', 'countries');
			} elseif (isset($data["code"])) {
				$return["data"]["code"] = strtoupper(substr(strip_tags($data["code"]), 0, 2));

				if (empty($id) || $id != $return["data"]["code"]) {
					$params["where"]["code"] = $return["data"]["code"];
					$countries = $this->get_countries(array(), $params);
					if (!empty($countries)) {
						$return["errors"][] = l('error_code_already_exists', 'countries');
					}
				}
			}
		}

		if ($type == "region") {

			if (isset($data["country_code"])) {
			$return["data"]["country_code"] = strtoupper(substr(strip_tags($data["country_code"]), 0, 2));
			if (empty($return["data"]["country_code"])) {
				$return["errors"][] = l('error_code_empty', 'countries');
			}
			}

			if (isset($data["code"])) {
			$return["data"]["code"] = strip_tags($data["code"]);
			if (empty($return["data"]["code"])) {
				$return["errors"][] = l('error_region_code_empty', 'countries');
			}
			}
		}

		if ($type == "city") {
			if (isset($data["country_code"])) {
				$return["data"]["country_code"] = strtoupper(substr(strip_tags($data["country_code"]), 0, 2));
				if (empty($return["data"]["country_code"])) {
					$return["errors"][] = l('error_code_empty', 'countries');
				}
			}
			if (isset($data["region_code"])) {
				$return["data"]["region_code"] = strip_tags($data["region_code"]);
				if (empty($return["data"]["region_code"])) {
					$return["errors"][] = l('error_region_code_empty', 'countries');
				}
			}
			if (isset($data["id_region"])) {
				$return["data"]["id_region"] = intval($data["id_region"]);
				if (empty($return["data"]["id_region"])) {
					$return["errors"][] = l('error_region_empty', 'countries');
				}
			}
			if (isset($data["latitude"])) {
				$return["data"]["latitude"] = strval($data["latitude"]);
			}
			if (isset($data["longitude"])) {
				$return["data"]["longitude"] = strval($data["longitude"]);
			}
		}

		if (isset($data["name"])) {
			$return["data"]["name"] = strip_tags($data["name"]);
			if (empty($return["data"]["name"])) {
				$return["errors"][] = l('error_name_empty', 'countries');
			}
		}

		return $return;
	}

	public function get_country_max_priority() {
		$this->DB->select("MAX(priority) as max_priority")->from(COUNTRIES_TABLE);
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return intval($results[0]["max_priority"]);
		}
		return 0;
	}

	public function get_region_max_priority($country_code) {
		$this->DB->select("MAX(priority) as max_priority")->from(REGIONS_TABLE)->where("country_code", $country_code);
		$results = $this->DB->get()->result_array();
		if (!empty($results) && is_array($results)) {
			return intval($results[0]["max_priority"]);
		}
		return 0;
	}

	public function set_country_priority($country_code, $priority) {
		$data["priority"] = intval($priority);
		$this->DB->where("code", $country_code);
		$this->DB->update(COUNTRIES_TABLE, $data);
	}

	public function set_region_priority($id_region, $priority) {
		$data["priority"] = intval($priority);
		$this->DB->where("id", $id_region);
		$this->DB->update(REGIONS_TABLE, $data);
	}

	public function lang_dedicate_module_callback_add($lang_id = false) {
		if ($lang_id) {
			$this->CI->load->dbforge();
			$fields["lang_" . $lang_id] = array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => FALSE,
			);
			$default_lang_id = $this->CI->pg_language->get_default_lang_id();
			// Add contries column
			$table_query = $this->CI->db->get(COUNTRIES_TABLE);
			$exists_fields = $table_query->list_fields();
			$this->CI->dbforge->add_column(COUNTRIES_TABLE, $fields);
			// Set default names
			if (in_array("lang_" . $default_lang_id, $exists_fields)) {
				$this->CI->db->set('lang_' . $lang_id, 'lang_' . $default_lang_id, false);
			} else {
				$this->CI->db->set('lang_' . $lang_id, 'name', false);
			}
			$this->CI->db->update(COUNTRIES_TABLE);
			// Add regions column
			$table_query = $this->CI->db->get(REGIONS_TABLE);
			$exists_fields = $table_query->list_fields();
			$this->CI->dbforge->add_column(REGIONS_TABLE, $fields);
			// Set default name
			if (in_array("lang_" . $default_lang_id, $exists_fields)) {
				$this->CI->db->set('lang_' . $lang_id, 'lang_' . $default_lang_id, false);
			} else {
				$this->CI->db->set('lang_' . $lang_id, 'name', false);
			}
			$this->CI->db->update(REGIONS_TABLE);
			// Add cities column
			$table_query = $this->CI->db->get(CITIES_TABLE);
			$exists_fields = $table_query->list_fields();
			$this->CI->dbforge->add_column(CITIES_TABLE, $fields);
			// Set default names
			if (in_array("lang_" . $default_lang_id, $exists_fields)) {
				$this->CI->db->set('lang_' . $lang_id, 'lang_' . $default_lang_id, false);
			} else {
				$this->CI->db->set('lang_' . $lang_id, 'name', false);
			}
			$this->CI->db->update(CITIES_TABLE);
		}
	}

	public function lang_dedicate_module_callback_delete($lang_id = false) {
		if ($lang_id){
			$field_name = "lang_" . $lang_id;
			$this->CI->load->dbforge();
			// Delete countries column
			$table_query = $this->CI->db->get(COUNTRIES_TABLE);
			if (in_array("lang_" . $lang_id, $table_query->list_fields())){
				$this->CI->dbforge->drop_column(COUNTRIES_TABLE, $field_name);
			}
			// Delete regions column
			$table_query = $this->CI->db->get(REGIONS_TABLE);
			if (in_array("lang_" . $lang_id, $table_query->list_fields())){
				$this->CI->dbforge->drop_column(REGIONS_TABLE, $field_name);
			}
			// Delete cities column
			$table_query = $this->CI->db->get(CITIES_TABLE);
			if (in_array("lang_" . $lang_id, $table_query->list_fields())){
				$this->CI->dbforge->drop_column(CITIES_TABLE, $field_name);
			}
		}
	}

}
