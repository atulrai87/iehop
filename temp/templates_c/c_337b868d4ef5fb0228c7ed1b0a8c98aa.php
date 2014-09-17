<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.country_select.php'); $this->register_function("country_select", "tpl_function_country_select");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-08 07:38:02 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="edit_block">
	<form method="post" enctype="multipart/form-data">
		<?php if ($this->_vars['action'] == 'personal'): ?>
			<?php if (! $this->_vars['not_editable_fields']['user_type']): ?>
				<div class="r">
					<div class="f"><?php echo l('field_user_type', 'users', '', 'text', array()); ?>:</div>
					<div class="v">
						<select name="user_type">
							<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['data']['user_type'] || ( ! $this->_vars['data']['user_type'] && $this->_vars['key'] == 2 )): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
						</select>
					</div>
				</div>
			<?php endif; ?>
			<?php if (! $this->_vars['not_editable_fields']['looking_user_type']): ?>
				<div class="r">
					<div class="f"><?php echo l('field_looking_user_type', 'users', '', 'text', array()); ?>:</div>
					<div class="v">
						<select name="looking_user_type">
							<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['data']['looking_user_type'] || ( ! $this->_vars['data']['looking_user_type'] && $this->_vars['key'] == 2 )): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
						</select>
					</div>
				</div>
			<?php endif; ?>
			<?php if (! $this->_vars['not_editable_fields']['age_min'] && ! $this->_vars['not_editable_fields']['age_max']): ?>
				<div class="r">
					<div class="f"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?>: </div>
					<div class="v">
						<?php if (! $this->_vars['not_editable_fields']['age_min']): ?>
							<?php echo l('from', 'users', '', 'text', array()); ?>&nbsp;
							<select name="age_min" class="short">
								<?php if (is_array($this->_vars['age_range']) and count((array)$this->_vars['age_range'])): foreach ((array)$this->_vars['age_range'] as $this->_vars['age']): ?>
									<option value="<?php echo $this->_vars['age']; ?>
"<?php if ($this->_vars['age'] == $this->_vars['data']['age_min']): ?> selected<?php endif; ?>><?php echo $this->_vars['age']; ?>
</option>
								<?php endforeach; endif; ?>
							</select>&nbsp;
						<?php endif; ?>
						<?php if (! $this->_vars['not_editable_fields']['age_max']): ?>
							<?php echo l('to', 'users', '', 'text', array()); ?>&nbsp;
							<select name="age_max" class="short">
								<?php if (is_array($this->_vars['age_range']) and count((array)$this->_vars['age_range'])): foreach ((array)$this->_vars['age_range'] as $this->_vars['age']): ?>
									<option value="<?php echo $this->_vars['age']; ?>
"<?php if ($this->_vars['age'] == $this->_vars['data']['age_max']): ?> selected<?php endif; ?>><?php echo $this->_vars['age']; ?>
</option>
								<?php endforeach; endif; ?>
							</select>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if (! $this->_vars['not_editable_fields']['nickname']): ?>
				<div class="r">
					<div class="f"><?php echo l('field_nickname', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="nickname" value="<?php echo $this->_run_modifier($this->_vars['data']['nickname'], 'escape', 'plugin', 1); ?>
"></div>
				</div>
			<?php endif; ?>
			<?php if (! $this->_vars['not_editable_fields']['fname']): ?>
				<div class="r">
					<div class="f"><?php echo l('field_fname', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="fname" value="<?php echo $this->_run_modifier($this->_vars['data']['fname'], 'escape', 'plugin', 1); ?>
"></div>
				</div>
			<?php endif; ?>
			<?php if (! $this->_vars['not_editable_fields']['sname']): ?>
				<div class="r">
					<div class="f"><?php echo l('field_sname', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="sname" value="<?php echo $this->_run_modifier($this->_vars['data']['sname'], 'escape', 'plugin', 1); ?>
"></div>
				</div>
			<?php endif; ?>
			<div class="r">
				<div class="f"><?php echo l('field_icon', 'users', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="file" name="user_icon">
					<?php if ($this->_vars['data']['user_logo'] || $this->_vars['data']['user_logo_moderation']): ?>
						<br><input type="checkbox" name="user_icon_delete" value="1" id="uichb"><label for="uichb"><?php echo l('field_icon_delete', 'users', '', 'text', array()); ?></label><br>
						<?php if ($this->_vars['data']['user_logo_moderation']): ?><img src="<?php echo $this->_vars['data']['media']['user_logo_moderation']['thumbs']['middle']; ?>
"><?php else: ?><img src="<?php echo $this->_vars['data']['media']['user_logo']['thumbs']['middle']; ?>
"><?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
			<?php if (! $this->_vars['not_editable_fields']['birth_date']): ?>
				<div class="r">
					<div class="f"><?php echo l('birth_date', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type='text' value='<?php echo $this->_vars['data']['birth_date']; ?>
' name="birth_date" id="datepicker" maxlength="10"></div>
				</div>
			<?php endif; ?>
			<div class="r">
				<div class="f"><?php echo l('field_region', 'users', '', 'text', array()); ?>: </div>
				<div class="v"><?php echo tpl_function_country_select(array('select_type' => 'city','id_country' => $this->_vars['data']['id_country'],'id_region' => $this->_vars['data']['id_region'],'id_city' => $this->_vars['data']['id_city']), $this);?></div>
			</div>
		<?php else: ?>
			<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "custom_form_fields.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
		<?php endif; ?>

		<div class="r">
			<div class="f">&nbsp;</div>
			<div class="v"><input type="submit" value="<?php if ($this->_vars['edit_mode']):  echo l('btn_save', 'start', '', 'button', array());  else:  echo l('btn_register', 'start', '', 'button', array());  endif; ?>" name="btn_register"></div>
		</div>
	</form>
	<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
	
	<script type='text/javascript'><?php echo '
		$(function(){
			now = new Date();
			yr =  (new Date(now.getYear() - 80, 0, 1).getFullYear()) + \':\' + (new Date(now.getYear() - 18, 0, 1).getFullYear());
			$( "#datepicker" ).datepicker({
				dateFormat :\'yy-mm-dd\',
				changeYear: true,
				changeMonth: true,
				yearRange: yr
			});
		});
	</script>'; ?>

</div>