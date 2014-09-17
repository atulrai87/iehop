<?php 

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('MODULES_TABLE', DB_PREFIX.'modules');
define('MODULES_CONFIG_TABLE', DB_PREFIX.'modules_config');
define('MODULES_METHODS_TABLE', DB_PREFIX.'modules_methods');

/**
 * PG Modules Model
 * 
 * @package PG_Core
 * @subpackage Libraries
 * @category	libraries
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (, 02  2010) $ $Author: kkashkova $
 */
class CI_Pg_module {

	/**
	 * link to CodeIgniter object
	 * @var object
	 */
	var $CI;

	/**
	 * Modules cache array
	 * @var array
	 */
	var $modules=array();

	/**
	 * Modules config cache array
	 * @var array
	 */
	var $config=array();

	/**
	 * Constructor
	 *
	 * @return CI_PG_Module Object
	 */
	function CI_PG_Module(){
		$this->CI =& get_instance();
	}

	/**
	 * Get installed modules data from base, put into the $this->modules
	 * 
	 */
	function get_modules($order_by = 'module_gid ASC'){
		unset($this->modules);
		$this->modules = array();

		$this->CI->db->select('id, module_gid, module_name, module_description, version, date_add, date_update')
				->from(MODULES_TABLE)->order_by($order_by);
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			foreach($results as $result){
				$this->modules[$result["id"]] = $result;
			}
		}
		return $this->modules;
	}

	/**
	 * Execute get_modules, if modules cache not exists
	 * 
	 */
	function return_modules(){
		if(!isset($this->modules) || empty($this->modules)){
			$this->get_modules();
		}
		return $this->modules;
	}

	/**
	 * Get module by id
	 * 
	 */
	function get_module_by_id($module_id){
		$modules = $this->return_modules();
		return $modules[$module_id];
	}

	/**
	 * Get module by gid
	 * 
	 */
	function get_module_by_gid($module_gid){
		$modules = $this->return_modules();
		foreach($modules as $module){
			if($module["module_gid"] == $module_gid){
				return $module;
			}
		}
		return false;
	}

	/**
	 * Get module status
	 *  
	 * @param string $module_gid
	 * @return boolean
	 */
	function is_module_installed($module_gid){
		$this->return_modules();
		
		if(empty($this->modules)){
			return false;
		}

		foreach($this->modules as $id => $module){
			if($module["module_gid"] == $module_gid){
				return true;
			}
		}
		return false;
	}

	/**
	 * Insert module info into modules table
	 *  
	 * @param array $data
	 */
	function set_module_install($data){
		$this->CI->db->insert(MODULES_TABLE, $data);
		$this->get_modules();
		return;
	}

	/**
	 * Insert module info into modules table
	 *  
	 * @param array $data
	 */
	function set_module_update($module_gid, $data){
		$this->CI->db->where('module_gid', $module_gid);
		$this->CI->db->update(MODULES_TABLE, $data);
		$this->get_modules();
		return;
	}

	/**
	 * Delete uninstalled module from table by gid
	 *  
	 * @param string $module_gid
	 */
	function set_module_uninstall($module_gid){
		$this->CI->db->where("module_gid", $module_gid);
		$this->CI->db->delete(MODULES_TABLE);
		$this->delete_module_all_config($module_gid);
		$this->get_modules();
		return;
	}

	/**
	 * Get module config strings from base
	 *  
	 * @param string $module_gid
	 * @return array
	 */
	function get_module_all_config($module_gid){
		unset($this->config[$module_gid]);
		$this->config[$module_gid] = array();
		$this->CI->db->select('config_gid, value')->from(MODULES_CONFIG_TABLE)->where("module_gid", $module_gid)->order_by("config_gid DESC");
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			foreach($results as $result){
				$this->config[$module_gid][$result["config_gid"]] = $result["value"];
			}
		}
		return $this->config[$module_gid];
	}

	/**
	 * Return cache if exists, get from base if not
	 *  
	 * @param string $module_gid
	 * @return array
	 */
	function return_module_all_config($module_gid){
		if(!isset($this->config[$module_gid]) || empty($this->config[$module_gid])){
			$this->get_module_all_config($module_gid);
		}
		return $this->config[$module_gid];
	}

	/**
	 * Update module config 
	 *  
	 * @param string $module_gid
	 * @param array $data 
	 */
	function set_module_all_config($module_gid, $data){
		if(empty($data)) return;
		$update_data = $this->return_module_all_config($module_gid);

		foreach($data as $config_gid => $value){
			if(isset($update_data[$config_gid])){
				$this->CI->db->set('value', $value);
				$this->CI->db->where('module_gid', $module_gid);
				$this->CI->db->where('config_gid', $config_gid);
				$this->CI->db->update(MODULES_CONFIG_TABLE);
			}else{
				$dbdata = array(
					"value" => $value,
					"module_gid" => $module_gid,
					"config_gid" => $config_gid
				);
				$this->CI->db->insert(MODULES_CONFIG_TABLE, $dbdata);
			}
		}

		$this->get_module_all_config($module_gid);
		return;
	}

	/**
	 * Delete all module config strings from base
	 *  
	 * @param string $module_gid
	 */
	function delete_module_all_config($module_gid){
		$this->CI->db->where('module_gid', $module_gid);
		$this->CI->db->delete(MODULES_CONFIG_TABLE);
		$this->get_module_all_config($module_gid);
		return;
	}

	/**
	 * Get single module config string by config_gid
	 *  
	 * @param string $module_gid
	 * @param string $config_gid
	 * @return mixed string/boolean
	 */
	function get_module_config($module_gid, $config_gid){
		$config = $this->return_module_all_config($module_gid);
		if(isset($config[$config_gid])){
			return $config[$config_gid];
		}else{
			return false;
		}
	}

	/**
	 * Update single module config string
	 *  
	 * @param string $module_gid
	 * @param string $config_gid
	 * @param string $value
	 */
	function set_module_config($module_gid, $config_gid, $value){
		$config = $this->return_module_all_config($module_gid);
		if(isset($config[$config_gid])){
			$this->CI->db->set('value', $value);
			$this->CI->db->where('module_gid', $module_gid);
			$this->CI->db->where('config_gid', $config_gid);
			$this->CI->db->update(MODULES_CONFIG_TABLE);
		}else{
			$dbdata = array(
				"value" => $value,
				"module_gid" => $module_gid,
				"config_gid" => $config_gid
			);
			$this->CI->db->insert(MODULES_CONFIG_TABLE, $dbdata);
		}
		$this->get_module_all_config($module_gid);
		return;
	}

	/**
	 * Delete single config string
	 *  
	 * @param string $module_gid
	 * @param string $config_gid 
	 */
	function delete_module_config($module_gid, $config_gid=null){
		$this->CI->db->where('module_gid', $module_gid);
		if(!empty($config_gid)){
			$this->CI->db->where('config_gid', $config_gid);
		}
		$this->CI->db->delete(MODULES_CONFIG_TABLE);
		$this->get_module_all_config($module_gid);
		return;
	}

	function get_module_methods($module){
		$data = array();
		$this->CI->db->select('id, controller, method, access')->from(MODULES_METHODS_TABLE);
		$this->CI->db->where('module_gid', $module);
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			foreach($results as $result){
				$data[$result["controller"]][$result["method"]] = $result["access"];
			}
		}
		return $data;
	}

	function get_module_method_access($module, $controller, $method){
		$this->CI->db->select('id, access')->from(MODULES_METHODS_TABLE);
		$this->CI->db->where('module_gid', $module);
		$this->CI->db->where('controller', $controller);
		$this->CI->db->where('method', $method);
		$results = $this->CI->db->get()->result();
		if(!empty($results)){
			$result = intval($results[0]->access);
			return $result;
		}
		return false;
	}

	function is_module_method_exists($module, $controller, $method){
		$model_file = MODULEPATH . $module . '/controllers/' . $controller .EXT;
		if(file_exists($model_file)){
			require_once $model_file;
			$model_methods = get_class_methods(ucfirst($controller));
			if(!empty($model_methods) && in_array($method, $model_methods)){
				return true;
			}else{
				return false;
			}
		}
		return false;
	}

	function clear_module_metods_access($module_gid){
		$this->CI->db->where('module_gid', $module_gid);
		$this->CI->db->delete(MODULES_METHODS_TABLE);
	}

	function set_module_methods_access($module_gid, $controller, $methods_data){
		if(empty($methods_data)) return;
		foreach($methods_data as $method => $access){
			$data = array(
				"method" => $method,
				"access" => $access,
				"module_gid" => $module_gid,
				"controller" => $controller
			);
			$this->CI->db->insert(MODULES_METHODS_TABLE, $data);
		}
		return;
	}

	////// generate functions
	function generate_install_module_permissions($module_gid){
		$data = $this->get_module_methods($module_gid);
		$return = "<?php\n\n";

		foreach($data as $controller => $method_data){
			ksort($method_data);
			foreach($method_data as $method => $access){
				$return .= '$_permissions["'. $controller .'"]["'. $method .'"] = "'. $access .'";' . "\n";
			}
			$return .= "\n";
		}

/*		$return .= "?>";*/
		return $return;
	}

	function generate_install_module_settings($module_gid){
		$data = $this->return_module_all_config($module_gid);
		$return = "<?php\n\n";

		foreach($data as $gid => $value){
			$return .= '$install_settings["'. $gid .'"] = "'. str_replace('"', '\"', $value) .'";' . "\n";
		}
		$return .= "\n";

/*		$return .= "?>";*/
		return $return;
	}
}
