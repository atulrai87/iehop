<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Php languages pages driver
 * 
 * @package PG_Core
 * @subpackage Libraries
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (, 02  2010) $ $Author: kkashkova $
 */
class lang_pages_driver {
	var $CI;
	var $languages_path;

	function lang_pages_driver(){
		$this->CI =& get_instance();
		$this->languages_path = APPPATH . "languages/php/";
	}

	function get_module($module_gid, $lang_id){	//// get all module strings from base and put it to cache ($modules_data)
		global $language;
		unset($language);

		$return = array();

		$file = $this->languages_path . $lang_id . "/" . $module_gid . "_pages.php";
		if(file_exists($file)){
			require $file;
			if(!empty($language)){
				$return = $language;
			}
		}

		return $return;
	}

	function set_module_strings($module_gid, $strings_data, $lang_id){
		$module_lang = $this->get_module($module_gid, $lang_id);
		foreach($strings_data as $gid=>$value){
			$module_lang[$gid] = $value;
		}
		$this->_save_file($module_gid, $lang_id, $module_lang);
		return;
	}

	function delete_module_strings($module_gid, $gids){
		$lang_ids = $this->_get_exists_lang_ids();
		foreach($lang_ids as $lang_id){
			$module_lang = $this->get_module($module_gid, $lang_id);
			foreach($gids as $gid){
				if(isset($module_lang[$gid])) unset($module_lang[$gid]);
			}
			$this->_save_file($module_gid, $lang_id, $module_lang);
		}
		return;
	}

	function delete_module($module_gid){
		$lang_ids = $this->_get_exists_lang_ids();
		foreach($lang_ids as $lang_id){
			$this->_delete_file($module_gid, $lang_id);
		}
		return;
	}

	function add_language($lang_id){
		//// only create dir
		$dir = $this->languages_path . $lang_id . "/";

		if(!is_dir($dir)){
			mkdir($dir);
		}

		return;
	}

	function delete_language($lang_id){
		$dir = $this->languages_path . $lang_id . "/";
		
		$d = dir($dir);
		while (false !== ($entry = $d->read())) {
			if(is_file($dir . $entry) && substr($entry, -10) == "_pages.php"){
				unlink($dir . $entry);
			}
		}
		$d->close();
		rmdir($dir);
		return;
	}

	function copy_language($lang_from, $lang_to){
		$dir_from = $this->languages_path . $lang_from . "/";
		$dir_to = $this->languages_path . $lang_to . "/";

		if(!is_dir($dir_from)){
			return false;
		}

		if(!is_dir($dir_to)){
			mkdir($dir_to);
		}

		$d = dir($dir_from);
		while (false !== ($entry = $d->read())) {
			if(substr($entry, -10) == "_pages.php"){
				$file_from = $dir_from . $entry;
				$file_to = $dir_to . $entry;
				copy($file_from, $file_to);
			}
		}
		$d->close();

		return;
	}

	function _save_file($module_gid, $lang_id, $data=array()){
		$dir = $this->languages_path . $lang_id . "/";
		if(!is_dir($dir)){
			mkdir($dir);
		}

		$file = $dir . $module_gid . '_pages.php';
		$h = fopen($file, "w");
		if($h){
			$text = $this->_compile_file($data);
			fwrite($h, $text);
			fclose($h);
		}
		return;
	}

	function _delete_file($module_gid, $lang_id){
		$file = $this->languages_path . $lang_id . "/" . $module_gid . '_pages.php';
		if(file_exists($file)){
			unlink($file);
		}
		return;
	}

	function _create_file($module_gid, $lang_id){
		$this->_save_file($module_gid, $lang_id);
	}

	function _compile_file($data = array()){
		$str = "<?php\n\n";
		if(!empty($data)){
			foreach($data as $gid => $value){
				$value = str_replace("'", "\'", stripslashes($value));
				$str .= '$language["'.$gid.'"] = \''.$value.'\';' . "\n";
			}
		}
		$str .= "?>";
		return $str;
	}

	function _get_exists_lang_ids(){
		$ids = array();
		$d = dir($this->languages_path);
		while (false !== ($entry = $d->read())) {
			if(intval($entry)){
				$ids[] = intval($entry);
			}
		}
		$d->close();
		return $ids;
	}
}
