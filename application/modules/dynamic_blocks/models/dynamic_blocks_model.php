<?php
/**
* Dynamic blocks main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('DYNBLOCKS_AREAS_TABLE', DB_PREFIX.'dynblocks_areas');
define('DYNBLOCKS_AREA_BLOCK_TABLE', DB_PREFIX.'dynblocks_area_block');
define('DYNBLOCKS_BLOCKS_TABLE', DB_PREFIX.'dynblocks_blocks');

class Dynamic_blocks_model extends Model
{
	private $CI;
	private $DB;

	private $block_attrs = array(
		'id', 
		'gid', 
		'module', 
		'model', 
		'method', 
		'min_width', 
		'params', 
		'views', 
		'date_add', 
		'date_modified'
	);
	
	/**
	 * Area block fields
	 * @var array
	 */
	private $fields_area_block = array(
		"id",
		"id_area",
		"id_block",
		"params",
		"view_str",
		"width",
		"sorter",
		"cache_time",
	);
	
	/**
	 * Area fields
	 * @param array
	 */
	private $_fields_area = array(
		"id", 
		"name", 
		"gid", 
		"date_add",
	);

	private $cache_dir = '';
	
	/**
	 * Module GUID
	 * @param string
	 */
	public $module_gid = "dynamic_blocks";
	
	/**
	 * Block types GUID
	 * @param string
	 */
	public $block_types_gid = "block_types";
	/**
	 * Constructor
	 *
	 * @return
	 */
	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
		$this->cache_dir = TEMPPATH . "dynamic_blocks/";
	}


	public function get_area_by_id($id){
		$data = array();
		$this->DB->select(implode(",", $this->_fields_area));
		$this->DB->from(DYNBLOCKS_AREAS_TABLE);
		$this->DB->where("id", $id);
		$result = $this->DB->get()->result_array();
		if(!empty($result)){
			$data = array_shift($this->format_area(array($result[0])));
		}
		return $data;
	}

	public function get_area_by_gid($gid){
		$data = array();
		$this->DB->select(implode(",", $this->_fields_area));
		$this->DB->from(DYNBLOCKS_AREAS_TABLE);
		$this->DB->where("gid", $gid);
		$result = $this->DB->get()->result_array();
		if(!empty($result)){
			$data = array_shift($this->format_area(array($result[0])));
		}
		return $data;
	}

	public function save_area($id, $data){
		if (empty($id)){
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->insert(DYNBLOCKS_AREAS_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(DYNBLOCKS_AREAS_TABLE, $data);
		}
		return $id;
	}

	public function validate_area($id, $data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["name"])){
			$return["data"]["name"] = strip_tags($data["name"]);
			if(empty($return["data"]["name"])){
				$return["errors"][] = l('error_name_mandatory_field', 'dynamic_blocks');
			}
		}

		if(isset($data["gid"])){
			$return["data"]["gid"] = trim(strip_tags($data["gid"]));
			$return["data"]["gid"] = preg_replace('/[^a-z\-_0-9]+/i', '', $return["data"]["gid"]);
			$return["data"]["gid"] = preg_replace('/[\s\n\t\r]+/', '-', $return["data"]["gid"]);
			$return["data"]["gid"] = preg_replace('/\-{2,}/', '-', $return["data"]["gid"]);

			if(empty($return["data"]["gid"])){
				$return["errors"][] = l('error_gid_mandatory_field', 'dynamic_blocks');
			}else{
				$this->DB->select('COUNT(*) AS cnt')->from(DYNBLOCKS_AREAS_TABLE)->where("gid", $return["data"]["gid"]);
				if(!empty($id)){
					$this->DB->where("id <>", $id);
				}
				$result = $this->DB->get()->result_array();
				if(!empty($result) && $result[0]["cnt"] > 0){
					$return["errors"][] = l('error_area_already_exists', 'dynamic_blocks');
				}
			}
		}
		
		return $return;
	}
	
	/**
	 * Format area data
	 * @param array $data area $data
	 */
	public function format_area($data){
		return $data;
	}

	public function delete_area($id){
		$this->DB->where("id", $id);
		$this->DB->delete(DYNBLOCKS_AREAS_TABLE);

		$this->DB->where("id_area", $id);
		$this->DB->delete(DYNBLOCKS_AREA_BLOCK_TABLE);
		return;
	}

	public function delete_area_by_gid($gid){
		$area_data = $this->get_area_by_gid($gid);
		if(empty($area_data)) return false;

		$this->delete_area($area_data["id"]);
	}

	public function get_areas_list($page=null, $items_on_page=null, $order_by=null, $params=array(), $filter_object_ids=null){
		$this->DB->select(implode(',', $this->_fields_area))->from(DYNBLOCKS_AREAS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
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

		$data = array();
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$data=$results;
		}
		return $data;
	}

	public function get_areas_count($params=array(), $filter_object_ids=null){
		$this->DB->select('COUNT(*) AS cnt')->from(DYNBLOCKS_AREAS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
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

	public function get_area_block_by_id($id){
		$data = array();
		$this->DB->select(implode(",", $this->fields_area_block))->from(DYNBLOCKS_AREA_BLOCK_TABLE)->where("id", $id);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$results = $this->format_area_block(array($results[0]));
			$data = $results[0];
		}
		return $data;
	}

	public function get_area_blocks($id_area){
		$data = array();
		$this->DB->select(implode(",", $this->fields_area_block))->from(DYNBLOCKS_AREA_BLOCK_TABLE)->where("id_area", $id_area)->order_by("sorter ASC");
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$data = $this->format_area_block($results);
		}
		return $data;
	}

	public function get_area_blocks_count($id_area){
		$this->DB->select('COUNT(*) AS cnt')->from(DYNBLOCKS_AREA_BLOCK_TABLE)->where("id_area", $id_area);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return intval($results[0]["cnt"]);
		}
		return 0;
	}

	public function save_area_block($id, $data){
		if (empty($id)){
			if(!isset($data["width"]) || !$data["width"]) $data["width"] = 100;
			$this->DB->insert(DYNBLOCKS_AREA_BLOCK_TABLE, $data);
			$id = $this->DB->insert_id();
			if(!isset($data['sorter'])) $this->refresh_area_block_sorter($data["id_area"]);
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(DYNBLOCKS_AREA_BLOCK_TABLE, $data);
		}
		return $id;
	}

	public function save_area_block_sorter($id, $sorter, $width=0){
		$data["sorter"] = $sorter;
		if($width) $data["width"] = $width;
		$this->DB->where('id', $id);
		$this->DB->update(DYNBLOCKS_AREA_BLOCK_TABLE, $data);
		return;
	}

	public function delete_area_block($id){
		$area_block_data = $this->get_area_block_by_id($id);

		$this->DB->where('id', $id);
		$this->DB->delete(DYNBLOCKS_AREA_BLOCK_TABLE);
		
		$this->CI->load->model('Start_model');
		$this->CI->Start_model->clear_wysiwyg_uploads_dir('dynamic_blocks' ,$id);

		$this->refresh_area_block_sorter($area_block_data["id_area"]);
	}
	
	/**
	 * Validate data of area block
	 * @param array $data 
	 * @param boolean $required
	 * @return array
	 */
	public function validate_area_block($data, $required=false){
		$return = array("errors"=>array(), "data"=>array());
		
		foreach($this->fields_area_block as $field){
			if(isset($data[$field])){
				$return["data"][$field] = $data[$field];
			}
		}
		
		return $return;
	}
	
	public function format_area_block($data){
		foreach($data as $i=>$block){
			$data[$i]["params"] = $block["params"] ? unserialize($block["params"]) : array();
			$data[$i]["width"] = $block["width"] ? $block["width"] : 100;
		}
		return $data;
	}

	public function refresh_area_block_sorter($id_area){
		$blocks = $this->get_area_blocks($id_area);
		if(empty($blocks)) return false;

		$sorter = 0;
		$prev_block = null;
		$row_width = 0;
		foreach($blocks as $block){
			$row_width += $block['width'];
			if($row_width > 100){
				$prev_block["width"] += 100 - ($row_width - $block["width"]);
				$this->save_area_block_sorter($prev_block["id"], $sorter, $prev_block['width']);
				$row_width = $block["width"];
			}elseif($row_width == 100){
				$row_width = 0;
			}
			$prev_block = $block;				
			$this->save_area_block_sorter($block["id"], ++$sorter, $block['width']);	
		}
		
		if($row_width){
			$prev_block["width"] += 100 - $row_width;
			$this->save_area_block_sorter($prev_block["id"], $sorter, $prev_block['width']);
		}
	}

	public function get_block_by_id($id){
		$data = array();
		$this->DB->select(implode(', ', $this->block_attrs))->from(DYNBLOCKS_BLOCKS_TABLE);
		if(is_array($id)){
			$this->DB->where_in("id", $id);
		}else{
			$this->DB->where("id", $id);
		}
		$result = $this->DB->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
		}
		return $data;
	}

	public function get_block_by_gid($gid){
		$data = array();
		$result = $this->DB->select(implode(', ', $this->block_attrs))->from(DYNBLOCKS_BLOCKS_TABLE)->where("gid", $gid)->get()->result_array();
		if(!empty($result)){
			$data = $result[0];
		}
		return $data;
	}

	public function save_block($id, $data, $langs=null){
		if (is_null($id)){
			$data["date_add"] = $data["date_modified"] = date("Y-m-d H:i:s");
			$this->DB->insert(DYNBLOCKS_BLOCKS_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$data["date_add"] = date("Y-m-d H:i:s");
			$this->DB->where('id', $id);
			$this->DB->update(DYNBLOCKS_BLOCKS_TABLE, $data);
		}

		if(!empty($langs)){
			$languages = $this->CI->pg_language->languages;
			if(!empty($languages)){
				foreach($languages as $language){
					$lang_ids[] = $language["id"];
				}
				foreach($langs as $gid => $strings_data){
					foreach($strings_data as $string => $lang_data){
						$this->CI->pg_language->pages->set_string_langs($gid, $string, $lang_data, $lang_ids);
					}
				}
			}
		}
		return $id;
	}

	public function validate_block($id, $data, $langs=null){
		$return = array("errors"=> array(), "data" => array(), 'temp' => array(), 'langs' => array());

		if(isset($data["gid"])){
			$return["data"]["gid"] = strtolower(preg_replace('/[\s\n\r_]{1,}/', '_', trim(preg_replace('/[^a-z_0-9]/i', '_', strip_tags($data["gid"])))));
			if(empty($return["data"]["gid"])){
				$return["errors"][] = l('error_gid_empty', 'dynamic_blocks');
			}
		}

		if(isset($data["module"])){
			$return["data"]["module"] = strtolower(preg_replace("/[\s\n\r]{1,}/", '_', $data["module"]));
		}
		if(isset($data["model"])){
			$return["data"]["model"] = ucfirst(preg_replace("/[\s\n\r]{1,}/", '_', $data["model"]));
		}
		if(isset($data["method"])){
			$return["data"]["method"] = preg_replace("/[\s\n\r]{1,}/", '_', $data["method"]);
		}

		if(empty($id) && ( empty($return["data"]["module"]) || empty($return["data"]["model"]) || empty($return["data"]["method"]) ) ){
			$return["errors"][] = l('error_function_empty', 'dynamic_blocks');
		}

		if(!(empty($return["data"]["module"]) || empty($return["data"]["model"]) || empty($return["data"]["method"]) )){
			$result = $this->is_method_callable($return["data"]["module"], $return["data"]["model"], $return["data"]["method"]);
			if(!$result){
				$return["errors"][] = l('error_function_invalid', 'dynamic_blocks');
			}
		}
		
		if(isset($data["min_width"]) && !empty($data["min_width"])){
			$return["data"]["min_width"] = $data['min_width'] ? $data['min_width'] : '30';
		}

		if(isset($data["params"]) && !empty($data["params"])){
			foreach($data["params"] as $key => $param){
				$gid = strtolower(preg_replace('/[\s\n\r_]{1,}/', '_', trim(preg_replace('/[^a-z_0-9]/i', '_', strip_tags($param["gid"])))));
				if(!empty($gid)){
					$return["data"]["params"][$gid]["type"] = $param["type"] ? $param['type'] : 'text';
					$return["data"]["params"][$gid]["default"] = trim($param["default"]);
					$return["temp"]["params"][$key] = $gid;
				}
			}
			$return["data"]["params"] = serialize($return["data"]["params"]);
		}

		if(isset($data["views"]) && !empty($data["views"])){
			foreach($data["views"] as $key => $view){
				$gid = strtolower(preg_replace('/[\s\n\r_]{1,}/', '_', trim(preg_replace('/[^a-z_0-9]/i', '_', strip_tags($view["gid"])))));
				if(!empty($gid)){
					$return["data"]["views"][] = $gid;
					$return["temp"]["views"][$key] = $gid;
				}
			}
			$return["data"]["views"] = serialize($return["data"]["views"]);
		}

		if(!empty($langs)){
			$languages = $this->CI->pg_language->languages;
			$current_lang_id = $this->CI->pg_language->current_lang_id;
			$lang_gid = $return["data"]["module"]."_dynamic_blocks";

			if(isset($langs["name"])){
				$lang_i = "block_".$return["data"]["gid"];
				foreach($languages as $language){
					$str = (!empty($langs["name"][$language["id"]]))?$langs["name"][$language["id"]]:$langs["name"][$current_lang_id];
					$str = trim(strip_tags($str));

					$return["langs"][$lang_gid][$lang_i][$language["id"]] = $str;
				}
			}

			if(isset($langs["params"])){
				foreach($langs["params"] AS $key => $param){
					if(empty($return["temp"]["params"][$key])) continue;
					$lang_i = 'param_'.$return["data"]["gid"].'_'.$return["temp"]["params"][$key];
					foreach($languages as $language){
						$str = (!empty($langs["params"][$key][$language["id"]]))?$langs["params"][$key][$language["id"]]:$langs["params"][$key][$current_lang_id];
						$str = trim(strip_tags($str));

						$return["langs"][$lang_gid][$lang_i][$language["id"]] = $str;
					}
				}
			}

			if(isset($langs["views"])){
				foreach($langs["views"] AS $key => $param){
					if(empty($return["temp"]["views"][$key])) continue;
					$lang_i = 'view_'.$return["data"]["gid"].'_'.$return["temp"]["views"][$key];
					foreach($languages as $language){
						$str = (!empty($langs["views"][$key][$language["id"]]))?$langs["views"][$key][$language["id"]]:$langs["views"][$key][$current_lang_id];
						$str = trim(strip_tags($str));

						$return["langs"][$lang_gid][$lang_i][$language["id"]] = $str;
					}
				}
			}
		}
		return $return;
	}

	public function format_block($data, $get_langs = false){
		$data["params"] = $data["params"] ? (array)unserialize($data["params"]) : array();
		$data["views"] = $data["views"] ? (array)unserialize($data["views"]) : array();

		$data["lang_gid"] = $data["module"]."_dynamic_blocks";
		$data["name_i"] = "block_".$data["gid"];
		$data["name"] = ($get_langs)?(l($data["name_i"], $data["lang_gid"])):"";

		if(!empty($data["params"])){
			foreach($data["params"] as $param_gid => $param_data){
				$param_i = 'param_'.$data["gid"].'_'.$param_gid;
				$data["params_data"][] = array(
					"gid" => $param_gid,
					"type" => $param_data["type"],
					"default" => $param_data["default"],
					"i" => $param_i,
					"name" => ($get_langs)?(l($param_i, $data["lang_gid"])):""
				);
			}
		}

		if(!empty($data["views"])){
			foreach($data["views"] as $view_gid){
				$param_i = 'view_'.$data["gid"].'_'.$view_gid;
				$data["views_data"][] = array(
					"gid" => $view_gid,
					"i" => $param_i,
					"name" => ($get_langs)?(l($param_i, $data["lang_gid"])):""
				);
			}
		}

		return $data;
	}

	public function delete_block($id){
		$block_data = $this->get_block_by_id($id);
		if(empty($block_data)) return false;

		$block_data = $this->format_block($block_data);

		$this->DB->where("id", $id);
		$this->DB->delete(DYNBLOCKS_BLOCKS_TABLE);

		$this->DB->where("id_block", $id);
		$this->DB->delete(DYNBLOCKS_AREA_BLOCK_TABLE);

		$this->CI->pg_language->pages->delete_string($block_data['lang_gid'], $block_data['name_i']);
		if(!empty($block_data["params_data"])){
			foreach($block_data["params_data"] as $param){
				$this->CI->pg_language->pages->delete_string($block_data['lang_gid'], $param['i']);
			}
		}
		if(!empty($block_data["views_data"])){
			foreach($block_data["views_data"] as $view){
				$this->CI->pg_language->pages->delete_string($block_data['lang_gid'], $view['i']);
			}
		}
		return;
	}

	public function delete_block_by_gid($gid){
		$block_data = $this->get_block_by_gid($gid);
		return $this->delete_block($block_data["id"]);
	}

	public function get_blocks_list($page=null, $items_on_page=null, $order_by=null, $params=array(), $filter_object_ids=null){
		$this->DB->select(implode(', ', $this->block_attrs))->from(DYNBLOCKS_BLOCKS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
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

		$data = array();
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $result){
				$data[] = $this->format_block($result);
			}
		}
		return $data;
	}

	public function get_blocks_list_by_id($page=null, $items_on_page=null, $order_by=null, $params=array(), $filter_object_ids=null){
		$return_blocks = array();
		$blocks = $this->get_blocks_list($page, $items_on_page, $order_by, $params, $filter_object_ids);
		if(!empty($blocks)){
			foreach($blocks as $block){
				$return_blocks[$block["id"]] = $block;
			}
		}
		return $return_blocks;
	}

	public function get_blocks_count($params=array(), $filter_object_ids=null){
		$this->DB->select("COUNT(*) AS cnt")->from(DYNBLOCKS_BLOCKS_TABLE);

		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->DB->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->DB->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->DB->where($value);
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

	private function is_method_callable($module, $model, $method){
		$result = false;

		$model_url = $module."/models/".$model;
		$model_path = MODULEPATH.strtolower($model_url).EXT;

		if(file_exists($model_path)){
			$this->CI->load->model($model_url);
			$object = array($this->CI->$model, $method);
			$result = is_callable($object);
		}

		return $result;
	}

	private function cache_get_file_name($id_area_block, $lang_id = null){
		if(!$lang_id) {
			$lang_id = $this->CI->pg_language->current_lang_id;
		}
		return $this->cache_dir."block_content_".$id_area_block."_".$lang_id.".txt";
	}

	private function cache_get_content($file_path, $cache_time=0){
		if(!$cache_time) return false;

		if(!file_exists($file_path)) return false;
		clearstatcache();
		$stat = stat($file_path);
		$mod_time = $stat["mtime"];

		if(time() < $mod_time + $cache_time){
			return file_get_contents($file_path);
		}

		return false;
	}

	private function cache_set_content($file_path, $content){
		$h = fopen($file_path, 'w');
		if($h){
			fwrite($h, $content);
			fclose($h);
		}
		return;
	}

	public function cache_clear(){
		$languages = $this->CI->pg_language->languages;
		$blocks = $this->get_blocks_list();
		foreach($blocks as $block) {
			foreach($languages as $lang) {
				$filename = $this->cache_get_file_name($block['id'], $lang['id']);
				if(file_exists($filename)) {
					unlink($filename);
				}
			}
		}
	}

	public function html_area_blocks($id_area){
		$area = $this->get_area_by_id($id_area);

		$area_blocks = $this->get_area_blocks($id_area);
		if(empty($area_blocks)) return "";

		$html = "";

		foreach($area_blocks as $block){
			$block_ids[] = $block["id_block"];
		}

		$blocks = $this->get_blocks_list_by_id(null, null, null, array(), $block_ids);

		$layout = array();
		$row = array();
		$row_width = 0;
			
		foreach($area_blocks as $index=>$block){
			$row_width += $block["width"];
			$row[] = $block;
			if($row_width < 100) continue;
		
			if($row_width > 100){
				array_pop($row);
				$count = count($row);
				$row[$count-1]["width"] += 100 - ($row_width - $block["width"]);
				$layout[] = $row;
				$row = array($block);
				$row_width = $block["width"];
			}else{
				$layout[] = $row;
				$row = array();
				$row_width = 0;
			}
		}
		
		if($row_width){
			$count = count($row);
			$row[$count-1]["width"] += 100 - $row_width;
			$layout[] = $row;
		}

		foreach($layout as $row=>$layout_blocks){
			$block_html = '';
			$first = false;
			foreach($layout_blocks as $key=>$area_block){
				$temp_content = false;
				if($area_block["cache_time"]){
					$file_name = $this->cache_get_file_name($area_block["id"]);
					$temp_content = $this->cache_get_content($file_name, $area_block["cache_time"]);
				}

				if(!$temp_content){
					$module = $blocks[$area_block["id_block"]]['module'];
					$model = $blocks[$area_block["id_block"]]['model'];
					$method = $blocks[$area_block["id_block"]]['method'];
					$model_url = $module."/models/".$model;
					$this->CI->load->model($model_url);
					$temp_content = $this->CI->$model->$method($area_block["params"], $area_block["view_str"], $area_block['width']);

					if($area_block["cache_time"]){
						$this->cache_set_content($file_name, $temp_content);
					}
				}
				//$t = strip_tags(trim($temp_content));
				//if (empty($t)) continue;

				$class = "dynamic_block_content";
				
				$w = ($area_block["width"] != 40 ? $area_block["width"] : 30);
				
				$class .= ($area_block["width"] < 100) ? ' col'.$w : ' col100';
				
				if(!$key){
				    $class .= " first";
				    $first = true;
				}
				else if($area_block["width"] < 100 && $w == 30 && $first == true){
				    $class .= " middle";
				    $first = false;
				}
				
				$block_html .= '<div class="'.$class.'" data-gid="'.$blocks[$area_block['id_block']]['gid'].'">'.$temp_content.'</div>';
			}
			if(!$block_html) continue;
			$html .= '<div class="dynamic_block_row">'.$block_html.'</div><div class="clr"></div>';
		}
		
		return $html;
	}

	public function html_area_blocks_by_gid($gid_area){
		$area = $this->get_area_by_gid($gid_area);
		return $this->html_area_blocks($area["id"]);
	}

	public function update_langs($data, $langs_file, $langs_ids) {
		$module = $data[0]['module'] . '_dynamic_blocks';
		foreach($data as $block) {
			$this->CI->pg_language->pages->set_string_langs($module,
															'block_' . $block['gid'],
															$langs_file['block_' . $block['gid']],
															(array)$langs_ids);
			if(!empty($block['views'])) {
				$views = unserialize($block['views']);
				if(is_array($views)){
					foreach($views as $value) {
						$this->CI->pg_language->pages->set_string_langs($module,
																'view_' . $block['gid'] . '_' . $value,
																$langs_file['view_' . $block['gid'] . '_' . $value],
																(array)$langs_ids);
					}
				}
			}		
			
			if(!empty($block['params'])){
				$params = unserialize($block['params']);
				if(is_array($params)) {
					foreach($params as $key => $value) {
						$this->CI->pg_language->pages->set_string_langs($module,
																'param_' . $block['gid'] . '_' . $key,
																$langs_file['param_' . $block['gid'] . '_' . $key],
																(array)$langs_ids);
					}
				}
			}
		}
	}

	public function export_langs($data, $langs_ids = null) {
		$gids = array();
		$module = $data[0]['module'] . '_dynamic_blocks';
		foreach($data as $block){
			$gids[] = 'block_' . $block['gid'];
			if(!empty($block['views'])) {
				$views = unserialize($block['views']);
				if(is_array($views)) {
					foreach($views as $value) {
						$gids[] = 'view_' . $block['gid'] . '_' . $value;
					}
				}
			}
			if(!empty($block['params'])) {
				$params = unserialize($block['params']);
				if(is_array($params)) {
					foreach($params as $key => $value) {
						$gids[] = 'param_' . $block['gid'] . '_' . $key;
					}
				}
			}
		}
		return $this->CI->pg_language->export_langs($module, $gids, $langs_ids);
	}

	/**
	 * Dynamic block callback method for returning html code
	 * @param array $params
	 * @param string $view
	 * @return string
	 */
	public function _dynamic_block_get_html_code($params, $view, $width){
		if(!$params['html']) return '';
		$this->CI->template_lite->assign('block_title', $params['title_'.$this->pg_language->current_lang_id]);
		$this->CI->template_lite->assign('block_html', $params['html']);
		return $this->CI->template_lite->fetch("helper_html_block", "user", "dynamic_blocks");
	}
	
	public function _dynamic_block_get_rich_text($params, $view, $width){
		$data['title'] = $params['title_'.$this->pg_language->current_lang_id];
		$data['html'] = $params['html_'.$this->pg_language->current_lang_id];
		$this->CI->template_lite->assign('dynamic_block_rich_text_data', $data);
		return $this->CI->template_lite->fetch("dynamic_block_rich_text", "user", "dynamic_blocks");
	}
	
	public function _dynamic_block_get_empty_block($params, $view, $width){
		$height = !empty($params['height']) ? intval($params['height']) : 1;
		return '<div style="height: '.$height.'px;">&nbsp;</div>';
	}
}
