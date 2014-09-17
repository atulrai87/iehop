<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_breadcrumbs($params, &$tpl)
{
	$tpl->CI->load->helper('menu');
	return get_breadcrumbs($params['template']);
}

