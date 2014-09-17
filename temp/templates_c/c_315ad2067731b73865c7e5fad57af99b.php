<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.ld_option.php'); $this->register_function("ld_option", "tpl_function_ld_option");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 19:50:30 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => get_admin_level1_menu,'helper_name' => menu,'func_param' => 'media_menu_item'), $this);?>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w110"><?php echo l('field_name', 'media', '', 'text', array()); ?></th>
	<th><?php echo l('album_info', 'media', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['albums']) and count((array)$this->_vars['albums'])): foreach ((array)$this->_vars['albums'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center">
		<?php echo $this->_vars['item']['name']; ?>

		
	</td>
	<td>
		<b><?php echo l('media_user', 'media', '', 'text', array()); ?></b>: <?php if ($this->_vars['item']['user_info']['nickname']):  echo $this->_vars['item']['user_info']['nickname'];  else: ?><font class="error"><?php echo l('success_delete_user', 'users', '', 'text', array()); ?></font><?php endif; ?><br>
		<b><?php echo l('field_permitted_for', 'media', '', 'text', array()); ?></b>: <?php echo tpl_function_ld_option(array('i' => 'permissions','gid' => 'media','option' => $this->_vars['item']['permissions']), $this);?><br>
		<b><?php echo l('album_items', 'media', '', 'text', array()); ?></b>: <?php echo $this->_vars['item']['media_count']; ?>
 
		
	</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/media/delete_album/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_service', 'packages', '', 'text', array()); ?>" title="<?php echo l('link_delete_service', 'packages', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center"><?php echo l('no_albums', 'media', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
