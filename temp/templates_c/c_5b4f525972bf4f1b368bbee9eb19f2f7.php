<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.ld.php'); $this->register_function("ld", "tpl_function_ld");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.block.php'); $this->register_function("block", "tpl_function_block");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.seolink.php'); $this->register_function("seolink", "tpl_function_seolink");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/block.strip.php'); $this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 03:46:17 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if ($this->_vars['media']['upload_gid'] == 'gallery_video'): ?>
	<?php if ($this->_vars['media']['media_video_data']['status'] == 'start'): ?>
		<div class="pos-rel">
			<div class="center lh0 pos-rel">
				<img data-image-src="<?php echo $this->_vars['media']['video_content']['thumbs']['great']; ?>
" src='<?php echo $this->_vars['media']['video_content']['thumbs']['great']; ?>
'>
				<div id="next_media" class="load_content_right media_view_scroller_right"></div>
				<div id="prev_media" class="load_content_left media_view_scroller_left"></div>
			</div>
			<div class="subinfo box-sizing">
				<p><?php echo l('video_wait_converting', 'media', '', 'text', array()); ?></p>
				<?php if ($this->_vars['media']['id_parent'] || ! $this->_vars['is_user_media_owner']): ?>
					<?php if ($this->_vars['media']['id_parent']): ?>
						<?php if ($this->_vars['media']['permissions'] == 0): ?><p><?php echo l('permissions_restrict', 'media', '', 'text', array()); ?></p><?php endif; ?>
						<?php if ($this->_vars['media']['video_content'] && ! $this->_vars['media']['media_video']): ?><p><?php echo l('media_deleted_by_owner', 'media', '', 'text', array()); ?></p><?php endif; ?>
					<?php endif; ?>
					<span>
						<?php echo l('media_owner', 'media', '', 'text', array()); ?>:&nbsp;
						<?php if ($this->_vars['media']['owner_info']['id']): ?>
							<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['media']['owner_info']), $this);?>"><?php echo $this->_vars['media']['owner_info']['output_name']; ?>
</a>
						<?php else: ?>
							<span><?php echo $this->_vars['media']['owner_info']['output_name']; ?>
</span>
						<?php endif; ?>
					</span>
				<?php endif; ?>
			</div>
		</div>
	<?php else: ?>
		<div class="plr50 pos-rel">
			<div style="width: <?php echo $this->_vars['media']['video_content']['width']; ?>
px;" class="center-block">
				<?php echo $this->_vars['media']['video_content']['embed']; ?>

			</div>
			<div id="next_media" class="load_content_right media_view_scroller_right"></div>
			<div id="prev_media" class="load_content_left media_view_scroller_left"></div>
		</div>
		<?php if (! $this->_vars['is_user_media_owner']): ?>
			<div>
				<?php echo l('media_owner', 'media', '', 'text', array()); ?>:&nbsp;
				<?php if ($this->_vars['media']['owner_info']['id']): ?>
					<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['media']['owner_info']), $this);?>"><?php echo $this->_vars['media']['owner_info']['output_name']; ?>
</a>
				<?php else: ?>
					<span><?php echo $this->_vars['media']['owner_info']['output_name']; ?>
</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
<?php elseif ($this->_vars['media']['upload_gid'] == 'gallery_image'): ?>
	<div class="pos-rel">
		<div class="center lh0">
			<div class="photo-edit hide" data-area="recrop">
				<div class="source-box">
					<div id="photo_source_recrop_box" class="photo-source-box">
						<img src="<?php echo $this->_vars['media']['media']['mediafile']['file_url']; ?>
" id="photo_source_recrop">
					</div>
					<div class="ptb5 oh tab-submenu" id="recrop_menu">
						<ul class="fleft" id="photo_sizes"></ul>
						<ul class="fright">
							<li><span data-section="view"><?php echo l("view", "media", '', 'text', array()); ?></span></li>
						</ul>
					</div>
				</div>
			</div>

			<div data-area="view">
				<img data-image-src="<?php echo $this->_vars['media']['media']['mediafile']['thumbs']['grand']; ?>
" src="<?php echo $this->_vars['media']['media']['mediafile']['thumbs']['grand']; ?>
">
				<div id="next_media" class="load_content_right"></div>
				<div id="prev_media" class="load_content_left"></div>
			</div>
		</div>

		<?php if ($this->_vars['media']['id_parent'] || ! $this->_vars['is_user_media_owner']): ?>
			<div class="subinfo box-sizing">
				<?php if ($this->_vars['media']['id_parent']): ?>
					<?php if ($this->_vars['media']['permissions'] == 0): ?><p><?php echo l('permissions_restrict', 'media', '', 'text', array()); ?></p><?php endif; ?>
					<?php if ($this->_vars['media']['media'] && ! $this->_vars['media']['mediafile']): ?><p><?php echo l('media_deleted_by_owner', 'media', '', 'text', array()); ?></p><?php endif; ?>
				<?php endif; ?>
				<span>
					<?php echo l('media_owner', 'media', '', 'text', array()); ?>:&nbsp;
					<?php if ($this->_vars['media']['owner_info']['id']): ?>
						<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['media']['owner_info']), $this);?>"><?php echo $this->_vars['media']['owner_info']['output_name']; ?>
</a>
					<?php else: ?>
						<span><?php echo $this->_vars['media']['owner_info']['output_name']; ?>
</span>
					<?php endif; ?>
				</span>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>

<div class="media-preloader hide" id="media_preloader"></div>

<div>
	<div class="ptb5 oh tab-submenu" data-area="view">
		<div class="fleft">
			<?php echo $this->_run_modifier($this->_vars['media']['date_add'], 'date_format', 'plugin', 1, $this->_vars['date_formats']['date_time_format']); ?>

			<span class="ml20">
				<?php echo tpl_function_block(array('name' => like_block,'module' => likes,'gid' => 'media'.$this->_vars['media']['id'],'type' => button), $this);?>
			</span>
			<?php if (! $this->_vars['is_user_media_owner']): ?>
				<span class="ml20">
					<span title="<?php echo l('favorites', 'media', '', 'text', array()); ?>" class="to_favorites pointer<?php if ($this->_vars['in_favorites']): ?> active<?php endif; ?>" data-id="<?php echo $this->_vars['default_album']['id']; ?>
">
						<i class="<?php if ($this->_vars['in_favorites']): ?>icon-star<?php else: ?>icon-star-empty<?php endif; ?> pr5 status-icon"></i>
					</span>
				</span>
				<span class="ml20">
					<?php echo tpl_function_block(array('name' => 'mark_as_spam_block','module' => 'spam','object_id' => $this->_vars['media']['id'],'type_gid' => 'media_object','template' => 'minibutton'), $this);?>
				</span>
			<?php endif; ?>
			
		</div>
		<div class="fright">
			<ul id="media_menu">
				<li class="active"><span data-section="comments"><?php echo l("comments", "media", '', 'text', array()); ?></span></li>
				<?php if ($this->_vars['is_user_media_owner']): ?><li><span data-section="access"><?php echo l("access", "media", '', 'text', array()); ?></span></li><?php endif; ?>
				<li><span data-section="albums"><?php echo l("albums", "media", '', 'text', array()); ?></span></li>
				<?php if ($this->_vars['is_user_media_owner'] && $this->_vars['media']['upload_gid'] == 'gallery_image'): ?><li><span data-section="recrop"><?php echo l("recrop", "media", '', 'text', array()); ?></span></li><?php endif; ?>
			</ul>
		</div>
	</div>
	<?php if ($this->_vars['is_user_media_owner']): ?>
		<div class="contenteditable mt5" title="<?php echo l('edit_description', 'media', '', 'button', array()); ?>">
			<span contenteditable>
				<?php if ($this->_vars['media']['description']):  echo $this->_run_modifier($this->_vars['media']['description'], 'nl2br', 'PHP', 1);  endif; ?>
			</span>
			<i class="edge icon- hover active"></i>
		</div>
	<?php else: ?>
		<?php if ($this->_vars['media']['description']): ?>
			<div><?php echo $this->_run_modifier($this->_vars['media']['description'], 'nl2br', 'PHP', 1); ?>
</div>
		<?php endif; ?>
	<?php endif; ?>
</div>


<div id="media_sections" class="pt10">
	<div data-section="comments">
		<?php echo tpl_function_block(array('name' => comments_form,'module' => comments,'gid' => media,'id_obj' => $this->_vars['media']['id'],'hidden' => 0,'max_height' => 500), $this);?>
	</div>

	<?php if ($this->_vars['is_user_media_owner']): ?>
		<div data-section="access" class="hide">
			<div class="h2"><?php echo l('field_permitted_for', 'media', '', 'text', array()); ?></div>
			<?php if (! $this->_vars['is_user_media_owner']): ?>
				<div class="h3 error-text"><?php echo l('only_owner_access', 'media', '', 'text', array()); ?></div>
			<?php endif; ?>
			<div class="perm">
				<?php echo tpl_function_ld(array('gid' => 'media','i' => 'permissions'), $this);?>
				<ul>
					<?php if (is_array($this->_vars['ld_permissions']['option']) and count((array)$this->_vars['ld_permissions']['option'])): foreach ((array)$this->_vars['ld_permissions']['option'] as $this->_vars['key'] => $this->_vars['item']): ?>
						<li><label><input type="radio"<?php if (! $this->_vars['is_user_media_owner']): ?> disabled<?php endif; ?> name="permissions" id="permissions" value="<?php echo $this->_vars['key']; ?>
" <?php if ($this->_vars['media']['permissions'] == $this->_vars['key']): ?> checked<?php endif; ?>> <?php echo $this->_vars['item']; ?>
</label></li>
					<?php endforeach; endif; ?>
				</ul>
			</div>
			<?php if ($this->_vars['is_user_media_owner']): ?>
				<input type="button" class="btn" value="<?php echo l('btn_apply', 'start', '', 'text', array()); ?>" name="save_permissions" id="save_permissions">
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div data-section="albums" class="hide"></div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
