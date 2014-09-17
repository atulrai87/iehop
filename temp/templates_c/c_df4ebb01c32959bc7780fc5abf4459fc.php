<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.js.php'); $this->register_function("js", "tpl_function_js");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.block.php'); $this->register_function("block", "tpl_function_block");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.escape.php'); $this->register_modifier("escape", "tpl_modifier_escape");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/block.strip.php'); $this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 05:47:37 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block">
	<div class="view small">
		<div class="image">
			<div id="user_photo" class="pos-rel dimp100<?php if ($this->_vars['data']['user_logo']): ?> pointer<?php endif; ?>">
				<img src="<?php echo $this->_vars['data']['media']['user_logo']['thumbs']['middle']; ?>
" alt="<?php echo $this->_run_modifier($this->_vars['data']['output_name'], 'escape', 'plugin', 1); ?>
" />
			</div>
		</div>
		<div class="info">
			<div class="body">
				<h1>
				<span style="font-size:30px;line-height:28px;"><?php echo $this->_vars['data']['output_name']; ?>
</span>
				<span data-role="online_status" class="fright online-status"><s class="<?php echo $this->_vars['data']['statuses']['online_status_text']; ?>
"><?php echo $this->_vars['data']['statuses']['online_status_lang']; ?>
</s></span></h1>
				<div>
					<div class="fright"><?php echo l('views', 'users', '', 'text', array()); ?>: <?php echo $this->_vars['data']['views_count']; ?>
</div>
					<?php echo l('field_age', 'users', '', 'text', array()); ?>: <?php echo $this->_vars['data']['age'];  if ($this->_vars['data']['location']): ?><i class="delim-alone"></i><span class=""><?php echo $this->_vars['data']['location']; ?>
</span><?php endif; ?>
				</div>
			</div>
			<div class="actions noPrint">
				<?php echo tpl_function_block(array('name' => send_message_button,'module' => mailbox,'id_user' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_helper(array('func_name' => lists_links,'module' => users_lists,'func_param' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_helper(array('func_name' => im_chat_add_button,'module' => im,'func_param' => $this->_vars['data']['id']), $this);?>
				<?php echo tpl_function_block(array('name' => 'mark_as_spam_block','module' => 'spam','object_id' => $this->_vars['data']['id'],'type_gid' => 'users_object','template' => 'button'), $this);?>
			</div>
		</div>
	</div>

	<div class="edit_block" id="profile_tab_sections">
		<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "view_profile_menu.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
		<div class="view-user">
			<?php if (! $this->_vars['profile_section'] || $this->_vars['profile_section'] == 'profile'): ?>
				<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "view_users_profile.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
			<?php elseif ($this->_vars['profile_section'] == 'wall'): ?>
				<?php echo tpl_function_block(array('name' => wall_block,'module' => wall_events,'place' => 'viewprofile','id_wall' => $this->_vars['user_id']), $this);?>
			<?php elseif ($this->_vars['profile_section'] == 'gallery'): ?>
				<?php echo tpl_function_block(array('name' => media_block,'module' => media,'param' => $this->_vars['subsection'],'page' => '1','user_id' => $this->_vars['user_id'],'location_base_url' => $this->_vars['location_base_url']), $this);?>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="clr"></div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  if ($this->_vars['data']['user_logo']): ?>
	<script type='text/javascript'><?php echo '
		$(function(){
			loadScripts(
				["';  echo tpl_function_js(array('file' => 'users-avatar.js','module' => 'users','return' => 'path'), $this); echo '"],
				function(){
					user_avatar = new avatar({
						site_url: site_url,
						id_user: ';  echo $this->_vars['user_id'];  echo '
					});
				},
				[\'user_avatar\'],
				{async: true}
			);
		});
	</script>'; ?>

<?php endif;  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
