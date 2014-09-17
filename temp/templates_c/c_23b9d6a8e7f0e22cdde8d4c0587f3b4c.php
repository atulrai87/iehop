<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-08 14:09:35 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_ausers_change', 'ausers', '', 'text', array());  else:  echo l('admin_header_ausers_add', 'ausers', '', 'text', array());  endif; ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_nickname', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['nickname']; ?>
" name="nickname"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_email', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['email']; ?>
" name="email"></div>
		</div>
		<?php if ($this->_vars['data']['id']): ?>
		<div class="row">
			<div class="h"><?php echo l('field_change_password', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="checkbox" value="1" name="update_password" id="pass_change_field"></div>
		</div>
		<?php endif; ?>
		<div class="row">
			<div class="h"><?php echo l('field_password', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="password" value="" name="password" id="pass_field" <?php if ($this->_vars['data']['id']): ?>disabled<?php endif; ?>></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_repassword', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="password" value="" name="repassword" id="repass_field"<?php if ($this->_vars['data']['id']): ?>disabled<?php endif; ?>></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_user_type', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v">
				<select name="user_type" id="user_type_select">
				<option value="admin"<?php if ($this->_vars['data']['user_type'] == 'admin'): ?> selected<?php endif; ?>><?php echo l('field_user_type_admin', 'ausers', '', 'text', array()); ?></option>
				<option value="moderator"<?php if ($this->_vars['data']['user_type'] == 'moderator'): ?> selected<?php endif; ?>><?php echo l('field_user_type_moderator', 'ausers', '', 'text', array()); ?></option>
				</select>			
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_name', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['name']; ?>
" name="name"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_description', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><textarea name="description"><?php echo $this->_vars['data']['description']; ?>
</textarea></div>
		</div>

		<div class="row" id="permissions_block" <?php if ($this->_vars['data']['user_type'] != 'moderator'): ?>style="display: none"<?php endif; ?>>
			<div class="h"><?php echo l('field_permissions', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v">
				<?php if (is_array($this->_vars['methods']) and count((array)$this->_vars['methods'])): foreach ((array)$this->_vars['methods'] as $this->_vars['key'] => $this->_vars['module']): ?>
				<div class="permissions">
					<input type="checkbox" name="permission_data[<?php echo $this->_vars['key']; ?>
][<?php echo $this->_vars['module']['main']['method']; ?>
]" value=1 <?php if ($this->_vars['module']['main']['checked']): ?>checked<?php endif; ?> id="pd_<?php echo $this->_vars['key']; ?>
"> <label for="pd_<?php echo $this->_vars['key']; ?>
"><b><?php echo $this->_vars['module']['module']['module_name']; ?>
</b></label><br>
					<ul>
					<?php if (is_array($this->_vars['module']['methods']) and count((array)$this->_vars['module']['methods'])): foreach ((array)$this->_vars['module']['methods'] as $this->_vars['index'] => $this->_vars['item']): ?>
					<?php if ($this->_vars['index'] !== 'main'): ?><li><input type="checkbox" name="permission_data[<?php echo $this->_vars['key']; ?>
][<?php echo $this->_vars['item']['method']; ?>
]" value=1 <?php if ($this->_vars['item']['checked']): ?>checked<?php endif; ?> id="pd_<?php echo $this->_vars['key']; ?>
_<?php echo $this->_vars['item']['method']; ?>
" <?php if (! $this->_vars['module']['main']['checked']): ?>disabled<?php endif; ?>> <label for="pd_<?php echo $this->_vars['key']; ?>
_<?php echo $this->_vars['item']['method']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</label></li><?php endif; ?>					
					<?php endforeach; endif; ?>					
					</ul>	
				</div>			
				<?php endforeach; endif; ?>
			</div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/ausers"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<script><?php echo '
$(function(){
	$("div.row:odd").addClass("zebra");
	$("#pass_change_field").click(function(){
		if(this.checked){
			$("#pass_field").removeAttr("disabled"); $("#repass_field").removeAttr("disabled");
		}else{
			$("#pass_field").attr(\'disabled\', \'disabled\'); $("#repass_field").attr(\'disabled\', \'disabled\');
		}
	});
	
	$(\'#user_type_select\').bind(\'change\', function(){
		if($(this).val() == \'admin\'){
			$(\'#permissions_block\').hide();		
		}else{
			$(\'#permissions_block\').show();		
		}
	});
	
	$(\'.permissions > input[type=checkbox]\').bind(\'click\', function(){
		if($(this).is(\':checked\')){
			$(this).parent().find(\'input[id^=\'+$(this).attr(\'id\')+\'_]\').removeAttr("disabled");
		}else{
			$(this).parent().find(\'input[id^=\'+$(this).attr(\'id\')+\'_]\').attr(\'disabled\', \'disabled\');
		}
	});
});
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>