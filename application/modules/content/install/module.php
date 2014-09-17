<?php
$module['module'] = 'content';
$module['install_name'] = 'Text content managment';
$module['install_descr'] = 'Module allow to create info pages';
$module['version'] = '2.04';
$module['files'] = array(
	array('file', 'read', "application/modules/content/controllers/api_content.php"),
	array('file', 'read', "application/modules/content/controllers/admin_content.php"),
	array('file', 'read', "application/modules/content/controllers/content.php"),
	array('file', 'read', "application/modules/content/install/demo_content.php"),
	array('file', 'read', "application/modules/content/install/module.php"),
	array('file', 'read', "application/modules/content/install/permissions.php"),
	array('file', 'read', "application/modules/content/install/settings.php"),
	array('file', 'read', "application/modules/content/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/content/install/structure_install.sql"),
	array('file', 'read', "application/modules/content/models/content_install_model.php"),
	array('file', 'read', "application/modules/content/models/content_model.php"),
	array('file', 'read', "application/modules/content/models/content_promo_model.php"),
	array('file', 'read', "application/modules/content/views/default/dynamic_block_info_pages.tpl"),
	array('file', 'read', "application/modules/content/views/default/list.tpl"),
	array('file', 'read', "application/modules/content/views/default/show_block.tpl"),
	array('file', 'read', "application/modules/content/views/default/show_promo_block.tpl"),
	array('file', 'read', "application/modules/content/views/default/tree.tpl"),
	array('file', 'read', "application/modules/content/views/default/view.tpl"),
	array('file', 'read', "application/modules/content/views/admin/link_index.tpl"),
	array('file', 'read', "application/modules/content/views/admin/css/style.css"),
	array('file', 'read', "application/modules/content/views/admin/edit_form.tpl"),
	array('file', 'read', "application/modules/content/views/admin/promo_form.tpl"),
	array('file', 'read', "application/modules/content/views/admin/list.tpl"),
	array('file', 'read', "application/modules/content/views/admin/tree_level.tpl"),
	array('file', 'read', "application/modules/content/helpers/content_helper.php"),
	array('dir', 'read', "application/modules/content/langs"),
);
$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
	'uploads' => array('version'=>'1.03'),
	'file_uploads' => array('version'=>'1.03'),
);
$module['linked_modules'] = array(
	'install' => array(
		'menu' => 'install_menu',
		'site_map' => 'install_site_map',
		'banners' => 'install_banners',
		'ausers' => 'install_ausers',
		'uploads' => 'install_uploads',
		'file_uploads' => 'install_file_uploads',
		'social_networking' => 'install_social_networking',
		'dynamic_blocks' => 'install_dynamic_blocks'
	),
	'deinstall' => array(
		'menu' => 'deinstall_menu',
		'site_map' => 'deinstall_site_map',
		'banners' => 'deinstall_banners',
		'ausers' => 'deinstall_ausers',
		'uploads' => 'deinstall_uploads',
		'file_uploads' => 'deinstall_file_uploads',
		'social_networking' => 'deinstall_social_networking',
		'dynamic_blocks' => 'deinstall_dynamic_blocks'
	)
);