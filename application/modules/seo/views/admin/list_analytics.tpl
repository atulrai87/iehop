{include file="header.tpl"}
{helper func_name=get_admin_level1_menu helper_name=menu func_param='admin_seo_menu'}
<div class="actions">&nbsp;</div>

<div class="filter-form">
<form method="post">
http://<input type="text" name="url" value="{$url}">
<input type="submit" name="btn_save" value="{l i='btn_send' gid='start' type='button'}">
</form>
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first" colspan="2">{l i='analytics_h_basic' gid='seo'}</th>
	<th colspan="2">{l i='analytics_h_alexa' gid='seo'}</th>
	<th colspan="2">{l i='analytics_h_backlinks' gid='seo'}</th>
</tr>
<tr class="zebra">
	<td>{l i='field_domain_age' gid='seo'}</td>
	<td class="center">{if $domain.registered}{$domain.age.y} {l i='da_years' gid='seo'} {$domain.age.m} {l i='da_months' gid='seo'} {$domain.age.d} {l i='da_days' gid='seo'}{else}{l i='domain_not_registered' gid='seo'}{/if}</td>
	<td>{l i='field_backlinks' gid='seo'}:</td>
	<td class="center"><a href="{$check_links.alexa_backlinks}" target="_blank">{$domain.alexa_backlinks}</a></td>
	<td>{l i='field_google' gid='seo'}</td>
	<td class="center"><a href="{$check_links.google_backlinks}" target="_blank">{$domain.google_backlinks}</a></td>
</tr>
<tr>
	<td>{l i='field_page_rank' gid='seo'}</td>
	<td class="center">{$domain.page_rank}</td>
	<td>{l i='field_traffic_rank' gid='seo'}:</td>
	<td class="center"><a href="{$check_links.alexa_rank}" target="_blank">{$domain.alexa_rank}</a></td>
	<td>{l i='field_yahoo' gid='seo'}</td>
	<td class="center"><a href="{$check_links.yahoo_backlinks}" target="_blank">{$domain.yahoo_backlinks}</a></td>
</tr>
<tr>
	<th class="first" colspan="2">{l i='analytics_h_tech' gid='seo'}</th>
	<th colspan="2">{l i='analytics_h_directory' gid='seo'}</th>
	<th colspan="2">{l i='analytics_h_indexed' gid='seo'}</th>
</tr>
<tr class="zebra">
	<td>{l i='field_rank' gid='seo'}</td>
	<td class="center"><a href="{$check_links.technorati_rank}" target="_blank">{$domain.technorati_rank}</a></td>
	<td>{l i='field_dmoz' gid='seo'}:</td>
	<td class="center"><a href="{$check_links.dmoz_listed}" target="_blank">{if $domain.dmoz_listed}{l i='field_listed' gid='seo'}{else}{l i='field_not_listed' gid='seo'}{/if}</a></td>
	<td>{l i='field_google' gid='seo'}</td>
	<td class="center"><a href="{$check_links.google_indexed}" target="_blank">{$domain.google_indexed}</a></td>
</tr>
<tr>
	<td>{l i='field_authority' gid='seo'}</td>
	<td class="center"><a href="{$check_links.technoraty_authority}" target="_blank">{$domain.technoraty_authority}</a></td>
	<td>{l i='field_google' gid='seo'}:</td>
	<td class="center"><a href="{$check_links.google_listed}" target="_blank">{if $domain.google_listed}{l i='field_listed' gid='seo'}{else}{l i='field_not_listed' gid='seo'}{/if}</a></td>
	<td>{l i='field_yahoo' gid='seo'}</td>
	<td class="center"><a href="{$check_links.yahoo_indexed}" target="_blank">{$domain.yahoo_indexed}</a></td>
</tr>
</table>
{include file="footer.tpl"}