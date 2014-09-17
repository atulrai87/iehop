<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

define('LANGUAGES_TABLE', DB_PREFIX.'languages');
define('LANG_DEDICATE_MODULES_TABLE', DB_PREFIX.'lang_dedicate_modules');

/**
 * PG Languages Model
 *
 * @package PG_Core
 * @subpackage Libraries
 * @category	libraries
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 */
class CI_Pg_language {

	/**
	 * Languages cache, allways contain actual data
	 * @var array
	 */
	public $languages=array();

	/**
	 * Current language ID
	 * @var integer
	 */
	public $current_lang_id;

	/**
	 * link to CodeIgniter object
	 * @var object
	 */
	public $CI;

	/**
	 * Config data
	 * @var object
	 */
	public $params;

	/**
	 * link to Pages object
	 * @var object
	 */
	public $pages;

	/**
	 * link to DS object
	 * @var object
	 */
	public $ds;

	/**
	 * Constructor
	 *
	 * @return CI_PG_Language Object
	 */
	 function __construct(){
		$this->CI =& get_instance();

		///// get lang cache
		$this->get_langs('is_default DESC');

		///// get lang model settings and save it
		include(APPPATH.'config/languages'.EXT);

		if ( ! isset($lang_config) OR count($lang_config) == 0){
			show_error('No languages settings were found in the language config file.');
		}
		$this->params = $lang_config;

		if ( ! isset($this->params['type']) OR $this->params['type'] == ''){
			show_error('You have not selected a language storage type.');
		}

		// Get lang from URI
		$lang_from_uri = $this->CI->router->fetch_lang();
		if ($lang_from_uri) {
			$lang_id = $this->get_lang_id_by_code($lang_from_uri);
			$lang = $this->get_lang_by_id($lang_id, 1);
			if(!$lang) show_404();
		} else {
			$lang_id = $this->CI->session->userdata('lang_id');
		}

		// get default language, if $lang_id isn't set or active
		if(!$lang_id || !$this->get_lang_by_id($lang_id, 1)){
			$lang_id = $this->get_default_lang_id();
		}

		$this->current_lang_id = $lang_id;
		
		// Direction mark (&rtm; | &ltm;)
		define('DM', 'rtl' === $this->languages[$this->current_lang_id]['rtl'] ? '&rlm;' : '&lrm;');
		
		$lang = $this->get_lang_by_id($lang_id, true);

		if (@require(APPPATH . 'config/locales' . EXT)) {
			$locales;
		}

		setlocale(LC_TIME, array($locales[$lang['code']].'.UTF-8', 'en_EN.UTF-8', 0));

		////// Load pages model
		$this->load_pages_model();

		////// Load ds model
		$this->load_ds_model();

	}

	/**
	 * Get languages data from base, put into the $this->languages
	 *
	 */
	 function get_langs($order = 'id ASC'){
		unset($this->languages);
		$this->CI->db->select('id, name, code, status, rtl, is_default, date_created')->from(LANGUAGES_TABLE)->order_by($order);
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			foreach($results as $result){
				$this->languages[$result["id"]] = $result;
			}
		}
		return $this->languages;
	}

	/**
	 * Determines whether language is active or not
	 *
	 * @param int $lang_id
	 * @return bool
	 */
	function is_active($lang_id) {
		$lang = $this->get_lang_by_id($lang_id);
		if(!empty($lang)){
			return (bool)$lang['status'];
		}
		return false;
	}

	/**
	 * Execute get_langs, if language cache not exists
	 *
	 */
	function return_langs(){
		if(!isset($this->languages) || empty($this->languages)){
			$this->get_langs();
		}
		return $this->languages;
	}

	/**
	 * Get lang data by ID, from languge cache
	 *
	 * @param integer $lang_id
	 * @param mixed $activity (integer/boolean)
	 * @return mixed  (array/boolean)
	 */
	function get_lang_by_id($lang_id, $activity=false){ ///// из $languages
		$this->return_langs();

		$language = false;

		if(isset($this->languages[$lang_id]) && !empty($this->languages[$lang_id])){
			$language = $this->languages[$lang_id];

			if($activity !== false && $language["status"] != $activity){
				$language = false;
			}
		}

		return $language;
	}

	/**
	 * Save or add new language
	 *
	 * @param integer $lang_id
	 * @param array $data
	 * @return integer
	 */
	 function set_lang($lang_id=null, $data){
		if(is_null($lang_id)){
			$data["date_created"] = date("Y-m-d H:i:s");
			$this->CI->db->insert(LANGUAGES_TABLE, $data);
			$lang_id = $this->CI->db->insert_id();
			if(!empty($data['is_default']) && $data['is_default']) {
				$this->pages->default_lang_id	= $lang_id;
				$this->ds->default_lang_id		= $lang_id;
			}
			$this->pages->add_lang($lang_id);
			$this->ds->add_lang($lang_id);

			$this->update_dedicate_modules($lang_id, "add");
		}else{
			$this->CI->db->where("id", $lang_id);
			$this->CI->db->update(LANGUAGES_TABLE, $data);
		}
		// Update config/langs_route.php
		$this->update_route_langs();
		/// refresh cache
		$this->get_langs();
		return $lang_id;
	}

	private function update_route_langs() {

		$langs = $this->CI->pg_language->get_langs();
		if (0 == count($langs)) {
			return false;
		}

		$file_content = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";
		$file_content .= "\$config['langs_route'] = array(";
		foreach($langs as $lang) {
			$file_content .= "'" . $lang['code'] . "', ";
		}
		$file_content = substr_replace($file_content, '', -2);
		$file_content .= ');';

		$file = APPPATH . 'config/langs_route' . EXT;
		try {
			$handle = fopen($file, 'w');
			fwrite($handle, $file_content);
			fclose($handle);
		} catch (Exception $e) {
			log_message('error','Error while updating langs_route' . EXT .
					'(' . $e->getMessage() . ') in ' . $e->getFile());
			throw $e;
		}
		return true;
	}

	/**
	 * Validate language data
	 *
	 * @param integer $lang_id
	 * @param array $data
	 * @return array
	 */
	 function validate_lang($lang_id=null, $data){
		$return = array("errors"=> array(), "data" => array());

		if(isset($data["name"])){
			$return["data"]["name"] = strip_tags($data["name"]);
			if(empty($return["data"]["name"]) ){
				$return["errors"][] = l('error_name_incorrect', 'languages');
			}
		}

		if(!empty($data["code"])){
			$return["data"]["code"] = strip_tags($data["code"]);
			if(empty($return["data"]["name"]) ){
				$return["errors"][] = l('error_code_incorrect', 'languages');
			}

			$this->CI->db->select("COUNT(*) AS cnt")->from(LANGUAGES_TABLE)->where("code", $return["data"]["code"]);
			if($lang_id){
				$this->CI->db->where("id <>", $lang_id);
			}
			$results = $this->CI->db->get()->result_array();
			if(!empty($results)){
				$count = intval($results[0]["cnt"]);
			}else{
				$count = 0;
			}
			if($count > 0){
				$return["errors"][] = l('error_code_exists', 'languages');
			}
		} else {
			$return["errors"][] = l('error_code_incorrect', 'languages');
		}

		if(isset($data["rtl"])){
			$return["data"]["rtl"] = $data["rtl"];
			if(empty($return["data"]["rtl"])){
				$return["errors"][] = l('error_rtl_incorrect', 'languages');
			}
		}
		return $return;
	}

	/**
	 * Set default language
	 *
	 * @param integer $lang_id
	 * @return boolean
	 */
	 function set_default_lang($lang_id){
		$data['is_default'] = 0;
		$this->CI->db->update(LANGUAGES_TABLE, $data);

		$data['is_default'] = 1;
		$this->CI->db->where("id", $lang_id);
		$this->CI->db->update(LANGUAGES_TABLE, $data);

		/// refresh cache
		$this->get_langs();
		return true;
	}


	/**
	 * Delete language
	 *
	 * @param integer $lang_id
	 */
	function delete_lang($lang_id){
		$this->CI->db->where("id", $lang_id);
		$this->CI->db->delete(LANGUAGES_TABLE);

		/// refresh cache
		unset($this->languages[$lang_id]);

		$this->pages->delete_lang($lang_id);
		$this->ds->delete_lang($lang_id);

		// Update config/langs_route.php
		$this->update_route_langs();

		$this->update_dedicate_modules($lang_id, "delete");
	}

	/**
	 * Copy language
	 *
	 * @param integer $lang_from
	 * @param integer $lang_to
	 */
	function copy_lang($lang_from, $lang_to){
		$this->pages->copy_lang($lang_from, $lang_to);
		$this->ds->copy_lang($lang_from, $lang_to);
	}

	/**
	 * Get default language ID
	 *
	 * @return integer
	 */
	function get_default_lang_id(){
		$this->return_langs();

		if (0 === count($this->languages)) {
			return false;
		}
		$lang_id = false;
		foreach($this->languages as $id => $lang_data){
			if($lang_data["is_default"] == 1){
				$lang_id = $id;
			}
		}

		return $lang_id;
	}

	/**
	 * Get language ID by code
	 *
	 * @return integer
	 */
	function get_lang_id_by_code($code, $activity = false) {
		$this->return_langs();
		$lang_id = false;
		foreach($this->languages as $id => $lang_data) {
			if($lang_data['code'] == $code) {
				if($activity !== false && $lang_data['status'] != $activity) {
					$lang_id = false;
				} else {
					$lang_id = $id;
				}
				break;
			}
		}
		return $lang_id;
	}

	/**
	 * Get language code by ID
	 *
	 * @return integer
	 */
	function get_lang_code_by_id($lang_id){
		$this->return_langs();

		$lang_code = false;

		foreach($this->languages as $id => $lang_data){
			if($id == $lang_id){
				$lang_code = $lang_data["code"];
			}
		}

		return $lang_code;
	}

	/**
	 * Load Pages_model
	 *
	 */
	function load_pages_model(){
		require_once(BASEPATH.'libraries/pg_language/Language_pages'.EXT);
		$this->pages = new Language_pages($this->current_lang_id, $this->params, $this->return_langs(), $this->get_default_lang_id());
	}

	/**
	 * Load Ds_model
	 *
	 */
	function load_ds_model(){
		require_once(BASEPATH.'libraries/pg_language/Language_ds'.EXT);
		$this->ds = new Language_ds($this->current_lang_id, $this->params, $this->return_langs(), $this->get_default_lang_id());
	}

	function get_string($module_gid, $gid, $lang_id=''){
		return $this->pages->get_string($module_gid, $gid, $lang_id);
	}

	function get_reference($module_gid, $gid, $lang_id=''){
		return $this->ds->get_reference($module_gid, $gid, $lang_id);
	}

	function generate_install_module_lang($module_gid, $lang_id, $type="pages"){
		if($type == 'pages'){
			$generated = $this->pages->generate_install_module_lang($module_gid, $lang_id);
		}else{
			$generated = $this->ds->generate_install_module_lang($module_gid, $lang_id);
		}
		if($generated) {
			return "<?php\n\n" . $generated . "\n";
		} else {
			return false;
		}
	}

	function generate_install_linked_lang($lang_data, $lang_id){
		$generated = '';
		if(!empty($lang_data)){
			foreach($lang_data as $gid => $lang_array){
				if(is_array($lang_array[$lang_id])){
					$data_ds[$gid] = $lang_array[$lang_id];
				}else{
					$data_pages[$gid] = $lang_array[$lang_id];
				}
			}
			if(!empty($data_pages)){
				$generated .= $this->pages->generate_install_lang($data_pages);
			}
			if(!empty($data_ds)){
				$generated .= $this->ds->generate_install_lang($data_ds);
			}
		}
		if($generated) {
			return "<?php\n\n" . $generated . "\n";
		} else {
			return false;
		}
	}

	function generate_install_lang_description($lang_id){
		$generated = '';
		$lang_data = $this->languages[$lang_id];
		if(!empty($lang_id) && !empty($lang_data)){
			$generated .= "return array(\n";
			$generated .= "\t'code' => '".$lang_data["code"]."',\n";
			$generated .= "\t'name' => '".$lang_data["name"]."',\n";
			$generated .= "\t'dir' => '".$lang_data["rtl"]."',\n";
			$generated .= ");\n";
		}
		if($generated) {
			return "<?php\n\n" . $generated . "\n";
		} else {
			return false;
		}
	}

	/**
	 * Exports langs data for the module
	 *
	 * @param string $module_gid Module gid
	 * @param array $strings_gids String gids
	 * @param array $langs_ids Langs
	 * @return array
	 */
	public function export_langs($module_gid, $strings_gids, $langs_ids = array()) {
		if(!is_array($strings_gids)) {
			return false;
		}
		$lang_data = array();
		if(is_int($langs_ids)) {
			$langs_ids = array($langs_ids);
		}
		foreach($strings_gids as $block) {
			foreach($this->return_langs() as $lang) {
				if(0 === count($langs_ids) || in_array($lang['id'], $langs_ids)) {
					$string = $this->get_string($module_gid, $block, $lang['id']);
					if($string) {
						$lang_data[$block][$lang['id']] = $string;
					}
				}
			}
		}
		return $lang_data;
	}

	/**
	 * Update dedicate modules after language adding
	 *
	 */
	function update_dedicate_modules($lang_id, $type="add"){
		$this->CI->db->select('id, model, module, method_add, method_delete')->from(LANG_DEDICATE_MODULES_TABLE);
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			foreach($results as $result){
				if(strpos($result["model"], "pg_") !== 0){
					$model_url = $result["module"]."/models/".$result["model"];
					$model_path = MODULEPATH.strtolower($model_url).EXT;
					$this->CI->load->model($model_url);
				}

				@ob_end_clean();
				ob_start();
				$function_result = call_user_func_array(array(&$this->CI->$result["model"], $result["method_".$type]), array($lang_id));
				if(!empty($function_result)) $log[$result["id"]]["function_result"] = $function_result;
				$log[$result["id"]]["output"] = ob_get_contents();
			}
		}
		return $log;
	}

	/**
	 * save dedicate modules entry
	 *
	 */
	function add_dedicate_modules_entry($module_data){
		$return = array("errors"=>array(), "success"=>false);
		$data = array(
			"module" => trim(strip_tags($module_data["module"])),
			"model" => trim(strip_tags($module_data["model"])),
			"method_add" => trim(strip_tags($module_data["method_add"])),
			"method_delete" => trim(strip_tags($module_data["method_delete"])),
			"date_created" => date('Y-m-d H:i:s')
		);
		$result = $this->is_method_callable($data["module"], $data["model"], $data["method_add"]);
		if($result !== true){
			$return["errors"][] = "method_add: ".$result;
		}
		$result = $this->is_method_callable($data["module"], $data["model"], $data["method_delete"]);
		if($result !== true){
			$return["errors"][] = "method_delete: ".$result;
		}
		if(empty($return["errors"])){
			$this->CI->db->insert(LANG_DEDICATE_MODULES_TABLE, $data);
			$return["success"] = true;
		}

		return $return;
	}

	/**
	 * delete dedicate modules entry
	 *
	 */
	function delete_dedicate_modules_entry($params){
		if(isset($params["where"]) && is_array($params["where"]) && count($params["where"])){
			foreach($params["where"] as $field=>$value) $this->CI->db->where($field, $value);
		}

		if(isset($params["where_in"]) && is_array($params["where_in"]) && count($params["where_in"])){
			foreach($params["where_in"] as $field=>$value) $this->CI->db->where_in($field, $value);
		}

		if(isset($params["where_sql"]) && is_array($params["where_sql"]) && count($params["where_sql"])){
			foreach($params["where_sql"] as $value) $this->CI->db->where($value);
		}
		$this->CI->db->delete(LANG_DEDICATE_MODULES_TABLE);

	}

	private function is_method_callable($module, $model, $method){
		$result = false;

		if(strpos($model, "pg_") !== 0){
			$model_url = $module."/models/".$model;
			$model_path = MODULEPATH.strtolower($model_url).EXT;

			if(file_exists($model_path)){
				$this->CI->load->model($model_url);
				$object = array($this->CI->$model, $method);
				$result = is_callable($object);
				if(!$result){
					return "method_not_callable";
				}
			}else{
				return "model_not_exist";
			}
		}else{
			$object = array($this->CI->$model, $method);
			$result = is_callable($object);
			if(!$result){
				return "method_not_callable";
			}

		}

		return true;
	}

}
