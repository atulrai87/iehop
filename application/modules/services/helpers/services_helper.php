<?php

if (!function_exists('services_buy_list')) {
	function services_buy_list($params = array()) {
		$CI = & get_instance();
		$CI->load->model('Services_model');

		$where['where']['status'] = 1;
		$services = $CI->Services_model->get_service_list($where);
		$services_modules = array();
		foreach($services as $key => $service){
			if($service['template']['price_type'] > 2){
				unset($services[$key]);
			}else{
				$model = strtolower($service['template']['callback_model']);
				$services_modules[$service['template']['callback_module']][$model] = ucfirst($model);
			}
		}
		$buy_gids = array();
		foreach($services_modules as $module => $models){
			foreach($models as $model){
				$CI->load->model("$module/models/$model");
				if(!empty($CI->$model->services_buy_gids)){
					$buy_gids = array_merge($buy_gids, $CI->$model->services_buy_gids);
				}
			}
		}
		foreach($services as $key => $service){
			if(!in_array($service['gid'], $buy_gids)){
				unset($services[$key]);
			}
		}

		$CI->template_lite->assign('services_block_services', $services);
		return $CI->template_lite->fetch('helper_services_buy_list', 'user', 'services');
	}
}

if (!function_exists('user_services_list')) {
	function user_services_list($params) {
		$CI = & get_instance();
		$CI->load->model('services/models/Services_users_model');
		$order_by = array(
			'status' => 'DESC',
			'count' => 'DESC',
			'date_created' => 'DESC'
		);
		$where = array();
		$where['where_sql'][] = "id_user = {$params['id_user']} AND (id_users_package = '0' OR status = '0')";
		$services = $CI->Services_users_model->get_services_list($where, $order_by);
		$CI->template_lite->assign('services_block_services', $services);
		$date_formats = array(
			'date_format' => $CI->pg_date->get_format('date_literal', 'st'),
			'date_time_format' => $CI->pg_date->get_format('date_time_literal', 'st')
		);
		$CI->template_lite->assign('services_block_date_formats', $date_formats);
		return $CI->template_lite->fetch('helper_user_services_list', 'user', 'services');
	}
}