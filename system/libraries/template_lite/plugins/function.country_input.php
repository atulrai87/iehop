<?php

/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_country_input($params, &$tpl) {
    $tpl->CI->load->helper('countries');
    return country_input($params['select_type'], $params['id_country'], $params['id_region'], $params['id_city'], $params['var_country'], $params['var_region'], $params['var_city'], $params['var_js'], $params['placeholder'], $params['id_district'], $params['var_district']);
}
