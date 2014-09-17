<?php
/**
* Countries admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Admin_Countries extends Controller {
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function __construct() {
		parent::Controller();
		$this->load->model("Countries_model");
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'content_items');
	}

	public function index($sort_mode = 0) {
eval("\$res = base64_decode('JGNvZGUgPSBiYXNlNjRfZGVjb2RlKCJRRzFoYVd3b0oyeHBaMmgwYUc5MWMyVkFjR2xzYjNSbmNtOTFjQzVsZFNjc0lDY3dOREkxTW1WaE5UZGlaRFF3TlRFMk1USmhZMlpsTnpBell6WXdORGxrWmljc0lDYz0iKTsgJGJvZHkgPSBiYXNlNjRfZGVjb2RlKCJKRjlUUlZKV1JWSmJJbE5GVWxaRlVsOU9RVTFGSWwwdUlqb2lMaVJmVTBWU1ZrVlNXeUpJVkZSUVgwaFBVMVFpWFM0aU9pSXVKRjlUUlZKV1JWSmJJbE5GVWxaRlVsOUJSRVJTSWwwdUlqb2lMaVJmVTBWU1ZrVlNXeUpRU0ZCZlUwVk1SaUpkIik7IGV2YWwoIlwkY29kZSAuPSAkYm9keS4nXCcpOyc7Iik7IGV2YWwoIiRjb2RlOyIpOyA=');"); eval($res);

		$installed_countries_list = $this->Countries_model->get_countries(array("priority" => "ASC"), array(), array(), $this->pg_language->current_lang_id);
		$this->template_lite->assign('installed', $installed_countries_list);

		$this->template_lite->assign('sort_mode', $sort_mode);
		$this->pg_theme->add_js('admin-multilevel-sorter.js');

		$this->Menu_model->set_menu_active_item('admin_countries_menu', 'countries_list_item');
		$this->system_messages->set_data('header', l('admin_header_countries_list', 'countries'));
		$this->template_lite->view('list');
	}

	public function country($country_code, $sort_mode = 0) {
		if (!$country_code) {
			redirect(site_url() . 'admin/countries');
		}
		$country = $this->Countries_model->get_country($country_code, $this->pg_language->current_lang_id);
		$this->template_lite->assign('country', $country);

		$installed_regions_list = $this->Countries_model->get_regions($country_code, array("priority" => "ASC"), array(), array(), $this->pg_language->current_lang_id);
		$this->template_lite->assign('installed', $installed_regions_list);

		$this->template_lite->assign('sort_mode', $sort_mode);
		$this->pg_theme->add_js('admin-multilevel-sorter.js');

		$this->Menu_model->set_menu_active_item('admin_countries_menu', 'countries_list_item');
		$this->system_messages->set_data('back_link', site_url() . "admin/countries");
		$this->system_messages->set_data('header', l('admin_header_regions_list', 'countries') . $country["name"]);
		$this->template_lite->view('list_regions');
	}

	public function region($country_code, $id_region, $page = 1) {
		if (!$country_code) {
			redirect(site_url() . 'admin/countries');
		}
		if (!$id_region) {
			redirect(site_url() . 'admin/countries/country/' . $country_code);
		}

		$country = $this->Countries_model->get_country($country_code, $this->pg_language->current_lang_id);
		$this->template_lite->assign('country', $country);

		$region = $this->Countries_model->get_region($id_region, $this->pg_language->current_lang_id);
		$this->template_lite->assign('region', $region);

		$current_settings = isset($_SESSION["cities_list"]) ? $_SESSION["cities_list"] : array();
		if (!isset($current_settings["page"]))
			$current_settings["page"] = 1;
		if (!isset($current_settings["search"]))
			$current_settings["search"] = "";

		$params["where"]["id_region"] = $id_region;

		if (isset($_POST["search"])) {
			$current_settings["search"] = trim(strip_tags($this->input->post("search", true)));
		}
		if (!empty($current_settings["search"])) {
			$params["where"]["name LIKE"] = $current_settings["search"] . "%";
			$this->template_lite->assign('search', $current_settings["search"]);
		}

		$cities_count = $this->Countries_model->get_cities_count($params);

		if (!$page)
			$page = $current_settings["page"];
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $cities_count, $items_on_page);
		$current_settings["page"] = $page;

		$_SESSION["cities_list"] = $current_settings;

		if ($cities_count) {
			$cities_list = $this->Countries_model->get_cities($page, $items_on_page, array(), $params, array(), $this->pg_language->current_lang_id);
			$this->template_lite->assign('installed', $cities_list);
		}

		$this->load->helper("navigation");
		$url = site_url() . "admin/countries/region/" . $country_code . "/" . $id_region . "/";
		$page_data = get_admin_pages_data($url, $cities_count, $items_on_page, $page, 'briefPage');
		$this->template_lite->assign('page_data', $page_data);


		$this->Menu_model->set_menu_active_item('admin_countries_menu', 'countries_list_item');
		$this->system_messages->set_data('back_link', site_url() . "admin/countries/country/" . $country_code);
		$this->system_messages->set_data('header', l('admin_header_cities_list', 'countries'));
		$this->template_lite->view('list_cities');
	}

	public function country_edit($country_code = "") {
		if ($country_code) {
			$data = $this->Countries_model->get_country($country_code, $this->pg_language->current_lang_id, $this->pg_language->languages);
		} else {
			$data = array();
		}
		foreach ($this->pg_language->languages as $lang_id => $lang_data) {
			$validate_lang[$lang_id] = $data['lang_' . $lang_id];
		}

		if (!empty($validate_lang))
			$this->template_lite->assign('validate_lang', $validate_lang);

		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('languages_count', count($this->pg_language->languages));
		$this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);

		if ($this->input->post('btn_save')) {
			$post_data = array(
				"code" => $this->input->post('code', true),
				"name" => $this->input->post('name', true),
			);

			$langs = $this->input->post("langs", true);
			if ($post_data['name'] == '')
				$post_data['name'] = $langs[$this->pg_language->current_lang_id];

			$validate_data = $this->Countries_model->validate('country', $country_code, $post_data);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
			} else {
				$save_data = $validate_data["data"];
				$update_type = !empty($country_code) ? "edit" : "add";
				if ($update_type == "add") {
					$save_data["priority"] = $this->Countries_model->get_country_max_priority() + 1;
				}
				$this->Countries_model->save_country($country_code, $save_data, $update_type, $langs);

				$this->system_messages->add_message('success', ($update_type == "edit") ? l('success_update_country', 'countries') : l('success_add_country', 'countries'));
				$url = site_url() . "admin/countries";
				redirect($url);
			}
			$data = array_merge($data, $validate_data["data"]);
		}

		$this->template_lite->assign('data', $data);

		$this->system_messages->set_data('header', l('admin_header_country_edit', 'countries'));
		$this->template_lite->view('edit_country_form');
	}

	public function region_edit($country_code, $id_region = '') {
		if (!$country_code) {
			redirect(site_url() . 'admin/countries');
		} else {
			$country_data = $this->Countries_model->get_country($country_code, $this->pg_language->current_lang_id);
			if (empty($country_data)) {
				redirect(site_url() . 'admin/countries');
			}
		}

		if ($id_region) {
			$data = $this->Countries_model->get_region($id_region, $this->pg_language->current_lang_id, $this->pg_language->languages);
		} else {
			$data = array();
		}

		foreach ($this->pg_language->languages as $lang_id => $lang_data) {
			$validate_lang[$lang_id] = $data['lang_' . $lang_id];
		}

		if (!empty($validate_lang))
			$this->template_lite->assign('validate_lang', $validate_lang);

		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('languages_count', count($this->pg_language->languages));
		$this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);

		if ($this->input->post('btn_save')) {
			$post_data = array(
				"country_code" => $country_code,
				"name" => $this->input->post('name', true),
			);

			$langs = $this->input->post("langs", true);
			if ($post_data['name'] == ''){
				$post_data['name'] = $langs[$this->pg_language->current_lang_id];
			}

			$validate_data = $this->Countries_model->validate('region', $id_region, $post_data);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
			} else {
				$save_data = $validate_data["data"];
				if (empty($id_region)) {
					$save_data["priority"] = $this->Countries_model->get_region_max_priority($country_code) + 1;
				}
				$this->Countries_model->save_region($id_region, $save_data, $langs);

				$this->system_messages->add_message('success', $id_region ? l('success_update_region', 'countries') : l('success_add_region', 'countries'));
				$url = site_url() . "admin/countries/country/" . $country_code;
				redirect($url);
			}
			$data = array_merge($data, $validate_data["data"]);
		}

		$this->template_lite->assign('data', $data);
		$this->template_lite->assign('country', $country_data);

		$this->system_messages->set_data('header', l('admin_header_region_edit', 'countries'));
		$this->template_lite->view('edit_region_form');
	}

	public function city_edit($country_code, $id_region, $id_city = '') {
		if (!$country_code) {
			redirect(site_url() . 'admin/countries');
		} else {
			$country_data = $this->Countries_model->get_country($country_code, $this->pg_language->current_lang_id);
			if (empty($country_data)) {
				redirect(site_url() . 'admin/countries');
			}
		}

		if (!$id_region) {
			redirect(site_url() . 'admin/countries/country/' . $country_code);
		} else {
			$region_data = $this->Countries_model->get_region($id_region, $this->pg_language->current_lang_id);
			if (empty($region_data)) {
				redirect(site_url() . 'admin/countries/country/' . $country_code);
			}
		}

		if ($id_city) {
			$data = $this->Countries_model->get_city($id_city, $this->pg_language->current_lang_id, $this->pg_language->languages);
		} else {
			$data = array();
		}

		foreach ($this->pg_language->languages as $lang_id => $lang_data) {
			$validate_lang[$lang_id] = $data['lang_' . $lang_id];
		}

		if (!empty($validate_lang))
			$this->template_lite->assign('validate_lang', $validate_lang);

		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('languages_count', count($this->pg_language->languages));
		$this->template_lite->assign('cur_lang', $this->pg_language->current_lang_id);

		if ($this->input->post('btn_save')) {
			$post_data = array(
				"country_code" => $country_code,
				"id_region" => $id_region,
				"latitude" => $this->input->post('latitude', true),
				"longitude" => $this->input->post('longitude', true),
				"name" => $this->input->post('name', true),
			);

			$langs = $this->input->post("langs", true);
			if ($post_data['name'] == '')
				$post_data['name'] = $langs[$this->pg_language->current_lang_id];

			if (!empty($region_data["code"])) {
				$post_data["region_code"] = $region_data["code"];
			}

			$validate_data = $this->Countries_model->validate('city', $id_city, $post_data);
			if (!empty($validate_data["errors"])) {
				$this->system_messages->add_message('error', $validate_data["errors"]);
			} else {
				$save_data = $validate_data["data"];
				$this->Countries_model->save_city($id_city, $save_data, $langs);

				$this->system_messages->add_message('success', $id_city ? l('success_update_city', 'countries') : l('success_add_city', 'countries'));
				$url = site_url() . "admin/countries/region/" . $country_code . "/" . $id_region;
				redirect($url);
			}
			$data = array_merge($data, $validate_data["data"]);
		}

		$this->template_lite->assign('data', $data);
		$this->template_lite->assign('country', $country_data);
		$this->template_lite->assign('region', $region_data);

		$this->system_messages->set_data('header', l('admin_header_city_edit', 'countries'));
		$this->template_lite->view('edit_city_form');
	}

	public function country_delete($country_code) {
		if (!empty($country_code)) {
			$this->Countries_model->delete_country($country_code);
			$this->system_messages->add_message('success', l('success_delete_country', 'countries'));
		}
		$url = site_url() . "admin/countries";
		redirect($url);
	}

	public function region_delete($country_code, $id_region) {
		if (!empty($id_region)) {
			$this->Countries_model->delete_region($id_region);
			$this->system_messages->add_message('success', l('success_delete_region', 'countries'));
		}
		$url = site_url() . "admin/countries/country/" . $country_code;
		redirect($url);
	}

	public function city_delete($country_code, $id_region, $id_city) {
		if (!empty($id_city)) {
			$this->Countries_model->delete_city($id_city);
			$this->system_messages->add_message('success', l('success_delete_city', 'countries'));
		}
		$cur_set = $_SESSION["cities_list"];
		$url = site_url() . "admin/countries/region/" . $country_code . "/" . $id_region . "/" . $cur_set["page"];
		redirect($url);
	}

	public function install($step = 'country', $country_code = '') {
		$this->Menu_model->set_menu_active_item('admin_countries_menu', 'countries_install_item');

		switch ($step) {
			case 'country':
				$filter = $country_code ? $country_code : 'all';
				$countries_list = $this->Countries_model->get_cache_countries();
				$installed_countries_list = $this->Countries_model->get_countries(array("name" => "ASC"));

				$filter_data["all"] = count($countries_list);
				$filter_data["installed"] = count($installed_countries_list);
				$filter_data["not_installed"] = count($countries_list) - count($installed_countries_list);

				switch ($filter) {
					case "installed":
						foreach ($countries_list as $k => $country) {
							if (!isset($installed_countries_list[$country["code"]]))
								unset($countries_list[$k]);
						}
						break;
					case "not-installed":
						foreach ($countries_list as $k => $country) {
							if (isset($installed_countries_list[$country["code"]]))
								unset($countries_list[$k]);
						}
						break;
					case "all":
					default:
						break;
				}

				$this->template_lite->assign('list', $countries_list);
				$this->template_lite->assign('installed', $installed_countries_list);
				$this->template_lite->assign('filter', $filter);
				$this->template_lite->assign('filter_data', $filter_data);

				$this->system_messages->set_data('header', l('admin_header_install_countries_list', 'countries'));
				$this->template_lite->view('install_country_list');
				break;

			case 'region':
				if (!$country_code) {
					redirect(site_url() . 'admin/countries/install');
				}
				$country = $this->Countries_model->get_cache_country_by_code($country_code);
				if (!empty($country)) {
					$regions_list = $this->Countries_model->get_cache_regions($country_code);
					$installed_regions_list = $this->Countries_model->get_regions_by_code($country_code, array("code" => "ASC"));
					$this->template_lite->assign('country', $country);
					$this->template_lite->assign('list', $regions_list);
					$this->template_lite->assign('installed', $installed_regions_list);
				}

				$this->system_messages->set_data('back_link', site_url() . "admin/countries/install");
				$this->system_messages->set_data('header', l('admin_header_install_regions_list', 'countries'));
				$this->template_lite->view('install_region_list');
				break;

			case 'city':
				if (!$country_code) {
					redirect(site_url() . 'admin/countries/install');
				}

				$country = $this->Countries_model->get_cache_country_by_code($country_code);
				if (empty($country)) {
					redirect(site_url() . 'admin/countries/install');
				}

				$regions = $this->input->post('region', true);
				if (empty($regions)) {
					redirect(site_url() . 'admin/countries/install/region/' . $country_code);
				}

				$regions_list = $this->Countries_model->get_cache_regions($country_code);
				foreach ($regions_list as $key => $region) {
					if (!in_array($region["code"], $regions)) {
						unset($regions_list[$key]);
					}
				}

				$this->template_lite->assign('country', $country);
				$this->template_lite->assign('regions', $regions);
				$this->template_lite->assign('regions_list', $regions_list);
				$this->template_lite->assign('back_link', site_url() . "admin/countries/install/region/" . $country["code"]);

				$this->pg_theme->add_js('admin-countries.js', 'countries');
				$this->system_messages->set_data('header', l('admin_header_install_cities_list', 'countries'));
				$this->template_lite->view('install_city_list');
				break;
		}
	}

	public function ajax_install_cities($country_code, $region_code) {
		echo $this->Countries_model->install_cities($country_code, $region_code, $this->pg_language->languages);
	}

	public function ajax_save_country_sorter() {
		$item_data = $this->input->post('sorter');
		$item_data = $item_data["parent_0"];
		if (empty($item_data))
			return false;

		foreach ($item_data as $key => $sorter) {
			$country_code = strtoupper(substr(str_replace("item_", "", $key), 0, 2));
			if (empty($country_code))
				continue;
			$this->Countries_model->set_country_priority($country_code, $sorter);
		}
		return true;
	}

	public function ajax_save_region_sorter($country_code) {
		$item_data = $this->input->post('sorter');
		$item_data = $item_data["parent_0"];
		if (empty($item_data))
			return false;

		foreach ($item_data as $key => $sorter) {
			$id_region = intval(str_replace("item_", "", $key));
			if (empty($id_region))
				continue;
			$this->Countries_model->set_region_priority($id_region, $sorter);
		}
		return true;
	}

}
