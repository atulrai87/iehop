<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('country_select'))
{
	function country_select($select_type='city', $id_country='', $id_region='', $id_city='', $var_country_name='id_country', $var_region_name='id_region', $var_city_name='id_city', $var_js_name=''){
		$CI = & get_instance();
		$CI->load->model("Countries_model");

		if(!empty($id_country)){
			$data["country"] = $CI->Countries_model->get_country($id_country);
		}
		if(!empty($id_region)){
			$data["region"] = $CI->Countries_model->get_region($id_region);
		}
		if(!empty($id_city)){
			$data["city"] = $CI->Countries_model->get_city($id_city);
		}

		$data["var_country_name"] = $var_country_name?$var_country_name:"id_country";
		$data["var_region_name"] = $var_region_name?$var_region_name:"id_region";
		$data["var_city_name"] = $var_city_name?$var_city_name:"id_city";
		$data["select_type"] = $select_type?$select_type:"city";
		$data["var_js_name"] = $var_js_name;

		$data["rand"] = rand(100000, 999999);

		$CI->template_lite->assign('country_helper_data', $data);
		return $CI->template_lite->fetch('helper_country_select', 'user', 'countries');
	}
}

if ( ! function_exists('country_input'))
{
	function country_input($select_type='city', $id_country='', $id_region='', $id_city='', $var_country_name='id_country', $var_region_name='id_region', $var_city_name='id_city', $var_js_name='', $placeholder = ''){
		$CI = & get_instance();
		$CI->load->model("Countries_model");

		if(!empty($id_country)){
			$data["country"] = $CI->Countries_model->get_country($id_country);
		}
		if(!empty($id_region)){
			$data["region"] = $CI->Countries_model->get_region($id_region);
		}
		if(!empty($id_city)){
			$data["city"] = $CI->Countries_model->get_city($id_city);
		}

		$data["var_country_name"] = $var_country_name?$var_country_name:"id_country";
		$data["var_region_name"] = $var_region_name?$var_region_name:"id_region";
		$data["var_city_name"] = $var_city_name?$var_city_name:"id_city";
		$data["select_type"] = $select_type?$select_type:"city";
		$data["var_js_name"] = $var_js_name;
		$data["placeholder"] = $placeholder;

		if ($data["city"]["name"]) {
			$data["location_text"] = $data["city"]["name"];
		} elseif ($data["region"]["name"]) {
			$data["location_text"] = $data["region"]["name"];
		} elseif ($data["country"]["name"]) {
			$data["location_text"] = $data["country"]["name"];
		}

		$data["rand"] = rand(100000, 999999);

		$CI->template_lite->assign('country_helper_data', $data);
		return $CI->template_lite->fetch('helper_country_input', 'user', 'countries');
	}
}

if ( ! function_exists('country'))
{
	function country($id_country='', $id_region='', $id_city=''){
		$CI = & get_instance();
		$CI->load->model("Countries_model");
		if(!empty($id_country)){
			$data["country"] = $CI->Countries_model->get_country($id_country);
			$return_array[] = $data["country"]["name"];
		}
		if(!empty($id_region)){
			$data["region"] = $CI->Countries_model->get_region($id_region);
			$return_array[] = $data["region"]["name"];
		}
		if(!empty($id_city)){
			$data["city"] = $CI->Countries_model->get_city($id_city);
			$return_array[] = $data["city"]["name"];
		}
		$return = (is_array($return_array))?implode(', ', $return_array):'';
		return $return;
	}
}

/*
 * Data is array( id => array(id_country, id_region, id_city), id => array(id_country, id_region, id_city), .....)
 * return array(id => str, id => str, id => str...);
 */
if ( ! function_exists('countries_output_format')){
	function countries_output_format($data){
		if(empty($data)){
			return array();
		}
		$CI = & get_instance();
		$location_data = get_location_data($data, 'country');
		$country_template = $CI->pg_module->get_module_config('countries', 'output_country_format');
		
		$return = array();
		foreach($data as $id => $location){
			if(isset($location[0]) && isset($location_data["country"][$location[0]])){
				$str = str_replace("[country]", $location_data["country"][$location[0]]["name"], $country_template);
				$str = str_replace('[country_code]', $location[0], $str);
			}else{
				$str = "";
			}
			$return[$id] = $str;
		} 
		return $return;
	}
}	

if ( ! function_exists('regions_output_format')){
	function regions_output_format($data){
		if(empty($data)){
			return array();
		}
		$CI = & get_instance();
		$location_data = get_location_data($data, 'region');
		$country_template = $CI->pg_module->get_module_config('countries', 'output_country_format');
		$region_template = $CI->pg_module->get_module_config('countries', 'output_region_format');
		
		$return = array();
		foreach($data as $id => $location){
			$template = $country = $country_code = $region = '';
			if(isset($location[0]) && !empty($location_data["country"][$location[0]])){
				$country = $location_data["country"][$location[0]]["name"];
				$country_code = $location[0];
				$template = $country_template;
			}
			
			if(isset($location[1]) && !empty($location_data["region"][$location[1]])){
				$region = $location_data["region"][$location[1]]["name"];
				$template = $region_template;
			}
			
			if($template){
				$template = str_replace("[country]", $country, $template);
				$template = str_replace("[country_code]", $country_code, $template);
				$template = str_replace("[region]", $region, $template);
			}
			$return[$id] = $template;
		} 
		return $return;
	}
}	

if ( ! function_exists('cities_output_format')){
	function cities_output_format($data){
		if(empty($data)){
			return array();
		}
		$CI = & get_instance();
		$location_data = get_location_data($data, 'city');
		$country_template = $CI->pg_module->get_module_config('countries', 'output_country_format');
		$region_template = $CI->pg_module->get_module_config('countries', 'output_region_format');
		$city_template = $CI->pg_module->get_module_config('countries', 'output_city_format');

		$return = array();
		foreach($data as $id => $location){
			$template = $country = $country_code = $region = $city = '';
			if(isset($location[0]) && !empty($location_data["country"][$location[0]])){
				$country = $location_data["country"][$location[0]]["name"];
				$country_code = $location[0];
				$template = $country_template;
			}
			
			if(isset($location[1]) && !empty($location_data["region"][$location[1]])){
				$region = $location_data["region"][$location[1]]["name"];
				$template = $region_template;
			}
			
			if(isset($location[2]) && !empty($location_data["city"][$location[2]])){
				$city = $location_data["city"][$location[2]]["name"];
				$template = $city_template;
			}
			
			if($template){
				$template = str_replace("[country]", $country, $template);
				$template = str_replace("[country_code]", $country_code, $template);
				$template = str_replace("[region]", $region, $template);
				$template = str_replace("[city]", $city, $template);
			}
			$return[$id] = $template;
		} 
		return $return;	
	}
}

if ( ! function_exists('get_location_data')){
	function get_location_data($data, $type = 'city'){
		$CI = & get_instance();
		$CI->load->model("Countries_model");

		if(empty($data)){
			return array();
		}
		$return = $country_ids = $region_ids = $city_ids = array();
		foreach($data as $set){
			/// country
			if(isset($set[0]) && !empty($set[0]) && !in_array($set[0], $country_ids)){
				$country_ids[] = $set[0];
			}
			
			/// region
			if(($type != 'country') && isset($set[1]) && !empty($set[1]) && !in_array($set[1], $region_ids)){
				$region_ids[] = $set[1];
			}
			
			/// city
			if(($type == 'city') && isset($set[2]) && !empty($set[2]) && !in_array($set[2], $city_ids)){
				$city_ids[] = $set[2];
			}
		}
		
		if(!empty($country_ids)){
			$return["country"] = $CI->Countries_model->get_countries_by_code($country_ids);
		}
		
		if(!empty($region_ids)){
			$return["region"] = $CI->Countries_model->get_regions_by_id($region_ids);
		}
		
		if(!empty($city_ids)){
			$return["city"] = $CI->Countries_model->get_cities_by_id($city_ids);
		}

		return $return;
	}
}
