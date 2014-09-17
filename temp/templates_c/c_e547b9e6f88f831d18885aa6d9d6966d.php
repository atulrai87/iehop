<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.js.php'); $this->register_function("js", "tpl_function_js");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/block.strip.php'); $this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-06 21:02:12 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if (is_array($this->_vars['albums']) and count((array)$this->_vars['albums'])): foreach ((array)$this->_vars['albums'] as $this->_vars['key'] => $this->_vars['item']): ?>
	<div class="item album-cover" data-album-id="<?php echo $this->_vars['item']['id']; ?>
">
		<div class="album-bg"></div>
		<div class="user">
			<div class="photo">
				<img class="pointer" data-click="album" src="<?php if ($this->_vars['item']['mediafile']['media']):  echo $this->_vars['item']['mediafile']['media']['mediafile']['thumbs']['big'];  elseif ($this->_vars['item']['mediafile']['video_content']):  echo $this->_vars['item']['mediafile']['video_content']['thumbs']['big'];  endif; ?>" />
				<?php if ($this->_vars['item']['mediafile']['video_content']): ?><div class="overlay-icon pointer" data-click="album"><i class="icon-play-sign w icon-4x opacity60"></i></div><?php endif; ?>
				<?php if ($this->_vars['is_user_album_owner']): ?><a href="<?php echo $this->_vars['site_url']; ?>
media/delete_album/<?php echo $this->_vars['item']['id']; ?>
" class="delete-media plr5" data-album-id="<?php echo $this->_vars['item']['id']; ?>
"><i class="icon-remove w icon-big"></i></a><?php endif; ?>
				<?php if ($this->_vars['item']['description'] || $this->_vars['is_user_album_owner']): ?>
					<div class="info">
						<div class="info-icons">
							<s title="<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  echo $this->_vars['item']['description'];  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>"><?php echo $this->_run_modifier($this->_vars['item']['description'], 'nl2br', 'PHP', 1); ?>
</s>
							<?php if ($this->_vars['is_user_album_owner']): ?><a href="<?php echo $this->_vars['site_url']; ?>
media/edit_album/<?php echo $this->_vars['item']['id']; ?>
" class="edit-album fright"><i class="icon-pencil edge w"></i></a><?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="subinfo">
			<div class="text-overflow" title="<?php echo $this->_vars['item']['name']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</div>
			<div class="text-overflow"><?php echo l('album_items', 'media', '', 'text', array()); ?>: <?php echo $this->_vars['item'][$this->_vars['albums_count_field']]; ?>
</div>
		</div>
	</div>
<?php endforeach; else: ?>
	<div class="fixmargin"><?php echo l('no_albums', 'media', '', 'text', array()); ?></div>
<?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<?php if ($this->_vars['albums_page'] == 1): ?>
	<script type='text/javascript'><?php echo '
			$(function(){
			loadScripts(
				"';  echo tpl_function_js(array('file' => 'albums.js','module' => media,'return' => 'path'), $this); echo '", 
				function(){
					albums_list = new albums({
						siteUrl: site_url,
						edit_album_success_request: function(){
							mediagallery.properties.galleryContentPage = 1,
							mediagallery.properties.all_loaded = 0;
							mediagallery.load_content(1);
							error_object.show_error_block(\'';  echo l("album_update_success", "media", '', 'text', array());  echo '\', \'success\');
							this.windowObj.hide_load_block();
						},
					});
				},
				[\'albums_list\'],
				{async: false}
			);
			});
	'; ?>
</script>
<?php endif; ?>