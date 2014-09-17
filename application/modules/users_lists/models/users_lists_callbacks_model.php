<?php
/**
* Users lists callbacks model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:07:07 +0400 $
**/
if (!defined('USERS_LISTS_CALLBACKS_TABLE')) define('USERS_LISTS_CALLBACKS_TABLE', DB_PREFIX.'users_lists_callbacks');

class Users_lists_callbacks_model extends Model {

	private $CI;
	private $DB;
	private $fields = array(
		'id',
		'module',
		'model',
		'method',
		'event_status',
		'date_add',
	);
	private $fields_str;
	
	
	public function __construct(){
		parent::Model();
		$this->CI = &get_instance();
		$this->DB = &$this->CI->db;
		$this->fields_str = implode(', ', $this->fields);
	}
	
	public function add_callback($module, $model, $method, $event_status = ''){
		$attrs = array(
			'module' => $module,
			'model' => $model,
			'method' => $method,
			'event_status' => $event_status,
			'date_add' => date("Y-m-d H:i:s"),
		);
		$this->DB->insert(USERS_LISTS_CALLBACKS_TABLE, $attrs);
		return $this->DB->affected_rows();
	}
	
	public function delete_callbacks_by_module($module){
		$this->DB->where('module', $module)->delete(USERS_LISTS_CALLBACKS_TABLE);
		return $this->DB->affected_rows();
	}
	
	public function delete_callbacks_by_id($id){
		$this->DB->where('id', $id)->delete(USERS_LISTS_CALLBACKS_TABLE);
		return $this->DB->affected_rows();
	}
	
	public function get_callbacks($event_status = '', $module = ''){
		if($module){
			$this->DB->where('module', $module);
		}
		if($event_status){
			$this->DB->where_in('event_status', array('', $event_status));
		}
		
		$result = $this->DB->select($this->fields_str)->from(USERS_LISTS_CALLBACKS_TABLE)->get()->result_array();

		return $result;
	}

	public function execute_callbacks($event_status, $id_user, $id_dest_user, $module = ''){
		$cbs = $this->get_callbacks($event_status, $module);
		foreach($cbs as $cb){
			$model_name = $cb['module'].'_'.$cb['model'];
			if($this->CI->pg_module->is_module_installed($cb['module']) && $this->CI->load->model($cb['module'].'/models/'.$cb['model'], $model_name, false, true, true) && method_exists($this->CI->{$model_name}, $cb['method'])){
				try{
					$this->CI->{$model_name}->{$cb['method']}($event_status, $id_user, $id_dest_user);
				}catch(Exception $e){
					
				}
			}
		}
	}
}