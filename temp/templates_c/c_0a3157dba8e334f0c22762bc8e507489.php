<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 03:46:17 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  if (is_array($this->_vars['comments']['comments']) and count((array)$this->_vars['comments']['comments'])): foreach ((array)$this->_vars['comments']['comments'] as $this->_vars['comment']): ?>
	<?php $this->assign('comment_id_user', $this->_vars['comment']['id_user']); ?>
	<div id="comment_id_<?php echo $this->_vars['comment']['id']; ?>
" class="comment_block item b user-content">
		<div class="image">
			<a<?php if (! $this->_vars['comments']['users'][$this->_vars['comment_id_user']]['is_guest']): ?> href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['comments']['users'][$this->_vars['comment_id_user']]), $this);?>"<?php endif; ?>><img src="<?php echo $this->_vars['comments']['users'][$this->_vars['comment_id_user']]['user_logo']; ?>
" alt="" title="" /></a>
		</div>
		<div class="content">
			<h3>
				<span><?php if ($this->_vars['comments']['users'][$this->_vars['comment_id_user']]['is_guest'] && $this->_vars['comment']['user_name']):  echo $this->_vars['comment']['user_name'];  else: ?><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['comments']['users'][$this->_vars['comment_id_user']]), $this);?>"><?php echo $this->_vars['comments']['users'][$this->_vars['comment_id_user']]['output_name']; ?>
</a><?php endif; ?></span><?php if (! $this->_vars['comment']['is_author']): ?><span class="ml10"><?php echo tpl_function_block(array('name' => 'mark_as_spam_block','module' => 'spam','object_id' => $this->_vars['comment']['id'],'type_gid' => 'comments_object','template' => 'minibutton'), $this);?></span><?php endif; ?>
				&nbsp;&nbsp;<span class="h5 fright"><?php echo $this->_run_modifier($this->_vars['comment']['date'], 'date_format', 'plugin', 1, $this->_vars['date_format']); ?>
</span>
			</h3>
			<div><?php echo $this->_run_modifier($this->_vars['comment']['text'], 'nl2br', 'PHP', 1); ?>
</div>
			<div class="pt10">
				<span>
					<?php if ($this->_vars['comments_type']['settings']['use_likes']): ?>
						<span class="fright mr20"><?php echo tpl_function_block(array('name' => like_block,'module' => likes,'gid' => 'cmnt'.$this->_vars['comment']['id'],'type' => button), $this);?></span>
					<?php endif; ?>
					<?php if ($this->_vars['comment']['can_edit']): ?>
						<a href="#" onclick="comments.deleteComment('<?php echo $this->_vars['comment']['id']; ?>
'); event.preventDefault();"><?php echo l('btn_delete', 'start', '', 'text', array()); ?></a>
					<?php endif; ?>
					
				</span>
			</div>
		</div>
	</div>
<?php endforeach; endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
