DROP TABLE IF EXISTS `[prefix]cnt_cache_countries`;
CREATE TABLE `[prefix]cnt_cache_countries` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` char(2) NOT NULL,
  `name` varchar(200) NOT NULL,
  `areainsqkm` double NOT NULL,
  `continent` char(2) NOT NULL,
  `currency` char(3) NOT NULL,
  `region_update_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]cnt_cache_regions`;
CREATE TABLE `[prefix]cnt_cache_regions` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `country_code` char(2) NOT NULL,
  `code` varchar(10) NOT NULL,
  `id_region` int(3) NOT NULL,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_code_1` (`country_code`,`code`),
  KEY `country_code` (`country_code`,`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]cnt_cities`;
CREATE TABLE `[prefix]cnt_cities` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_region` int(3) NOT NULL,
  `name` varchar(200) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `country_code` char(2) NOT NULL,
  `region_code` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_region` (`id_region`),
  KEY `country_code` (`country_code`,`region_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]cnt_countries`;
CREATE TABLE `[prefix]cnt_countries` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` char(2) NOT NULL,
  `name` varchar(200) NOT NULL,
  `areainsqkm` double NOT NULL,
  `continent` char(2) NOT NULL,
  `currency` char(3) NOT NULL,
  `priority` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]cnt_regions`;
CREATE TABLE `[prefix]cnt_regions` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `country_code` char(2) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `priority` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_code` (`country_code`,`priority`),
  KEY `country_code_2` (`country_code`,`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]cnt_cities` VALUES(1, 1, 'Paris', 48.8666670, 2.3333330, 'FR', 'A8');
INSERT INTO `[prefix]cnt_cities` VALUES(2, 2, 'Berlin', 52.5166667, 13.4000000, 'DE', '16');
INSERT INTO `[prefix]cnt_cities` VALUES(3, 3, 'Madrid', 40.4165000, -3.7025600, 'ES', '29');
INSERT INTO `[prefix]cnt_cities` VALUES(4, 4, 'London', 51.5085300, -0.1257400, 'GB', 'GLA');
INSERT INTO `[prefix]cnt_cities` VALUES(5, 5, 'New York City', 40.7142700, -74.0059700, 'US', 'NY');

INSERT INTO `[prefix]cnt_countries` VALUES(1, 'US', 'United States', 0, '', '', 1);
INSERT INTO `[prefix]cnt_countries` VALUES(2, 'GB', 'United Kingdom', 0, '', '', 2);
INSERT INTO `[prefix]cnt_countries` VALUES(3, 'FR', 'France', 0, '', '', 3);
INSERT INTO `[prefix]cnt_countries` VALUES(4, 'DE', 'Germany', 0, '', '', 4);
INSERT INTO `[prefix]cnt_countries` VALUES(5, 'ES', 'Spain', 0, '', '', 5);

INSERT INTO `[prefix]cnt_regions` VALUES(1, 'FR', 'A8', 'Région Île-de-France', 1);
INSERT INTO `[prefix]cnt_regions` VALUES(2, 'DE', '16', 'Land Berlin', 1);
INSERT INTO `[prefix]cnt_regions` VALUES(3, 'ES', '29', 'Comunidad de Madrid', 1);
INSERT INTO `[prefix]cnt_regions` VALUES(4, 'GB', 'GLA', 'Greater London', 1);
INSERT INTO `[prefix]cnt_regions` VALUES(5, 'US', 'NY', 'New York', 1);
