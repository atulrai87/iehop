<?php
$module['module'] = 'likes';
$module['install_name'] = 'Likes module';
$module['install_descr'] = 'Module for liking objects';
$module['version'] = '1.01';
$module['files'] = array(
	array('file', 'read', "application/hooks/autoload/pre_view-get_likes.php"),
	array('file', 'read', "application/modules/likes/controllers/likes.php"),
	array('file', 'read', "application/modules/likes/helpers/likes_helper.php"),
	array('file', 'read', "application/modules/likes/install/module.php"),
	array('file', 'read', "application/modules/likes/install/permissions.php"),
	array('file', 'read', "application/modules/likes/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/likes/install/structure_install.sql"),
	array('file', 'read', "application/modules/likes/js/likes.js"),
	array('file', 'read', "application/modules/likes/models/likes_install_model.php"),
	array('file', 'read', "application/modules/likes/models/likes_model.php"),
	array('file', 'read', "application/modules/likes/views/default/helper_button.tpl"),
	array('file', 'read', "application/modules/likes/views/default/helper_likes.tpl"),
	array('file', 'read', "application/modules/likes/views/default/users.tpl"),
	array('dir', 'read', 'application/modules/likes/langs'),
);
$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'users' => array('version'=>'3.01'),
);