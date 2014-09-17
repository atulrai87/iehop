<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.breadcrumbs.php'); $this->register_function("breadcrumbs", "tpl_function_breadcrumbs");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.start_search_form.php'); $this->register_function("start_search_form", "tpl_function_start_search_form");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.menu.php'); $this->register_function("menu", "tpl_function_menu");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.json_encode.php'); $this->register_function("json_encode", "tpl_function_json_encode");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.seotag.php'); $this->register_function("seotag", "tpl_function_seotag");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/block.strip.php'); $this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-12 00:35:59 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  if (! $this->_vars['is_pjax']): ?>
<!DOCTYPE html>
<html DIR="<?php echo $this->_vars['_LANG']['rtl']; ?>
">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<?php echo tpl_function_seotag(array('tag' => 'robots'), $this);?>
	<link rel="shortcut icon" href="<?php echo $this->_vars['site_root']; ?>
favicon.ico">
<?php endif; ?>
	<?php echo tpl_function_seotag(array('tag' => 'description|keyword|canonical|og_title|og_type|og_url|og_image|og_site_name|og_description'), $this);?>
	<?php echo tpl_function_seotag(array('tag' => 'title'), $this);?>
	<script>
		var site_rtl_settings = '<?php echo $this->_vars['_LANG']['rtl']; ?>
';
		var is_pjax = parseInt('<?php echo $this->_vars['is_pjax']; ?>
');
		var js_events = <?php echo tpl_function_json_encode(array('data' => $this->_vars['js_events']), $this);?>;
		var id_user = <?php if ($this->_vars['user_session_data']['user_id']):  echo $this->_vars['user_session_data']['user_id'];  else: ?>0<?php endif; ?>;
	</script>

<?php if (! $this->_vars['is_pjax']): ?>
	<script>
		var site_url = '<?php echo $this->_vars['site_url']; ?>
';
		var img_folder = '<?php echo $this->_vars['img_folder']; ?>
';
		var site_error_position = 'center';
		var use_pjax = parseInt('<?php echo $this->_vars['use_pjax']; ?>
');
		var pjax_container = '#pjaxcontainer';
	</script>
	<?php echo tpl_function_helper(array('func_name' => 'js','helper_name' => 'theme','func_param' => $this->_vars['load_type']), $this); endif; ?>

	<script>
		var messages = <?php echo tpl_function_json_encode(array('data' => $this->_vars['_PREDEFINED']), $this);?>;
		var alerts;
		var notifications;
		<?php echo '
			new pginfo({messages: messages});
			$(function(){
				alerts = new Alerts();
				notifications = new Notifications();
			});
		'; ?>

	</script>


	<?php echo tpl_function_helper(array('func_name' => 'css','helper_name' => 'theme','func_param' => $this->_vars['load_type']), $this);?>

<?php if (! $this->_vars['is_pjax']): ?>

	<?php echo '<!--[if gt IE 9]><style type="text/css">.gradient,.gradient:before,.gradient:after,[class*="icon-"] [class*="icon-"], [class*="icon-"] [class*="icon-"]:before, [class*="icon-"] [class*="icon-"]:after{filter: none;}</style><![endif]-->'; ?>

</head>

<body>
	

	<?php echo tpl_function_helper(array('func_name' => 'im_chat_button','module' => 'im'), $this);?>
	<?php echo tpl_function_helper(array('func_name' => 'likes','module' => 'likes'), $this);?>
	<?php echo tpl_function_helper(array('func_name' => 'demo_panel','helper_name' => 'start','func_param' => 'user'), $this);?>
	<div id="pjaxcontainer" class="hp100">
<?php endif; ?>
		<script>$('body').removeClass('index-page site-page').addClass('<?php if ($this->_vars['header_type'] == 'index'): ?>index-page<?php else: ?>site-page<?php endif; ?>');</script>

		<?php echo tpl_function_helper(array('func_name' => 'banner_initialize','module' => 'banners'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'show_social_networks_head','module' => 'social_networking'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'seo_traker','helper_name' => 'seo_module_helper','module' => 'seo','func_param' => 'top'), $this);?>
		<?php if ($this->_vars['display_browser_error']): ?>
			<?php echo tpl_function_helper(array('func_name' => 'available_browsers','helper_name' => 'start'), $this);?>
		<?php endif; ?>

		<div id="error_block"><?php if (is_array($this->_vars['_PREDEFINED']['error']) and count((array)$this->_vars['_PREDEFINED']['error'])): foreach ((array)$this->_vars['_PREDEFINED']['error'] as $this->_vars['item']):  if ($this->_vars['item']['text']):  echo $this->_vars['item']['text']; ?>
<br><?php endif;  endforeach; endif; ?></div>
		<div id="info_block"><?php if (is_array($this->_vars['_PREDEFINED']['info']) and count((array)$this->_vars['_PREDEFINED']['info'])): foreach ((array)$this->_vars['_PREDEFINED']['info'] as $this->_vars['item']):  if ($this->_vars['item']['text']):  echo $this->_vars['item']['text']; ?>
<br><?php endif;  endforeach; endif; ?></div>
		<div id="success_block"><?php if (is_array($this->_vars['_PREDEFINED']['success']) and count((array)$this->_vars['_PREDEFINED']['success'])): foreach ((array)$this->_vars['_PREDEFINED']['success'] as $this->_vars['item']):  if ($this->_vars['item']['text']):  echo $this->_vars['item']['text']; ?>
<br><?php endif;  endforeach; endif; ?></div>

		<?php if ($this->_vars['header_type'] != 'index'): ?>
			<div class="header">
				<div class="content">
					<div class="header-logo">
						<a href="<?php echo $this->_vars['site_url']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['mini_logo_settings']['path']; ?>
" border="0" alt="<?php echo tpl_function_helper(array('func_name' => 'seo_tags_default','func_param' => 'header_text'), $this);?>" width="<?php echo $this->_vars['mini_logo_settings']['width']; ?>
" height="<?php echo $this->_vars['mini_logo_settings']['height']; ?>
"></a>
					</div>
					<div class="header-menu">
						<?php if ($this->_vars['auth_type'] == 'user'): ?>
							<?php echo tpl_function_helper(array('func_name' => 'auth_links','module' => 'users'), $this);?>
							<?php echo tpl_function_helper(array('func_name' => 'user_account','module' => 'users_payments'), $this);?>
							<?php echo tpl_function_block(array('name' => 'new_messages','module' => 'mailbox','template' => 'header'), $this);?>
							<?php echo tpl_function_block(array('name' => 'admin_new_messages','module' => 'tickets','template' => 'header'), $this);?>
							<?php echo tpl_function_helper(array('func_name' => 'users_lang_select','module' => 'users'), $this);?>
							<?php echo tpl_function_menu(array('gid' => 'settings_menu','template' => 'settings_menu'), $this);?>
						<?php else: ?>
							<?php echo tpl_function_helper(array('func_name' => 'users_lang_select','module' => 'users'), $this);?>
							<?php echo tpl_function_helper(array('func_name' => 'auth_links','module' => 'users'), $this);?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div id="top_bar_fixed">
				<div class="menu-search-bar">
					<div class="content table-div">
						<div class="w30">
							<a href="javascript: history.back();"><i class="icon-arrow-left icon-big w edge hover"></i></a>
						</div>
						<div class="top_menu">
							<?php if ($this->_vars['auth_type'] == 'user'):  echo tpl_function_menu(array('gid' => 'user_top_menu','template' => 'user_top_menu'), $this); else:  echo tpl_function_menu(array('gid' => 'guest_main_menu','template' => 'user_main_menu'), $this); endif; ?>
						</div>
						<div class="righted">
							<?php echo tpl_function_start_search_form(array('type' => 'line'), $this);?>
						</div>
					</div>
				</div>
			</div>
			<div class="clr"></div>
		<?php endif; ?>
		<div class="main">
			<div class="content">
				<?php echo tpl_function_breadcrumbs(array(), $this);?>
				<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
					<?php echo tpl_function_helper(array('func_name' => show_banner_place,'module' => banners,'func_param' => 'top-banner'), $this);?>
					<?php echo tpl_function_helper(array('func_name' => show_banner_place,'module' => banners,'func_param' => 'left-top-banner'), $this);?>
					<?php echo tpl_function_helper(array('func_name' => show_banner_place,'module' => banners,'func_param' => 'right-top-banner'), $this);?>
					<div class="clr"></div>
				<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
				<?php if ($this->_vars['header_type'] != 'index'): ?><div class="mb20 mt5"><?php echo tpl_function_helper(array('func_name' => featured_users,'module' => users), $this);?></div><?php endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
