<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-27 09:06:59 CDT */ ?>

<div class="<?php echo $this->_vars['place']['keyword']; ?>
-place">
<?php if ($this->_vars['place']['rotate_time'] > 0): ?>
	<script language="JavaScript">
		var place_data<?php echo $this->_vars['place']['id']; ?>
 = <?php echo '{'; ?>
id: <?php echo $this->_vars['place']['id']; ?>
, width: <?php echo $this->_vars['place']['width']; ?>
, height: <?php echo $this->_vars['place']['height']; ?>
, rotate_time: <?php echo $this->_vars['place']['rotate_time']; ?>
, keyword: '<?php echo $this->_vars['place']['keyword']; ?>
', div_id: 'banner-place-<?php echo $this->_vars['place']['keyword']; ?>
'<?php echo '}'; ?>

		
		var banner_data<?php echo $this->_vars['place']['id']; ?>
 = new Array();
		
		<?php if (is_array($this->_vars['banners']) and count((array)$this->_vars['banners'])): foreach ((array)$this->_vars['banners'] as $this->_vars['key'] => $this->_vars['banner']): ?>
			<?php if ($this->_vars['banner']['banner_type'] == 1): ?>
				banner_data<?php echo $this->_vars['place']['id']; ?>
[<?php echo $this->_vars['key']; ?>
] = <?php echo '{'; ?>
 id: <?php echo $this->_vars['banner']['id']; ?>
, banner_type: 'image', banner_src: '<?php echo $this->_vars['banner']['media']['banner_image']['file_url']; ?>
', html: '<a href="<?php echo $this->_vars['site_url']; ?>
banners/go/<?php echo $this->_vars['banner']['id']; ?>
" title="<?php echo $this->_vars['banner']['alt_text']; ?>
" <?php if ($this->_vars['banner']['new_window'] == 1 || $this->_vars['banner']['user_id']): ?>target="_blank"<?php endif; ?>><img alt="<?php echo $this->_vars['banner']['alt_text']; ?>
" width="<?php echo $this->_vars['place']['width']; ?>
" height="<?php echo $this->_vars['place']['height']; ?>
" src="<?php echo $this->_vars['banner']['media']['banner_image']['file_url']; ?>
" class="banner"/></a>'<?php echo '}'; ?>
;
			<?php else: ?>
				banner_data<?php echo $this->_vars['place']['id']; ?>
[<?php echo $this->_vars['key']; ?>
] = <?php echo '{'; ?>
 id: <?php echo $this->_vars['banner']['id']; ?>
, banner_type: 'html', html: '<?php echo $this->_run_modifier($this->_vars['banner']['html'], 'mysql_escape_string', 'PHP', 1); ?>
'<?php echo '}'; ?>
;
			<?php endif; ?>
		<?php endforeach; endif; ?>
		
		<?php echo '
		(function(){
			var banners_trys = 0;
			load_banners();
			function load_banners(){
				if(window.banners){
					banners.create_banner_area(place_data';  echo $this->_vars['place']['id'];  echo ', banner_data';  echo $this->_vars['place']['id'];  echo ');
				}else if(banners_trys++ < 20){
					setTimeout(load_banners, 500);
				}
			};
		})();
		'; ?>

	</script>
	<div id="banner-place-<?php echo $this->_vars['place']['keyword']; ?>
" style="width: <?php echo $this->_vars['place']['width']; ?>
px; height: <?php echo $this->_vars['place']['height']; ?>
px; overflow: hidden;"></div>
<?php elseif ($this->_vars['banners']): ?>
	<?php $this->assign('banner', $this->_vars['banners'][0]); ?>
	<div id="banner-place-<?php echo $this->_vars['place']['keyword']; ?>
" style="width: <?php echo $this->_vars['place']['width']; ?>
px; height: <?php echo $this->_vars['place']['height']; ?>
px;">
	<?php if ($this->_vars['banner']['banner_type'] == 1): ?>
	<a href="<?php echo $this->_vars['site_url']; ?>
banners/go/<?php echo $this->_vars['banner']['id']; ?>
" title="<?php echo $this->_vars['banner']['alt_text']; ?>
" <?php if ($this->_vars['banner']['new_window'] == 1 || $this->_vars['banner']['user_id']): ?>target="_blank"<?php endif; ?>><img alt="<?php echo $this->_vars['banner']['alt_text']; ?>
" width="<?php echo $this->_vars['place']['width']; ?>
" height="<?php echo $this->_vars['place']['height']; ?>
" src="<?php echo $this->_vars['banner']['media']['banner_image']['file_url']; ?>
" class="banner"/></a>
	<?php else: ?>
	<?php echo $this->_vars['banner']['html']; ?>

	<?php endif; ?>	
	</div>
<?php endif; ?>
</div>
