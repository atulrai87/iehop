<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="title" content="" />
    <meta name="description" content="" />
    <style type="text/css">
		{literal}
		body{color: #{/literal}{$widget.colors.text}{literal}; border: 1px solid #{/literal}{$widget.colors.border}{literal}; background: #{/literal}{$widget.colors.background}{literal}; margin: 0; padding: 0; font-size: 12px;}
		.title{color: #{/literal}{$widget.colors.block}{literal}; background: #{/literal}{$widget.colors.border}{literal};text-align: center; font-size: 14px; line-height: 26px;}
		.footer{color: #{/literal}{$widget.colors.block}{literal}; font-size: 10px; text-align: right; padding: 4px; text-align: center; background: #{/literal}{$widget.colors.border}{literal};}
		a{color: #{/literal}{$widget.colors.link}{literal}; text-decoration: none;}
		input[type=text], input[type=password], select, textarea{border: solid 1px #{/literal}{$widget.colors.border}{literal}; background-color: #{/literal}{$widget.colors.background}{literal};}
		input[type=text]:focus, input[type=password]:focus, select:focus, textarea:focus{border-color: #{/literal}{$widget.colors.border}{literal};}
		.item{border-bottom: 1px solid #{/literal}{$widget.colors.border}{literal};}
		.hide{display: none;}
		{/literal}
    </style>
    {js file='jquery.js'}
    {js file='jquery.browser.js' module='widgets'} 
    {js file='postmessage.js' module='widgets'} 
</head> 
<body>
	{if $widget.title}<div class="title">{$widget.title}</div>{/if}
	<div class="content">{$widget.content}</div>
	{if $widget.footer}<div class="footer">{$widget.footer}</div>{/if}	
	
	<script>{literal}
		function resizeBlock(){
			var parent_url = decodeURIComponent(document.location.hash.replace(/^#/, ''));
			$.postMessage({widget_{/literal}{$widget.gid}{literal}_height: $('body').outerHeight(true)}, parent_url, parent);
		}
		$(function(){
			resizeBlock();
		});
	{/literal}</script>
</body>
</html>
