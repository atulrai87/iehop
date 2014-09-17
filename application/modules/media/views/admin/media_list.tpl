{include file="header.tpl"}
{helper func_name=get_admin_level1_menu helper_name=menu func_param='media_menu_item'}
{strip}
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w110">{l i='field_files' gid='media'}</th>
		<th>{l i='media_info' gid='media'}</th>
		<th class="w100">&nbsp;</th>
	</tr>
	{foreach item=item from=$media}
	{counter print=false assign=counter}
	<tr{if $counter is div by 2} class="zebra"{/if}>
		<td class="first center">
			{if $item.media}
				<a href="{$item.media.mediafile.file_url}" target="_blank"><img src="{$item.media.mediafile.thumbs.small}"/></a>
			{/if}
			{if $item.video_content}
				<span onclick="vpreview = new loadingContent({literal}{'closeBtnClass': 'w'}{/literal}); vpreview.show_load_block('{$item.video_content.embed|escape}');"><img class="pointer" src="{$item.video_content.thumbs.small}"/></span>
				{*<img src="{$item.video_content.thumbs.small}"/>*}
			{/if}
		</td>
		<td>
			<b>{l i='media_owner' gid='media'}</b>: {if $item.owner_info.nickname}{$item.owner_info.nickname}{else}<font class="error">{l i='success_delete_user' gid='users'}</font>{/if}<br>
			<b>{l i='media_user' gid='media'}</b>: {if $item.user_info.nickname}{$item.user_info.nickname}{else}<font class="error">{l i='success_delete_user' gid='users'}</font>{/if}<br>
			<b>{l i='field_permitted_for' gid='media'}</b>: {ld_option i='permissions' gid='media' option=$item.permissions}
		</td>
		<td class="icons">
			<a href="{$site_url}admin/media/delete_media/{$item.id}"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_service' gid='packages'}" title="{l i='link_delete_service' gid='packages'}"></a>
		</td>
	</tr>
	{foreachelse}
	<tr><td colspan="4" class="center">{l i='no_media' gid='media'}</td></tr>
	{/foreach}
</table>
{/strip}
{include file="pagination.tpl"}

{include file="footer.tpl"}
