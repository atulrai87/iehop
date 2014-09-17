<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Php languages datasource driver
 * 
 * @package PG_Core
 * @subpackage Libraries
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (, 02  2010) $ $Author: kkashkova $
 */
class lang_ds_driver {
	var $CI;
	var $languages_path;

	function lang_ds_driver(){
		$this->CI =& get_instance();
		$this->languages_path = APPPATH . "languages/php/";
	}

	function get_module($module_gid, $lang_id){	//// get all module strings from base and put it to cache ($modules_data)
		global $language;
		unset($language);

		$return = array();

		$file = $this->languages_path . $lang_id . "/" . $module_gid . "_ds.php";
		if(file_exists($file)){
			require $file;
			if(!empty($language)){
				$return = $language;
			}
		}

		return $return;
	}

	function set_module_reference($module_gid, $gid, $data, $lang_id){
		$module_lang = $this->get_module($module_gid, $lang_id);
		$module_lang[$gid] = $data;
		$this->_save_file($module_gid, $lang_id, $module_lang);
		return;
	}

	function set_reference_sorter($module_gid, $gid, $sorter_data, $languages){
		if(empty($sorter_data)) return false;

		$temp_reference = array();
		foreach($languages as $lang_id => $language){
			unset($temp_reference);

			$module_lang = $this->get_module($module_gid, $lang_id);
			$reference = $module_lang[$gid];

			$temp_reference["header"] = $reference["header"];
			foreach($sorter_data as $index => $option_gid){
				$temp_reference["option"][$option_gid]  = $reference["option"][$option_gid];
			}

			$module_lang[$gid] = $temp_reference;
			$this->_save_file($module_gid, $lang_id, $module_lang);
		}
	}

	function delete_module_reference($module_gid, $gids){
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
			if(is_file($dir . $entry) && substr($entry, -7) == "_ds.php"){
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
			if(substr($entry, -7) == "_ds.php"){
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

		$file = $dir . $module_gid . '_ds.php';
		$h = fopen($file, "w");
		if($h){
			$text = $this->_compile_file($data);
			fwrite($h, $text);
			fclose($h);
		}
		return;
	}

	function _delete_file($module_gid, $lang_id){
		$file = $this->languages_path . $lang_id . "/" . $module_gid . '_ds.php';
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
			foreach($data as $gid => $gid_data){
				$header = str_replace("'", "\'", stripslashes($gid_data["header"]));
				$str .= '$language["'.$gid.'"]["header"] = \''.$header.'\';' . "\n";
				if(!empty($gid_data["option"])){
					foreach($gid_data["option"] as $option_gid => $option_value){
						$option_value = str_replace("'", "\'", stripslashes($option_value));
						$str .= '$language["'.$gid.'"]["option"][\''.$option_gid.'\'] = \''.$option_value.'\';' . "\n";
					}
				}
				$str .= "\n";
			}
		}
		$str .= "?>";
		return $str;
	}

	function _get_exists_lang_ids(){
		$ids = array();
		$d = dir($this->languages_path);
		do{
			$entry = $d->read();
			if(intval($entry)){
				$ids[] = intval($entry);
			}
		}while($entry !== false);
		$d->close();
		return $ids;
	}

}
