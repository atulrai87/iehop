<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.truncate.php'); $this->register_modifier("truncate", "tpl_modifier_truncate");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-10 02:30:29 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => get_admin_level1_menu,'helper_name' => menu,'func_param' => 'admin_seo_menu'), $this);?>
<div class="actions">&nbsp;</div>

<div class="filter-form">
	<?php echo l('text_module_select', 'seo', '', 'text', array()); ?>:
	<select name="module_gid" onchange="javascript: reload_this_page(this.value);">
	<?php if (is_array($this->_vars['modules']) and count((array)$this->_vars['modules'])): foreach ((array)$this->_vars['modules'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['item']['module_gid']; ?>
" <?php if ($this->_vars['module_gid'] == $this->_vars['item']['module_gid']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']['module_name']; ?>
 (<?php echo $this->_vars['item']['module_gid']; ?>
)</option><?php endforeach; endif; ?>
	</select>
</div>
<?php if ($this->_vars['default_settings']): ?>
<table cellspacing="0" cellpadding="0" class="data">
<tr>
	<th class="first"><?php echo l('target_field', 'seo', '', 'text', array()); ?></th>
	<th class="w30">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['default_settings']) and count((array)$this->_vars['default_settings'])): foreach ((array)$this->_vars['default_settings'] as $this->_vars['key'] => $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php echo $this->_vars['site_url'];  echo $this->_vars['module_gid']; ?>
/<?php echo $this->_vars['key'];  if ($this->_vars['item']['url']): ?><br>(<b><?php echo l('rewrite_url', 'seo', '', 'text', array()); ?>: </b><i><?php echo $this->_vars['site_url'];  echo $this->_run_modifier($this->_vars['item']['url'], 'truncate', 'plugin', 1, 50, '...', true); ?>
</i>)<?php endif; ?></td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/seo/edit/<?php echo $this->_vars['module_gid']; ?>
/<?php echo $this->_vars['key']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_settings', 'seo', '', 'text', array()); ?>" title="<?php echo l('link_edit_settings', 'seo', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; endif; ?>
</table>
<?php endif;  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<script type="text/javascript">
var reload_link = "<?php echo $this->_vars['site_url']; ?>
admin/seo/listing/";
<?php echo '
function reload_this_page(value){
	var link = reload_link + value ;
	location.href=link;
}
'; ?>

</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
