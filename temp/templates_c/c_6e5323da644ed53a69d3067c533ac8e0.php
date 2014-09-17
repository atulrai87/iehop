<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.seolink.php'); $this->register_function("seolink", "tpl_function_seolink");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/block.strip.php'); $this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 03:45:41 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
	<?php if (is_array($this->_vars['event']['data']) and count((array)$this->_vars['event']['data'])): foreach ((array)$this->_vars['event']['data'] as $this->_vars['key'] => $this->_vars['edata']): ?>
		<?php $this->assign('id_dest_user', $this->_vars['edata']['id_dest_user']); ?>
		<?php if (! $this->_vars['users'][$this->_vars['id_dest_user']]):  $this->assign('id_dest_user', 0);  endif; ?>
		
		<div><?php echo $this->_run_modifier($this->_vars['edata']['event_date'], 'date_format', 'plugin', 1, $this->_vars['date_format']); ?>
</div>
		
		<div>
			<div class="user-content">
				<div class="image"><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => view,'data' => $this->_vars['users'][$this->_vars['id_dest_user']]), $this);?>"><img src="<?php echo $this->_vars['users'][$this->_vars['id_dest_user']]['media']['user_logo']['thumbs']['small']; ?>
" /></a></div>
				<div class="content">
					<?php echo l('wall_events_and', 'users_lists', '', 'text', array()); ?>&nbsp;<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => view,'data' => $this->_vars['users'][$this->_vars['id_dest_user']]), $this);?>"><?php echo $this->_vars['users'][$this->_vars['id_dest_user']]['output_name']; ?>
</a>&nbsp;
					<?php if ($this->_vars['event']['event_type_gid'] == 'friend_add'): ?>
						<?php echo l('wall_now_friends', 'users_lists', '', 'text', array()); ?>
					<?php elseif ($this->_vars['event']['event_type_gid'] == 'friend_del'): ?>
						<?php echo l('wall_not_friends', 'users_lists', '', 'text', array()); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>