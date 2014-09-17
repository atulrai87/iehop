<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 00:29:14 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100">Library</th>
	<th >Name</th>
	<th class="w100">Version</th>
	<th class="w100">Installed</th>
</tr>
<?php if (is_array($this->_vars['libraries']) and count((array)$this->_vars['libraries'])): foreach ((array)$this->_vars['libraries'] as $this->_vars['key'] => $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><b><?php echo $this->_vars['item']['gid']; ?>
</b></td>
	<td><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="center">
	<?php if ($this->_vars['item']['update']): ?>
	<font class="req"><?php echo $this->_vars['item']['version']; ?>
.00</font> <br><a href="<?php echo $this->_vars['site_url']; ?>
admin/install/library_update_install/<?php echo $this->_vars['item']['gid']; ?>
">Update</a>
	<?php else:  echo $this->_vars['item']['version']; ?>
.00<?php endif; ?>
	</td>
	<td class="center date"><?php echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center">No libraries</td></tr>
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