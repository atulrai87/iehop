<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-09 22:49:29 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => get_admin_level1_menu,'helper_name' => menu,'func_param' => 'admin_uploads_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/uploads/thumb_edit/<?php echo $this->_vars['config_id']; ?>
"><?php echo l('link_add_thumb', 'uploads', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_prefix', 'uploads', '', 'text', array()); ?></th>
	<th class="w150"><?php echo l('field_sizes', 'uploads', '', 'text', array()); ?></th>
	<th class="w100"><?php echo l('field_thumb_watermark', 'uploads', '', 'text', array()); ?></th>
	<th class="w70"><?php echo l('field_resize_type', 'uploads', '', 'text', array()); ?></th>
	<th class="w100"><?php echo l('field_date_add', 'uploads', '', 'text', array()); ?></th>
	<th class="w70">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['thumbs']) and count((array)$this->_vars['thumbs'])): foreach ((array)$this->_vars['thumbs'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><b><?php echo $this->_vars['item']['prefix']; ?>
</b></td>
	<td class="center"><?php echo $this->_vars['item']['width']; ?>
x<?php echo $this->_vars['item']['height']; ?>
</td>
	<td class="center"><?php if ($this->_vars['item']['watermark_id']): ?><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png"><?php else: ?>&nbsp;<?php endif; ?></td>
	<td class="center"><?php echo $this->_vars['item']['crop_param']; ?>
</td>
	<td class="center"><?php echo $this->_vars['item']['date_add']; ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/uploads/thumb_edit/<?php echo $this->_vars['config_id']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_thumb', 'uploads', '', 'text', array()); ?>" title="<?php echo l('link_edit_thumb', 'uploads', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/uploads/thumb_delete/<?php echo $this->_vars['config_id']; ?>
/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_thumb', 'uploads', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_thumb', 'uploads', '', 'text', array()); ?>" title="<?php echo l('link_delete_thumb', 'uploads', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td class="center zebra" colspan=6><?php echo l('no_thumbs', 'uploads', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
