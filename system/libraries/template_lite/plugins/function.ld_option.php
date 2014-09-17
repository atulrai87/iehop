<?php

function tpl_function_ld_option($params, &$tpl)
{
	return ld_option($params['i'], $params['gid'], $params['option'], $params['lang_id'], $params['default']);
}
