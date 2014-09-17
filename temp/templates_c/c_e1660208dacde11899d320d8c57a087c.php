<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 00:29:17 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100">Module</th>
	<th >Description</th>
	<th class="w70">Old version</th>
	<th class="w70">New version</th>
	<th class="w50">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['updates']) and count((array)$this->_vars['updates'])): foreach ((array)$this->_vars['updates'] as $this->_vars['key'] => $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><b><?php echo $this->_vars['item']['module_gid']; ?>
</b></td>
	<td>
		<b><?php echo $this->_vars['item']['update_name']; ?>
</b><br>
		<?php echo $this->_vars['item']['update_description']; ?>

	</td>
	<td class="center"><?php echo $this->_vars['item']['version_from']; ?>
</td>
	<td class="center"><?php echo $this->_vars['item']['version_to']; ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/install/update_install/<?php echo $this->_vars['item']['module_gid']; ?>
/<?php echo $this->_vars['key']; ?>
">Install</a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" class="center">No updates</td></tr>
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