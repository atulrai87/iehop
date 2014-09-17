<?php

/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_start_search_form($params, &$tpl) {
	$tpl->CI->load->helper('start');
    
	if(empty($params['object']) && $tpl->CI->pg_module->is_module_installed('resumes')){
		$params['object'] = 'resume';	
	}elseif(empty($params['object']) && $tpl->CI->pg_module->is_module_installed('vacancies')){
		$params['object'] = 'vacancy';
	}elseif(empty($params['object']) && $tpl->CI->pg_module->is_module_installed('listings')){
		$params['object'] = 'sale';
	}elseif(empty($params['object']) && $tpl->CI->pg_module->is_module_installed('ads')){
		$params['object'] = 'ad';
	}elseif(empty($params['object']) && $tpl->CI->pg_module->is_module_installed('users')){
		$params['object'] = 'user';
	}
	
	if(empty($params['type'])) $params['type'] = 'line';
	if(!isset($params['show_data'])) $params['show_data'] = false;
    
	return main_search_form($params['object'], $params['type'], $params['show_data']);
}

