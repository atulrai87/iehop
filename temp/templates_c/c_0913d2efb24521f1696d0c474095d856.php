<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.block.php'); $this->register_function("block", "tpl_function_block");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.seotag.php'); $this->register_function("seotag", "tpl_function_seotag");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/block.strip.php'); $this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-08 13:44:43 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block">
	<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?>: <?php echo l('header_'.$this->_vars['action'], 'users', '', 'text', array()); ?></h1>
	
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('"default"', '"users"'). "account_menu.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	
	<?php if ($this->_vars['action'] == 'services'): ?>
		<div class="line top"><?php echo tpl_function_block(array('name' => 'services_buy_list','module' => 'services'), $this);?></div>
		<div class="line top"><?php echo tpl_function_block(array('name' => 'packages_list','module' => 'packages'), $this);?></div>
	<?php elseif ($this->_vars['action'] == 'my_services'): ?>
		<div class="line top"><?php echo tpl_function_block(array('name' => 'user_services_list','module' => 'services','id_user' => $this->_vars['user_id']), $this);?></div>
		<div class="line top"><?php echo tpl_function_block(array('name' => 'user_packages_list','module' => 'packages'), $this);?></div>
	<?php elseif ($this->_vars['action'] == 'update'): ?>
		<?php echo tpl_function_helper(array('func_name' => update_account_block,'module' => users_payments), $this);?>
	<?php elseif ($this->_vars['action'] == 'payments_history'): ?>
		<div><?php echo tpl_function_block(array('name' => 'user_payments_history','module' => 'payments','id_user' => $this->_vars['user_id'],'page' => $this->_vars['page'],'base_url' => $this->_vars['base_url']), $this);?></div>
	<?php elseif ($this->_vars['action'] == 'banners'): ?>
		<div><?php echo tpl_function_block(array('name' => 'my_banners','module' => 'banners','page' => $this->_vars['page'],'base_url' => $this->_vars['base_url']), $this);?></div>
	<?php endif; ?>
</div>
<div class="clr"></div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
