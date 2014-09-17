<?php
$module['module'] = 'users_lists';
$module['install_name'] = 'Users lists module';
$module['install_descr'] = 'Friends and blacklist';
$module['version'] = '1.01';
$module['files'] = array(
	array('file', 'read', "application/modules/users_lists/controllers/api_users_lists.php"),
	array('file', 'read', "application/modules/users_lists/controllers/users_lists.php"),
	array('file', 'read', "application/modules/users_lists/helpers/users_lists_helper.php"),
	array('file', 'read', "application/modules/users_lists/install/demo_structure_install.sql"),
	array('file', 'read', "application/modules/users_lists/install/module.php"),
	array('file', 'read', "application/modules/users_lists/install/permissions.php"),
	array('file', 'read', "application/modules/users_lists/install/settings.php"),
	array('file', 'read', "application/modules/users_lists/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/users_lists/install/structure_install.sql"),
	array('file', 'read', "application/modules/users_lists/js/blacklist.js"),
	array('file', 'read', "application/modules/users_lists/js/friends-input.js"),
	array('file', 'read', "application/modules/users_lists/js/friends-select.js"),
	array('file', 'read', "application/modules/users_lists/js/lists_links.js"),
	array('file', 'read', "application/modules/users_lists/js/users_lists_multi_request.js"),
	array('file', 'read', "application/modules/users_lists/models/users_lists_callbacks_model.php"),
	array('file', 'read', "application/modules/users_lists/models/users_lists_install_model.php"),
	array('file', 'read', "application/modules/users_lists/models/users_lists_model.php"),
	array('file', 'read', "application/modules/users_lists/views/default/ajax_friend_select_form.tpl"),
	array('file', 'read', "application/modules/users_lists/views/default/helper_add_blacklist.tpl"),
	array('file', 'read', "application/modules/users_lists/views/default/helper_friend_input.tpl"),
	array('file', 'read', "application/modules/users_lists/views/default/helper_friend_select.tpl"),
	array('file', 'read', "application/modules/users_lists/views/default/helper_lists_links.tpl"),
	array('file', 'read', "application/modules/users_lists/views/default/request_block.tpl"),
	array('file', 'read', "application/modules/users_lists/views/default/users_lists.tpl"),
	array('file', 'read', "application/modules/users_lists/views/default/wall_events_users_lists.tpl"),
	array('dir', 'read', 'application/modules/users_lists/langs'),
);

$module['dependencies'] = array(
	'start'			=> array('version'=>'1.03'),
	'menu'			=> array('version'=>'2.03'),
	'users'			=> array('version'=>'3.01'),
	'notifications' => array('version'=>'1.04'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'			=> 'install_menu',
		'wall_events'	=> 'install_wall_events',
		'site_map'		=> 'install_site_map',
		'notifications'	=> 'install_notifications',
		'banners'		=> 'install_banners',
	),
	'deinstall' => array(
		'menu'			=> 'deinstall_menu',
		'wall_events'	=> 'deinstall_wall_events',
		'site_map'		=> 'deinstall_site_map',
		'notifications'	=> 'deinstall_notifications',
		'banners'		=> 'deinstall_banners',
	)
);