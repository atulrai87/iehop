<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.json_encode.php'); $this->register_function("json_encode", "tpl_function_json_encode");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.js.php'); $this->register_function("js", "tpl_function_js");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/block.strip.php'); $this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 03:46:15 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block load_content">
	<h1><?php echo l('view_media', 'media', '', 'text', array()); ?> <span id="media_position"></span></h1>
	<div class="m20 oh">
		<div class="edit-media-content" id="image_content"></div>
	</div>
	<div class="clr"></div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<script type='text/javascript'><?php echo '
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'edit_media.js','module' => media,'return' => 'path'), $this); echo '", 
			function(){
				ep = new editMedia({
					siteUrl: site_url,
					mediaId: \'';  echo $this->_vars['media_id'];  echo '\',
					galleryContentParam: \'';  echo $this->_vars['param'];  echo '\',
					albumId: \'';  echo $this->_vars['album_id'];  echo '\',
					gallery_name: \'';  if ($this->_vars['gallery_name']):  echo $this->_vars['gallery_name'];  else: ?>mediagallery<?php endif;  echo '\',
					selections: ';  echo tpl_function_json_encode(array('data' => $this->_vars['selections']), $this); echo ',
					success_request: function(message) {
						if (message){
							error_object.show_error_block(message, \'success\');
						} else {
							error_object.show_error_block(\'';  echo l("image_update_success", "media", '', 'text', array());  echo '\', \'success\');
						}
					},
					fail_request: function(message) {error_object.show_error_block(message, \'error\');}
				});
			},
			[\'ep\'],
			{async: true}
		);
	});
'; ?>
</script>