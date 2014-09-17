DROP TABLE IF EXISTS `[prefix]ausers`;
CREATE TABLE IF NOT EXISTS `[prefix]ausers` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(3) NOT NULL,
  `lang_id` int(3) NOT NULL,
  `user_type` varchar(10) NOT NULL,
  `permission_data` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nickname` (`nickname`),
  KEY `password` (`password`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]ausers_moderate_methods`;
CREATE TABLE `[prefix]ausers_moderate_methods` (
`id` INT( 3 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`module` VARCHAR( 25 ) NOT NULL ,
`method` VARCHAR( 100 ) NOT NULL ,
`is_default` TINYINT( 3 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;