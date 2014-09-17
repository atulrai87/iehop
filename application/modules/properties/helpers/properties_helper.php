<?php

if ( ! function_exists('job_categories_input')){
	function job_categories_input($input_name, $selected_categories = array(), $available_to_select = 1, $var_js_name=''){
		$CI = & get_instance();
		$CI->load->model("properties/models/Job_categories_model");   
		$data["selected_categories"] = (is_array($selected_categories) && !empty($selected_categories) ? implode(',', $selected_categories): '');
		$data["available_to_select"] = $available_to_select;
		$data["var_js_name"] = $var_js_name;
		$data["input_name"] = $input_name;

		$data["rand"] = rand(100000, 999999);

		$CI->template_lite->assign('job_categories_helper_data', $data);
		return $CI->template_lite->fetch('helper_job_categories_input', 'admin', 'properties');
	}
}

if(!function_exists('property_select')){
	function property_select($property_gid, $var_name='', $selected=array(), $multi=0, $empty=1, $lang_id=''){
		$CI = & get_instance();
		$CI->load->model("Properties_model"); 
		
		if(empty($property_gid)){
			return false;
		}
		
		$data = $CI->Properties_model->get_property($property_gid, $lang_id);
		
		if(empty($data)){
			return false;
		}
		
		if(!$var_name){
			$var_name = $property_gid;
		}
		
		if(!is_array($selected) && !empty($selected)){
			$selected = array(0 => $selected);
		}
		if(!empty($selected) ){
			foreach($selected as $item) $selected_reverse[$item] = 1;
		}
		
		$select = array(
			'options' => $data['option'],
			'name' => $var_name,
			'selected' => $selected_reverse,
			'multi' => $multi,
			'empty_option' => $empty,
		);
		$CI->template_lite->assign('properties_helper_data', $select);
		return $CI->template_lite->fetch('helper_properties_select', 'admin', 'properties');
	}
}

if ( ! function_exists('job_category_select'))
{
	///// categories = array of ids
	function job_category_select($max=5, $categories=array(), $var='id_category', $level=2, $var_js_name='', $output="max"){
		$CI = & get_instance();
		$CI->load->model("properties/models/Job_categories_model");   
		
		$max = intval($max);
		if($max < 1) $max = 1;
		
		$level = intval($level);
		if($level < 1) $level = 1;
		if($level > 2) $level = 2;

		$c_by_id = $selected_all = $selected = array();
		$selected_data = "";
		if(!empty($categories)){
			$categories_data = $CI->Job_categories_model->get_job_category_by_id($categories);

			if(!empty($categories_data)){
				foreach($categories_data as $category){
					$selected_all[$category['id']] = 1;
					$c_by_id[$category['id']] = $category;
					
					if( ($level == 1 && $category['parent'] == 0) || ($level == 2 && $category['parent'] != 0) ){
						if($max == 1){
							//// get text
							$selected_data .= $category['name']." ";
						}else{
							/// get count
							$selected_data = intval($selected_data); $selected_data++;
						}
					}

				}
				
			}
		}

		$data["var"] = $var?$var:"id_category";
		$data["selected_all"] = $selected_all;
		$data["selected_all_json"] = json_encode($selected_all);
		$data["var_js_name"] = $var_js_name;
		$data["raw_data"] = $c_by_id;
		$data["raw_data_json"] = json_encode($c_by_id);
		$data["max"] = $max;
		$data["level"] = $level;
		$data["output"] = ($output)?$output:'max';

		$data["selected_data"] = $selected_data;
		$data["rand"] = rand(100000, 999999);

		$CI->template_lite->assign('category_helper_data', $data);
		return $CI->template_lite->fetch('helper_category_select', 'user', 'properties');
	}
}

if ( ! function_exists('job_category_search_select'))
{
	///// categories = array of ids
	function job_category_search_select($categories=array(), $var='id_category', $var_js_name=''){
		$CI = & get_instance();
		$CI->load->model("properties/models/Job_categories_model");   
		$c_by_id = $selected_all = $selected = $selected_data = array();
		if(!empty($categories)){
			$categories_data = $CI->Job_categories_model->get_job_category_by_id($categories);
			if (!is_array($categories) || count($categories) <= 1){
				$categories_data = array(0 => $categories_data);
			}
			if(!empty($categories_data)){
				foreach($categories_data as $category){
					$selected[$category['id']] = 1;
					$c_by_id[$category['id']] = $category;
				}
			}
		}

		$data["var"] = $var?$var:"id_category";
		$data["selected"] = $selected;
		$data["selected_json"] = json_encode($selected);
		$data["var_js_name"] = $var_js_name;
		$data["raw_data"] = $c_by_id;
		$data["raw_data_json"] = json_encode($c_by_id);

		$data["rand"] = rand(100000, 999999);

		$CI->template_lite->assign('category_helper_data', $data);
		return $CI->template_lite->fetch('helper_category_search_select', 'user', 'properties');
	}
}
