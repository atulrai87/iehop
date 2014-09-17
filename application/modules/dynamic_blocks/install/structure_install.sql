DROP TABLE IF EXISTS `[prefix]dynblocks_areas`;
CREATE TABLE IF NOT EXISTS `[prefix]dynblocks_areas` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(30) NOT NULL,
  `name` varchar(250) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'index-page', 'Index page', '2011-09-08 10:26:06');
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'mediumturquoise', 'MediumTurquoise theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'lavender', 'Lavender theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'adult', 'Couple theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'matrimonial', 'Matrimonial theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'boyfriends', 'Boyfriend theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'girlfriends', 'Girlfriend theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'niche', 'Niche theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'guys', 'Guys theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'christian', 'Christian theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'community', 'Community theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'companions', 'Companions theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'girls', 'Girls theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'jewish', 'Jewish singles theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'lovers', 'Hobby theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'neighbourhood', 'Neighbors theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'blackonwhite', 'Black on white theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'deepblue', 'Deep blue theme index page', '2014-06-30 15:18:49');

DROP TABLE IF EXISTS `[prefix]dynblocks_area_block`;
CREATE TABLE IF NOT EXISTS `[prefix]dynblocks_area_block` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_area` int(3) NOT NULL,
  `id_block` int(3) NOT NULL,
  `params` text NOT NULL,
  `view_str` varchar(100) NOT NULL,
  `width` tinyint(4) NOT NULL,
  `sorter` int(3) NOT NULL,
  `cache_time` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_area` (`id_area`,`id_block`,`sorter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]dynblocks_blocks`;
CREATE TABLE IF NOT EXISTS `[prefix]dynblocks_blocks` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(50) NOT NULL,
  `name` VARCHAR( 255 ) NOT NULL,
  `module` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `method` varchar(100) NOT NULL,
  `min_width` tinyint(4) NOT NULL,
  `params` text NOT NULL,
  `views` text NOT NULL,
  `date_add` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;