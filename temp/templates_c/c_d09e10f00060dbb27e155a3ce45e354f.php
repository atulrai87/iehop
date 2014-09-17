<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 05:47:33 CDT */ ?>

<h2 class="line top bottom linked">
	<?php echo l('table_header_personal', 'users', '', 'text', array()); ?>
</h2>
<div class="view-section">
	<?php 
$this->assign('no_info_str', l('no_information', 'users', '', 'text', array()));
 ?>
	<div class="r">
		<div class="f"><?php echo l('field_user_type', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['user_type_str']; ?>
</div>
	</div>
	<div class="r">
		<div class="f"><?php echo l('field_looking_user_type', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php if ($this->_vars['data']['looking_user_type_str']):  echo $this->_vars['data']['looking_user_type_str'];  else:  echo l('no_information', 'users', '', 'text', array());  endif; ?></div>
	</div>
	<div class="r">
		<div class="f"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?> <?php echo l('from', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php if ($this->_vars['data']['age_min']):  echo $this->_vars['data']['age_min'];  else:  echo l('no_information', 'users', '', 'text', array());  endif; ?></div>
	</div>
	<div class="r">
		<div class="f"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?> <?php echo l('to', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php if ($this->_vars['data']['age_max']):  echo $this->_vars['data']['age_max'];  else:  echo l('no_information', 'users', '', 'text', array());  endif; ?></div>
	</div>
	<div class="r">
		<div class="f"><?php echo l('field_nickname', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['nickname']; ?>
</div>
	</div>
	<?php if ($this->_vars['data']['fname']): ?>
		<div class="r">
			<div class="f"><?php echo l('field_fname', 'users', '', 'text', array()); ?>:</div>
			<div class="v"><?php echo $this->_vars['data']['fname']; ?>
</div>
		</div>
	<?php endif; ?>
	<?php if ($this->_vars['data']['sname']): ?>
		<div class="r">
			<div class="f"><?php echo l('field_sname', 'users', '', 'text', array()); ?>:</div>
			<div class="v"><?php echo $this->_vars['data']['sname']; ?>
</div>
		</div>
	<?php endif; ?>
	<div class="r">
		<div class="f"><?php echo l('birth_date', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_run_modifier($this->_vars['data']['birth_date'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format'], '', $this->_vars['no_info_str']); ?>
</div>
	</div>
	<div class="r">
		<div class="f"><?php echo l('field_region', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php if ($this->_vars['data']['location']):  echo $this->_vars['data']['location'];  else:  echo l('no_information', 'users', '', 'text', array());  endif; ?></div>
	</div>
</div>
<?php if (is_array($this->_vars['sections']) and count((array)$this->_vars['sections'])): foreach ((array)$this->_vars['sections'] as $this->_vars['item']): ?>
<h2 class="line top bottom linked">
	<?php echo $this->_vars['item']['name']; ?>

</h2>
<div class="view-section">
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "custom_view_fields.tpl", array('fields_data' => $this->_vars['item']['fields']));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</div>
<?php endforeach; endif; ?>
