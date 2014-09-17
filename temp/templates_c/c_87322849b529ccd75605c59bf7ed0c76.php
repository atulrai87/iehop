<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.js.php'); $this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-12 00:35:59 CDT */ ?>

<script type='text/javascript'><?php echo '
	var banners;
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('module' => banners,'file' => 'banners.js','return' => 'path'), $this); echo '",
			function(){
				banners = new Banners;
			},
			\'banners\'
		);
	});
'; ?>
</script>