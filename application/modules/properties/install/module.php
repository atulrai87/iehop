<?php
$module['module'] = 'properties';
$module['install_name'] = 'Properties management';
$module['install_descr'] = 'Manage properties , edit default properties';
$module['version'] = '1.03';

$module['files'] = array(
	array('file', 'read', "application/modules/properties/helpers/properties_helper.php"),
	array('file', 'read', "application/modules/properties/controllers/admin_properties.php"),
	array('file', 'read', "application/modules/properties/controllers/api_properties.php"),
	array('file', 'read', "application/modules/properties/controllers/properties.php"),
	array('file', 'read', "application/modules/properties/install/module.php"),
	array('file', 'read', "application/modules/properties/install/permissions.php"),
	array('file', 'read', "application/modules/properties/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/properties/install/structure_install.sql"),
	array('file', 'read', "application/modules/properties/js/job-category-select.js"),
	array('file', 'read', "application/modules/properties/js/job-category-search-select.js"),
	array('file', 'read', "application/modules/properties/models/properties_install_model.php"),
	array('file', 'read', "application/modules/properties/models/properties_model.php"),
	array('file', 'read', "application/modules/properties/models/job_categories_model.php"),
	array('file', 'read', "application/modules/properties/views/admin/css/style.css"),
	array('file', 'read', "application/modules/properties/views/admin/edit_ds_item.tpl"),
	array('file', 'read', "application/modules/properties/views/admin/helper_job_categories_input.tpl"),
	array('file', 'read', "application/modules/properties/views/admin/helper_properties_select.tpl"),
	array('file', 'read', "application/modules/properties/views/admin/list.tpl"),
	array('file', 'read', "application/modules/properties/views/admin/job_categories_list.tpl"),
	array('file', 'read', "application/modules/properties/views/admin/job_category_edit.tpl"),
	array('file', 'read', "application/modules/properties/views/admin/menu.tpl"),
	array('file', 'read', "application/modules/properties/views/default/ajax_category_form.tpl"),
	array('file', 'read', "application/modules/properties/views/default/ajax_category_search_form.tpl"),
	array('file', 'read', "application/modules/properties/views/default/helper_category_search_select.tpl"),
	array('file', 'read', "application/modules/properties/views/default/helper_category_select.tpl"),
	array('dir', 'read', 'application/modules/properties/langs'),
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'ausers'	=> 'install_ausers'
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'ausers'	=> 'deinstall_ausers'
	)
);