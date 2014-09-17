{strip}
{if !$is_pjax}
<!DOCTYPE html>
<html DIR="{$_LANG.rtl}">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	{seotag tag='robots'}
	<link rel="shortcut icon" href="{$site_root}favicon.ico">
{/if}
	{seotag tag='description|keyword|canonical|og_title|og_type|og_url|og_image|og_site_name|og_description'}
	{seotag tag='title'}
	<script>
		var site_rtl_settings = '{$_LANG.rtl}';
		var is_pjax = parseInt('{$is_pjax}');
		var js_events = {json_encode data=$js_events};
		var id_user = {if $user_session_data.user_id}{$user_session_data.user_id}{else}0{/if};
	</script>

{if !$is_pjax}
	<script>
		var site_url = '{$site_url}';
		var img_folder = '{$img_folder}';
		var site_error_position = 'center';
		var use_pjax = parseInt('{$use_pjax}');
		var pjax_container = '#pjaxcontainer';
	</script>
	{helper func_name='js' helper_name='theme' func_param=$load_type}
{/if}

	<script>
		var messages = {json_encode data=$_PREDEFINED};
		var alerts;
		var notifications;
		{literal}
			new pginfo({messages: messages});
			$(function(){
				alerts = new Alerts();
				notifications = new Notifications();
			});
		{/literal}
	</script>


	{helper func_name='css' helper_name='theme' func_param=$load_type}

{if !$is_pjax}

	{literal}<!--[if gt IE 9]><style type="text/css">.gradient,.gradient:before,.gradient:after,[class*="icon-"] [class*="icon-"], [class*="icon-"] [class*="icon-"]:before, [class*="icon-"] [class*="icon-"]:after{filter: none;}</style><![endif]-->{/literal}
</head>

<body>
	{*<div id="fixed_content" style="background-color: #000000;color: #FFFFFF;font-size: 16px;padding: 10px;text-align: center; position: fixed; width: 100%; z-index: 100; top: 0; box-shadow: 1px 0 5px #000000;">
		<script>document.write('('+new Date().toLocaleTimeString()+')');</script> <span id="rnd_color" style="padding: 3px; border: 1px solid #fff;">Постоянный контент</span>
		<script>var rnd_color = 'rgb('+Math.ceil(Math.random()*255)+','+Math.ceil(Math.random()*255)+','+Math.ceil(Math.random()*255)+')'; $('#rnd_color').css('background-color', rnd_color)</script>
	</div>*}

	{helper func_name='im_chat_button' module='im'}
	{helper func_name='likes' module='likes'}
	{helper func_name='demo_panel' helper_name='start' func_param='user'}
	<div id="pjaxcontainer" class="hp100"{* style="margin-top: 55px;"*}>
{/if}
		<script>$('body').removeClass('index-page site-page').addClass('{if $header_type == 'index'}index-page{else}site-page{/if}');</script>

		{helper func_name='banner_initialize' module='banners'}
		{helper func_name='show_social_networks_head' module='social_networking'}
		{helper func_name='seo_traker' helper_name='seo_module_helper' module='seo' func_param='top'}
		{if $display_browser_error}
			{helper func_name='available_browsers' helper_name='start'}
		{/if}

		<div id="error_block">{foreach item=item from=$_PREDEFINED.error}{if $item.text}{$item.text}<br>{/if}{/foreach}</div>
		<div id="info_block">{foreach item=item from=$_PREDEFINED.info}{if $item.text}{$item.text}<br>{/if}{/foreach}</div>
		<div id="success_block">{foreach item=item from=$_PREDEFINED.success}{if $item.text}{$item.text}<br>{/if}{/foreach}</div>

		{if $header_type ne 'index'}
			<div class="header">
				<div class="content">
					<div class="header-logo">
						<a href="{$site_url}"><img src="{$site_root}{$mini_logo_settings.path}" border="0" alt="{helper func_name='seo_tags_default' func_param='header_text'}" width="{$mini_logo_settings.width}" height="{$mini_logo_settings.height}"></a>
					</div>
					<div class="header-menu">
						{if $auth_type eq 'user'}
							{helper func_name='auth_links' module='users'}
							{helper func_name='user_account' module='users_payments'}
							{block name='new_messages' module='mailbox' template='header'}
							{block name='admin_new_messages' module='tickets' template='header'}
							{helper func_name='users_lang_select' module='users'}
							{menu gid='settings_menu' template='settings_menu'}
						{else}
							{helper func_name='users_lang_select' module='users'}
							{helper func_name='auth_links' module='users'}
						{/if}
					</div>
				</div>
			</div>
			<div id="top_bar_fixed">
				<div class="menu-search-bar">
					<div class="content table-div">
						<div class="w30">
							<a href="javascript: history.back();"><i class="icon-arrow-left icon-big w edge hover"></i></a>
						</div>
						<div class="top_menu">
							{if $auth_type eq 'user'}{menu gid='user_top_menu' template='user_top_menu'}{else}{menu gid='guest_main_menu' template='user_main_menu'}{/if}
						</div>
						<div class="righted">
							{start_search_form type='line'}
						</div>
					</div>
				</div>
			</div>
			<div class="clr"></div>
		{/if}
		<div class="main">
			<div class="content">
				{breadcrumbs}
				{/strip}
					{helper func_name=show_banner_place module=banners func_param='top-banner'}
					{helper func_name=show_banner_place module=banners func_param='left-top-banner'}
					{helper func_name=show_banner_place module=banners func_param='right-top-banner'}
					<div class="clr"></div>
				{strip}
				{if $header_type ne 'index'}<div class="mb20 mt5">{helper func_name=featured_users module=users}</div>{/if}
{/strip}
