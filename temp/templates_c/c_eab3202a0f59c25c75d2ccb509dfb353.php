<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-12 00:16:58 CDT */ ?>

<script><?php echo '
	$(function(){
		var data = ';  echo tpl_function_json_encode(array('data' => $this->_vars['likes_helper_data']), $this); echo ';
		loadScripts(
			\'';  echo tpl_function_js(array('module' => "likes",'file' => 'likes.js','return' => 'path'), $this); echo '\',
			function(){
				likes = new Likes({
					siteUrl: site_url,
					likeTitle: data.like_title,
					canLike: data.can_like
				});
			},
			\'\',
			{async: true}
		);
	});
</script>'; ?>
