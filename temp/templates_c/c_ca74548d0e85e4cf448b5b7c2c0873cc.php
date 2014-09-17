<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-04 00:36:27 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => get_admin_level1_menu,'helper_name' => menu,'func_param' => 'admin_countries_menu'), $this);?>
<form action="<?php echo $this->_vars['site_url']; ?>
admin/countries/install/city/<?php echo $this->_vars['country']['code']; ?>
" method="post">
<div class="actions">
	<ul>
		<li><div class="l"><input type="submit" name="install-btn" value="<?php echo l('install_regions_link', 'countries', '', 'button', array()); ?>" onclick="javascript: return checkBoxes();"></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w30"><input type="checkbox" onclick="javascript: checkAll(this.checked);"></th>
	<th><?php echo l('field_country', 'countries', '', 'text', array()); ?></th>
	<th class="w50"><?php echo l('field_region_code', 'countries', '', 'text', array()); ?></th>
	<th><?php echo l('field_region_name', 'countries', '', 'text', array()); ?></th>
	<th class="w100"><?php echo l('field_region_status', 'countries', '', 'text', array()); ?></th>
</tr>
<?php if (is_array($this->_vars['list']) and count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['item']):  $this->assign('region_code', $this->_vars['item']['code']);  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center"><input type="checkbox" class="ch-reg" name="region[]" value="<?php echo $this->_vars['item']['code']; ?>
" <?php if ($this->_vars['installed'][$this->_vars['region_code']]): ?>disabled<?php endif; ?>></td>
	<td class="center"><?php echo $this->_vars['country']['name']; ?>
(<?php echo $this->_vars['country']['code']; ?>
)</td>
	<td class="center"><?php echo $this->_vars['item']['code']; ?>
</td>
	<td><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="icons"><?php if ($this->_vars['installed'][$this->_vars['region_code']]): ?><i><?php echo l('region_installed', 'countries', '', 'text', array()); ?></i><?php else: ?><i><?php echo l('region_not_installed', 'countries', '', 'text', array()); ?></i><?php endif; ?>&nbsp;</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center zebra"><?php echo l('no_regions', 'countries', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
</form>

<script><?php echo '
	function checkAll(checked){
		if(checked)
			$(\'.ch-reg:enabled\').attr(\'checked\', \'checked\');
		else
			$(\'.ch-reg:enabled\').removeAttr(\'checked\');
	}
	function checkBoxes(){
		if($(\'.ch-reg:checked\').length > 0){
			return true;
		}else{
			return false;
		}
	}
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>