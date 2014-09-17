<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('LANG_PAGES_TABLE', DB_PREFIX.'lang_pages');

/**
 * Base languages pages driver
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

	function lang_pages_driver(){
		$this->CI =& get_instance();
	}

	function get_module($module_gid, $lang_id){	//// get all module strings from base and put it to cache ($modules_data)
		$lang_value="value_".$lang_id;
		$this->CI->db->select('gid, '.$lang_value)->from(LANG_PAGES_TABLE)->where('module_gid', $module_gid);
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			$return = array();
			foreach($results as $result){
				$return[$result["gid"]] = (!empty($result[$lang_value]))?$result[$lang_value]:"";
			}
			return $return;
		}else
			return false;
	}

	function set_module_strings($module_gid, $strings_data, $lang_id){
		$lang_value="value_".$lang_id;
		$module_lang = $this->get_module($module_gid, $lang_id);
		foreach($strings_data as $gid=>$value){
			if(isset($module_lang[$gid])){
				if($module_lang[$gid] != $value){
					$this->CI->db->where('module_gid', $module_gid);
					$this->CI->db->where('gid', $gid);
					$this->CI->db->update(LANG_PAGES_TABLE, array($lang_value=>strval($value)));
				}
			}else{
				$data = array(
					'module_gid' => $module_gid,
					'gid' => $gid,
					$lang_value => strval($value),
				);
				$this->CI->db->insert(LANG_PAGES_TABLE, $data); 
			}
		}
		return;
	}

	function delete_module_strings($module_gid, $gids){
		foreach($gids as $gid){
			$this->CI->db->where('module_gid', $module_gid);
			$this->CI->db->where('gid', $gid);
			$this->CI->db->delete(LANG_PAGES_TABLE); 
		}
		return;
	}

	function delete_module($module_gid){
		$this->CI->db->where('module_gid', $module_gid);
		$this->CI->db->delete(LANG_PAGES_TABLE); 
		return;
	}

	function add_language($lang_id){
		////// add field to base
		if (!$this->CI->db->table_exists(LANG_PAGES_TABLE)){
			$this->create_table();
		}
		
		$field_name = "value_".$lang_id;
		if(!$this->CI->db->field_exists($field_name, LANG_PAGES_TABLE)){
			$this->CI->load->dbforge();
			$fields = array(
				$field_name => array('type' => 'TEXT', 'null' => FALSE)
			);
			$this->CI->dbforge->add_column(LANG_PAGES_TABLE, $fields);
		}
		return;
	}

	function delete_language($lang_id){
		////// delete field from base

		if (!$this->CI->db->table_exists(LANG_PAGES_TABLE)){
			$this->create_table();
		}
		
		$field_name = "value_".$lang_id;
		if($this->CI->db->field_exists($field_name, LANG_PAGES_TABLE)){
			$this->CI->load->dbforge();
			$this->CI->dbforge->drop_column(LANG_PAGES_TABLE, $field_name);
		}
		return;
	}

	function copy_language($lang_from, $lang_to){
		$field_name_from = "value_".$lang_from;
		$field_name_to = "value_".$lang_to;
		$this->CI->db->query('UPDATE '.LANG_PAGES_TABLE.' SET '.$field_name_to.'='.$field_name_from);
		return;
	}

	function create_table(){
		$this->CI->load->dbforge();

		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 3,
				'null' => FALSE,
				'auto_increment' => TRUE
			),
			'module_gid' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => FALSE,
			),
			'gid' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => FALSE,
			)
		);

		$this->CI->dbforge->add_field($fields);
		$this->CI->dbforge->add_key('id', TRUE);
		$this->CI->dbforge->add_key('module_gid');
		$this->CI->dbforge->create_table(LANG_PAGES_TABLE);
		return;
	}
}
