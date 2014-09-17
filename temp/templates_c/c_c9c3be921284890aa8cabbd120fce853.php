<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-08 20:49:42 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block">
	<div class="tab-submenu bg-highlight_bg">
		<div class="ib">
			<ul id="filters">
				<li data-param="all"<?php if ($this->_vars['location_base_url']): ?> data-history="<?php echo $this->_vars['location_base_url']; ?>
all"<?php endif;  if ($this->_vars['gallery_param'] == 'all'): ?> class="active"<?php endif; ?>><span><?php echo l('all', 'media', '', 'text', array()); ?></span></li>
				<li data-param="photo"<?php if ($this->_vars['location_base_url']): ?> data-history="<?php echo $this->_vars['location_base_url']; ?>
photo"<?php endif;  if ($this->_vars['gallery_param'] == 'photo'): ?> class="active"<?php endif; ?>><span><?php echo l('photo', 'media', '', 'text', array()); ?></span></li>
				<li data-param="video"<?php if ($this->_vars['location_base_url']): ?> data-history="<?php echo $this->_vars['location_base_url']; ?>
video"<?php endif;  if ($this->_vars['gallery_param'] == 'video'): ?> class="active"<?php endif; ?>><span><?php echo l('video', 'media', '', 'text', array()); ?></span></li>
				<li data-param="albums"<?php if ($this->_vars['location_base_url']): ?> data-history="<?php echo $this->_vars['location_base_url']; ?>
albums"<?php endif;  if ($this->_vars['gallery_param'] == 'albums'): ?> class="active"<?php endif; ?>><span><?php echo l('albums', 'media', '', 'text', array()); ?></span></li>
				<?php if ($this->_vars['is_owner']): ?>
					<li data-param="favorites"<?php if ($this->_vars['location_base_url']): ?> data-history="<?php echo $this->_vars['location_base_url']; ?>
favorites"<?php endif;  if ($this->_vars['gallery_param'] == 'favorites'): ?> class="active"<?php endif; ?>><span><?php echo l('favorites', 'media', '', 'text', array()); ?></span></li>
				<?php endif; ?>
			</ul>
			<span id="album_id_container"<?php if ($this->_vars['gallery_param'] != 'albums'): ?> class="hide"<?php endif; ?>><?php echo $this->_vars['albums']; ?>
</span>
			<span id="media_sorter"<?php if ($this->_vars['gallery_param'] == 'albums'): ?> class="hide"<?php endif; ?>>
				<select>
					<?php if (is_array($this->_vars['media_sorter']['links']) and count((array)$this->_vars['media_sorter']['links'])): foreach ((array)$this->_vars['media_sorter']['links'] as $this->_vars['key'] => $this->_vars['item']): ?>
						<option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['media_sorter']['order']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option>
					<?php endforeach; endif; ?>
				</select>
				<i data-role="sorter-dir" class="icon-long-arrow <?php if ($this->_vars['media_sorter']['direction'] == 'ASC'): ?>up<?php else: ?>down<?php endif; ?> icon-big pointer plr5"></i>
			</span>
		</div>
		<?php if ($this->_vars['is_owner']): ?>
			<div class="fright">
				<ul>
					<li><s id="add_photo" class="a btn-link"><i class="icon-camera icon-big edge hover"><i class="icon-mini-stack icon-plus bottomright"></i></i><i><?php echo l('add_photo', 'media', '', 'text', array()); ?></i></s></li>
					<li><s id="add_video" class="a btn-link"><i class="icon-facetime-video icon-big edge hover"><i class="icon-mini-stack icon-plus bottomright"></i></i><i><?php echo l('add_video', 'media', '', 'text', array()); ?></i></s></li>
				</ul>
			</div>
		<?php endif; ?>
	</div>
	<div id="gallery_content" class="user-gallery media-gallery medium">
		<?php echo $this->_vars['content']['content']; ?>

	</div>
	<div class="clr"></div>
	<div class="media-button-content" <?php if (! $this->_vars['content']['have_more']): ?>style="display:none;"<?php endif; ?>><input id="media_button" type="button" value="<?php echo l('show_more', 'media', '', 'text', array()); ?>"></div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<script type='text/javascript'><?php echo '
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'media.js','module' => media,'return' => 'path'), $this); echo '",
			function(){
				mediagallery = new media({
					siteUrl: site_url,
					galleryContentPage: ';  echo $this->_vars['page'];  echo ',
					galleryContentParam: \'';  echo $this->_vars['gallery_param'];  echo '\',
					idUser: ';  echo $this->_vars['id_user'];  echo ',
					all_loaded: ';  if ($this->_vars['content']['have_more']): ?>0<?php else: ?>1<?php endif;  echo ',
					lang_delete_confirm: "';  echo l('delete_confirm', 'media', '', 'text', array());  echo '"
				});
			},
			[\'mediagallery\'],
			{async: true}
		);
	});
</script>'; ?>

