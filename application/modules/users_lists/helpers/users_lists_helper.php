<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('lists_links')){
	function lists_links($id_dest_user){
		$CI = & get_instance();
		$id_dest_user = intval($id_dest_user);
		$id_user = $CI->session->userdata('user_id');
		if($id_dest_user && $CI->session->userdata("auth_type") == "user" && $id_user != $id_dest_user){
			$CI->load->model("Users_lists_model");
			$statuses = $CI->Users_lists_model->get_statuses($id_user, $id_dest_user);
			$buttons = array();
			foreach($statuses['allowed_btns'] as $btn => $params){
				if($params['allow']){
					$buttons[$btn] = $params;
				}
			}
			$CI->template_lite->assign('id_user', $id_user);
			$CI->template_lite->assign('id_dest_user', $id_dest_user);
			$CI->template_lite->assign('buttons', $buttons);
		}
		return $CI->template_lite->fetch('helper_lists_links', 'user', 'users_lists');
	}
}

if(!function_exists('friend_input')){
	function friend_input($params){
		$CI = & get_instance();
		$CI->load->model("Users_model");
		$CI->load->model("Users_lists_model");

		$user_id = $CI->session->userdata('user_id');
		
		$friends_ids = $CI->Users_lists_model->get_friendlist_users_ids($user_id);
		if(empty($friends_ids)) return '';
		
		if(!isset($params['id_user']) && !empty($params['id_user'])){
			$data['user'] = $CI->Users_model->get_user_by_id($params['id_user']);
		}
		
		$data['var_user_name'] = isset($params['var_user_name'])?$params['var_user_name']:'id_user';
		$data['var_js_name'] = isset($params['var_js_name']) ? $params['var_js_name'] : '';
		$data['placeholder'] = isset($params['placeholder']) ? $params['placeholder'] : '';
		$data['values_callback'] = isset($params['values_callback']) ? $params['values_callback'] : '';
		
		$data['rand'] = rand(100000, 999999);

		$CI->template_lite->assign('friend_helper_data', $data);
		return $CI->template_lite->fetch('helper_friend_input', 'user', 'users_lists');
	}
}

if(!function_exists('friend_select')){
	function friend_select($params){
		$CI = & get_instance();
		$CI->load->model("Users_model");
		$CI->load->model("Users_lists_model");

		$user_id = $CI->session->userdata('user_id');
		$friends_ids = $CI->Users_lists_model->get_friendlist_users_ids($user_id);
		if(empty($friends_ids)) return '';
		
		if(isset($params['id_user']) && !empty($params['id_user'])){
			$data["user"] = $CI->Users_model->get_user($params['id_user']);
		}
		
		$data['var_user_name'] = isset($params['var_user_name'])?$params['var_user_name']:'id_user';
		$data['var_js_name'] = isset($params['var_js_name']) ? $params['var_js_name'] : '';
		
		$data['rand'] = rand(100000, 999999);

		$CI->template_lite->assign('friend_helper_data', $data);
		return $CI->template_lite->fetch('helper_friend_select', 'user', 'users_lists');
	}
}

if(!function_exists('add_blacklist_button')){
	function add_blacklist_button($params){
		$CI = & get_instance();
		$CI->load->model("Users_model");
		$CI->load->model("Users_lists_model");

		if(!isset($params['id_user']) || empty($params['id_user'])) return '';

		$user_id = $CI->session->userdata('user_id');

		$blacklist_ids = $CI->Users_lists_model->get_blacklist_users_ids($user_id);
		if(in_array($params['id_user'], $blacklist_ids)) return '';
	
		$data['id_user'] = $params['id_user'];
		$data['rand'] = rand(100000, 999999);

		$CI->template_lite->assign('blacklist_helper_data', $data);
		return $CI->template_lite->fetch('helper_add_blacklist', 'user', 'users_lists');
	}
}
