<?php

/**
 * Seo management
 * 
 * @package PG_RealEstate
 * @subpackage Seo
 * @category	helpers
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Mikhail Makeev <mmakeev@pilotgroup.net>
 * @version $Revision: 68 $ $Date: 2010-01-11 16:02:23 +0300 (Пн, 11 янв 2010) $ $Author: irina $
 **/

if ( ! function_exists('seo_traker'))
{
	function seo_traker($placement='top')
	{
		$CI = &get_instance();
		$CI->load->model('Seo_model');
		$return = $CI->Seo_model->get_tracker_html($placement);
		echo $return;
	}

}
