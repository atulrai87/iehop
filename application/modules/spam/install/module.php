<?php
$module["module"] = "spam";
$module["install_name"] = "Spam";
$module["install_descr"] = "Spam objects";
$module["version"] = "1.02";
$module["files"] = array(
	array("file", "read", "application/modules/spam/controllers/admin_spam.php"),
	array("file", "read", "application/modules/spam/controllers/api_spam.php"),
	array("file", "read", "application/modules/spam/controllers/spam.php"),
	array("file", "read", "application/modules/spam/helpers/spam_helper.php"),
	array("file", "read", "application/modules/spam/install/module.php"),
	array("file", "read", "application/modules/spam/install/permissions.php"),
	array("file", "read", "application/modules/spam/install/settings.php"),
	array("file", "read", "application/modules/spam/install/structure_deinstall.sql"),
	array("file", "read", "application/modules/spam/install/structure_install.sql"),
	array("file", "read", "application/modules/spam/js/spam.js"),
	array("file", "read", "application/modules/spam/models/spam_alert_model.php"),
	array("file", "read", "application/modules/spam/models/spam_install_model.php"),
	array("file", "read", "application/modules/spam/models/spam_reason_model.php"),
	array("file", "read", "application/modules/spam/models/spam_type_model.php"),
	array("file", "read", "application/modules/spam/views/admin/css/style-ltr.css"),
	array("file", "read", "application/modules/spam/views/admin/css/style-rtl.css"),
	array("file", "read", "application/modules/spam/views/admin/alerts_list.tpl"),
	array("file", "read", "application/modules/spam/views/admin/alerts_view.tpl"),
	array("file", "read", "application/modules/spam/views/admin/helper_home_spam_block.tpl"),
	array("file", "read", "application/modules/spam/views/admin/reasons_edit.tpl"),
	array("file", "read", "application/modules/spam/views/admin/reasons_list.tpl"),
	array("file", "read", "application/modules/spam/views/admin/settings.tpl"),
	array("file", "read", "application/modules/spam/views/admin/types_edit.tpl"),
	array("file", "read", "application/modules/spam/views/admin/types_list.tpl"),
	array("file", "read", "application/modules/spam/views/default/helper_mark_as_spam.tpl"),
	array("file", "read", "application/modules/spam/views/default/mark_as_spam_form.tpl"),
	
	array("file", "dir", "application/modules/spam/langs"),
);
$module["dependencies"] = array(
	"start" => array("version"=>"1.01"),
	"menu" => array("version"=>"1.01"),
	"notifications" => array("version"=>"1.03"),
	"users"	=> array("version"=>"2.02"),
);

$module["linked_modules"] = array(
	"install" => array(
		"menu"			=> "install_menu",
		"notifications"	=> "install_notifications",
		"ausers"		=> "install_ausers",
	),
	"deinstall" => array(
		"menu"			=> "deinstall_menu",
		"notifications"	=> "deinstall_notifications",
		"ausers"		=> "deinstall_ausers",
	)
);
