<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.block.php'); $this->register_function("block", "tpl_function_block");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 03:45:41 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  if (is_array($this->_vars['events']) and count((array)$this->_vars['events'])): foreach ((array)$this->_vars['events'] as $this->_vars['e']): ?>
	<?php if ($this->_vars['e']['html']): ?>
		<?php $this->assign('e_user_id', $this->_vars['e']['id_poster']); ?>
		<?php if ($this->_vars['users'][$this->_vars['e_user_id']]): ?>
			<?php $this->assign('e_user', $this->_vars['users'][$this->_vars['e_user_id']]); ?>
		<?php else: ?>
			<?php $this->assign('e_user', $this->_vars['users'][0]); ?>
		<?php endif; ?>
		<div id="wall_event_<?php echo $this->_vars['e']['id']; ?>
" class="user-content" gid="<?php echo $this->_vars['e']['event_type_gid']; ?>
">
			<div class="image small">
				<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => view,'data' => $this->_vars['e_user']), $this);?>"><img id="avatar_<?php echo $this->_vars['e_user']['id']; ?>
_e_<?php echo $this->_vars['e']['id']; ?>
"  src="<?php echo $this->_vars['e_user']['media']['user_logo']['thumbs']['small']; ?>
" /></a>
			</div>
			<div class="content">
				<div class="fleft"><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => view,'data' => $this->_vars['e_user']), $this);?>"><?php echo $this->_vars['e_user']['output_name']; ?>
</a>&nbsp;&nbsp;</div>
				<?php echo $this->_vars['e']['html']; ?>

				<span class="fright"><?php echo tpl_function_block(array('name' => like_block,'module' => likes,'gid' => 'wevt'.$this->_vars['e']['id'],'type' => button), $this);?></span>
				<div><?php echo tpl_function_block(array('name' => comments_form,'module' => comments,'gid' => wall_events,'id_obj' => $this->_vars['e']['id'],'hidden' => 1,'count' => $this->_vars['e']['comments_count']), $this);?></div>
			</div>
		</div>
	<?php endif;  endforeach; endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>