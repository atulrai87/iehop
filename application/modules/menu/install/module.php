<?php
$module['module'] = 'menu';
$module['install_name'] = 'Menu managment';
$module['install_descr'] = 'Module allow to create menus';
$module['version'] = '2.03';
$module['files'] = array(
	array('file', 'read', "application/modules/menu/controllers/admin_menu.php"),
	array('file', 'read', "application/modules/menu/install/module.php"),
	array('file', 'read', "application/modules/menu/install/permissions.php"),
	array('file', 'read', "application/modules/menu/install/settings.php"),
	array('file', 'read', "application/modules/menu/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/menu/install/structure_install.sql"),
	array('file', 'read', "application/modules/menu/models/menu_install_model.php"),
	array('file', 'read', "application/modules/menu/models/menu_model.php"),
	array('file', 'read', "application/modules/menu/models/indicators_model.php"),
	array('file', 'read', "application/modules/menu/views/admin/css/style.css"),
	array('file', 'read', "application/modules/menu/views/admin/edit_form.tpl"),
	array('file', 'read', "application/modules/menu/views/admin/edit_item_form.tpl"),
	array('file', 'read', "application/modules/menu/views/admin/items_list.tpl"),
	array('file', 'read', "application/modules/menu/views/admin/level1_menu.tpl"),
	array('file', 'read', "application/modules/menu/views/admin/level2_menu.tpl"),
	array('file', 'read', "application/modules/menu/views/admin/list.tpl"),
	array('file', 'read', "application/modules/menu/views/admin/main_menu.tpl"),
	array('file', 'read', "application/modules/menu/views/admin/tree_level.tpl"),
	array('file', 'read', "application/modules/menu/views/default/account_menu.tpl"),
	array('file', 'read', "application/modules/menu/views/default/guest_main_menu.tpl"),
	array('file', 'read', "application/modules/menu/views/default/helper_breadcrumbs.tpl"),
	array('file', 'read', "application/modules/menu/views/default/settings_menu.tpl"),
	array('file', 'read', "application/modules/menu/views/default/user_footer_menu.tpl"),
	array('file', 'read', "application/modules/menu/views/default/user_main_menu.tpl"),
	array('file', 'read', "application/modules/menu/views/default/user_top_menu.tpl"),
	array('file', 'read', "application/modules/menu/helpers/menu_helper.php"),
	array('dir', 'read', "application/modules/menu/langs"),
	array('file', 'read', "application/hooks/autoload/post_controller_constructor-fetch_menu_indicators.php"),
);
$module['linked_modules'] = array(
	'install' => array(
		'ausers'	=> 'install_ausers',
		'start'		=> 'install_menu'
	),
	'deinstall' => array(
		'ausers'	=> 'deinstall_ausers',
		'start'		=> 'deinstall_menu'
	)
);