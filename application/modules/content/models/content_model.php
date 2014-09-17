<?php
/**
* COntent main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('CONTENT_TABLE')) define('CONTENT_TABLE', DB_PREFIX.'content');

class Content_model extends Model
{
	public $CI;
	public $DB;

	public $fields_all = array(
		"id",
		"lang_id",
		"parent_id",
		"gid",
		"title",
		"content",
		"sorter",
		"status",
		"date_created",
		"date_modified",
		"id_seo_settings",
	);

	public $fields_list = array(
		"id",
		"lang_id",
		"parent_id",
		"gid",
		"title",
		"sorter",
		"status",
		"date_created",
		"date_modified"
	);

	public $curent_active_item_id = array();

	public $temp_generate_raw_tree = array();
	public $temp_generate_raw_items = array();

	public function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	public function get_pages_list($lang_id, $parent_id=0, $params=array()){
		$this->DB->select(implode(", ", $this->fields_all));
		$this->DB->from(CONTENT_TABLE);
		$this->DB->where("lang_id", $lang_id);

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

		$this->DB->order_by("parent_id ASC");
		$this->DB->order_by("sorter ASC");

		$this->temp_generate_raw_items = $this->temp_generate_raw_tree = array();
		$results = $this->DB->get()->result_array();

		if(!empty($results) && is_array($results)){
			$active_parent_id = array();
			foreach($results as $r){
				$r["active"] = $this->_is_active_item($r);
				if($r["active"]){
					$active_parent_id[] = $r["parent_id"];
				}
				$this->temp_generate_raw_items[$r["id"]] = $r;
			}

			if(!empty($active_parent_id)){
				$this->_set_active_chain($active_parent_id);
			}

			foreach($this->temp_generate_raw_items as $r){
				$this->temp_generate_raw_tree[$r["parent_id"]][] = $r;
			}

			$tree = $this->_generate_tree($parent_id);
			return $tree;
		}

		return array();
	}

	public function get_active_pages_list($lang_id, $parent_id=0, $params=array()){
		$params["where"]["status"] = 1;
		return $this->get_pages_list($lang_id, $parent_id, $params);
	}

	public function get_pages_count($lang_id, $params=array()){
		$this->DB->select("COUNT(*) AS cnt");
		$this->DB->from(CONTENT_TABLE);
		$this->DB->where("lang_id", $lang_id);

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

		$result = $this->DB->get()->result();
		if(!empty($result)){
			return intval($result[0]->cnt);
		}else{
			return 0;
		}
	}

	public function get_active_pages_count($lang_id, $params=array()){
		$params["where"]["status"] = 1;
		return $this->get_pages_count($lang_id, $params);
	}

	public function get_page_by_id($page_id){
		$page_data = array();
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(CONTENT_TABLE)->where("id", $page_id)->get()->result_array();
		if(!empty($result)){
			$page_data = $result[0];
		}
		return $page_data;
	}

	public function get_page_by_gid($lang_id, $gid){
		$page_data_arr = $page_data = array();
		$default_lang_id = $this->pg_language->get_default_lang_id();
		$result = $this->DB->select(implode(", ", $this->fields_all))->from(CONTENT_TABLE)->where("gid", $gid)->get()->result_array();
		if(!empty($result)){
			foreach($result as $r){
				$page_data_arr[$r["lang_id"]] = $r;
			}

			if(isset($page_data_arr[$lang_id])){
				$page_data = $page_data_arr[$lang_id];
			}elseif(isset($page_data_arr[$default_lang_id])){
				$page_data = $page_data_arr[$default_lang_id];
			}elseif(!empty($page_data_arr)){
				$page_data = current($page_data_arr);
			}
		}
		return $page_data;
	}

	public function save_page($page_id, $attrs){
		if (is_null($page_id)){
			if(empty($attrs["date_created"])) {
				$attrs["date_created"] = $attrs["date_modified"] = date("Y-m-d H:i:s");
			}
			if(!isset($attrs["status"])) {
				$attrs["status"] = 1;
			}
			if(!isset($attrs["sorter"]) && isset($attrs["lang_id"])){
				$sorter_params = array();
				$sorter_params["where"]["parent_id"] = isset($attrs["parent_id"])?$attrs["parent_id"]:0;
				$attrs["sorter"] = $this->get_pages_count($attrs["lang_id"], $sorter_params)+1;
			}
			$this->DB->insert(CONTENT_TABLE, $attrs);
			$page_id = $this->DB->insert_id();
		}else{
			$attrs["date_modified"] = date("Y-m-d H:i:s");
			$this->DB->where('id', $page_id);
			$this->DB->update(CONTENT_TABLE, $attrs);
		}
		return $page_id;
	}

	public function validate_page($page_id, $data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["title"])){
			$return["data"]["title"] = strip_tags($data["title"]);
			if(empty($return["data"]["title"]) ){
				$return["errors"][] = l('error_content_title_invalid', 'content');
			}
		}

		if(isset($data["gid"])){
			$return["data"]["gid"] = strip_tags($data["gid"]);
			$return["data"]["gid"] = preg_replace("/[\n\t\s]{1,}/", "-", trim($return["data"]["gid"]));
			if(empty($return["data"]["gid"]) ){
				$return["errors"][] = l('error_content_gid_invalid', 'content');
			}
		}

		if(isset($data["content"])){
			$return["data"]["content"] = $data["content"];
		}

		if(isset($data["lang_id"])){
			$return["data"]["lang_id"] = intval($data["lang_id"]);
		}

		if(isset($data["parent_id"])){
			$return["data"]["parent_id"] = intval($data["parent_id"]);
		}

		if(isset($data["sorter"])){
			$return["data"]["sorter"] = intval($data["sorter"]);
		}

		if(isset($data["status"])){
			$return["data"]["status"] = intval($data["status"]);
		}

		return $return;
	}

	public function delete_page($page_id){
		$page_data = $this->get_page_by_id($page_id);
		if(!empty($page_data)){
			$this->DB->where('id', $page_id);
			$this->DB->delete(CONTENT_TABLE);
			$this->resort_pages($page_data["lang_id"], $page_data["parent_id"]);
		}
		return;
	}

	public function activate_page($page_id, $status=1){
		$attrs["status"] = intval($status);
		$this->DB->where('id', $page_id);
		$this->DB->update(CONTENT_TABLE, $attrs);
	}

	public function resort_pages($lang_id, $parent_id=0){
		$results = $this->DB->select("id, sorter")->from(CONTENT_TABLE)->where("lang_id", $lang_id)->where("parent_id", $parent_id)->order_by('sorter ASC')->get()->result_array();
		if(!empty($results)){
			$i = 1;
			foreach($results as $r){
				$data["sorter"] = $i;
				$this->DB->where('id', $r["id"]);
				$this->DB->update(CONTENT_TABLE, $data);
				$i++;
			}
		}
	}

	public function set_page_active($lang_id, $page_id){
		if(!$lang_id){
			return false;
		}
		if(!is_numeric($page_id)){
			$item = $this->get_page_by_gid($lang_id, $page_id);
			$page_id = $item["id"];
		}
		if(!$page_id){
			return false;
		}
		$this->curent_active_item_id[$lang_id] = $page_id;
		return;
	}

	///// inner functions
	public function _is_active_item($item){
		if(!empty($this->curent_active_item_id[$item["lang_id"]])){
			if($this->curent_active_item_id[$item["lang_id"]] == $item["id"]){
				return true;
			}
		}
		return false;
	}

	public function _set_active_chain($parent_ids){
		foreach($parent_ids as $id){
			$parent_id = $id;
			do{
				$this->temp_generate_raw_items[$parent_id]["in_chain"] = true;
				$parent_id = $this->temp_generate_raw_items[$parent_id]["parent_id"];
			}while($parent_id > 0);
		}
	}

	public function _generate_tree($parent_id){

		if(empty($this->temp_generate_raw_tree) || empty($this->temp_generate_raw_tree[$parent_id])){
			return array();
		}

		$tree = array();
		foreach($this->temp_generate_raw_tree[$parent_id] as $subitem){
			if(isset($this->temp_generate_raw_tree[$subitem["id"]]) && !empty($this->temp_generate_raw_tree[$subitem["id"]])){
				$subitem["sub"] = $this->_generate_tree($subitem["id"]);
			}
			$tree[] = $subitem;
		}

		return $tree;
	}

	////// seo
	public function get_seo_settings($method='', $lang_id=''){
		if(!empty($method)){
			return $this->_get_seo_settings($method, $lang_id);
		}else{
			$actions = array('index', 'view');
			$return = array();
			foreach($actions as $action){
				$return[$action] = $this->_get_seo_settings($action, $lang_id);
			}
			return $return;
		}
	}

	public function _get_seo_settings($method, $lang_id=''){
		if($method == "index"){
			return array(
				"templates" => array(),
				"url_vars" => array()
			);
		}elseif($method == "view"){
			return array(
				"templates" => array('title', 'gid'),
				"url_vars" => array(
					"gid" => array('gid'=>'literal')
				)
			);
		}
	}

	public function request_seo_rewrite($var_name_from, $var_name_to, $value){
		$user_data = array();

		if($var_name_from == $var_name_to){
			return $value;
		}

		if($var_name_from == "gid"){
			$lang_id = $this->CI->pg_language->current_lang_id;
			$page_data = $this->get_page_by_gid($lang_id, $value);
		}

		if($var_name_to == "id"){
			return $page_data["id"];
		}
	}

	public function get_sitemap_xml_urls(){
		$this->CI->load->helper('seo');
		$return = array(
			array(
				"url" => rewrite_link('content', 'index'),
				"priority" => 0.1
			),
		);

		$this->DB->select(implode(", ", $this->fields_list))->from(CONTENT_TABLE)->where('status', '1');
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				$return[] = array(
					"url" => rewrite_link('content', 'view', $r),
					"priority" => 0.5
				);
			}
		}
		return $return;
	}

	public function get_sitemap_urls(){
		$this->CI->load->helper('seo');

		$lang_id = $this->CI->pg_language->current_lang_id;
		$pages = $this->get_active_pages_list($lang_id, 0);
		$block = array();

		foreach($pages as $page){
			$sub = array();
			if(!empty($page["sub"])){
				foreach($page["sub"] as $sub_page){
					$sub[] = array(
						"name" => $sub_page["title"],
						"link" => rewrite_link('content', 'view', $sub_page),
						"clickable" => true
					);
				}
			}
			$block[] = array(
				"name" => $page["title"],
				"link" => rewrite_link('content', 'view', $page),
				"clickable" => true,
				"items" => $sub
			);
		}
		return $block;
	}

	////// banners callback method
	public function _banner_available_pages(){
		$return[] = array("link"=>"content/index", "name"=> l('seo_tags_index_header', 'content'));
		$return[] = array("link"=>"content/view", "name"=> l('seo_tags_view_header', 'content'));
		return $return;
	}

	public function _dynamic_block_get_info_pages($params, $view, $width){
		$parent_id = 0;

		$data['section'] = array();
		$data['pages'] = array();
		if(!empty($params['keyword'])){
			$data['section'] = $this->get_page_by_gid($this->pg_language->current_lang_id, $params['keyword']);
			if($data['section']){
				$parent_id = $data['section']['id'];
			}
		}
		if($params['show_subsections']){
			$this->load->helper('text');
			$data['pages'] = $this->get_active_pages_list($this->pg_language->current_lang_id, $parent_id, array('where'=>array('parent_id' => $parent_id)));
			if($params['trim_subsections_text']){
				foreach($data['pages'] as &$page){
					$page['short_content'] = word_limiter(trim(strip_tags($page['content'])), 50, '...');
				}
			}
		}

		$data['params'] = $params;
		$data['view'] = $view;
		$data['width'] = $width;

		$this->CI->template_lite->assign('dynamic_block_info_pages_data', $data);

		return $this->CI->template_lite->fetch('dynamic_block_info_pages', 'user', 'content');
	}
}
