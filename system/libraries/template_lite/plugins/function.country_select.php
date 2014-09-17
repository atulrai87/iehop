<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_country_select($params, &$tpl)
{
	$tpl->CI->load->helper('countries');
	return country_select($params['select_type'], $params['id_country'], $params['id_region'], $params['id_city'], $params['var_country'], $params['var_region'], $params['var_city'], $params['var_js'], $params['id_district'], $params['var_district']);
}
