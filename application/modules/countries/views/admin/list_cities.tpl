{include file="header.tpl"}
{helper func_name=get_admin_level1_menu helper_name=menu func_param='admin_countries_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/countries/city_edit/{$country.code}/{$region.id}">{l i='link_add_city' gid='countries'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="filter-form">
<form method="post">
	<h3>{$country.name}: {$region.name}</h3>
	{l i="search_city" gid='countries'}: <input type="text" name="search" value="{$search}">
	<input type="submit" name="btn_save" value="{l i='btn_send' gid='start' type='button'}">
</form>
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_city_name' gid='countries'}</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach item=item from=$installed}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.name}</td>
	<td class="icons">
		<a href="{$site_url}admin/countries/city_edit/{$country.code}/{$region.id}/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_city' gid='countries'}" title="{l i='link_edit_city' gid='countries'}"></a>
		<a href="{$site_url}admin/countries/city_delete/{$country.code}/{$region.id}/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_city' gid='countries' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_city' gid='countries'}" title="{l i='link_delete_city' gid='countries'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="3" class="center">{l i='no_cities' gid='countries'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
