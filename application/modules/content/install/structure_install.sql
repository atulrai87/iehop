DROP TABLE IF EXISTS `[prefix]content`;
CREATE TABLE IF NOT EXISTS `[prefix]content` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `lang_id` int(3) NOT NULL,
  `parent_id` int(3) NOT NULL,
  `gid` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `sorter` int(3) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `id_seo_settings` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_id` (`lang_id`),
  KEY `parent_id` (`parent_id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]content_promo`;
CREATE TABLE `[prefix]content_promo` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_lang` int(3) NOT NULL,
  `content_type` char(1) NOT NULL,
  `promo_text` text NOT NULL,
  `promo_image` varchar(255) NOT NULL,
  `promo_flash` varchar(255) NOT NULL,
  `block_width` int(3) NOT NULL,
  `block_width_unit` varchar(4) NOT NULL,
  `block_height` int(3) NOT NULL,
  `block_height_unit` varchar(4) NOT NULL,
  `block_align_hor` varchar(10) NOT NULL,
  `block_align_ver` varchar(10) NOT NULL,
  `block_image_repeat` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_lang` (`id_lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `[prefix]content_promo` VALUES(1, 1, 't', '<p><span style="font-size:20px">Welcome to Dating Pro, professional service</span></p>\n\n<p><span style="font-size:20px">for Online Dating and Networking.<br />\nThousands of members join our community</span></p>\n\n<p><span style="font-size:20px">from all over the world.<br />\nCreate a profile, post your photos, and soon</span></p>\n\n<p><span style="font-size:20px">you will be communicating with all these incredible people.</span></p>\n\n<p><span style="font-size:20px">We hope that our site is the place where you</span></p>\n\n<p><span style="font-size:20px">will find your lifemate!</span></p>', '0532da456d.jpg', '', 100, '%', 0, 'auto', 'right', 'top', 'no-repeat');
INSERT INTO `[prefix]content_promo` VALUES(2, 2, 't', '<p><span style="font-size:20px">Добро пожаловать на сайт знакомств</span></p><p><span style="font-size:20px">Dating Pro - место, где люди находят</span></p><p><span style="font-size:20px">друг друга.<br />Создайте свою анкету, загрузите</span></p><p><span style="font-size:20px">фотографии, и Вы сможете общаться</span></p><p><span style="font-size:20px">с замечательными людьми на нашем</span></p><p><span style="font-size:20px">сайте. К нам уже присоединились тысячи</span></p><p><span style="font-size:20px">человек, и теперь мы рады приветствовать</span></p><p><span style="font-size:20px">Вас. Начните сегодня и сделайте шаг</span></p><p><span style="font-size:20px">вперед навстречу своей судьбе!</span></p>', 'promo.jpg', '', 100, '%', 0, 'auto', 'right', 'top', 'no-repeat');
INSERT INTO `[prefix]content_promo` VALUES(3, 3, 't', '', 'promo.jpg', '', 100, '%', 0, 'auto', 'right', 'top', 'no-repeat');
