<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 01:16:35 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100">Module</th>
	<th >Description</th>
	<th class="w50">Version</th>
	<th class="w100">Installed</th>
	<th class="w70">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['modules']) and count((array)$this->_vars['modules'])): foreach ((array)$this->_vars['modules'] as $this->_vars['key'] => $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><b><?php echo $this->_vars['item']['module_gid']; ?>
</b></td>
	<td>
		<b><?php echo $this->_vars['item']['module_name']; ?>
</b><br>
		<?php echo $this->_vars['item']['module_description']; ?>

		<?php if ($this->_vars['item']['dependencies']): ?><br><br><b>Depend from:</b> <?php if (is_array($this->_vars['item']['dependencies']) and count((array)$this->_vars['item']['dependencies'])): foreach ((array)$this->_vars['item']['dependencies'] as $this->_vars['dmod'] => $this->_vars['depend']):  endforeach; endif;  endif; ?>
	</td>
	<td class="center"><?php echo $this->_vars['item']['version']; ?>
</td>
	<td class="date"><?php echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/install/module_view/<?php echo $this->_vars['item']['module_gid']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-view.png" width="16" height="16" border="0" alt=""></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/install/module_delete/<?php echo $this->_vars['item']['module_gid']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt=""></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" class="center">No modules</td></tr>
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
