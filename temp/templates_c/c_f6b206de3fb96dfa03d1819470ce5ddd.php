<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.js.php'); $this->register_function("js", "tpl_function_js");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.seolink.php'); $this->register_function("seolink", "tpl_function_seolink");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.block.php'); $this->register_function("block", "tpl_function_block");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.date_format.php'); $this->register_modifier("date_format", "tpl_modifier_date_format");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/block.strip.php'); $this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 20:23:54 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div><?php echo $this->_run_modifier($this->_vars['event']['date_update'], 'date_format', 'plugin', 1, $this->_vars['date_format']);  if ($this->_vars['event']['id_poster'] != $this->_vars['user_id']): ?><span class="ml10"><?php echo tpl_function_block(array('name' => 'mark_as_spam_block','module' => 'spam','object_id' => $this->_vars['event']['id'],'type_gid' => 'wall_events_object','template' => 'minibutton'), $this);?></span><?php endif; ?></div>
<div class="ptb5"><?php if ($this->_vars['event']['event_type_gid'] == 'image_upload'):  echo l('uploads_new_photos', 'media', '', 'text', array());  elseif ($this->_vars['event']['event_type_gid'] == 'video_upload'):  echo l('uploads_new_videos', 'media', '', 'text', array());  endif; ?> (<?php echo $this->_vars['event']['media_count_all']; ?>
)</div>
<div class="user-gallery medium">
	<?php if (is_array($this->_vars['event']['data']) and count((array)$this->_vars['event']['data'])): foreach ((array)$this->_vars['event']['data'] as $this->_vars['key'] => $this->_vars['edata']): ?>
		<div class="item">
			<div class="user">
				<div class="photo">
					<span class="a" data-click="view-media" data-user-id="<?php echo $this->_vars['edata']['id_owner']; ?>
" data-id-media="<?php echo $this->_vars['edata']['id']; ?>
">
						<?php if ($this->_vars['edata']['video_content']): ?><div class="overlay-icon pointer"><i class="icon-play-sign w icon-4x opacity60"></i></div><?php endif; ?>
						<img src="<?php if ($this->_vars['edata']['media']):  echo $this->_vars['edata']['media']['mediafile']['thumbs']['big'];  elseif ($this->_vars['edata']['video_content']):  echo $this->_vars['edata']['video_content']['thumbs']['big'];  endif; ?>" />
					</span>
					<div class="info">
						<div class="info-icons">
							<?php if ($this->_vars['edata']['id_parent'] && ( ( $this->_vars['edata']['media'] && ! $this->_vars['edata']['mediafile'] ) || ( $this->_vars['edata']['video_content'] && ! $this->_vars['edata']['media_video'] ) )): ?><p><?php echo l('media_deleted_by_owner', 'media', '', 'text', array()); ?></p><?php endif; ?>
							<div>
								<i class="icon-eye-open edge w">&nbsp;</i><span class="mr10"><?php echo $this->_vars['edata']['views']; ?>
</span>
								<span class="mr10"><?php echo tpl_function_block(array('name' => like_block,'module' => likes,'gid' => 'media'.$this->_vars['edata']['id'],'type' => button,'btn_class' => "edge w"), $this);?></span>
								<?php if ($this->_vars['edata']['is_adult']): ?><i class="icon-female edge w">&nbsp;</i><span>18+</span><?php endif; ?>
								<?php if ($this->_vars['edata']['id_user'] != $this->_vars['user_id']): ?><span style="float:right"><?php echo tpl_function_block(array('name' => 'mark_as_spam_block','module' => 'spam','object_id' => $this->_vars['edata']['id'],'type_gid' => 'media_object','template' => 'whitebutton'), $this);?></span><?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; endif; ?>
</div>
<?php if ($this->_vars['event']['media_count_more']): ?>
	<div class="fright righted"><a class="hover-icon" href="<?php if ($this->_vars['user_id'] == $this->_vars['event']['id_poster']):  echo tpl_function_seolink(array('module' => 'users','method' => 'profile'), $this);?>gallery<?php else:  echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['users'].$this->_vars['event']['id_poster']), $this);?>/gallery<?php endif; ?>"><i class="icon-arrow-right edge hover"></i><span class="ml5"><?php echo l('show_more', 'media', '', 'text', array()); ?>&nbsp;(<?php echo $this->_vars['event']['media_count_more']; ?>
)</span></a></div>
<?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<script><?php echo '
	$(function(){
		if(!window.wall_mediagallery){
			loadScripts(
				"';  echo tpl_function_js(array('file' => 'media.js','module' => media,'return' => 'path'), $this); echo '", 
				function(){
					wall_mediagallery = new media({
						siteUrl: site_url,
						gallery_name: \'wall_mediagallery\',
						galleryContentPage: 1,
						idUser: 0,
						all_loaded: 1,
						lang_delete_confirm: "';  echo l('delete_confirm', 'media', '', 'text', array());  echo '",
						galleryContentDiv: \'wall_events\',
						post_data: {filter_duplicate: 1},
						load_on_scroll: false,
						sorterId: \'\',
						direction: \'asc\'
					});
				},
				\'wall_mediagallery\', 
				{async: false}
			);
		}
	});
</script>'; ?>

