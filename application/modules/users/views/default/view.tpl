{include file="header.tpl"}
{strip}
<div class="content-block">
	<div class="view small">
		<div class="image">
			<div id="user_photo" class="pos-rel dimp100{if $data.user_logo} pointer{/if}">
				<img src="{$data.media.user_logo.thumbs.middle}" alt="{$data.output_name|escape}" />
			</div>
		</div>
		<div class="info">
			<div class="body">
				<h1>
				<span style="font-size:30px;line-height:28px;">{$data.output_name}</span>
				<span data-role="online_status" class="fright online-status"><s class="{$data.statuses.online_status_text}">{$data.statuses.online_status_lang}</s></span></h1>
				<div>
					<div class="fright">{l i='views' gid='users'}: {$data.views_count}</div>
					{l i='field_age' gid='users'}: {$data.age}{if $data.location}<i class="delim-alone"></i><span class="">{$data.location}</span>{/if}
				</div>
			</div>
			<div class="actions noPrint">
				{block name=send_message_button module=mailbox id_user=$data.id}
				{helper func_name=lists_links module=users_lists func_param=$data.id}
				{helper func_name=im_chat_add_button module=im func_param=$data.id}
				{block name='mark_as_spam_block' module='spam' object_id=$data.id type_gid='users_object' template='button'}
			</div>
		</div>
	</div>

	<div class="edit_block" id="profile_tab_sections">
		{include file="view_profile_menu.tpl" module="users"}
		<div class="view-user">
			{if !$profile_section || $profile_section eq 'profile'}
				{include file="view_users_profile.tpl" module="users"}
			{elseif $profile_section eq 'wall'}
				{block name=wall_block module=wall_events place='viewprofile' id_wall=$user_id}
			{elseif $profile_section eq 'gallery'}
				{block name=media_block module=media param=$subsection page='1' user_id=$user_id location_base_url=$location_base_url}
			{/if}
		</div>
	</div>
</div>
<div class="clr"></div>
{/strip}
{if $data.user_logo}
	<script type='text/javascript'>{literal}
		$(function(){
			loadScripts(
				["{/literal}{js file='users-avatar.js' module='users' return='path'}{literal}"],
				function(){
					user_avatar = new avatar({
						site_url: site_url,
						id_user: {/literal}{$user_id}{literal}
					});
				},
				['user_avatar'],
				{async: true}
			);
		});
	</script>{/literal}
{/if}
{include file="footer.tpl"}
