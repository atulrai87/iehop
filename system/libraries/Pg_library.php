<?php 

if (!defined('BASEPATH')) exit('No direct script access allowed');

define('LIBRARIES_TABLE', DB_PREFIX.'libraries');

/**
 * PG Libraries Model
 * 
 * @package PG_Core
 * @subpackage Libraries
 * @category libraries
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02  2010) $ $Author: kkashkova $
 */
class CI_Pg_library {

	/**
	 * link to CodeIgniter object
	 * @var object
	 */
	var $CI;

	/**
	 * Libraries cache array
	 * @var array
	 */
	var $libraries=array();

	/**
	 * Constructor
	 *
	 * @return CI_PG_Module Object
	 */
	function CI_Pg_library(){
		$this->CI =& get_instance();
	}

	/**
	 * Get installed libraries data from base, put into the $this->libraries
	 * 
	 */
	function get_libraries(){
		unset($this->libraries);
		$this->libraries = array();

		$this->CI->db->select('id, gid, name, date_add')->from(LIBRARIES_TABLE)->order_by("name DESC");
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			foreach($results as $result){
				$this->libraries[$result["id"]] = $result;
			}
		}
		return $this->libraries;
	}

	/**
	 * Execute get_libraries, if libraries cache not exists
	 * 
	 */
	function return_libraries(){
		if(!isset($this->libraries) || empty($this->libraries)){
			$this->get_libraries();
		}
		return $this->libraries;
	}

	/**
	 * Get module by id
	 * 
	 */
	function get_module_by_id($library_id){
		$libraries = $this->return_libraries();
		return $libraries[$library_id];
	}

	/**
	 * Get module by gid
	 * 
	 */
	function get_library_by_gid($library_gid){
		$libraries = $this->return_libraries();
		foreach($libraries as $library){
			if($library["gid"] == $library_gid){
				return $library;
			}
		}
		return false;
	}

	/**
	 * Get library status
	 *  
	 * @param string $library_gid
	 * @return boolean
	 */
	function is_library_installed($library_gid){
		$this->return_libraries();
		
		if(empty($this->libraries)){
			return false;
		}

		foreach($this->libraries as $id => $library){
			if($library["gid"] == $library_gid){
				return true;
			}
		}
		return false;
	}

	/**
	 * Insert library info into libraries table
	 *  
	 * @param array $data
	 */
	function set_library_install($data){
		$this->CI->db->insert(LIBRARIES_TABLE, $data);
		$this->get_libraries();
		return;
	}

	/**
	 * Insert library info into libraries table
	 *  
	 * @param array $data
	 */
	function set_library_update($library_gid, $data){
		$this->CI->db->where('gid', $library_gid);
		$this->CI->db->update(LIBRARIES_TABLE, $data);
		$this->get_libraries();
		return;
	}

	/**
	 * Delete uninstalled library from table by gid
	 *  
	 * @param string $library_gid
	 */
	function set_library_uninstall($library_gid){
		$this->CI->db->where("gid", $library_gid);
		$this->CI->db->delete(LIBRARIES_TABLE);
		$this->get_libraries();
		return;
	}

}
