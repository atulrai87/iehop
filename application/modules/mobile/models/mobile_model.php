<?php
/**
* Mobile main model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 1 $ $Date: 2013-12-02 14:53:00 +0300 $ $Author: dpopenov $
**/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mobile_model extends Model {

	public $CI;
	public $DB;

	public function __construct() {
		$this->CI = & get_instance();
		$this->DB = &$this->CI->db;
	}

	/**
	 * Backend method to get menu indicators.
	 *
	 * @param array $params
	 * @return array
	 */
	public function backend_get_indicators($params) {
		$this->id_user = $params['id_user'];
		$results = array();
		foreach($params['indicators'] as $indicator) {
			if(method_exists($this, $indicator)) {
				// TODO: refactor to use modules own methods
				$results[$indicator] = $this->$indicator();
			}
		}
		return $results;
	}

	private function new_messages() {
		$this->CI->load->model('im/models/Im_messages_model');
		return $this->CI->Im_messages_model->get_unread_count($this->id_user, 'i');
	}
	
	private function new_friends() {
		$this->CI->load->model('Users_lists_model');
		return $this->CI->Users_lists_model->get_list_count($this->id_user, 'request_in');
	}

}
