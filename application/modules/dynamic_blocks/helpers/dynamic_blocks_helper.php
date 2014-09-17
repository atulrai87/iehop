<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('dynamic_blocks_area'))
{
	function dynamic_blocks_area($area_gid){
		$CI = & get_instance();
		$CI->load->model("Dynamic_blocks_model");
		$area = $CI->Dynamic_blocks_model->get_area_by_gid($area_gid);
		if(empty($area)){
			$area_gid = "index-page";
		}
		return $CI->Dynamic_blocks_model->html_area_blocks_by_gid($area_gid);
	}
}
