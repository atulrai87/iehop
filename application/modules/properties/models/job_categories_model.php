<?php
/**
* Job categories model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/


if (!defined('JOB_CATEGORIES_TABLE')) define('JOB_CATEGORIES_TABLE', DB_PREFIX.'job_categories');
class Job_categories_model extends Model {
	private $CI;
	private $DB;
	private $attrs = array('id', 'parent', 'name', 'sorter', 'statistics');

	function __construct()
	{
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;	
	}
	
	public function get_job_categories($parent=0, $lang_id=''){
		if(!$lang_id) $lang_id = $this->pg_language->current_lang_id;
		
		$attrs = $this->attrs;
		if($lang_id){
			$attrs[] = 'lang_' . $lang_id;
		}
		
		$this->DB->select(implode(", ", $attrs))->from(JOB_CATEGORIES_TABLE);
		$this->DB->where('parent', intval($parent));
		$this->DB->order_by("sorter ASC");

		$data = array();
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				if( $lang_id ){
					$r['name'] = $r["lang_".$lang_id];
				}
				$r["statistics"] = unserialize($r["statistics"]);
				$data[] = $r;
			}
		}
		return $data;
	}
	
	public function export_job_categories_langs($lang_ids=array()){
		$attrs = $this->attrs;
		foreach($lang_ids as $lang_id){
			$attrs[] = 'lang_' . $lang_id;
		}
		
		$this->DB->select(implode(", ", $attrs))->from(JOB_CATEGORIES_TABLE);
		$data = array();
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			foreach($results as $r){
				foreach($lang_ids as $lang_id){
					$data[$r["id"]][$lang_id] = $r['lang_' . $lang_id];
				}
			}
		}
		return $data;
	}
	
	public function get_job_category_by_id($id, $lang_id='all'){
		
		if(!is_array($id) && !intval($id)){
			return array();
		}
		if(!is_array($id)){
			$id = array(0 => intval($id));
		}
		if(empty($id)){
			return array();
		}
		
		$attrs = $this->attrs;
		if($lang_id == 'all'){
			foreach($this->CI->pg_language->languages as $lid => $lang){
				$attrs[] = 'lang_'.$lid;
			}
		}else{
			if(!$lang_id) $lang_id = $this->pg_language->current_lang_id;
			$attrs[] = 'lang_' . $lang_id;
		}

		$this->DB->select(implode(", ", $attrs))->from(JOB_CATEGORIES_TABLE)->where_in('id', $id);
		$results = $this->DB->get()->result_array();
		
		$data = array();
		if(!empty($results)){
			foreach($results as $result){
				if($lang_id == 'all'){
					foreach($this->CI->pg_language->languages as $lid => $lang){
						$result['lang_data'][$lid] = $result['lang_'.$lid];
						unset($result['lang_'.$lid]);
					}
				}else{
					$result["name"] = $result['lang_'.$lang_id];
				}
				$result["statistics"] = unserialize($result["statistics"]);
				$data[] = $result;
			}
		}
		return (count($data) == 1)?$data[0]:$data;
	}
	
	public function validate_job_category_langs($langs){
		$return = array();
		foreach($this->CI->pg_language->languages as $lid => $lang){
			$return['lang_'.$lid] = strip_tags($langs[$lid]);
		}
		return $return;
	}
	
	public function get_sorter($parent){
		$this->DB->select('MAX(sorter) as sorter')->from(JOB_CATEGORIES_TABLE);
		$this->DB->where('parent', intval($parent));
		
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			return (intval($results[0]["sorter"]) + 1);
		}
		return 1;
	}
	
	public function save_job_category($id, $data, $langs=null){
		if (!$id){
			$this->DB->insert(JOB_CATEGORIES_TABLE, $data);
			$id = $this->DB->insert_id();
		}else{
			$this->DB->where('id', $id);
			$this->DB->update(JOB_CATEGORIES_TABLE, $data);
		}
		return $id;
	}
	
	public function update_job_statistic($id, $name, $value=''){
		if(!is_array($name)){
			$name = array($name => $value);
		}
		$this->DB->select('statistics')->from(JOB_CATEGORIES_TABLE)->where('id', $id);
		$results = $this->DB->get()->result_array();
		if(!empty($results) && is_array($results)){
			$data["statistics"] = unserialize($results[0]["statistics"]);
			foreach($name as $index => $value){
				$data["statistics"][$index] = intval($value);
			}
			$data["statistics"] = serialize($data["statistics"]);
			$this->DB->where('id', $id);
			$this->DB->update(JOB_CATEGORIES_TABLE, $data);
		}
		return;
	}
	
	public function update_sorter($sorter_array = array()){
		if (!empty($sorter_array)){
			foreach($sorter_array as $sorter => $id){
				$data['sorter'] = $sorter;
				$this->save_job_category($id, $data);
			}
		}
	}

	public function delete_job_category($id){
		///delete all subcategories	
		$this->DB->where("id", $id);
		$this->DB->or_where("parent", $id);
		$this->DB->delete(JOB_CATEGORIES_TABLE);
	}

	public function lang_dedicate_module_callback_add($lang_id = false) {
		if ($lang_id) {
			$this->CI->load->dbforge();
			$fields["lang_" . $lang_id] = array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => FALSE,
			);
			$default_lang_id = $this->CI->pg_language->get_default_lang_id();
			// Add column
			$table_query = $this->CI->db->get(JOB_CATEGORIES_TABLE);
			$exists_fields = $table_query->list_fields();
			$this->CI->dbforge->add_column(JOB_CATEGORIES_TABLE, $fields);
			// Set default names
			if (in_array("lang_" . $default_lang_id, $exists_fields)) {
				$this->CI->db->set('lang_' . $lang_id, 'lang_' . $default_lang_id, false);
			} else {
				$this->CI->db->set('lang_' . $lang_id, 'name', false);
			}
			$this->CI->db->update(JOB_CATEGORIES_TABLE);
			
		}
	}
	
	public function lang_dedicate_module_callback_delete($lang_id = false) {
		if ($lang_id){
			$field_name = "lang_" . $lang_id;
			$this->CI->load->dbforge();
			// Delete column
			$table_query = $this->CI->db->get(JOB_CATEGORIES_TABLE);
			if (in_array("lang_" . $lang_id, $table_query->list_fields())){
				$this->CI->dbforge->drop_column(JOB_CATEGORIES_TABLE, $field_name);
			}
		}
	}
}