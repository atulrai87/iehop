<?php
/**
 * Template_Lite {helper} function plugin
 *
 * Type:     function
 * Name:     helper
 * Purpose:  Execute helper function
 * Input:
 */

function tpl_function_block($params, &$tpl)
{
	if(isset($params['module'])){
		$params['module'] = strtolower($params['module']);
		$is_installed = $tpl->CI->pg_module->is_module_installed($params['module']);
		if(!$is_installed){
			return '';
		}
	}else{
		$params['module'] = 'start';
	}

	if (!function_exists($params['name']) && isset($params['module']) && !empty($params['module']))
	{
		//$file_name = "helpers/".strtolower($params['module'])."_helper.php";
		//if(file_exists(APPPATH.$file_name) || file_exists(BASEPATH.$file_name) )
		{
			$tpl->CI->load->helper($params['module']);
		}
	}

	if(function_exists($params['name'])){
		$return = $params['name']($params);
	}else{
		$return  = '';
	}

	return $return;
}
