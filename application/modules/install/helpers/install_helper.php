<?php
/**
* Add css and js files on any page
*
* @package PG_Core
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Makeev <mmakeev@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (��, 02 ��� 2009) $ $Author: irina $
**/

if ( ! function_exists('get_initial_setup_menu'))
{
	function get_initial_setup_menu($step=1)
	{
		$CI = &get_instance();
		$CI->template_lite->assign("step", $step);
		$html = $CI->template_lite->fetch("menu_initial_install", 'admin', 'install');
		echo $html;
	}
}

if ( ! function_exists('get_product_setup_menu'))
{
	function get_product_setup_menu($step=1)
	{
		$CI = &get_instance();
		$CI->template_lite->assign("step", $step);
		$html = $CI->template_lite->fetch("menu_product_install", 'admin', 'install');
		echo $html;
	}
}

if ( ! function_exists('get_modules_setup_menu'))
{
	function get_modules_setup_menu($step="")
	{
		$CI = &get_instance();
		if(!$step){
			$step = $CI->router->fetch_method();
		}

		if($step != 'enable_modules'){
			$CI->load->model('Install_model');
			$enabled = count($CI->Install_model->get_enabled_modules());
			$CI->template_lite->assign("enabled", $enabled);
		}

		if($step != 'updates'){
			$CI->load->model('install/models/Updates_model');
			$updates = count($CI->Updates_model->get_enabled_updates());
			$CI->template_lite->assign("updates", $updates);
		}
		
		if($step != 'product_updates'){
			$CI->load->model('install/models/Updates_model');
			$product_updates = count($CI->Updates_model->get_enabled_product_updates());
			$CI->template_lite->assign("product_updates", $product_updates);
		}

		if($step != 'enable_libraries'){
			$CI->load->model('install/models/Libraries_model');
			$enabled_libraries = count($CI->Libraries_model->get_enabled_libraries());
			$CI->template_lite->assign("enabled_libraries", $enabled_libraries);
		}

		$CI->template_lite->assign("step", $step);
		$html = $CI->template_lite->fetch("menu_modules_install", 'admin', 'install');
		echo $html;
	}
}

