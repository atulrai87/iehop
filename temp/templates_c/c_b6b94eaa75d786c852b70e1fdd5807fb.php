<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.seotag.php'); $this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 01:21:49 CDT */ ?>

<!DOCTYPE html>
<html DIR="<?php echo $this->_vars['_LANG']['rtl']; ?>
">
<head>
	<meta http-equiv="X-UA-Compatible">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<meta name="robot" content="All">
	<?php echo tpl_function_seotag(array('tag' => 'title|description|keyword|canonical|og_title|og_type|og_url|og_image|og_site_name|og_description'), $this);?>

	<?php echo tpl_function_helper(array('func_name' => css,'helper_name' => theme,'func_param' => $this->_vars['load_type']), $this);?>
	<script type="text/javascript">
		var site_url = '<?php echo $this->_vars['site_url']; ?>
';
		var site_rtl_settings = '<?php echo $this->_vars['_LANG']['rtl']; ?>
';
		var site_error_position = 'left';
	</script>

	<link rel="shortcut icon" href="<?php echo $this->_vars['site_root']; ?>
favicon.ico">
	<?php echo tpl_function_helper(array('func_name' => js,'helper_name' => theme,'func_param' => $this->_vars['load_type']), $this);?>
</head>

<body>

<?php echo tpl_function_helper(array('func_name' => demo_panel,'helper_name' => start,'func_param' => 'admin'), $this);?>
	<div id="error_block"><?php if (is_array($this->_vars['_PREDEFINED']['error']) and count((array)$this->_vars['_PREDEFINED']['error'])): foreach ((array)$this->_vars['_PREDEFINED']['error'] as $this->_vars['item']):  if ($this->_vars['item']['text']):  echo $this->_vars['item']['text']; ?>
<br><?php endif;  endforeach; endif; ?></div>
	<div id="info_block"><?php if (is_array($this->_vars['_PREDEFINED']['info']) and count((array)$this->_vars['_PREDEFINED']['info'])): foreach ((array)$this->_vars['_PREDEFINED']['info'] as $this->_vars['item']):  if ($this->_vars['item']['text']):  echo $this->_vars['item']['text']; ?>
<br><?php endif;  endforeach; endif; ?></div>
	<div id="success_block"><?php if (is_array($this->_vars['_PREDEFINED']['success']) and count((array)$this->_vars['_PREDEFINED']['success'])): foreach ((array)$this->_vars['_PREDEFINED']['success'] as $this->_vars['item']):  if ($this->_vars['item']['text']):  echo $this->_vars['item']['text']; ?>
<br><?php endif;  endforeach; endif; ?></div>

	<div class="bg">
		<div class="main">
			<!-- Left column -->
			<div class="lc">
				<!-- Logo -->
				<div class="logo">
					<div class="b">
						<a href="<?php echo $this->_vars['site_url']; ?>
admin/"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['logo_settings']['path']; ?>
" border="0" alt="logo" width="<?php echo $this->_vars['logo_settings']['width']; ?>
" height="<?php echo $this->_vars['logo_settings']['height']; ?>
"></a>
					</div>
				</div>
				<!-- Menu -->
				<?php if ($this->_vars['initial_setup']): ?>
					<?php echo tpl_function_helper(array('func_name' => get_initial_setup_menu,'helper_name' => install,'func_param' => $this->_vars['step']), $this);?>
				<?php elseif ($this->_vars['modules_setup']): ?>
					<?php echo tpl_function_helper(array('func_name' => get_modules_setup_menu,'helper_name' => install,'func_param' => $this->_vars['step']), $this);?>
				<?php elseif ($this->_vars['product_setup']): ?>
					<?php echo tpl_function_helper(array('func_name' => get_product_setup_menu,'helper_name' => install,'func_param' => $this->_vars['step']), $this);?>
				<?php else: ?>
					<?php echo tpl_function_helper(array('func_name' => get_admin_main_menu,'helper_name' => menu), $this);?>
				<?php endif; ?>
			</div>

			<!-- Right column -->
			<div class="rc">
				<?php if ($this->_vars['auth_type'] == 'admin'): ?>
					<div class="w-login-str">
						<div class="version"><?php echo tpl_function_helper(array('func_name' => product_version,'helper_name' => start), $this);?></div>
						<?php if (! $this->_vars['initial_setup'] && ! $this->_vars['modules_setup'] && ! $this->_vars['product_setup']): ?>
							<span class="ib">
								<?php echo tpl_function_helper(array('func_name' => users_lang_select,'module' => users), $this);?>
							</span>
						<?php endif; ?> |
						<a href="http://www.pilotgroup.net/support/" target="_blank"><?php echo l('btn_support', 'start', '', 'text', array()); ?></a>
						

						<?php if ($this->_vars['modules_setup']): ?> | <a href="<?php echo $this->_vars['site_url']; ?>
admin/install/logoff" class="logoff"><?php echo l('btn_logoff', 'start', '', 'text', array()); ?></a>
						<?php elseif ($this->_vars['initial_setup'] || $this->_vars['product_setup']): ?>
						<?php elseif ($this->_vars['auth_type'] == 'admin'): ?> | <a href="<?php echo $this->_vars['site_url']; ?>
admin/ausers/logoff" class="logoff"><?php echo l('btn_logoff', 'start', '', 'text', array()); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="w-area">
					<div class="b">
						<?php if ($this->_vars['_PREDEFINED']['back_link']): ?>
							<div class="quest-block">
								 <a href="<?php echo $this->_vars['_PREDEFINED']['back_link']; ?>
" class="back"><?php echo l('btn_back', 'start', '', 'text', array()); ?></a>&nbsp;
							</div>
						<?php endif; ?>
							<h1><?php echo $this->_vars['_PREDEFINED']['header']; ?>
</h1>
