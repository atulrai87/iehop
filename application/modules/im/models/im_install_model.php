<?php
/**
* IM install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 2 $ $Date: 2013-01-30 10:50:07 +0400 $
**/
class Im_install_model extends Model
{
	private $CI;
	
	private $lang_services = array(
		'service' => array('im'),
		'template' => array('im_template'),
		'param' => array(
			'im_template' => array('period'),
		),
	);


	public function __construct(){
		parent::Model();
		$this->CI = & get_instance();
	}
	
	/**
	 * Check system requirements of module
	 */
	public function _validate_requirements(){
		$result = array("data"=>array(), "result" => true);

		//check for Mbstring
		$good			= function_exists("mb_substr");
		$result["data"][] = array(
			"name" => "Mbstring extension (required for feeds parsing) is installed",
			"value" => $good?"Yes":"No",
			"result" => $good,
		);
		$result["result"] = $result["result"] && $good;

		return $result;
	}
	
	public function install_users(){
		$this->CI->load->model('users/models/Users_statuses_model');
		$this->CI->Users_statuses_model->add_callback('im', 'im_contact_list_model', 'callback_update_contacts_statuses');
	}

	public function deinstall_users(){
		$this->CI->load->model('users/models/Users_statuses_model');
		$this->CI->Users_statuses_model->delete_callbacks_by_module('im');
	}

	public function install_users_lists(){
		$this->CI->load->model('users_lists/models/Users_lists_callbacks_model');
		$this->CI->Users_lists_callbacks_model->add_callback('im', 'im_contact_list_model', 'callback_update_contact_list');
		$this->CI->load->model('im/models/Im_contact_list_model');
		$this->CI->Im_contact_list_model->_import_friendlist();
	}
	
	public function deinstall_users_lists(){
		$this->CI->load->model('users_lists/models/Users_lists_callbacks_model');
		$this->CI->Users_lists_callbacks_model->delete_callbacks_by_module('im');
	}
	
	public function install_services () {
		// add service type and service
		// create service template and service
		$this->CI->load->model('Services_model');
		$template_data = array(
			'gid' => 'im_template',
			'callback_module' => 'im',
			'callback_model' => 'Im_model',
			'callback_buy_method' => 'service_buy_im',
			'callback_activate_method' => 'service_activate_im',
			'callback_validate_method' => 'service_validate_im',
			'price_type' => 1,
			'data_admin' => serialize(array('period' => 'int')),
			'data_user'=> '',
			'date_add' => date('Y-m-d H:i:s'),
			'moveable' => 0,
			'alert_activate' => 0,
		);
		$this->CI->Services_model->save_template(null, $template_data);

		$service_data = array(
			'gid' => 'im',
			'template_gid' => 'im_template',
			'pay_type' => 2,
			'status' => 1,
			'price' => 10,
			'data_admin' => serialize(array('period' => '30')),
			'date_add' => date('Y-m-d H:i:s')
		);
		$this->CI->Services_model->save_service(null, $service_data);
	}

	public function install_services_lang_update($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Services_model');
		$langs_file = $this->CI->Install_model->language_file_read('im', 'services', $langs_ids);
		$this->CI->Services_model->update_langs($this->lang_services, $langs_file);
		return true;
	}

	public function install_services_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Services_model');
		return array('services' => $this->CI->Services_model->export_langs($this->lang_services, $langs_ids));
	}
	
	public function deinstall_services () {
		$this->CI->load->model("Services_model");
		$this->CI->Services_model->delete_template_by_gid('im_template');
		$this->CI->Services_model->delete_service_by_gid('im');
	}

	public function _arbitrary_installing(){

	}

	public function _arbitrary_deinstalling(){
		
	}
}