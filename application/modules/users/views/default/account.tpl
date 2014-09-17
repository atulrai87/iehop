{include file="header.tpl"}

{strip}
<div class="content-block">
	<h1>{seotag tag='header_text'}: {l i='header_'$action gid='users'}</h1>
	
	{include file="account_menu.tpl" module="users" theme="default"}
	
	{if $action eq 'services'}
		<div class="line top">{block name='services_buy_list' module='services'}</div>
		<div class="line top">{block name='packages_list' module='packages'}</div>
	{elseif $action eq 'my_services'}
		<div class="line top">{block name='user_services_list' module='services' id_user=$user_id}</div>
		<div class="line top">{block name='user_packages_list' module='packages'}</div>
	{elseif $action eq 'update'}
		{helper func_name=update_account_block module=users_payments}
	{elseif $action eq 'payments_history'}
		<div>{block name='user_payments_history' module='payments' id_user=$user_id page=$page base_url=$base_url}</div>
	{elseif $action eq 'banners'}
		<div>{block name='my_banners' module='banners' page=$page base_url=$base_url}</div>
	{/if}
</div>
<div class="clr"></div>
{/strip}
{include file="footer.tpl"}
