{strip}
<div class="view small">
	<div class="image">
		<div id="user_photo" class="pos-rel dimp100 pointer">
			{if $data.user_logo_moderation}
				<img src="{$data.media.user_logo_moderation.thumbs.middle}" alt="{$data.nickname|escape}" />
			{else}
				<img src="{$data.media.user_logo.thumbs.middle}" alt="{$data.nickname|escape}" />
			{/if}
		</div>
	</div>
	<div class="info">
		<div class="body">
			<h1>
				<span style="font-size:30px;line-height:28px;">{$data.output_name}</span>
				<span data-role="online_status" class="fright online-status"><s class="{$data.statuses.online_status_text}">{$data.statuses.online_status_lang}</s></span>
			</h1>
			<div>
				<div class="fright">{l i='views' gid='users'}: {$data.views_count}</div>
				{l i='field_age' gid='users'}: {$data.age}{if $data.location}<i class="delim-alone"></i><span class="">{$data.location}</span>{/if}
			</div>
		</div>
		<div class="actions noPrint">
			<a class="link-r-margin" title="{l i='edit_my_profile' gid='start'}" href="{seolink module='users' method='profile'}personal"><i class="icon-pencil icon-big edge hover"></i></a>
			{if $data.services_status.highlight_in_search.status}
				<span class="link-r-margin" title="{$data.services_status.highlight_in_search.name|escape}" onclick="highlight_in_search_available_view.check_available();"><i class="icon-sun icon-big edge hover zoom20"></i></span>
			{/if}
			{if $data.services_status.up_in_search.status}
				<span class="link-r-margin" title="{$data.services_status.up_in_search.name|escape}" onclick="up_in_search_available_view.check_available();"><i class="icon-level-up icon-big edge hover zoom20"></i></span>
			{/if}
			{if $data.services_status.hide_on_site.status}
				<span class="link-r-margin" title="{$data.services_status.hide_on_site.name|escape}" onclick="hide_on_site_available_view.check_available();"><i class="icon-eye-close icon-big edge hover zoom20"></i></span>
			{/if}
			<script type='text/javascript'>{literal}
				$(function(){
					loadScripts(
						["{/literal}{js file='available_view.js' return='path'}{literal}", "{/literal}{js file='users-avatar.js' module='users' return='path'}{literal}"],
						function(){
							highlight_in_search_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: 'users/ajax_available_highlight_in_search/',
								buyAbilityAjaxUrl: 'users/ajax_activate_highlight_in_search/',
								buyAbilityFormId: 'ability_form',
								buyAbilitySubmitId: 'ability_form_submit',
								success_request: function(message) {error_object.show_error_block(message, 'success'); locationHref('');},
								fail_request: function(message) {error_object.show_error_block(message, 'error');},
							});
							up_in_search_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: 'users/ajax_available_up_in_search/',
								buyAbilityAjaxUrl: 'users/ajax_activate_up_in_search/',
								buyAbilityFormId: 'ability_form',
								buyAbilitySubmitId: 'ability_form_submit',
								success_request: function(message) {error_object.show_error_block(message, 'success'); locationHref('');},
								fail_request: function(message) {error_object.show_error_block(message, 'error');},
							});
							hide_on_site_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: 'users/ajax_available_hide_on_site/',
								buyAbilityAjaxUrl: 'users/ajax_activate_hide_on_site/',
								buyAbilityFormId: 'ability_form',
								buyAbilitySubmitId: 'ability_form_submit',
								success_request: function(message) {error_object.show_error_block(message, 'success'); locationHref('');},
								fail_request: function(message) {error_object.show_error_block(message, 'error');},
							});

							user_avatar = new avatar({site_url: site_url});
						},
						['highlight_in_search_available_view', 'up_in_search_available_view', 'hide_on_site_available_view', 'user_avatar'],
						{async: false}
					);
				});
			</script>{/literal}
		</div>
	</div>
</div>

{if $data.approved && $data.confirm && !$data.activity && $data.available_activation.status}
	<div class="bg-highlight_bg mtb10 p10">
		<script type='text/javascript'>{literal}
			$(function(){
				loadScripts(
					"{/literal}{js file='available_view.js' return='path'}{literal}", 
					function(){
						activate_available_view = new available_view({
							siteUrl: '{/literal}{$site_url}{literal}',
							checkAvailableAjaxUrl: 'users/ajax_available_user_activate_in_search/',
							buyAbilityAjaxUrl: 'users/ajax_activate_user_activate_in_search/',
							buyAbilityFormId: 'ability_form',
							buyAbilitySubmitId: 'ability_form_submit',
							success_request: function(message) {error_object.show_error_block(message, 'success'); locationHref('{/literal}{seolink module='users' method='profile'}{literal}')},
							fail_request: function(message) {error_object.show_error_block(message, 'error');},
						});
					},
					'activate_available_view',
					{async: false}
				);
			});
		</script>{/literal}
		<div>
			<input type="button" class="inline-btn" onclick="activate_available_view.check_available();" value="{l i='link_activate_profile' gid='users'}" />
			<span class="ml10">{l i='text_activate_profile' gid='users'}</span>
		</div>
	</div>
{/if}

<div class="edit_block" id="profile_tab_sections">
	{include file="profile_menu.tpl" module="users"}
	<div class="view-user">
		{if !$action || $action eq 'view'}
			{include file="view_my_profile.tpl" module="users"}
		{elseif $action eq 'wall'}
			{block name=wall_block module=wall_events place='myprofile' id_wall=$user_id}
		{elseif $action eq 'gallery'}
			{block name=media_block module=media param=$subsection page='1' location_base_url=$location_base_url}
		{/if}
	</div>
</div>
{/strip}