<?php
/**
* Themes install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Themes_install_model extends Model
{
	var $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'interface-items' => array(
							'action'=>'none',
							'items' => array(
								'themes_menu_item' => array('action' => 'create', 'link' => 'admin/themes/installed_themes', 'status' => 1, 'sorter' => 3)
							)
						)
					)
				)
			)
		)
	);
	private $ausers_methods = array(
		array('module' => 'themes', 'method' => 'installed_themes', 'is_default' => 1),
		array('module' => 'themes', 'method' => 'enable_themes', 'is_default' => 0),
	);

	private $dynamic_blocks = array(
		array(
			'gid' => 'logo_block',
			'module' => 'themes',
			'model' => 'Themes_model',
			'method' => '_dynamic_block_get_logo_block',
			'params' => array(),
			'views' => array(array('gid'=>'default')),
			'area' => array(
				array(
					'gid' => 'index-page',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lavender',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'girls',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 30,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'girlfriends',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'jewish',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lovers',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'neighbourhood',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'blackonwhite',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 3,
					'cache_time' => 0,
				),
				array(
					'gid' => 'deepblue',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'companions',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'community',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 70,
					'sorter' => 2,
					'cache_time' => 0,
				),
				array(
					'gid' => 'christian',
					'params' => 'a:0:{}',
					'view_str' => 'default',
					'width' => 50,
					'sorter' => 2,
					'cache_time' => 0,
				),
			),
		),
	);
	
	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Themes_install_model()
	{
		parent::Model();
		$this->CI = & get_instance();
	}

	function _validate_requirements(){
		$result = array('data'=>array(), 'result' => true);

		//check for GD library
		$good			= extension_loaded('gd');
		$result["data"][] = array(
			"name" => "GD library (works with graphics and images) is installed",
			"value" => $good?"Yes":"No",
			"result" => $good,
		);
		$result["result"] = $result["result"] && $good;

		return $result;
	}

	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('themes', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( "menu" => $return );
	}

	public function deinstall_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			if($menu_data['action'] == 'create'){
				linked_install_set_menu($gid, 'delete');
			}else{
				linked_install_delete_menu_items($gid, $this->menu[$gid]['items']);
			}
		}
	}

	/**
	 * Ausers module methods
	 */
	public function install_ausers() {
		// install ausers permissions
		$this->CI->load->model('ausers/models/Ausers_model');

		foreach($this->ausers_methods as $method){
			$this->CI->Ausers_model->save_method(null, $method);
		}
	}

	public function install_ausers_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('themes', 'ausers', $langs_ids);

		// install ausers permissions
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'themes';
		$methods = $this->CI->Ausers_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Ausers_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_ausers_lang_export($langs_ids) {
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'themes';
		$methods =  $this->CI->Ausers_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('ausers' => $return);
	}

	public function deinstall_ausers() {
		// delete moderation methods in ausers
		$this->CI->load->model('ausers/models/Ausers_model');
		$params['where']['module'] = 'themes';
		$this->CI->Ausers_model->delete_methods($params);
	}

	function _arbitrary_installing(){
	}

	function _arbitrary_deinstalling(){
	}

	/*
	* Dynamic blocks methods
	*
	*/
	public function install_dynamic_blocks() {
		$this->CI->load->model('Dynamic_blocks_model');

		foreach($this->dynamic_blocks as $block_data){
			$validate_data = $this->CI->Dynamic_blocks_model->validate_block(null, $block_data);
			if(!empty($validate_data['errors'])) {
				continue;
			}
			$id_block = $this->CI->Dynamic_blocks_model->save_block(null, $validate_data['data']);

			if(!empty($block_data['area'])) {
				foreach($block_data['area'] as $block_area) {
					$area = $this->CI->Dynamic_blocks_model->get_area_by_gid($block_area['gid']);
					$area_data = array(
						'id_area' => $area['id'],
						'id_block' => $id_block,
						'params' => $block_area['params'],
						'view_str' => $block_area['view_str'],
						'cache_time' => $block_area['cache_time'],
						'sorter' => $block_area['sorter'],
						'width' => $block_area['width'],
					);
					$validate_data = $this->CI->Dynamic_blocks_model->validate_area_block($area_data, true);
					if(!empty($validate_data['errors'])) {
						continue;
					}
					$this->CI->Dynamic_blocks_model->save_area_block(null, $validate_data['data']);
				}
			}
		}
	}

	public function install_dynamic_blocks_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$langs_file = $this->CI->Install_model->language_file_read('themes', 'dynamic_blocks', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty dynamic_blocks langs data'); return false; }
		
		$this->CI->load->model('Dynamic_blocks_model');
		$data = array();
		foreach($this->dynamic_blocks as $block_data){
			if($block = $this->CI->Dynamic_blocks_model->get_block_by_gid($block_data['gid'])){
				$data[] = $block;
			}
		}
		$this->CI->Dynamic_blocks_model->update_langs($data, $langs_file, $langs_ids);
	}

	public function install_dynamic_blocks_lang_export($langs_ids = null) {
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		$this->CI->load->model('Dynamic_blocks_model');
		$data = array();
		foreach($this->dynamic_blocks as $block_data){
			if($block = $this->CI->Dynamic_blocks_model->get_block_by_gid($block_data["gid"])){
				$data[] = $block;
			}
		}
		$langs = $this->CI->Dynamic_blocks_model->export_langs($data, $langs_ids);
		return array("dynamic_blocks" => $langs);
	}

	public function deinstall_dynamic_blocks(){
		$this->CI->load->model('Dynamic_blocks_model');
		foreach($this->dynamic_blocks as $block) {
			$this->CI->Dynamic_blocks_model->delete_block_by_gid($block['gid']);
		}
	}
	
}