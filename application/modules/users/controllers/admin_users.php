<?php
/**
* Users admin side controller
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

Class Admin_Users extends Controller
{
	/**
	 * link to CodeIgniter object
	 * @var object
	 */

	/**
	 * Constructor
	 */
	function Admin_Users()
	{
		parent::Controller();
		$this->load->model("Users_model");
		$this->load->model('Menu_model');
		$this->Menu_model->set_menu_active_item('admin_menu', 'users_menu_item');
	}

	function index($filter="all", $user_type='all', $order="nickname", $order_direction="ASC", $page=1){
		$attrs = $search_params = array();
		$current_settings = isset($_SESSION["users_list"])?$_SESSION["users_list"]:array();
		if(!isset($current_settings["filter"])) $current_settings["filter"] = $filter;
		if(!isset($current_settings["order"])) $current_settings["order"] = $order;
		if(!isset($current_settings["order_direction"])) $current_settings["order_direction"] = $order_direction;
		if(!isset($current_settings["page"])) $current_settings["page"] = $page;
		if($this->uri->segment(4) === FALSE) {
			$current_settings["search_text"] = '';
			$current_settings["search_type"] = 'all';
			$current_settings["last_active"] = array();
		}
		if($this->input->post('btn_search', true)){
			$user_type = $this->input->post('user_type', true);
			$current_settings["search_type"] = $this->input->post('type_text', true);
			$current_settings["search_text"] = $this->input->post('val_text', true);
			$current_settings["last_active"]["from"] = $this->input->post('last_active_from', true);
			$current_settings["last_active"]["to"] = $this->input->post('last_active_to', true);
		}
		$current_settings["user_type"] = $user_type;
		if($current_settings["search_text"]){
			if($current_settings["search_type"]!='all'){
				$attrs["where"][$current_settings["search_type"]." LIKE"] = $search_params["like"][$current_settings["search_type"]] = "%" . $current_settings["search_text"] . "%";
			}else{
				$search_text_escape = $this->db->escape("%".$current_settings["search_text"]."%");
				$attrs["where_sql"][] = $search_params["where_sql"][] = "(nickname LIKE ".$search_text_escape." OR fname LIKE ".$search_text_escape." OR sname LIKE ".$search_text_escape." OR email LIKE ".$search_text_escape.")";
			}
		}
		
		if($current_settings["last_active"]["from"]){
			$attrs["where_sql"][] = $search_params["where_sql"][] = "date_last_activity >= '".$current_settings["last_active"]["from"]."'";
		}
		if($current_settings["last_active"]["to"]){
			$attrs["where_sql"][] = $search_params["where_sql"][] = "date_last_activity <= '".$current_settings["last_active"]["to"]." 23:59:59'";
		}
		
		if($user_type != 'all' && intval($user_type)){
			$attrs["where"]["user_type"] = $search_params["where"]["user_type"] = intval($user_type);
		}
		$search_param = array(
							'text' => $current_settings["search_text"],
							'type' => $current_settings["search_type"],
							'last_active' => $current_settings["last_active"],
						);

		$filter_data["all"] = $this->Users_model->get_users_count($search_params);
		$search_params["where"]["approved"] = 0;
		$filter_data["not_active"] = $this->Users_model->get_users_count($search_params);
		$search_params["where"]["approved"] = 1;
		$filter_data["active"] = $this->Users_model->get_users_count($search_params);

		switch($filter){
			case 'active' : $attrs["where"]['approved'] = 1; break;
			case 'not_active' : $attrs["where"]['approved'] = 0; break;
			case 'all' : break;
			default: $filter = $current_settings["filter"];
		}
		$current_settings["filter"] = $filter;

		$this->load->model('Properties_model');
		$user_types = $this->Properties_model->get_property('user_type');
		$this->template_lite->assign('user_types', $user_types);

		$this->template_lite->assign('search_param', $search_param);
		$this->template_lite->assign('user_type', $user_type);
		$this->template_lite->assign('filter', $filter);
		$this->template_lite->assign('filter_data', $filter_data);
		$current_settings["page"] = $page;

		if(!$order) $order = $current_settings["order"];
		$this->template_lite->assign('order', $order);
		$current_settings["order"] = $order;

		if(!$order_direction) $order_direction = $current_settings["order_direction"];
		$this->template_lite->assign('order_direction', $order_direction);
		$current_settings["order_direction"] = $order_direction;

		$users_count = $filter_data[$filter];

		if(!$page) $page = $current_settings["page"];
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $users_count, $items_on_page);
		$current_settings["page"] = $page;

		$_SESSION["users_list"] = $current_settings;

		$sort_links = array(
			"nickname" => site_url()."admin/users/index/{$filter}/{$user_type}/nickname/".(($order!='nickname' xor $order_direction=='DESC')?'ASC':'DESC'),
			"email" => site_url()."admin/users/index/{$filter}/{$user_type}/email/".(($order!='email' xor $order_direction=='DESC')?'ASC':'DESC'),
			"account" => site_url()."admin/users/index/{$filter}/{$user_type}/account/".(($order!='account' xor $order_direction=='DESC')?'ASC':'DESC'),
			"date_created" => site_url()."admin/users/index/{$filter}/{$user_type}/date_created/".(($order!='date_created' xor $order_direction=='DESC')?'ASC':'DESC'),
		);

		$this->template_lite->assign('sort_links', $sort_links);

		if ($users_count > 0){
			$users = $this->Users_model->get_users_list( $page, $items_on_page, array($order => $order_direction), $attrs);
			$this->template_lite->assign('users', $users);
		}

		$this->load->helper("navigation");
		$url = site_url()."admin/users/index/{$filter}/{$user_type}/{$order}/{$order_direction}/";
		$page_data = get_admin_pages_data($url, $users_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->load->model("users/models/Groups_model");
		$groups = $this->Groups_model->get_groups_list();
		$this->template_lite->assign('groups', $groups);

		$this->system_messages->set_data('header', l('admin_header_users_list', 'users'));
		$this->template_lite->view('list');
	}

	function edit($section = 'personal', $user_id=null){
		$this->Users_model->fields_not_editable = array();
		if(!$section) $section = 'personal';
		$this->load->model('Field_editor_model');
		$this->Field_editor_model->initialize($this->Users_model->form_editor_type);
		$sections = $this->Field_editor_model->get_section_list();
		$sections_gids = array_keys($sections);
		$this->template_lite->assign('sections', $sections);
		if($section == 'personal'){
			$age_range = range($this->pg_module->get_module_config('users', 'age_min'), $this->pg_module->get_module_config('users', 'age_max'));
			$this->template_lite->assign('age_range', $age_range);
			$this->load->model('Properties_model');
			$user_types = $this->Properties_model->get_property('user_type');
			$this->template_lite->assign('user_types', $user_types);
		}else{
			$fe_section = $this->Field_editor_model->get_section_by_gid($section);
			if(!empty($fe_section)){
				$fields_for_select = $this->Field_editor_model->get_fields_for_select($fe_section['gid']);
			}
			$this->Users_model->set_additional_fields($fields_for_select);
		}

		if($user_id){
			$data = $this->Users_model->get_user_by_id($user_id);
		}else{
			$data["lang_id"] = $this->pg_language->current_lang_id;
		}

		if($this->input->post('btn_save')){
			$post_data = array(); $validate_section = null;

			switch($section){
				case 'personal':
					$post_data = array(
						'looking_user_type' => $this->input->post('looking_user_type', true),
						'email' => $this->input->post('email', true),
						'confirm' => $this->input->post('confirm', true),
						'nickname' => $this->input->post('nickname', true),
						'fname' => $this->input->post('fname', true),
						'sname' => $this->input->post('sname', true),
						'id_country' => $this->input->post('id_country', true),
						'id_region' => $this->input->post('id_region', true),
						'id_city' => $this->input->post('id_city', true),
						'birth_date' => $this->input->post('birth_date', true),
						'phone' => $this->input->post('phone', true),
						'age_min' => $this->input->post('age_min', true),
						'age_max' => $this->input->post('age_max', true),
					);
				
					if(!$user_id){
						$post_data['user_type'] = $this->input->post('user_type', true);
					}
				break;
				case 'seo':
					$this->load->model('Seo_model');
					$seo_fields = $this->Seo_model->get_seo_fields();
					foreach($seo_fields as $key=>$section_data){
						if($this->input->post('btn_save_'.$section_data['gid'])){
							$post_data[$section_data['gid']] = $this->input->post($section_data['gid'], true);
							$validate_data = $this->Seo_model->validate_seo_tags($user_id, $post_data);
							if(!empty($validate_data["errors"])){
								$this->system_messages->add_message("error", implode("<br>", $validate_data["errors"]));
							}else{
								$user_data['id_seo_settings'] = $this->Seo_model->save_seo_tags($data['id_seo_settings'], $validate_data['data']);
								if(!$data['id_seo_settings']){
									$data['id_seo_settings'] = $user_data['id_seo_settings'];
									$this->Users_model->save_user($user_id, $user_data, false);
								}
								$this->system_messages->add_message('success', l('success_settings_updated', 'seo'));
							}
							$data = array_merge($data, $post_data);
							break;
						}
					}
				break;
				default:
					foreach($fields_for_select as $field){
						$post_data[$field] = $this->input->post($field, true);
					}
					$validate_section = $section;
				break;
			}

			if(intval($this->input->post('update_password')) || !$user_id){
				$post_data['password'] = $this->input->post('password', true);
				$post_data['repassword'] = $this->input->post('repassword', true);
			}

			$validate_data = $this->Users_model->validate($user_id, $post_data, 'user_icon', $validate_section);

			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$data = $validate_data['data'];
			}else{
				$save_data = $validate_data["data"];

				if($this->input->post('user_icon_delete') || (isset($_FILES['user_icon']) && is_array($_FILES['user_icon']) && is_uploaded_file($_FILES['user_icon']['tmp_name']))){
					$this->load->model("Uploads_model");
					if($data['user_logo_moderation']){
						$this->Uploads_model->delete_upload($this->Users_model->upload_config_id, $user_id . "/", $data['user_logo_moderation']);
						$save_data["user_logo_moderation"] = '';
						$this->load->model('Moderation_model');
						$this->Moderation_model->delete_moderation_item_by_obj($this->Users_model->moderation_type, $user_id);
					}elseif($data['user_logo']){
						$this->Uploads_model->delete_upload($this->Users_model->upload_config_id, $user_id . "/", $data['user_logo']);
						$save_data["user_logo"] = '';
					}
				}

				if(!empty($save_data['password'])){
					$save_password = $save_data['password'];
					$save_data['password'] = md5($save_data['password']);
				}
				if(!$user_id){
					$save_data['confirm'] = 1;
					$save_data['approved'] = 1;
				}
				
				$this->load->model('Notifications_model');
				if(!empty($save_data['password'])){
					///// send notification password
					$data["password"] = $save_password;
					$this->Notifications_model->send_notification($data["email"], 'users_change_password', $data);
				}
				if($data["email"] != $save_data["email"]){
					///// send notification email
					$data["new_email"] = $save_data["email"];
					$this->Notifications_model->send_notification($data["email"], 'users_change_email', $data);
					$data["email"] = $data["new_email"];
					$this->Notifications_model->send_notification($data["new_email"], 'users_change_email', $data);
				}

				$validate_data["data"]["id"] = $user_id = $this->Users_model->save_user($user_id, $save_data, 'user_icon', false);

				$this->system_messages->add_message('success', ($user_id)?l('success_update_user', 'users'):l('success_add_user', 'users'));
				$cur_set = $_SESSION["users_list"];
				
				//$url = site_url()."admin/users/index/{$cur_set["filter"]}/{$cur_set['user_type']}/{$cur_set["order"]}/{$cur_set["order_direction"]}/{$cur_set["page"]}";
				$url = site_url()."admin/users/edit/".$section."/".$user_id;
				redirect($url);
			}
			$data = array_merge($data, $validate_data["data"]);
		}

		$data = $this->Users_model->format_user($data);
		$this->template_lite->assign('langs', $this->pg_language->languages);
		$this->template_lite->assign('data', $data);
		$this->template_lite->assign('section', $section);

		switch($section){
			case 'personal':
				
			break;
			case 'seo':
				$this->load->model('Seo_model');
				$seo_fields = $this->Seo_model->get_seo_fields();
				$this->template_lite->assign('seo_fields', $seo_fields);
			
				$languages = $this->pg_language->languages;
				$this->template_lite->assign('languages', $languages);
				
				$current_lang_id = $this->pg_language->current_lang_id;
				$this->template_lite->assign('current_lang_id', $current_lang_id);
		
				$seo_settings = $this->Seo_model->get_seo_tags($data['id_seo_settings']);
				$this->template_lite->assign('seo_settings', $seo_settings);
			break;
			default:
				$params["where"]["section_gid"] = $fe_section['gid'];
				$fields_data = $this->Field_editor_model->get_form_fields_list($data, $params);
				$this->template_lite->assign('fields_data', $fields_data);
			break;
		}

		$cur_set = $_SESSION["users_list"];
		$back_url = site_url()."admin/users/index/{$cur_set["filter"]}/{$cur_set['user_type']}/{$cur_set["order"]}/{$cur_set["order_direction"]}/{$cur_set["page"]}";
		$this->template_lite->assign('back_url', $back_url);
		$this->system_messages->set_data('header', l('admin_header_users_edit', 'users'));
		$this->template_lite->view('edit_form');
	}

	function delete($user_id){
		if(!empty($user_id)){
			$this->Users_model->delete_user($user_id);
			$this->system_messages->add_message('success', l('success_delete_user', 'users'));
		}
		$cur_set = $_SESSION["users_list"];
		$url = site_url()."admin/users/index/{$cur_set["filter"]}/{$cur_set["user_type"]}/{$cur_set["order"]}/{$cur_set["order_direction"]}/{$cur_set["page"]}";
		redirect($url);
	}

	function activate($user_id, $status=0){
		if(!empty($user_id)){
			$this->Users_model->activate_user($user_id, $status);
			if($status)
				$this->system_messages->add_message('success', l('success_activate_user', 'users'));
			else
				$this->system_messages->add_message('success', l('success_deactivate_user', 'users'));
		}
		$cur_set = $_SESSION["users_list"];
		$url = site_url()."admin/users/index/{$cur_set["filter"]}/{$cur_set["user_type"]}/{$cur_set["order"]}/{$cur_set["order_direction"]}/{$cur_set["page"]}";
		redirect($url);
	}

	function ajax_change_user_group($user_id, $group_gid){
		$this->load->model("users/models/Groups_model");
		$group_gid = strval($group_gid);
		$group_data = $this->Groups_model->get_group_by_gid($group_gid);
		$group_id = (!empty($group_data))?$group_data["id"]:0;

		$user_id = intval($user_id);
		if($user_id && $group_id){
			$save_data["group_id"] = $group_id;
			$user_id = $this->Users_model->save_user($user_id, $save_data);
		}
	}

	function ajax_change_users_group($group_gid){
		$this->load->model("users/models/Groups_model");
		$group_gid = strval($group_gid);
		$group_data = $this->Groups_model->get_group_by_gid($group_gid);
		$group_id = (!empty($group_data))?$group_data["id"]:0;

		$user_ids = $this->input->post('user_ids');
		if(!empty($user_ids) && $group_id){
			$save_data["group_id"] = $group_id;
			foreach($user_ids as $user_id){
				$this->Users_model->save_user($user_id, $save_data);
			}
			$this->system_messages->add_message('success', l('error_user_successfully_change_group', 'users'));
		}
	}

	function groups($page=1){
		$this->load->model("users/models/Groups_model");

		$attrs = array();
		$current_settings["page"] = $page;
		if(!isset($current_settings["page"])) $current_settings["page"] = 1;

		$group_count = $this->Groups_model->get_groups_count();

		if(!$page) $page = $current_settings["page"];
		$items_on_page = $this->pg_module->get_module_config('start', 'admin_items_per_page');
		$this->load->helper('sort_order');
		$page = get_exists_page_number($page, $group_count, $items_on_page);
		$current_settings["page"] = $page;

		$_SESSION["groups_list"] = $current_settings;

		if ($group_count > 0){
			$groups = $this->Groups_model->get_groups_list( $page, $items_on_page, array("date_created" => "DESC"));
			$this->template_lite->assign('groups', $groups);

		}
		$this->load->helper("navigation");
		$url = site_url()."admin/users/groups/";
		$page_data = get_admin_pages_data($url, $group_count, $items_on_page, $page, 'briefPage');
		$page_data["date_format"] = $this->pg_date->get_format('date_time_literal', 'st');
		$this->template_lite->assign('page_data', $page_data);

		$this->Menu_model->set_menu_active_item('admin_users_menu', 'groups_list_item');
		$this->system_messages->set_data('header', l('admin_header_groups_list', 'users'));
		$this->template_lite->view('groups_list');
	}

	function group_edit($group_id=null){
		$this->load->model("users/models/Groups_model");
		if($group_id){
			$data = $this->Groups_model->get_group_by_id($group_id);
		}else{
			$data = array();
		}

		if($this->input->post('btn_save')){
			$post_data = array(
				"gid" => $this->input->post('gid', true),
			);

			$langs_data = $this->input->post('langs', true);
			$validate_data = $this->Groups_model->validate_group($group_id, $post_data);
			if(!empty($validate_data["errors"])){
				$this->system_messages->add_message('error', $validate_data["errors"]);
				$data = array_merge($data, $validate_data["data"]);
			}else{
				$data = $validate_data["data"];
				$group_id = $this->Groups_model->save_group($group_id, $data, $langs_data);
				$this->system_messages->add_message('success', ($group_id)?l('success_update_group', 'users'):l('success_add_group', 'users'));
				$url = site_url()."admin/users/groups";
				redirect($url);
			}
		}

		$data = $this->Groups_model->format_group($data);
		$data["langs"] = $this->Groups_model->_get_group_string_data($group_id);

		$this->template_lite->assign('languages', $this->pg_language->languages);
		$this->template_lite->assign('data', $data);

		$this->system_messages->set_data('header', l('admin_header_groups_edit', 'users'));
		$this->template_lite->view('group_edit_form');
	}

	function group_set_default($group_id){
		if(!empty($group_id)){
			$this->load->model("users/models/Groups_model");
			$this->Groups_model->set_default($group_id);
			$this->system_messages->add_message('success', l('success_defaulted_group', 'users'));
		}
		$current_settings = $_SESSION["groups_list"];
		$url = site_url()."admin/users/groups/".$current_settings["page"]."";
		redirect($url);
	}

	function group_delete($group_id){
		if(!empty($group_id)){
			$this->load->model("users/models/Groups_model");
			$group_data = $this->Groups_model->get_group_by_id($group_id);
			if($group_data["is_default"]){
				$this->system_messages->add_message('error', l('error_cant_delete_default_group', 'users'));
			}else{
				$this->Groups_model->delete_group($group_id);
				$this->system_messages->add_message('success', l('success_delete_group', 'users'));
			}
		}
		$current_settings = $_SESSION["groups_list"];
		$url = site_url()."admin/users/groups/".$current_settings["page"]."";
		redirect($url);
	}

}
