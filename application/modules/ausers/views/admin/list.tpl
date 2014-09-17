{include file="header.tpl"}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/ausers/edit">{l i='link_add_user' gid='ausers'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="{if $filter eq 'all'}active{/if}{if !$filter_data.all} hide{/if}"><a href="{$site_url}admin/ausers/index/all">{l i='filter_all_users' gid='ausers'} ({$filter_data.all})</a></li>
		<li class="{if $filter eq 'admin'}active{/if}{if !$filter_data.admin} hide{/if}"><a href="{$site_url}admin/ausers/index/admin">{l i='filter_admin_users' gid='ausers'} ({$filter_data.admin})</a></li>
		<li class="{if $filter eq 'moderator'}active{/if}{if !$filter_data.moderator} hide{/if}"><a href="{$site_url}admin/ausers/index/moderator">{l i='filter_moderator_users' gid='ausers'} ({$filter_data.moderator})</a></li>
		<li class="{if $filter eq 'not_active'}active{/if}{if !$filter_data.not_active} hide{/if}"><a href="{$site_url}admin/ausers/index/not_active">{l i='filter_not_active_users' gid='ausers'} ({$filter_data.not_active})</a></li>
		<li class="{if $filter eq 'active'}active{/if}{if !$filter_data.active} hide{/if}"><a href="{$site_url}admin/ausers/index/active">{l i='filter_active_users' gid='ausers'} ({$filter_data.active})</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><a href="{$sort_links.nickname'}"{if $order eq 'nickname'} class="{$order_direction|lower}"{/if}>{l i='field_nickname' gid='ausers'}</a></th>
	<th><a href="{$sort_links.name}"{if $order eq 'name'} class="{$order_direction|lower}"{/if}>{l i='field_name' gid='ausers'}</a></th>
	<th><a href="{$sort_links.email}"{if $order eq 'email'} class="{$order_direction|lower}"{/if}>{l i='field_email' gid='ausers'}</a></th>
	<th class="w150"><a href="{$sort_links.date_created}"{if $order eq 'date_created'} class="{$order_direction|lower}"{/if}>{l i='field_date_created' gid='ausers'}</a></th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$users}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.nickname}</td>
	<td>{$item.name}</td>
	<td>{$item.email}</td>
	<td class="center">{$item.date_created|date_format:$page_data.date_format}</td>
	<td class="icons">
		{if $item.status}
		<a href="{$site_root}admin/ausers/activate/{$item.id}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_deactivate_user' gid='ausers'}" title="{l i='link_deactivate_user' gid='ausers'}"></a>
		{else}
		<a href="{$site_root}admin/ausers/activate/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_user' gid='ausers'}" title="{l i='link_activate_user' gid='ausers'}"></a>
		{/if}
		<a href="{$site_root}admin/ausers/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_user' gid='ausers'}" title="{l i='link_edit_user' gid='ausers'}"></a>
		<a href="{$site_root}admin/ausers/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_user' gid='ausers' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_user' gid='ausers'}" title="{l i='link_delete_user' gid='ausers'}"></a>
	</td>
</tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
