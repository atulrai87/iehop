<?php

date_default_timezone_set(date_default_timezone_get());

define("INSTALL_DONE", true);
define("SHOW_CONFIG_READ_ERROR", false);

//define("SITE_SUBFOLDER", '~iehop/');
//define("SITE_PATH", '/home/iehop/public_html/');

//define("SITE_SERVER", 'http://162.144.100.131/');
//define("SITE_SERVER", 'http://162.159.243.99/');
define("SITE_SUBFOLDER", 'iehop/');
define("SITE_PATH", '');
define("SITE_PHYSICAL_PATH", SITE_PATH /*. SITE_SUBFOLDER*/ );
define("SITE_SERVER", 'http://localhost/');
define("COOKIE_SITE_SERVER", '');

define("SITE_VIRTUAL_PATH", SITE_SERVER . SITE_SUBFOLDER );

define("DB_HOSTNAME", 'localhost');
define("DB_USERNAME", 'iehop_dating');
define("DB_PASSWORD", 'h3K5E#^vE~TP');
define("DB_DATABASE", 'iehop_dating');
define("DB_PREFIX", 'pg_');
define("DB_DRIVER", "mysql");

define("UPLOAD_DIR", "uploads/");
define("DEFAULT_DIR", "default/");
define("DATASOURCE_ICONS_DIR", "datasource_icons/");

define("FRONTEND_PATH", SITE_PHYSICAL_PATH . UPLOAD_DIR);
define("FRONTEND_URL", SITE_VIRTUAL_PATH . UPLOAD_DIR);

define("GENERATE_BACKTRACE", false);
define("USE_PROFILING", false);
define("DISPLAY_ERRORS", true);
define("ADD_LANG_MODE", false);
define("DEMO_MODE", false);

/**
 * Set to true, if you use .htaccess rule to remove $config['index_page'] file 
 * from the site URLs
 */
define("HIDE_INDEX_PAGE", true);