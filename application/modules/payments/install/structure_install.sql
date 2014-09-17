DROP TABLE IF EXISTS `[prefix]payments`;
CREATE TABLE IF NOT EXISTS `[prefix]payments` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `payment_type_gid` varchar(20) NOT NULL,
  `id_user` int(3) NOT NULL,
  `amount` float NOT NULL,
  `currency_gid` varchar(5) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `system_gid` varchar(20) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  `payment_data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_type_gid` (`payment_type_gid`,`id_user`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]payments_currency`;
CREATE TABLE IF NOT EXISTS `[prefix]payments_currency` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(10) NOT NULL,
  `abbr` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `per_base` decimal(7,4) NOT NULL,
  `template` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]payments_currency` VALUES(1, 'USD', '$', 'American Dollar', '1', '[abbr][value|dec_part:|dec_sep:.|gr_sep: ]', 1);
INSERT INTO `[prefix]payments_currency` VALUES(2, 'RUB', 'руб.', 'Российский рубль', '0.0304', '[value|dec_part:|dec_sep:.|gr_sep: ] [abbr]', 0);
INSERT INTO `[prefix]payments_currency` VALUES(3, 'EUR', '€', 'Euro', '1.3411', '[abbr][value|dec_part:|dec_sep:.|gr_sep: ]', 0);

DROP TABLE IF EXISTS `[prefix]payments_log`;
CREATE TABLE IF NOT EXISTS `[prefix]payments_log` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `system_gid` varchar(20) NOT NULL,
  `log_type` varchar(10) NOT NULL,
  `payment_data` text NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]payments_systems`;
CREATE TABLE IF NOT EXISTS `[prefix]payments_systems` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `in_use` tinyint(3) NOT NULL,
  `date_add` datetime NOT NULL,
  `settings_data` text NOT NULL,
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`),
  KEY `in_use` (`in_use`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]payments_systems` VALUES(1, 'paypal', 'Paypal', 0, '2011-05-16 13:52:48', 'a:1:{s:9:"seller_id";s:0:"";}', 'logo_paypal.png');
INSERT INTO `[prefix]payments_systems` VALUES(2, 'authorize', 'Authorize.net', 0, '2011-05-18 11:36:00', 'a:2:{s:15:"transaction_key";s:16:"ab34986jg5678655";s:9:"seller_id";s:11:"authnettest";}', 'logo_authorize.png');
INSERT INTO `[prefix]payments_systems` VALUES(3, 'webmoney', 'Webmoney', 0, '2011-05-18 11:36:00', 'a:2:{s:9:"seller_id";s:13:"R397656178472";s:11:"secret_word";s:10:"secret_key";}', 'logo_webmoney.png');
INSERT INTO `[prefix]payments_systems` VALUES(4, 'offline', 'Offline payment', 1, '2011-05-18 11:36:00', '', 'logo_offline.png');
INSERT INTO `[prefix]payments_systems` VALUES(5, 'tcheckout', '2Checkout', 0, '2011-05-26 09:17:42', 'a:2:{s:9:"seller_id";s:9:"2checkout";s:11:"secret_word";s:18:"2checkout@mail.com";}', 'logo_tcheckout.png');
INSERT INTO `[prefix]payments_systems` VALUES(6, 'smscoin', 'SMS Coin', 0, '2012-09-16 09:17:42', 'a:2:{s:8:"purse_id";s:7:"smscoin";s:11:"secret_word";s:16:"smscoin@mail.com";}', 'logo_smscoin.png');
INSERT INTO `[prefix]payments_systems` VALUES(7, 'paygol', 'Paygol', 0, '2012-09-16 09:17:42', 'a:1:{s:9:"serviceid";s:6:"paygol";}', 'logo_paygol.png');
INSERT INTO `[prefix]payments_systems` VALUES(8, 'robokassa', 'Robokassa', 0, '2013-10-23 11:36:00', 'a:3:{s:14:"merchant_login";s:5:"login";s:14:"merchant_pass1";s:9:"password1";s:14:"merchant_pass2";s:9:"password2";}', 'logo_robokassa.png');
INSERT INTO `[prefix]payments_systems` VALUES(9, 'gwallet', 'Google Wallet', 0, '2013-10-23 11:36:00', 'a:2:{s:9:"seller_id";s:9:"seller id";s:13:"seller_secret";s:13:"seller secret";}', 'logo_gwallet.png');
INSERT INTO `[prefix]payments_systems` VALUES(10, 'skrill', 'Skrill', 0, '2013-10-23 11:36:00', 'a:2:{s:9:"seller_id";s:9:"seller id";s:11:"seller_word";s:11:"secret word";}', 'logo_skrill.png');
#INSERT INTO `[prefix]payments_systems` VALUES(11, 'pencepay', 'Pencepay', 0, '2013-10-23 11:36:00', 'a:1:{s:9:"seller_id";s:9:"seller id";}', 'logo_pencepay.png');
#INSERT INTO `[prefix]payments_systems` VALUES(12, 'fortumo', 'Fortumo', 0, '2013-10-23 11:36:00', 'a:2:{s:9:"seller_id";s:9:"seller id";s:11:"seller_word";s:11:"secret word";}', 'logo_fortumo.png');

DROP TABLE IF EXISTS `[prefix]payments_type`;
CREATE TABLE IF NOT EXISTS `[prefix]payments_type` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `gid` varchar(30) NOT NULL,
  `callback_module` varchar(50) NOT NULL,
  `callback_model` varchar(50) NOT NULL,
  `callback_method` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
