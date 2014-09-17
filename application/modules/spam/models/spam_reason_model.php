<?php

/**
 * Spam reason model
 * 
 * @package PG_RealEstate
 * @subpackage Spam
 * @category	models
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Katya Kashkova <katya@pilotgroup.net>
 * @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
 **/
class Spam_reason_model extends Model{
	
	/**
	 * link to CodeIgniter object
	 * @var object
	 */
	private $CI;

	/**
	 * link to DataBase object
	 * @var object
	 */
	private $DB;
	
	/**
	 * 
	 * @var array
	 */
	private $attrs = array();
	
	/**
	 * 
	 * @var array
	 */
	public $content = array("spam_object");
	
	/**
	 * Module GID
	 * @var string
	 */
	public $module_gid = "spam";

	/**
	 * Constructor
	 */
	function __construct(){
		parent::Model();
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}
	
	/**
	 * Return reason by gid
	 *  
	 * @param integer $lang_id
	 * @return string
	 */
	public function get_reason($lang_id = null){
		if(!$lang_id) $lang_id = $this->CI->session->userdata("lang_id");
		return $this->pg_language->ds->get_reference($this->module_gid, "spam_object", $lang_id);
	}
	
	/**
	 * Validate data
	 * @param string $option_gid
	 * @param array $langs
	 * @return array
	 */
	public function validate_reason($option_gid, $langs){
		$return = array("errors"=> array(), 'langs' => array());
		
		if(!empty($langs)){
			foreach($this->pg_language->languages as $lid => $lang_data){
				if(!isset($langs[$lid])){
					$return['errors'][] = l('error_empty_reason_name', "spam");
					break;
				}else{
					$return["langs"][$lid] = trim(strip_tags($langs[$lid]));
					if(empty($return["langs"][$lid])){
						$return['errors'][] = l('error_empty_reason_name', "spam");
						break;
					}
				}
			}
		}
		
		return $return;
	}
	
	/**
	 * Save data
	 * @param string $option_gid
	 * @param array $langs
	 * @return array
	 */
	public function save_reason($option_gid, $langs_data){
		if(empty($option_gid)){
			$reference = $this->pg_language->ds->get_reference($this->module_gid, $this->content[0], $this->CI->pg_language->current_lang_id);
			if(!empty($reference["option"])){
				$array_keys = array_keys($reference["option"]);
			}else{
				$array_keys = array(0);
			}
			$option_gid = max($array_keys) + 1;
		}
		
		foreach($langs_data as $lid => $string){
			$reference = $this->CI->pg_language->ds->get_reference($this->module_gid, $this->content[0], $lid);
			$reference["option"][$option_gid] = $string;
			$this->CI->pg_language->ds->set_module_reference($this->module_gid, $this->content[0], $reference, $lid);
		}
	}
}
