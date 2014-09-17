<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-22 02:31:35 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => get_admin_level1_menu,'helper_name' => menu,'func_param' => 'admin_payments_menu'), $this);?>
<div class="actions">
<?php if ($this->_vars['page_data']['add_service_link']): ?>
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/services/edit"><?php echo l('link_add_service', 'services', '', 'text', array()); ?></a></div></li>
	</ul>
<?php endif; ?>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_service_name', 'services', '', 'text', array()); ?></th>
	<th class="w100"><?php echo l('field_status', 'services', '', 'text', array()); ?></th>
	<th class="w50">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['services']) and count((array)$this->_vars['services'])): foreach ((array)$this->_vars['services'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center"><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="center">
	<?php if ($this->_vars['item']['status']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/services/activate/<?php echo $this->_vars['item']['id']; ?>
/0"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_deactivate_service', 'services', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_service', 'services', '', 'text', array()); ?>"></a>
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/services/activate/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_service', 'services', '', 'text', array()); ?>" title="<?php echo l('link_activate_service', 'services', '', 'text', array()); ?>"></a>
	<?php endif; ?>
	</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/services/edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_service', 'services', '', 'text', array()); ?>" title="<?php echo l('link_edit_service', 'services', '', 'text', array()); ?>"></a>
		<?php if ($this->_vars['item']['template']['moveable']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/services/delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_service', 'services', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_service', 'services', '', 'text', array()); ?>" title="<?php echo l('link_delete_service', 'services', '', 'text', array()); ?>"></a>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center"><?php echo l('no_services', 'services', '', 'text', array()); ?></td></tr>
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
