<?php
/**
* Dynamic blocks install model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Dynamic_blocks_install_model extends Model
{
	private $CI;
	private $menu = array(
		'admin_menu' => array(
			'action' => 'none',
			'items' => array(
				'settings_items' => array(
					'action' => 'none',
					'items' => array(
						'interface-items' => array(
							'action'=>'none',
							'items' => array(
								'dynblock_menu_item' => array('action' => 'create', 'link' => 'admin/dynamic_blocks', 'status' => 1, 'sorter' => 1)
							)
						)
					)
				)
			)
		),
		'admin_dynblocks_menu' => array(
			'action' => 'create',
			'name' => 'Admin mode - Interface - Dynamic blocks',
			'items' => array(
				'areas_list_item' => array('action' => 'create', 'link' => 'admin/dynamic_blocks', 'status' => 1),
				'blocks_list_item' => array('action' => 'create', 'link' => 'admin/dynamic_blocks/blocks', 'status' => 0)
			)
		)
	);
	
	private $ausers_methods = array(
		array('module' => 'dynamic_blocks', 'method' => 'index', 'is_default' => 1),
		array('module' => 'dynamic_blocks', 'method' => 'area_blocks', 'is_default' => 0),
	);

	/**
	 * Dynamic blocks
	 * @var array
	 */
	private $dynamic_blocks = array(
		array(
			"gid" => "html_code",
			"module" => "dynamic_blocks",
			"model" => "Dynamic_blocks_model",
			"method" => "_dynamic_block_get_html_code",
			"params" => array(
				"title"=>array("gid"=>"title", "type"=>"text", "default"=>""),
				"html"=>array("gid"=>"html", "type"=>"textarea", "default"=>""),
			),
			"views" => array(array("gid"=>"default")),
			"area" => array(
				array(
					'gid' => 'mediumturquoise',
					'params' => 'a:3:{s:7:"title_1";s:0:"";s:7:"title_2";s:0:"";s:4:"html";s:541:"<div style="color: #333; float: right; line-height: 24px; text-shadow:  1px 1px 0 rgba(255,255,255,0.5);"><h1>Welcome to Dating Pro</h1><p style="margin-bottom: 12px;">Professional service for Online Dating and Networking. Thousands of members join our communityfrom all over the world.</p><p style="margin-bottom: 12px;">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</p><p style="margin-bottom: 12px;">We hope that our site is the place where you will find your lifemate!</p></div>";}',
					'view_str' => 'default',
					'width' => 30,
					'sorter' => 9,
					'cache_time' => 0,
				),
				array(
					'gid' => 'lavender',
					'params' => 'a:3:{s:7:"title_1";s:0:"";s:7:"title_2";s:0:"";s:4:"html";s:541:"<div style="color: #333; float: right; line-height: 24px; text-shadow:  1px 1px 0 rgba(255,255,255,0.5);"><h1>Welcome to Dating Pro</h1><p style="margin-bottom: 12px;">Professional service for Online Dating and Networking. Thousands of members join our communityfrom all over the world.</p><p style="margin-bottom: 12px;">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</p><p style="margin-bottom: 12px;">We hope that our site is the place where you will find your lifemate!</p></div>";}',
					'view_str' => 'login_form',
					'width' => 30,
					'sorter' => 9,
					'cache_time' => 0,
				),
			),
		),
		array(
			"gid" => "rich_text",
			"module" => "dynamic_blocks",
			"model" => "Dynamic_blocks_model",
			"method" => "_dynamic_block_get_rich_text",
			"params" => array(
				"title"=>array("gid"=>"title", "type"=>"text", "default"=>""),
				"html"=>array("gid"=>"html", "type"=>"wysiwyg", "default"=>""),
			),
			"views" => array(array("gid"=>"default")),
			"area" => array(
				array(
					'gid' => 'index-page', 
					'params' => 'a:4:{s:7:"title_1";s:0:"";s:7:"title_2";s:0:"";s:6:"html_1";s:541:"<p><span style="font-size:24px">Welcome to Dating Pro</span></p><p>&nbsp;</p><span style="font-size:13px; line-height:1.6em">Professional service for Online Dating and Networking. Thousands of members join our communityfrom all over the world.</span><p>&nbsp;</p><p><span style="line-height:1.6em">Create a profile, post your photos, and soon you will be communicating with all these incredible people.</span></p><p>&nbsp;</p><p><span style="line-height:1.6em">We hope that our site is the place where you will find your lifemate!</span></p>";s:6:"html_2";s:0:"";}', 
					'view_str' => 'default', 
					'width' => 30,
					'sorter' => 6,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'index-page', 
					'params' => 'a:4:{s:7:"title_1";s:16:"Want to join in?";s:7:"title_2";s:42:"Хотите присоединиться?";s:6:"html_1";s:425:"<p><span style="font-size:16px">Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:6:"html_2";s:691:"<p><span style="font-size:16px">Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</span></p>";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 11,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'girls', 
					'params' => 'a:4:{s:7:"title_1";s:16:"Want to join in?";s:7:"title_2";s:42:"Хотите присоединиться?";s:6:"html_1";s:425:"<p><span style="font-size:16px">Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:6:"html_2";s:691:"<p><span style="font-size:16px">Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</span></p>";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 9,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'jewish', 
					'params' => 'a:4:{s:7:"title_1";s:8:"About us";s:7:"title_2";s:9:"О нас";s:6:"html_1";s:445:"<p><span style="line-height:1.6em">Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</span></p>";s:6:"html_2";s:698:"<p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</p>";}', 
					'view_str' => 'default', 
					'width' => 30,
					'sorter' => 7,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lovers', 
					'params' => 'a:4:{s:7:"title_1";s:8:"About Us";s:7:"title_2";s:9:"О нас";s:6:"html_1";s:406:"<p>Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match!</p>";s:6:"html_2";s:698:"<p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе!</p>";}', 
					'view_str' => 'default', 
					'width' => 30,
					'sorter' => 8,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lovers', 
					'params' => 'a:4:{s:7:"title_1";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:7:"title_2";s:144:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:6:"html_1";s:687:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p>&nbsp;</p><p><span style="line-height:1.6em">Want to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</span></p>";s:6:"html_2";s:1130:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p>Хотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 11,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'neighbourhood', 
					'params' => 'a:4:{s:7:"title_1";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:7:"title_2";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:6:"html_1";s:635:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p><br />\nWant to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</p>";s:6:"html_2";s:1138:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p><br />\nХотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 9,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'deepblue', 
					'params' => 'a:4:{s:7:"title_1";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:7:"title_2";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:6:"html_1";s:635:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p><br />\nWant to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</p>";s:6:"html_2";s:1138:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p><br />\nХотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 9,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'companions', 
					'params' => 'a:4:{s:7:"title_1";s:77:"Welcome to Dating Pro, professional service for Online Dating and Networking.";s:7:"title_2";s:152:"Добро пожаловать на Dating Pro, профессиональный сервис для онлайн знакомств и общения.";s:6:"html_1";s:635:"<p>Millions of members worldwide, looking for others to share their experiences with, are here in our community now! New singles are joining all the time, and many are making connections every day.</p><p><br />\nWant to join in? Just create a simple profile, post up your photos, and soon you&#39;ll be networking with our incredible personals. Online personals couldn&#39;t be easier! You can even upgrade your membership for email, instant messaging, and real-time chat. For real relationships, romance, friendships, networking and more, Dating Pro is the place for you. Get started today and find your match! &nbsp; &nbsp;&nbsp;</p>";s:6:"html_2";s:1138:"<p>Миллионы пользователей по всему миру ищут вас, чтобы поделиться своими впечатлениями и переживаниями! Новые люди присоединяются к нам постоянно, и вас ждет успех в поиске идеального партнера, который всегда будет с вами!.</p><p><br />\nХотите присоединиться? Просто создайте свою анкету, добавьте фотографии и вы сможете общаться со всеми замечательными людьми на этом сайте. Что может быть проще! Вы можете получить особые привилегии для более эффективного общения на сайте. Реальные взаимотношения, общение, дружба и многое другое! Dating Pro - это твой сайт! Начни сегодня и сделай шаг вперед навстречу своей судьбе! &nbsp; &nbsp;&nbsp;</p>";}', 
					'view_str' => 'default', 
					'width' => 30,
					'sorter' => 10,
					'cache_time' => 0, 
				),
			),
		),
		array(
			"gid" => "empty_block",
			"module" => "dynamic_blocks",
			"model" => "Dynamic_blocks_model",
			"method" => "_dynamic_block_get_empty_block",
			"params" => array(
				"height"=>array("gid"=>"height", "type"=>"int", "default"=>"100"),
			),
			"views" => array(array("gid"=>"default")),
			"area" => array(
				array(
					'gid' => 'index-page', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'index-page', 
					'params' => 'a:1:{s:6:"height";s:2:"30";}', 
					'view_str' => 'default', 
					'width' => 30,
					'sorter' => 8,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'index-page', 
					'params' => 'a:1:{s:6:"height";s:2:"30";}', 
					'view_str' => 'default', 
					'width' => 30,
					'sorter' => 7,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'index-page', 
					'params' => 'a:1:{s:6:"height";s:3:"175";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 5,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'index-page', 
					'params' => 'a:1:{s:6:"height";s:2:"30";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 9,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'index-page', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 12,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'mediumturquoise', 
					'params' => 'a:1:{s:6:"height";s:3:"100";}', 
					'view_str' => 'default', 
					'width' => 70,
					'sorter' => 5,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'mediumturquoise', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 7,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'mediumturquoise', 
					'params' => 'a:1:{s:6:"height";s:2:"15";}', 
					'view_str' => 'default', 
					'width' => 70,
					'sorter' => 8,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'mediumturquoise', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 10,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'mediumturquoise', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 12,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'mediumturquoise', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0, 
				),				
				array(
					'gid' => 'lavender', 
					'params' => 'a:1:{s:6:"height";s:3:"100";}', 
					'view_str' => 'default', 
					'width' => 70,
					'sorter' => 5,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lavender', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 7,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lavender', 
					'params' => 'a:1:{s:6:"height";s:2:"15";}', 
					'view_str' => 'default', 
					'width' => 70,
					'sorter' => 8,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lavender', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 10,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lavender', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 12,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lavender', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'girls', 
					'params' => 'a:1:{s:6:"height";s:2:"30";}', 
					'view_str' => 'default', 
					'width' => 30,
					'sorter' => 5,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'girls', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'girlfriends', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'girls', 
					'params' => 'a:1:{s:6:"height";s:2:"30";}', 
					'view_str' => 'default', 
					'width' => 40,
					'sorter' => 7,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'girls', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 10,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'girls', 
					'params' => 'a:1:{s:6:"height";s:3:"190";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 8,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'girls', 
					'params' => 'a:1:{s:6:"height";s:3:"175";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 4,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'jewish', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 4,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lovers', 
					'params' => 'a:1:{s:6:"height";s:3:"340";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 4,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'lovers', 
					'params' => 'a:1:{s:6:"height";s:2:"40";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 6,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'neighbourhood', 
					'params' => 'a:1:{s:6:"height";s:3:"100";}', 
					'view_str' => 'default', 
					'width' => 70,
					'sorter' => 6,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'neighbourhood', 
					'params' => 'a:1:{s:6:"height";s:3:"190";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 7,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'neighbourhood', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'neighbourhood', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 4,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'blackonwhite', 
					'params' => 'a:1:{s:6:"height";s:3:"270";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 5,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'blackonwhite', 
					'params' => 'a:1:{s:6:"height";s:2:"60";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 8,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'blackonwhite', 
					'params' => 'a:1:{s:6:"height";s:3:"100";}', 
					'view_str' => 'default', 
					'width' => 50,
					'sorter' => 7,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'blackonwhite', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'deepblue', 
					'params' => 'a:1:{s:6:"height";s:3:"320";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 4,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'deepblue', 
					'params' => 'a:1:{s:6:"height";s:2:"40";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 6,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'deepblue', 
					'params' => 'a:1:{s:6:"height";s:2:"15";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 8,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'deepblue', 
					'params' => 'a:1:{s:6:"height";s:2:"15";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 10,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'companions', 
					'params' => 'a:1:{s:6:"height";s:3:"100";}', 
					'view_str' => 'default', 
					'width' => 70,
					'sorter' => 5,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'companions', 
					'params' => 'a:1:{s:6:"height";s:3:"100";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 4,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'companions', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'companions', 
					'params' => 'a:1:{s:6:"height";s:3:"100";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 7,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'companions', 
					'params' => 'a:1:{s:6:"height";s:3:"100";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 11,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'christian', 
					'params' => 'a:1:{s:6:"height";s:3:"240";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 6,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'christian', 
					'params' => 'a:1:{s:6:"height";s:2:"50";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 8,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'christian', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 10,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'christian', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 1,
					'cache_time' => 0, 
				),
				array(
					'gid' => 'christian', 
					'params' => 'a:1:{s:6:"height";s:1:"0";}', 
					'view_str' => 'default', 
					'width' => 100,
					'sorter' => 4,
					'cache_time' => 0, 
				),
			),
		),
	);
	
	/**
	 * Constructor
	 *
	 * @return Install object
	 */
	function Dynamic_blocks_install_model()
	{
		parent::Model();
		$this->CI = & get_instance();
		//// load langs
		$this->CI->load->model('Install_model');
	}

	public function install_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			$this->menu[$gid]['id'] = linked_install_set_menu($gid, $menu_data["action"], $menu_data["name"]);
			linked_install_process_menu_items($this->menu, 'create', $gid, 0, $this->menu[$gid]["items"]);
		}
	}

	public function install_menu_lang_update($langs_ids = null) {
		if(empty($langs_ids)) return false;
		$langs_file = $this->CI->Install_model->language_file_read('dynamic_blocks', 'menu', $langs_ids);

		if(!$langs_file) { log_message('info', 'Empty menu langs data'); return false; }

		$this->CI->load->helper('menu');

		foreach($this->menu as $gid => $menu_data){
			linked_install_process_menu_items($this->menu, 'update', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_file);
		}
		return true;
	}

	public function install_menu_lang_export($langs_ids) {
		if(empty($langs_ids)) return false;
		$this->CI->load->helper('menu');

		$return = array();
		foreach($this->menu as $gid => $menu_data){
			$temp = linked_install_process_menu_items($this->menu, 'export', $gid, 0, $this->menu[$gid]["items"], $gid, $langs_ids);
			$return = array_merge($return, $temp);
		}
		return array( "menu" => $return );
	}

	public function deinstall_menu() {
		$this->CI->load->helper('menu');
		foreach($this->menu as $gid => $menu_data){
			if($menu_data['action'] == 'create'){
				linked_install_set_menu($gid, 'delete');
			}else{
				linked_install_delete_menu_items($gid, $this->menu[$gid]['items']);
			}
		}
	}

	/**
	 * Ausers module methods
	 */
	public function install_ausers() {
		// install ausers permissions
		$this->CI->load->model('Ausers_model');

		foreach($this->ausers_methods as $method){
			$this->CI->Ausers_model->save_method(null, $method);
		}
	}

	public function install_ausers_lang_update($langs_ids = null) {
		$langs_file = $this->CI->Install_model->language_file_read('dynamic_blocks', 'ausers', $langs_ids);

		// install ausers permissions
		$this->CI->load->model('Ausers_model');
		$params['where']['module'] = 'dynamic_blocks';
		$methods = $this->CI->Ausers_model->get_methods_lang_export($params);

		foreach($methods as $method){
			if(!empty($langs_file[$method['method']])){
				$this->CI->Ausers_model->save_method($method['id'], array(), $langs_file[$method['method']]);
			}
		}
	}

	public function install_ausers_lang_export($langs_ids) {
		$this->CI->load->model('Ausers_model');
		$params['where']['module'] = 'dynamic_blocks';
		$methods =  $this->CI->Ausers_model->get_methods_lang_export($params, $langs_ids);
		foreach($methods as $method){
			$return[$method['method']] = $method['langs'];
		}
		return array('ausers' => $return);
	}

	public function deinstall_ausers() {
		// delete moderation methods in ausers
		$this->CI->load->model('Ausers_model');
		$params['where']['module'] = 'dynamic_blocks';
		$this->CI->Ausers_model->delete_methods($params);
	}

	function _arbitrary_installing(){
		$this->CI->load->model("Dynamic_blocks_model");
		$area_ids = array();
		foreach((array)$this->dynamic_blocks as $block_data){
			$validate_data = $this->CI->Dynamic_blocks_model->validate_block(null, $block_data);
			if(!empty($validate_data["errors"])) {
				continue;
			}
			$id_block = $this->CI->Dynamic_blocks_model->save_block(null, $validate_data["data"]);
		
			if(!isset($block_data["area"])) {
				continue;
			}
			foreach($block_data["area"] as $block_area) {
				if(!isset($area_ids[$block_area["gid"]])){
					$area = $this->CI->Dynamic_blocks_model->get_area_by_gid($block_area["gid"]);
					$area_ids[$block_area["gid"]] = $area["id"];
				}

				// index area
				$block_area["id_area"] = $area_ids[$block_area["gid"]];
				$block_area["id_block"] = $id_block;

				$validate_data = $this->CI->Dynamic_blocks_model->validate_area_block($block_area, true);
				if(!empty($validate_data["errors"])) {
					continue;
				}
				$this->CI->Dynamic_blocks_model->save_area_block(null, $validate_data["data"]);
			}
		}
	}
	
	/**
	 * Import module languages
	 */
	public function _arbitrary_lang_install($langs_ids=null){
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;	
		if(empty($langs_ids)) return false;
		
		$langs_file = $this->CI->Install_model->language_file_read("dynamic_blocks", "arbitrary", $langs_ids);
		if(!$langs_file){log_message("info", "Empty dynamic_blocks langs data");return false;}
	
		$this->CI->load->model("Dynamic_blocks_model");
		
		$data = array();
		
		foreach((array)$this->dynamic_blocks as $block_data){
			$block = $this->CI->Dynamic_blocks_model->get_block_by_gid($block_data["gid"]);
			if(!$block) continue;
			$data[] = $block;
		}
		
		$this->CI->Dynamic_blocks_model->update_langs($data, $langs_file, $langs_ids);
	}
	
	/**
	 * Export module languages
	 * @param array $langs_ids
	 */
	public function _arbitrary_lang_export($langs_ids=null){
		if(!is_array($langs_ids)) $langs_ids = (array)$langs_ids;
		
		$this->CI->load->model("Dynamic_blocks_model");
		
		$data = array();
		
		foreach((array)$this->dynamic_blocks as $block_data){
			$block = $this->CI->Dynamic_blocks_model->get_block_by_gid($block_data["gid"]);
			if(!$block) continue;
			$data[] = $block;
		}
		
		$langs = $this->CI->Dynamic_blocks_model->export_langs($data, $langs_ids);
		return array("dynamic_blocks" => $langs);
	}

	function _arbitrary_deinstalling(){
		$this->CI->load->model("Dynamic_blocks_model");
		foreach((array)$this->dynamic_blocks as $block_data){
			$this->CI->Dynamic_blocks_model->delete_block_by_gid($block_data["gid"]);
		}
	}

}
