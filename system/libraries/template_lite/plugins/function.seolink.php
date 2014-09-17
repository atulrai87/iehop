<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_seolink($params, &$tpl)
{
	$tpl->CI->load->helper('seo');
	foreach($params as $name => $value){
		if($name != 'module' && $name != 'method' && $name != 'data' ){
			$params['data'][$name] = $value;
		}
	}
	return rewrite_link($params['module'], $params['method'], $params['data']);
}
