{strip}
	{if $users}
		<div class="sorter short-line" id="sorter_block">
			{sorter links=$sort_data.links order=$sort_data.order direction=$sort_data.direction url=$sort_data.url}
			<span class="h2">{if $search_type == 'perfect_match'}{l i='header_perfect_match_results' gid='users'}{else}{l i='header_users_search_results' gid='users'}{/if} - {$page_data.total_rows} {l i='header_users_found' gid='users'}</span>
			<div class="fright">{pagination data=$page_data type='cute'}</div>
		</div>
	{else}
		<h2>{if $search_type == 'perfect_match'}{l i='header_perfect_match_results' gid='users'}{else}{l i='header_users_search_results' gid='users'}{/if} - {$page_data.total_rows} {l i='header_users_found' gid='users'}</h2>
	{/if}

	{if $hl_data.service_highlight.status}
		<div id="hl_service_container" class="highlight mtb10 ptb10">
			<input type="button" class="ml10 inline-btn" value="{$hl_data.service_highlight.name}" onclick="highlight_in_search_available_view.check_available();" />
			<span class="ml10">{$hl_data.service_highlight.description}</span>
			<script>{literal}
				$(function(){
					loadScripts(
						"{/literal}{js file='available_view.js' return='path'}{literal}", 
						function(){
							highlight_in_search_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: 'users/ajax_available_highlight_in_search/',
								buyAbilityAjaxUrl: 'users/ajax_activate_highlight_in_search/',
								buyAbilityFormId: 'ability_form',
								buyAbilitySubmitId: 'ability_form_submit',
								formType: 'list',
								success_request: function(message) {error_object.show_error_block(message, 'success'); $('#hl_service_container').remove();},
								fail_request: function(message) {error_object.show_error_block(message, 'error');},
							});
						},
						['highlight_in_search_available_view'],
						{async: false}
					);
				});
			</script>{/literal}
		</div>
	{/if}
		
	<div{if $page_data.view_type == 'gallery'} class="user-gallery big"{/if}>
		{foreach item=item from=$users}
			{if $page_data.view_type == 'gallery'}
				<div id="item-block-{$item.id}" class="item{if $item.is_highlight_in_search || $item.leader_bid || ($item.is_up_in_search && $page_data.use_leader)} highlight{/if}">
					<div class="user">
						<div class="photo">
							<a href="{seolink module='users' method='view' data=$item}"><img alt="" src="{$item.media.user_logo.thumbs.great}" /></a>
							<div class="info">
								<div class="text-overflow"><a href="{seolink module='users' method='view' data=$item}">{$item.output_name}</a>, {$item.age}</div>
								{if $item.location}<div class="text-overflow">{$item.location}</div>{/if}
							</div>
						</div>
					</div>
				</div>
			{else}
				<div id="item-block-{$item.id}" class="item user{if $item.is_highlight_in_search || $item.leader_bid || ($item.is_up_in_search && $page_data.use_leader)} highlight{/if}">

					{if $item.is_up_in_search && $page_data.use_leader}<div class="lift_up">{l i='header_up_in_search' gid='users'}</div>{/if}
					{if $item.leader_bid}<div class="lift_up">{l i='header_leader' gid='users'}</div>{/if}
					<h3><a href="{seolink module='users' method='view' data=$item}">{$item.nickname}</a>&nbsp;</h3>
					<div class="image">
						<a href="{seolink module='users' method='view' data=$item}"><img src="{$item.media.user_logo.thumbs.middle}" title="{$item.output_name}"></a>
					</div>
					<div class="body">
						{l i='no_information' gid='start' assign='no_info_str'}
						<h3>{$item.output_name}</h3>
						<div class="t-1">
							<span>{l i='field_age' gid='users'}:</span> {$item.age}
						</div>
						<div class="t-2">
							<span>{l i='field_date_created' gid='users'}:</span> {$item.date_created|date_format:$page_data.date_format}
						</div>
						<div class="t-2">
							{$item.user_type_str}.&nbsp;<span>{l i='field_looking_user_type' gid='users'}:</span>&nbsp;{$item.looking_user_type_str}
						</div>
						{if $item.location}
							<div class="t-2">
								<span>{l i='field_location' gid='users'}:</span> {$item.location}
							</div>
						{/if}
					</div>
					<div class="clr"></div>
					<div class="actions">
						<a href="{seolink module='users' method='view' data=$item}">{l i='header_view_profile' gid='users'}</a>
					</div>
				</div>
			{/if}
		{foreachelse}
			<div class="item empty">{l i='empty_search_results' gid='users'}</div>
		{/foreach}
	</div>
	{if $users}<div id="pages_block_2">{pagination data=$page_data type='full'}</div>{/if}
{/strip}

<script>{literal}
	$('.user-gallery').not('.w-descr').find('.photo')
		.off('mouseenter').on('mouseenter', function(){
			$(this).find('.info').stop().slideDown(100);
		}).off('mouseleave').on('mouseleave', function(){
			$(this).find('.info').stop(true).delay(100).slideUp(100);
		});
</script>{/literal}
