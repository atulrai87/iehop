<?php
/**
* Mobile version controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Dmitry Popenov
* @version $Revision: 1 $ $Date: 2013-12-02 14:53:00 +0300 $ $Author: dpopenov $
**/

Class Mobile extends Controller {

	public function __construct() {
		parent::Controller();
	}
	
	public function index(){
		
	}

	public function init($lang_id = ''){
		$data['l'] = $this->pg_language->pages->return_module('mobile', $lang_id);
		$theme_data = $this->pg_theme->format_theme_settings($this->router->class);
		$data['data']['site_url'] = site_url();
		$data['data']['logo'] = $theme_data['mini_logo']['path'];
		exit(json_encode($data));
	}
}
