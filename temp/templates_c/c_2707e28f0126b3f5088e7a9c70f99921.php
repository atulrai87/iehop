<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-02 21:12:00 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => get_admin_level1_menu,'helper_name' => menu,'func_param' => 'admin_countries_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/region_edit/<?php echo $this->_vars['country']['code']; ?>
"><?php echo l('link_add_region', 'countries', '', 'text', array()); ?></a></div></li>
		<?php if ($this->_vars['sort_mode']): ?>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/country/<?php echo $this->_vars['country']['code']; ?>
/0"><?php echo l('link_view_mode', 'countries', '', 'text', array()); ?></a></div></li>
		<?php else: ?>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/country/<?php echo $this->_vars['country']['code']; ?>
/1"><?php echo l('link_sorting_mode', 'countries', '', 'text', array()); ?></a></div></li>
		<?php endif; ?>
	</ul>
	&nbsp;
</div>

<?php if ($this->_vars['sort_mode']): ?>
<div id="menu_items">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
	<?php if (is_array($this->_vars['installed']) and count((array)$this->_vars['installed'])): foreach ((array)$this->_vars['installed'] as $this->_vars['item']): ?>
	<li id="item_<?php echo $this->_vars['item']['id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</li>
	<?php endforeach; endif; ?>
	</ul>
</div>

<script ><?php echo '
	var mlSorter;
	$(function(){
		mlSorter = new multilevelSorter({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\', 
			onActionUpdate: true,
			urlSaveSort: \'admin/countries/ajax_save_region_sorter/';  echo $this->_vars['country']['code'];  echo '\'
		});
	});
'; ?>
</script>

<?php else: ?>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_region_name', 'countries', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
	<th class="w70">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['installed']) and count((array)$this->_vars['installed'])): foreach ((array)$this->_vars['installed'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="center"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/region/<?php echo $this->_vars['country']['code']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><?php echo l('view_cities_link', 'countries', '', 'text', array()); ?></a></td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/region_edit/<?php echo $this->_vars['country']['code']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_region', 'countries', '', 'text', array()); ?>" title="<?php echo l('link_edit_region', 'countries', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/region_delete/<?php echo $this->_vars['country']['code']; ?>
/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_region', 'countries', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_region', 'countries', '', 'text', array()); ?>" title="<?php echo l('link_delete_region', 'countries', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="3" class="center"><?php echo l('no_regions', 'countries', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  endif;  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
