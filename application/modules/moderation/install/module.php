<?php
$module['module'] = 'moderation';
$module['install_name'] = 'Moderation module';
$module['install_descr'] = 'Allow to moderate different type contents in one place';
$module['version'] = '1.03';
$module['files'] = array(
	array('file', 'read', "application/modules/moderation/controllers/admin_moderation.php"),
	array('file', 'read', "application/modules/moderation/install/module.php"),
	array('file', 'read', "application/modules/moderation/install/permissions.php"),
	array('file', 'read', "application/modules/moderation/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/moderation/install/structure_install.sql"),
	array('file', 'read', "application/modules/moderation/models/moderation_badwords_model.php"),
	array('file', 'read', "application/modules/moderation/models/moderation_install_model.php"),
	array('file', 'read', "application/modules/moderation/models/moderation_model.php"),
	array('file', 'read', "application/modules/moderation/models/moderation_type_model.php"),
	array('file', 'read', "application/modules/moderation/views/admin/css/style.css"),
	array('file', 'read', "application/modules/moderation/views/admin/admin_moder_badwords.tpl"),
	array('file', 'read', "application/modules/moderation/views/admin/admin_moder_list.tpl"),
	array('file', 'read', "application/modules/moderation/views/admin/admin_moder_settings.tpl"),
	array('file', 'read', "application/modules/moderation/views/admin/link_settings.tpl"),
	array('dir', 'read', 'application/modules/moderation/langs'),
);
$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03')
);
$module['linked_modules'] = array(
	'install' => array(
		'ausers'	=> 'install_ausers',
		'menu'		=> 'install_menu'
	),
	'deinstall' => array(
		'ausers'	=> 'deinstall_ausers',
		'menu'		=> 'deinstall_menu'
	)
);