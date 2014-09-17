<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.js.php'); $this->register_function("js", "tpl_function_js");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.block.php'); $this->register_function("block", "tpl_function_block");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.lower.php'); $this->register_modifier("lower", "tpl_modifier_lower");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 00:00:02 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/personal/"><?php echo l('link_add_user', 'users', '', 'text', array()); ?></a></div></li>
		<?php echo tpl_function_helper(array('func_name' => 'button_add_funds','module' => 'users_payments'), $this);?>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="<?php if ($this->_vars['filter'] == 'all'): ?>active<?php endif;  if (! $this->_vars['filter_data']['all']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/all/<?php echo $this->_vars['user_type']; ?>
"><?php echo l('filter_all_users', 'users', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['all']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'not_active'): ?>active<?php endif;  if (! $this->_vars['filter_data']['not_active']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/not_active/<?php echo $this->_vars['user_type']; ?>
"><?php echo l('filter_not_active_users', 'users', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['not_active']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'active'): ?>active<?php endif;  if (! $this->_vars['filter_data']['active']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/active/<?php echo $this->_vars['user_type']; ?>
"><?php echo l('filter_active_users', 'users', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['active']; ?>
)</a></li>
	</ul>
	&nbsp;
</div>
<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="filter" value="<?php echo $this->_vars['filter']; ?>
">
	<input type="hidden" name="order" value="<?php echo $this->_vars['order']; ?>
">
	<input type="hidden" name="order_direction" value="<?php echo $this->_vars['order_direction']; ?>
">
	<div class="filter-form">
		<div class="row">
			<div class="h"><?php echo l('user_type', 'users', '', 'text', array()); ?>:</div>
			<div class="v">
				<select name="user_type">
					<option value="all"<?php if ($this->_vars['user_type'] == 'all'): ?> selected<?php endif; ?>>...</option>
					<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?>
						<option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['user_type'] == $this->_vars['key']): ?>} selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('search_by', 'users', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="text" name="val_text" value="<?php echo $this->_vars['search_param']['text']; ?>
" style="width: 100px;">
				<select name="type_text" class="ml20">
					<option value="all" <?php if ($this->_vars['search_param']['type'] == 'all'): ?> selected<?php endif; ?>><?php echo l('filter_all', 'users', '', 'text', array()); ?></option>
					<option value="email" <?php if ($this->_vars['search_param']['type'] == 'email'): ?> selected<?php endif; ?>><?php echo l('field_email', 'users', '', 'text', array()); ?></option>
					<option value="fname" <?php if ($this->_vars['search_param']['type'] == 'fname'): ?> selected<?php endif; ?>><?php echo l('field_fname', 'users', '', 'text', array()); ?></option>
					<option value="sname" <?php if ($this->_vars['search_param']['type'] == 'sname'): ?> selected<?php endif; ?>><?php echo l('field_sname', 'users', '', 'text', array()); ?></option>
					<option value="nickname" <?php if ($this->_vars['search_param']['type'] == 'nickname'): ?> selected<?php endif; ?>><?php echo l('field_nickname', 'users', '', 'text', array()); ?></option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('latest_active', 'users', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="text" id="last_active_from" name="last_active_from" maxlength="10" style="width: 100px" value="<?php echo $this->_vars['search_param']['last_active']['from']; ?>
">
				<label for="last_active_to"><?php echo l('to', 'users', '', 'text', array()); ?></label>
				<input type="text" id="last_active_to" name="last_active_to" maxlength="10" style="width: 100px" value="<?php echo $this->_vars['search_param']['last_active']['to']; ?>
">
			</div>
		</div>
		<div class="row">
			<div class="btn">
				<div class="l">
					<input type="submit" value="<?php echo l('header_user_find', 'users', '', 'text', array()); ?>" name="btn_search">
				</div>
			</div>
		</div>		
	</div>
</form>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><input type="checkbox" id="grouping_all"></th>
	<th><a href="<?php echo $this->_vars['sort_links']['nickname']; ?>
"<?php if ($this->_vars['order'] == 'nickname'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_nickname', 'users', '', 'text', array()); ?></a></th>
	<th><?php echo l('user_type', 'users', '', 'text', array()); ?></th>
	<th><a href="<?php echo $this->_vars['sort_links']['email']; ?>
"<?php if ($this->_vars['order'] == 'email'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_email', 'users', '', 'text', array()); ?></a></th>
	<th><a href="<?php echo $this->_vars['sort_links']['account']; ?>
"<?php if ($this->_vars['order'] == 'account'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_account', 'users', '', 'text', array()); ?></a></th>
	<th class=""><a href="<?php echo $this->_vars['sort_links']['date_created']; ?>
"<?php if ($this->_vars['order'] == 'date_created'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_date_created', 'users', '', 'text', array()); ?></a></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['users']) and count((array)$this->_vars['users'])): foreach ((array)$this->_vars['users'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first w20 center"><input type="checkbox" class="grouping" value="<?php echo $this->_vars['item']['id']; ?>
"></td>
	<td><b><?php echo $this->_vars['item']['nickname']; ?>
</b><br><?php echo $this->_vars['item']['fname']; ?>
 <?php echo $this->_vars['item']['sname']; ?>
</td>
	<td><?php echo $this->_vars['item']['user_type_str']; ?>
</td>
	<td><?php echo $this->_vars['item']['email']; ?>
</td>
	<td class="center"><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['item']['account']), $this);?></td>
	<td class="center"><?php echo $this->_run_modifier($this->_vars['item']['date_created'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
	<td class="icons">
		<?php if ($this->_vars['item']['approved']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/users/activate/<?php echo $this->_vars['item']['id']; ?>
/0"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_deactivate_user', 'users', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_user', 'users', '', 'text', array()); ?>"></a>
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/users/activate/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_user', 'users', '', 'text', array()); ?>" title="<?php echo l('link_activate_user', 'users', '', 'text', array()); ?>"></a>
		<?php endif; ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/personal/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_user', 'users', '', 'text', array()); ?>" title="<?php echo l('link_edit_user', 'users', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/users/delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_user', 'users', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_user', 'users', '', 'text', array()); ?>" title="<?php echo l('link_delete_user', 'users', '', 'text', array()); ?>"></a>
		<?php echo tpl_function_block(array('name' => 'contact_user_link','module' => 'tickets','id_user' => $this->_vars['item']['id']), $this);?>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="7" class="center"><?php echo l('no_users', 'users', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_js(array('file' => 'jquery-ui.custom.min.js'), $this);?>
<link href='<?php echo $this->_vars['site_root'];  echo $this->_vars['js_folder']; ?>
jquery-ui/jquery-ui.custom.css' rel='stylesheet' type='text/css' media='screen' />
<script type="text/javascript">

var reload_link = "<?php echo $this->_vars['site_url']; ?>
admin/users/index/";
var filter = '<?php echo $this->_vars['filter']; ?>
';
var order = '<?php echo $this->_vars['order']; ?>
';
var loading_content;
var order_direction = '<?php echo $this->_vars['order_direction']; ?>
';

<?php echo '

$(function(){
	$(\'#grouping_all\').bind(\'click\', function(){
		var checked = $(this).is(\':checked\');
		if(checked){
			$(\'input.grouping\').attr(\'checked\', \'checked\');
		}else{
			$(\'input.grouping\').removeAttr(\'checked\');
		}
	});

	$(\'#grouping_all\').bind(\'click\', function(){
		var checked = $(this).is(\':checked\');
		if(checked){
			$(\'input[type=checkbox].grouping\').attr(\'checked\', \'checked\');
		}else{
			$(\'input[type=checkbox].grouping\').removeAttr(\'checked\');
		}
	});
	now = new Date();
	yr =  (new Date(now.getYear() - 80, 0, 1).getFullYear()) + \':\' + (new Date(now.getYear() - 18, 0, 1).getFullYear());
	$( "#last_active_from" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat :\'yy-mm-dd\',
		onClose: function( selectedDate ) {
			$( "#last_active_to" ).datepicker( "option", "minDate", selectedDate );
		}
    });
    $( "#last_active_to" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat :\'yy-mm-dd\',
		onClose: function( selectedDate ) {
			$( "#last_active_from" ).datepicker( "option", "maxDate", selectedDate );
		}
    });
});
function reload_this_page(value){
	var link = reload_link + filter + \'/\' + value + \'/\' + order + \'/\' + order_direction;
	location.href=link;
}
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
