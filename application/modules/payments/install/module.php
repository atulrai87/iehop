<?php
$module['module'] = 'payments';
$module['install_name'] = 'Payments module';
$module['install_descr'] = 'Payments settings (manage billing systems), view payments, manual payments and payments approve';
$module['version'] = '2.01';
$module['files'] = array(
	array('file', 'read', "application/modules/payments/helpers/payments_helper.php"),
	array('file', 'read', "application/modules/payments/controllers/admin_payments.php"),
	array('file', 'read', "application/modules/payments/controllers/api_payments.php"),
	array('file', 'read', "application/modules/payments/controllers/payments.php"),
	array('file', 'read', "application/modules/payments/install/demo_structure_install.sql"),
	array('file', 'read', "application/modules/payments/install/module.php"),
	array('file', 'read', "application/modules/payments/install/permissions.php"),
	array('file', 'read', "application/modules/payments/install/settings.php"),
	array('file', 'read', "application/modules/payments/install/structure_deinstall.sql"),
	array('file', 'read', "application/modules/payments/install/structure_install.sql"),
	array('file', 'read', "application/modules/payments/js/admin-payments-settings.js"),
	array('file', 'read', "application/modules/payments/js/admin-payments.js"),
	array('file', 'read', "application/modules/payments/models/systems/authorize_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/barclays_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/chronopay_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/daopay_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/egold_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/fortumo_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/gwallet_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/manual_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/offline_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/ogone_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/paygol_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/paypal_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/paypoint_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/pencepay_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/robokassa_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/skrill_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/smscoin_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/tcheckout_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/usaepay_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/webmoney_model.php"),
	array('file', 'read', "application/modules/payments/models/systems/worldpay_model.php"),
	array('file', 'read', "application/modules/payments/models/payment_currency_model.php"),
	array('file', 'read', "application/modules/payments/models/payment_driver_model.php"),
	array('file', 'read', "application/modules/payments/models/payment_systems_model.php"),
	array('file', 'read', "application/modules/payments/models/payments_install_model.php"),
	array('file', 'read', "application/modules/payments/models/payments_model.php"),
	array('file', 'read', "application/modules/payments/models/xe_currency_rates_model.php"),
	array('file', 'read', "application/modules/payments/models/yahoo_currency_rates_model.php"),
	array('file', 'read', "application/modules/payments/views/admin/css/style-ltr.css"),
	array('file', 'read', "application/modules/payments/views/admin/css/style-rtl.css"),
	array('file', 'read', "application/modules/payments/views/admin/css/style.css"),
	array('file', 'read', "application/modules/payments/views/admin/edit_settings.tpl"),
	array('file', 'read', "application/modules/payments/views/admin/edit_system.tpl"),
	array('file', 'read', "application/modules/payments/views/admin/helper_admin_home_block.tpl"),
	array('file', 'read', "application/modules/payments/views/admin/list_payments.tpl"),
	array('file', 'read', "application/modules/payments/views/admin/list_settings.tpl"),
	array('file', 'read', "application/modules/payments/views/admin/list_systems.tpl"),
	array('file', 'read', "application/modules/payments/views/default/helper_currency_regexp.tpl"),
	array('file', 'read', "application/modules/payments/views/default/helper_currency_select.tpl"),
	array('file', 'read', "application/modules/payments/views/default/helper_statistic.tpl"),
	array('file', 'read', "application/modules/payments/views/default/payment_form.tpl"),
	array('file', 'read', "application/modules/payments/views/default/payment_js.tpl"),
	array('file', 'read', "application/modules/payments/views/default/payment_return.tpl"),
	array('file', 'read', "application/modules/payments/views/default/statistic.tpl"),
	array('dir', 'read', "application/modules/payments/langs"),
	array('dir', 'write', "uploads/payments-logo"),
);

$module['libraries'] = array(
	'jwt',
	'simple_html_dom',
);

$module['dependencies'] = array(
	'start' => array('version'=>'1.03'),
	'menu' => array('version'=>'2.03'),
	'users' => array('version'=>'3.01'),
	'notifications' => array('version'=>'1.04'),
);

$module['linked_modules'] = array(
	'install' => array(
		'menu'		=> 'install_menu',
		'ausers'	=> 'install_ausers',
		'cronjob'		=> 'install_cronjob',
		'notifications'	=> 'install_notifications',
	),
	'deinstall' => array(
		'menu'		=> 'deinstall_menu',
		'ausers'	=> 'deinstall_ausers',
		'cronjob'		=> 'deinstall_cronjob',
		'notifications'	=> 'deinstall_notifications',
	)
);
