<?php

$module['module'] = 'polls';
$module['install_name'] = 'Polls management';
$module['install_descr'] = 'Manage polls and statistic';
$module['version'] = '1.04';

$module['files'] = array(
	array('file', 'read', "application/modules/polls/helpers/polls_helper.php"),
	array('file', 'read', "application/modules/polls/controllers/admin_polls.php"),
	array('file', 'read', "application/modules/polls/controllers/api_polls.php"),
	array('file', 'read', "application/modules/polls/controllers/polls.php"),
	array('file', 'read', "application/modules/polls/install/demo_content.php"),
	array('file', 'read', "application/modules/polls/install/module.php"),
	array('file', 'read', "application/modules/polls/install/permissions.php"),
	array('file', 'read', "application/modules/polls/install/settings.php"),
	array('file', 'read', "application/modules/polls/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/polls/install/structure_install.sql"),
	array('file', 'read', "application/modules/polls/js/admin-polls.js"),
	array('file', 'read', "application/modules/polls/js/polls.js"),
	array('file', 'read', "application/modules/polls/models/polls_install_model.php"),
	array('file', 'read', "application/modules/polls/models/polls_model.php"),
	array('file', 'read', "application/modules/polls/views/admin/css/style.css"),
	array('file', 'read', "application/modules/polls/views/admin/edit_answers.tpl"),
	array('file', 'read', "application/modules/polls/views/admin/edit_poll.tpl"),
	array('file', 'read', "application/modules/polls/views/admin/list_polls.tpl"),
	array('file', 'read', "application/modules/polls/views/admin/list_results.tpl"),
	array('file', 'read', "application/modules/polls/views/admin/helper_admin_home_block.tpl"),
	array('file', 'read', "application/modules/polls/views/default/css/style.css"),
	array('file', 'read', "application/modules/polls/views/default/list_polls.tpl"),
	array('file', 'read', "application/modules/polls/views/default/poll_form.tpl"),
	array('file', 'read', "application/modules/polls/views/default/poll_place_block.tpl"),
	array('file', 'read', "application/modules/polls/views/default/poll_results.tpl"),
	array('dir', 'read', "application/modules/polls/langs")
);

$module['dependencies'] = array(
	'start' => array('version' => '1.03'),
	'menu' => array('version' => '2.03'),
	'users' => array('version' => '3.01')
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'site_map'	=> 'install_site_map',
		'ausers'	=> 'install_ausers'
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'site_map'	=> 'deinstall_site_map',
		'ausers'	=> 'deinstall_ausers'
	)
);