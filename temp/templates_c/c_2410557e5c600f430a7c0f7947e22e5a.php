<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-08 20:49:42 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if (is_array($this->_vars['media']) and count((array)$this->_vars['media'])): foreach ((array)$this->_vars['media'] as $this->_vars['key'] => $this->_vars['item']): ?>
	<?php if ($this->_vars['item']['status'] != 1 || $this->_vars['item']['permissions'] == 0 || ( $this->_vars['item']['id_parent'] && ( ( $this->_vars['item']['media'] && ! $this->_vars['item']['mediafile'] ) || ( $this->_vars['item']['video_content'] && ! $this->_vars['item']['media_video'] ) ) )): ?>
		<?php $this->assign('is_active', '0'); ?>
	<?php else: ?>
		<?php $this->assign('is_active', '1'); ?>
	<?php endif; ?>
	<div class="item">
		<div class="user<?php if ($this->_vars['item']['id_user'] != $this->_vars['item']['id_owner']): ?> not-owner<?php endif; ?>">
			<div class="photo<?php if (! $this->_vars['is_active']): ?> inactive<?php endif; ?>">
				<img class="pointer" data-click="view-media" data-id-media="<?php echo $this->_vars['item']['id']; ?>
" src="<?php if ($this->_vars['item']['media']):  echo $this->_vars['item']['media']['mediafile']['thumbs']['big'];  elseif ($this->_vars['item']['video_content']):  echo $this->_vars['item']['video_content']['thumbs']['big'];  endif; ?>" />
				<?php if ($this->_vars['item']['status'] == 0): ?>
					<div class="overlay-icon<?php if ($this->_vars['is_active']): ?> pointer<?php endif; ?>" title="<?php echo l('moderation_wait', 'media', '', 'text', array()); ?>"><i class="icon-time w icon-4x opacity60"></i></div>
				<?php elseif ($this->_vars['item']['status'] == -1): ?>
					<div class="overlay-icon<?php if ($this->_vars['is_active']): ?> pointer<?php endif; ?>" title="<?php echo l('moderation_decline', 'media', '', 'text', array()); ?>"><i class="icon-remove-sign w icon-4x opacity60"></i></div>
				<?php elseif ($this->_vars['item']['video_content']): ?>
					<div class="overlay-icon <?php if ($this->_vars['is_active']): ?> pointer<?php endif; ?>" data-click="view-media" data-id-media="<?php echo $this->_vars['item']['id']; ?>
"><i class="icon-play-sign w icon-4x opacity60"></i></div>
				<?php endif; ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
media/delete_media/<?php echo $this->_vars['item']['id']; ?>
" class="delete-media plr5"><i class="icon-remove w icon-big"></i></a>
				<div class="info">
					<div class="info-icons">
						<?php if ($this->_vars['item']['permissions'] == 0): ?><p><?php echo l('permissions_restrict', 'media', '', 'text', array()); ?></p><?php endif; ?>
						<?php if ($this->_vars['item']['id_parent'] && ( ( $this->_vars['item']['media'] && ! $this->_vars['item']['mediafile'] ) || ( $this->_vars['item']['video_content'] && ! $this->_vars['item']['media_video'] ) )): ?><p><?php echo l('media_deleted_by_owner', 'media', '', 'text', array()); ?></p><?php endif; ?>
						<div class="table-div wp100 box-sizing">
							<div>
								<i class="icon-eye-open edge w">&nbsp;</i><span class="mr10"><?php echo $this->_vars['item']['views']; ?>
</span>
								<span class="mr10"><?php echo tpl_function_block(array('name' => like_block,'module' => likes,'gid' => 'media'.$this->_vars['item']['id'],'type' => button,'btn_class' => "edge w"), $this);?></span>
								<?php if ($this->_vars['item']['is_adult']): ?><i class="icon-female edge w">&nbsp;</i><span>18+</span><?php endif; ?>
								<?php if ($this->_vars['item']['id_user'] != $this->_vars['item']['id_owner']): ?><span style="float:right"><?php echo tpl_function_block(array('name' => 'mark_as_spam_block','module' => 'spam','object_id' => $this->_vars['item']['id'],'type_gid' => 'media_object','template' => 'whitebutton'), $this);?></span><?php endif; ?>
							</div>
							<div class="righted w30">
								<span class="a" data-click="view-media" data-id-media="<?php echo $this->_vars['item']['id']; ?>
"><i class="icon-pencil edge w"></i></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; else: ?>
	<div class="fixmargin"><?php echo l('no_media', 'media', '', 'text', array()); ?></div>
<?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>