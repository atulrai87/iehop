<?php

if (!function_exists('update_account_block')) {

	function update_account_block() {
		$CI = & get_instance();
		$CI->load->model("payments/models/Payment_systems_model");

		$billing_systems = $CI->Payment_systems_model->get_active_system_list();
		$CI->template_lite->assign('billing_systems', $billing_systems);
		
		$CI->load->model("payments/models/Payment_currency_model");
		$base_currency = $CI->Payment_currency_model->get_currency_default(true);
		$CI->template_lite->assign('base_currency', $base_currency);
		
		return $CI->template_lite->fetch('helper_update_account', 'user', 'users_payments');
	}

}

if (!function_exists('add_funds_button')) {

	function button_add_funds() {
		$CI = & get_instance();
		
		$CI->load->model("payments/models/Payment_currency_model");
		$base_currency = $CI->Payment_currency_model->get_currency_default(true);
		$CI->template_lite->assign('base_currency', $base_currency);
		
		return $CI->template_lite->fetch('helper_add_funds', 'admin', 'users_payments');
	}
}

if (!function_exists('user_account')) {

	function user_account() {
		$CI = & get_instance();
		if ('user' != $CI->session->userdata("auth_type")) {
			return false;
		}
		$CI->load->model("Users_payments_model");
		$account = $CI->Users_payments_model->get_user_account($CI->session->userdata("user_id"));
		$CI->template_lite->assign('user_account', $account);
		
		$CI->load->model("payments/models/Payment_currency_model");
		$base_currency = $CI->Payment_currency_model->get_currency_default(true);
		$CI->template_lite->assign('base_currency', $base_currency);
		
		return $CI->template_lite->fetch('helper_account', 'user', 'users_payments');
	}

}