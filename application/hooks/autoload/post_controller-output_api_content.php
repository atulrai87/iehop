<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('output_api_content')){

	function output_api_content(){
		$CI = & get_instance();
		if ($CI->router->is_api_class){
			$CI->load->helper('api');
			echo get_api_content();
			exit;
		}
	}

}