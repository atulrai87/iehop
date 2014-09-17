<?php

if ( ! function_exists('get_content_tree'))
{
	function get_content_tree($parent_id=0){
		$CI = & get_instance();
		$CI->load->model('Content_model');
		$lang_id = $CI->pg_language->current_lang_id;

		if($parent_id){
			$params["where"]["parent_id"] = $parent_id;
			$sub_count = $CI->Content_model->get_active_pages_count($lang_id, $params);
			if(empty($sub_count)){
				$CI->Content_model->set_page_active($lang_id, $parent_id);
				$parent_data = $CI->Content_model->get_page_by_id($parent_id);
				$parent_id = isset($parent_data["parent_id"])?$parent_data["parent_id"]:0;
			}
		}

		$pages = $CI->Content_model->get_active_pages_list($lang_id, $parent_id);

		$CI->template_lite->assign("content_tree", $pages);
		$html = $CI->template_lite->fetch("tree", 'user', 'content');
		echo $html;
	}
}

if ( ! function_exists('get_content_page'))
{
	function get_content_page($gid){
		$CI = & get_instance();
		$CI->load->model('Content_model');

		$lang_id = $CI->pg_language->current_lang_id;
		$page_data = $CI->Content_model->get_page_by_gid($lang_id, $gid);
		$CI->template_lite->assign("page", $page_data);
		$html = $CI->template_lite->fetch("show_block", 'user', 'content');
		echo $html;
	}
}

if ( ! function_exists('get_content_promo'))
{
	function get_content_promo($view=''){
		$CI = & get_instance();
		$CI->load->model('content/models/Content_promo_model');

		$lang_id = $CI->pg_language->current_lang_id;
		$promo_data = $CI->Content_promo_model->get_promo($lang_id);

		$CI->template_lite->assign("promo", $promo_data);
		$CI->template_lite->assign("view", $view);
		$html = $CI->template_lite->fetch("show_promo_block", 'user', 'content');
		return $html;
	}
}