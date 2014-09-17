<?php

/**
 * Mobile version API controller
 *
 * @package PG_Dating
 * @subpackage application
 * @category	modules
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Dmitry Popenov
 * @version $Revision: 1 $ $Date: 2013-12-02 14:53:00 +0300 $ $Author: dpopenov $
 * */
Class Api_Mobile extends Controller {

	public function __construct() {
		parent::Controller();
	}

	private function save_lang($lang_id = null) {
		if (!$lang_id) {
			$lang_id = $this->pg_language->get_default_lang_id();
		}
		$this->session->set_userdata('lang_id', $lang_id);
		$this->session->sess_update();
	}

	private function get_css_url() {
		$theme_settings = $this->pg_theme->return_active_settings($this->pg_theme->get_current_theme_type());
		return $this->pg_theme->theme_default_url . $theme_settings["theme"] . '/sets/' . $theme_settings["scheme"] . '/css/';
	}

	private function get_logo_path() {
		$theme_data = $this->pg_theme->format_theme_settings($this->router->class);
		return $theme_data['mini_logo']['path'];
	}

	public function init($lang_id = null) {
		$this->load->model('Users_model');
		$this->load->model('Properties_model');
		$this->load->model("payments/models/Payment_currency_model");

		$lang_id = $this->save_lang($lang_id);
		$user_id = intval($this->session->userdata('user_id'));

		$data = array(
			'data' => array(
				'cssUrl' => $this->get_css_url(),
				'siteUrl' => site_url(),
				'logo' => $this->get_logo_path()
			),
			'l' => $this->pg_language->pages->return_module('mobile', $lang_id),
			'language' => $this->pg_language->get_lang_by_id($lang_id),
			'languages' => $this->pg_language->languages,
			'userData' => $this->Users_model->get_user_by_id($user_id, true, false),
			'properties' => array(
				'userTypes' => $this->Properties_model->get_property('user_type', $lang_id),
				'age' => array(
					'min' => $this->pg_module->get_module_config('users', 'age_min'),
					'max' => $this->pg_module->get_module_config('users', 'age_max')
				),
				'currency' => $this->Payment_currency_model->default_currency
			)
		);
		$this->set_api_content('data', $data);
	}

	public function change_lang() {
		$lang_id = filter_input(INPUT_POST, 'lang_id');
		if (!$lang_id) {
			log_message('error', 'languages API: Empty lang id');
			$this->set_api_content('error', l('api_error_empty_lang_id', 'languages'));
			return false;
		}
		$this->load->model('Properties_model');
		$this->load->model('Users_model');

		$save_data["lang_id"] = $lang_id;
		$this->Users_model->save_user(intval($this->session->userdata('user_id')), $save_data);

		$this->session->set_userdata('lang_id', $lang_id);
		$this->session->sess_update();
		$this->set_api_content('data', array(
			'language' => $this->pg_language->get_lang_by_id($lang_id),
			'l' => $this->pg_language->pages->return_module('mobile', $lang_id),
			'properties' => array(
				'userTypes' => $this->Properties_model->get_property('user_type', $lang_id)
			)
		));
	}

	public function get_config() {
		// TODO: $this->pg_module->get_module_config($module, $gid);
	}

}
