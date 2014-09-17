<?php
$module['module'] = 'seo';
$module['install_name'] = 'SEO settings';
$module['install_descr'] = 'Meta tags, analytics, tracker and robots.txt';
$module['version'] = '1.04';
$module['files'] = array(
	array('file', 'read', "application/modules/seo/controllers/admin_seo.php"),
	array('file', 'read', "application/modules/seo/helpers/seo_analytics_helper.php"),
	array('file', 'read', "application/modules/seo/helpers/seo_module_helper.php"),
	array('file', 'read', "application/modules/seo/install/module.php"),
	array('file', 'read', "application/modules/seo/install/permissions.php"),
	array('file', 'read', "application/modules/seo/install/settings.php"),
	array('file', 'read', "application/modules/seo/js/seo-url-creator.js"),
	array('file', 'read', "application/modules/seo/models/seo_install_model.php"),
	array('file', 'read', "application/modules/seo/models/seo_model.php"),
	array('file', 'read', "application/modules/seo/views/admin/css/style-ltr.css"),
	array('file', 'read', "application/modules/seo/views/admin/css/style-rtl.css"),
	array('file', 'read', "application/modules/seo/views/admin/default_edit_form.tpl"),
	array('file', 'read', "application/modules/seo/views/admin/default_list.tpl"),
	array('file', 'read', "application/modules/seo/views/admin/edit_form.tpl"),
	array('file', 'read', "application/modules/seo/views/admin/edit_robots_form.tpl"),
	array('file', 'read', "application/modules/seo/views/admin/edit_tracker_form.tpl"),
	array('file', 'read', "application/modules/seo/views/admin/link_default_listing.tpl"),
	array('file', 'read', "application/modules/seo/views/admin/list_analytics.tpl"),
	array('file', 'read', "application/modules/seo/views/admin/list.tpl"),
	array('file', 'read', "application/modules/seo/views/default/tracker_block.tpl"),
	array('dir', 'read', 'application/modules/seo/langs'),
	array('file', 'write', "application/config/seo_module_routes.php"),
	array('file', 'write', "application/config/seo_module_routes.xml"),
	array('file', 'write', "application/config/langs_route.php"),
	array('file', 'write', "robots.txt"),
	array('file', 'write', "sitemap.xml"),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.01'),
	'menu' => array('version'=>'1.01')
);

$module['libraries'] = array(
	'Whois', 'Googlepr'
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'ausers'	=> 'install_ausers',
		'cronjob'	=> 'install_cronjob',
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'ausers'	=> 'deinstall_ausers',
		'cronjob'	=> 'deinstall_cronjob',
	)
);
