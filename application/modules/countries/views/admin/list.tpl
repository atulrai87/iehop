{include file="header.tpl" load_type='ui'}
{helper func_name=get_admin_level1_menu helper_name=menu func_param='admin_countries_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/countries/country_edit">{l i='link_add_country' gid='countries'}</a></div></li>
		{if $sort_mode}
		<li><div class="l"><a href="{$site_url}admin/countries/index/0">{l i='link_view_mode' gid='countries'}</a></div></li>
		{else}
		<li><div class="l"><a href="{$site_url}admin/countries/index/1">{l i='link_sorting_mode' gid='countries'}</a></div></li>
		{/if}
	</ul>
	&nbsp;
</div>

{if $sort_mode}
<div id="menu_items">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
	{foreach item=item from=$installed}
	<li id="item_{$item.code}">{$item.name}</li>
	{/foreach}
	</ul>
</div>

<script >{literal}
	var mlSorter;
	$(function(){
		mlSorter = new multilevelSorter({
			siteUrl: '{/literal}{$site_url}{literal}', 
			onActionUpdate: true,
			urlSaveSort: 'admin/countries/ajax_save_country_sorter'
		});
	});
{/literal}</script>

{else}
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w50">{l i='field_country_code' gid='countries'}</th>
	<th>{l i='field_country_name' gid='countries'}</th>
	<th class="w100">&nbsp;</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach item=item from=$installed}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.code}</td>
	<td>{$item.name}</td>
	<td class="center"><a href="{$site_url}admin/countries/country/{$item.code}">{l i='view_regions_link' gid='countries'}</a></td>
	<td class="icons">
		<a href="{$site_url}admin/countries/country_edit/{$item.code}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_country' gid='countries'}" title="{l i='link_edit_country' gid='countries'}"></a>
		<a href="{$site_url}admin/countries/country_delete/{$item.code}" onclick="javascript: if(!confirm('{l i='note_delete_country' gid='countries' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_country' gid='countries'}" title="{l i='link_delete_country' gid='countries'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_countries' gid='countries'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{/if}
{include file="footer.tpl"}
