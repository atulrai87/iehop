<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 05:47:35 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if ($this->_vars['media_count'] && $this->_vars['album']['media_count'] > $this->_vars['media_count']): ?>
	<div class="fixmargin mtb5"><?php echo l('no_permissions_for_view_part', 'media', '', 'text', array()); ?></div>
<?php endif; ?>

<?php if (is_array($this->_vars['media']) and count((array)$this->_vars['media'])): foreach ((array)$this->_vars['media'] as $this->_vars['key'] => $this->_vars['item']): ?>
	<div class="item">
		<div class="user<?php if ($this->_vars['item']['id_user'] != $this->_vars['item']['id_owner']): ?> not-owner<?php endif; ?>">
			<div class="photo">
				<span class="a" data-click="view-media" data-id-media="<?php echo $this->_vars['item']['id']; ?>
">
					<?php if ($this->_vars['item']['video_content']): ?><div class="overlay-icon pointer"><i class="icon-play-sign w icon-4x opacity60"></i></div><?php endif; ?>
					<img src="<?php if ($this->_vars['item']['media']):  echo $this->_vars['item']['media']['mediafile']['thumbs']['big'];  elseif ($this->_vars['item']['video_content']):  echo $this->_vars['item']['video_content']['thumbs']['big'];  endif; ?>" />
				</span>
				<div class="info">
					<div class="info-icons">
						<?php if ($this->_vars['item']['id_parent'] && ( ( $this->_vars['item']['media'] && ! $this->_vars['item']['mediafile'] ) || ( $this->_vars['item']['video_content'] && ! $this->_vars['item']['media_video'] ) )): ?><p><?php echo l('media_deleted_by_owner', 'media', '', 'text', array()); ?></p><?php endif; ?>
						<div>
							<i class="icon-eye-open edge w">&nbsp;</i><span class="mr10"><?php echo $this->_vars['item']['views']; ?>
</span>
							<span class="mr10"><?php echo tpl_function_block(array('name' => like_block,'module' => likes,'gid' => 'media'.$this->_vars['item']['id'],'type' => button,'btn_class' => "edge w"), $this);?></span>
							<?php if ($this->_vars['item']['is_adult']): ?><i class="icon-female edge w">&nbsp;</i><span>18+</span><?php endif; ?>
							<?php if (! $this->_vars['item']['is_owner']): ?><span style="float:right"><?php echo tpl_function_block(array('name' => 'mark_as_spam_block','module' => 'spam','object_id' => $this->_vars['item']['id'],'type_gid' => 'media_object','template' => 'whitebutton'), $this);?></span><?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; else: ?>
	<div class="fixmargin"><?php if ($this->_vars['album']['media_count']):  echo l('no_permissions_for_view_all', 'media', '', 'text', array());  else:  echo l('no_media', 'media', '', 'text', array());  endif; ?></div>
<?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>