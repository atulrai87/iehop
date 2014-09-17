<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-04 00:37:45 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => get_admin_level1_menu,'helper_name' => menu,'func_param' => 'admin_countries_menu'), $this);?>
<div class="actions">

	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="<?php if ($this->_vars['filter'] == 'all'): ?>active<?php endif;  if (! $this->_vars['filter_data']['all']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/install/country/all"><?php echo l('filter_all_countries', 'countries', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['all']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'installed'): ?>active<?php endif;  if (! $this->_vars['filter_data']['installed']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/install/country/installed"><?php echo l('filter_installed_countries', 'countries', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['installed']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'not-installed'): ?>active<?php endif;  if (! $this->_vars['filter_data']['not_installed']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/install/country/not-installed"><?php echo l('filter_not_installed_countries', 'countries', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['not_installed']; ?>
)</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w50"><?php echo l('field_country_code', 'countries', '', 'text', array()); ?></th>
	<th><?php echo l('field_country_name', 'countries', '', 'text', array()); ?></th>
	<th class="w100"><?php echo l('field_country_status', 'countries', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['list']) and count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['item']):  $this->assign('country_code', $this->_vars['item']['code']); ?>

<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center"><?php echo $this->_vars['item']['code']; ?>
</td>
	<td><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="icons"><?php if ($this->_vars['installed'][$this->_vars['country_code']]): ?><i><?php echo l('country_installed', 'countries', '', 'text', array()); ?></i><?php else: ?><i><?php echo l('country_not_installed', 'countries', '', 'text', array()); ?></i><?php endif; ?>&nbsp;</td>
	<td class="icons"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/install/region/<?php echo $this->_vars['item']['code']; ?>
"><?php if ($this->_vars['installed'][$this->_vars['country_code']]):  echo l('view_regions_link', 'countries', '', 'text', array());  else:  echo l('country_install_link', 'countries', '', 'text', array());  endif; ?></a></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center zebra"><?php echo l('no_countries', 'countries', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>