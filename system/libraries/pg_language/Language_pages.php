<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Languages pages Model
 * 
 * @package PG_Core
 * @subpackage Libraries
 * @category libraries
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (, 02  2010) $ $Author: kkashkova $
 */
class Language_pages {
	var $driver;
	var $type = 'base';
	var $current_lang_id;
	var $default_lang_id;
	var $lang = array();
	var $languages = array();

	function Language_pages($lang_id, $params, $languages_data, $lang_default_id=null){
		$this->current_lang_id = $lang_id;
		$this->default_lang_id = (!empty($lang_default_id))?$lang_default_id:$lang_id;
		$this->type = $params["type"];
		$this->languages = $languages_data;

		require_once(BASEPATH.'libraries/pg_language/'.$this->type.'/lang_pages_driver'.EXT);
		$this->driver = new lang_pages_driver();
	}

	////// get all module content
	function get_module($module_gid, $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->lang[$lang_id][$module_gid] = $this->driver->get_module($module_gid, $lang_id);
	}

	function return_module($module_gid, $lang_id=''){	///// check if module data is set in cache, request it in base if not
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		if(!isset($this->lang[$lang_id][$module_gid]) || empty($this->lang[$lang_id][$module_gid])){
			$this->get_module($module_gid, $lang_id);
		}
		return $this->lang[$lang_id][$module_gid];
	}

	function delete_module($module_gid){
		$this->driver->delete_module($module_gid);
	}

	////// strings data
	function get_strings($module_gid, $strings_keys=array(), $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->return_module($module_gid, $lang_id);

		$return = array();

		foreach($strings_keys as $gid){
			if(isset($this->lang[$lang_id][$module_gid][$gid])) $return[$gid] = $this->lang[$lang_id][$module_gid][$gid];
		}

		return $return;
	}

	function set_strings($module_gid, $strings_data=array(), $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->driver->set_module_strings($module_gid, $strings_data, $lang_id);
		$this->get_module($module_gid, $lang_id);
	}

	function delete_strings($module_gid, $strings_keys=array()){
		$this->driver->delete_module_strings($module_gid, $strings_keys);
		$this->get_module($module_gid);
	}

	////// aliases for single string
	function get_string($module_gid, $gid, $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->return_module($module_gid, $lang_id);
		$return = '';
		if(isset($this->lang[$lang_id][$module_gid][$gid])) $return = $this->lang[$lang_id][$module_gid][$gid];
		return $return;
	}

	function set_string($module_gid, $string, $value, $lang_id=''){
		if(empty($lang_id)) $lang_id = $this->current_lang_id;
		$this->driver->set_module_strings($module_gid, array($string=>$value), $lang_id);
		$this->get_module($module_gid, $lang_id);
	}

	function set_string_langs($module_gid, $string, $values=array(), $langs_id=array()){
		if(empty($langs_id)) $langs_id[] = $this->current_lang_id;
		if(is_array($langs_id)) {
			foreach($langs_id as $lang_id){
				$value = isset($values[$lang_id])?$values[$lang_id]:$values[$this->current_lang_id];
				$this->driver->set_module_strings($module_gid, array($string=>$value), $lang_id);
			}
		}
		$this->get_module($module_gid, $lang_id);
	}

	function is_string_exists($module_gid, $gid){
		$lang_id = $this->current_lang_id;
		$this->return_module($module_gid, $lang_id);
		if(isset($this->lang[$lang_id][$module_gid][$gid])){
			return true;
		}else{
			return false;
		}
	}

	function delete_string($module_gid, $string_key){
		$this->driver->delete_module_strings($module_gid, array($string_key));
		$this->get_module($module_gid);
	}

	////// lang managing functions
	function add_lang($lang_id){
		$this->driver->add_language($lang_id);
		if($this->default_lang_id != $lang_id) {
			$this->driver->copy_language($this->default_lang_id, $lang_id);
		}
	}

	function delete_lang($lang_id){
		$this->driver->delete_language($lang_id);
	}

	function copy_lang($lang_from, $lang_to){
		$this->driver->copy_language($lang_from, $lang_to);
	}

	////// generate functions
	function generate_install_module_lang($module_gid, $lang_id){
		$data = $this->return_module($module_gid, $lang_id);
		return $this->generate_install_lang($data);
	}

	function generate_install_lang($data){
		$html = '';
		if(!empty($data)){
			ksort($data);
			foreach($data as $gid => $string){
				$html .= '$install_lang["'. $gid .'"] = "'. $this->prepare_install_string($string) .'";' . "\n";
			}
		}
		return $html;
	}

	function prepare_install_string($str){
		$str = str_replace('"', '\"', $str);
		$str = preg_replace('/[\n\r]/', '\n', $str);
		return $str;
	}
}
