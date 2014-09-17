<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('LANG_DS_TABLE', DB_PREFIX.'lang_ds');

/**
 * Base languages datasource driver
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

	function lang_ds_driver(){
		$this->CI =& get_instance();
	}

	function get_module($module_gid, $lang_id){	//// get all module strings from base and put it to cache ($modules_data)
		$lang_value="value_".$lang_id;
		$this->CI->db->select('id, gid, option_gid, type, sorter, '.$lang_value)->from(LANG_DS_TABLE)->where('module_gid', $module_gid)->order_by("sorter ASC");
		$results = $this->CI->db->get()->result_array();
		if(!empty($results)){
			$return = array();
			foreach($results as $result){
				switch($result["type"]){
					case "header": $return[$result["gid"]]["header"] = $result[$lang_value]; break;
					case "option": $return[$result["gid"]]["option"][$result["option_gid"]] = $result[$lang_value]; break;
				}
			}

			return $return;
		}else
			return false;
	}

	function set_module_reference($module_gid, $gid, $data, $lang_id){
		$lang_value="value_".$lang_id;
		$module_lang = $this->get_module($module_gid, $lang_id);
		
		if(isset($module_lang[$gid])){
			/// update header
			$this->CI->db->where('module_gid', $module_gid);
			$this->CI->db->where('gid', $gid);
			$this->CI->db->where('type', "header");
			$this->CI->db->update(LANG_DS_TABLE, array($lang_value=>$data["header"]));

			//// adding options
			if(isset($data["option"])){
				$i = 1;
				foreach($data["option"] as $option_gid => $option_value){

					if(isset($module_lang[$gid]["option"][$option_gid])){
						// update
						$lang_ds_data = array(
							'sorter' => $i,
							$lang_value => strval($option_value)
						);
						$this->CI->db->where('type', 'option');
						$this->CI->db->where('module_gid', $module_gid);
						$this->CI->db->where('option_gid', $option_gid);
						$this->CI->db->where('gid', $gid);
						$this->CI->db->update(LANG_DS_TABLE, $lang_ds_data); 
					}else{
						// insert
						$lang_ds_data = array(
							'module_gid' => $module_gid,
							'gid' => $gid,
							'type' => "option",
							'sorter' => $i,
							'option_gid' => $option_gid,
							$lang_value => strval($option_value)
						);
						$this->CI->db->insert(LANG_DS_TABLE, $lang_ds_data); 
					}
					$i++;
				}
			}

			//// deleteing not used options
			if(isset($module_lang[$gid]["option"])){
				foreach($module_lang[$gid]["option"] as $option_gid => $option_value){
					if(!isset($data["option"][$option_gid])){
						$this->CI->db->where('type', 'option');
						$this->CI->db->where('option_gid', $option_gid);
						$this->CI->db->where('gid', $gid);
						$this->CI->db->where('module_gid', $module_gid);
						$this->CI->db->delete(LANG_DS_TABLE); 
					}
				}
			}
		}else{
			if(!$data["header"]) $data["header"] = "";
			/// insert header
			$lang_ds_data = array(
				'module_gid' => $module_gid,
				'gid' => $gid,
				'type' => "header",
				'sorter' => 0,
				$lang_value => $data["header"]
			);
			$this->CI->db->insert(LANG_DS_TABLE, $lang_ds_data); 

			if(isset($data["option"])){
				$i = 1;
				foreach($data["option"] as $option_gid => $option_value){
					if(!$option_value) $option_value = "";
					$lang_ds_data = array(
						'module_gid' => $module_gid,
						'gid' => $gid,
						'type' => "option",
						'sorter' => $i,
						'option_gid' => $option_gid,
						$lang_value => $option_value
					);
					$this->CI->db->insert(LANG_DS_TABLE, $lang_ds_data); 
					$i++;
				}
			}
		}

		return;
	}

	function set_reference_sorter($module_gid, $gid, $sorter_data, $languages){
		if(empty($sorter_data)) return false;
		
		$i = 1;
		foreach($sorter_data AS $index => $option_gid){
			$update['sorter'] = $i;
			$this->CI->db->where('type', 'option');
			$this->CI->db->where('option_gid', $option_gid);
			$this->CI->db->where('module_gid', $module_gid);
			$this->CI->db->where('gid', $gid);
			$this->CI->db->update(LANG_DS_TABLE, $update); 
			$i++;
		}
	}

	function delete_module_reference($module_gid, $gids){
		foreach($gids as $gid){
			$this->CI->db->where('module_gid', $module_gid);
			$this->CI->db->where('gid', $gid);
			$this->CI->db->delete(LANG_DS_TABLE); 
		}
		return;
	}

	function delete_module($module_gid){
		$this->CI->db->where('module_gid', $module_gid);
		$this->CI->db->delete(LANG_DS_TABLE); 
		return;
	}
	function add_language($lang_id){
		////// add field to base
		if (!$this->CI->db->table_exists(LANG_DS_TABLE)){
			$this->create_table();
		}
		
		$field_name = "value_".$lang_id;
		if(!$this->CI->db->field_exists($field_name, LANG_DS_TABLE)){
			$this->CI->load->dbforge();
			$fields = array(
				$field_name => array('type' => 'TEXT', 'null' => FALSE)
			);
			$this->CI->dbforge->add_column(LANG_DS_TABLE, $fields);
		}
		return;
	}

	function delete_language($lang_id){
		////// delete field from base

		if (!$this->CI->db->table_exists(LANG_DS_TABLE)){
			$this->create_table();
		}
		
		$field_name = "value_".$lang_id;
		if($this->CI->db->field_exists($field_name, LANG_DS_TABLE)){
			$this->CI->load->dbforge();
			$this->CI->dbforge->drop_column(LANG_DS_TABLE, $field_name);
		}
		return;
	}

	function copy_language($lang_from, $lang_to){
		$field_name_from = "value_".$lang_from;
		$field_name_to = "value_".$lang_to;
		$this->CI->db->query('UPDATE '.LANG_DS_TABLE.' SET '.$field_name_to.'='.$field_name_from);
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
			),
			'option_gid' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'null' => FALSE,
			),
			'type' => array(
				'type' => 'ENUM',
				'constraint' => "'header', 'option'",
				'null' => FALSE,
				'default' => 'option',
			),
			'sorter' => array(
				'type' => 'TINYINT',
				'constraint' => 3,
				'null' => FALSE,
			),
		);

		$this->CI->dbforge->add_field($fields);
		$this->CI->dbforge->add_key('id', TRUE);
		$this->CI->dbforge->add_key('module_gid');
		$this->CI->dbforge->add_key('sorter');
		$this->CI->dbforge->create_table(LANG_DS_TABLE);
		return;
	}
}
