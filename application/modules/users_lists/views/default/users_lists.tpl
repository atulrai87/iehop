{include file="header.tpl" load_type='ui'}
{strip}
<div class="content-block">
	<h1>{seotag tag='header_text'}</h1>

	<div class="edit_block">
		<div class="tabs tab-size-15 noPrint">
			<ul>
				<li{if $method eq 'friendlist'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users_lists' method=friendlist}">{l i=friendlist gid='users_lists'}{if $counts.friendlist}&nbsp;({$counts.friendlist}){/if}</a></li>
				<li{if $method eq 'friends_requests'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users_lists' method=friends_requests}">{l i=friends_requests gid='users_lists'}{if $counts.friends_requests}&nbsp;({$counts.friends_requests}){/if}</a></li>
				<li{if $method eq 'friends_invites'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users_lists' method=friends_invites}">{l i=friends_invites gid='users_lists'}{if $counts.friends_invites}&nbsp;({$counts.friends_invites}){/if}</a></li>
				<li{if $method eq 'blacklist'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users_lists' method=blacklist}">{l i=blacklist gid='users_lists'}{if $counts.blacklist}&nbsp;({$counts.blacklist}){/if}</a></li>
			</ul>
		</div>
		<div class="view-user">
			<div class="sorter short-line" id="sorter_block">
				<div class="search-line fleft">
					<form method="POST" action="">
						<input type="text" placeholder="{l i='search' gid='users_lists'}" name="search" value="{$search}" />&nbsp;
						<input type="submit" value="{l i='btn_search' gid='start'}" />
					</form>
				</div>
				{if $list}
					<div class="fright">{pagination data=$page_data type='cute'}</div>
				{/if}
			</div>
			
			{*<h2>{l i='header_users_search_results' gid='users'} - {$page_data.total_rows} {l i='header_users_found' gid='users'}</h2>*}

			<div class="user-gallery big w-actions">
				{foreach item=item from=$list}
					<div class="item">
						<div class="user">
							<div class="photo">
								<div class="actions">
									<div class="text fright">
										<span>{$item.date_update|date_format:$page_data.date_time_format}</span>
									</div>
									<div class="btns black">
										{if $method == 'friends_requests'}
											<a href="{seolink module='users_lists' method=accept}{$item.user.id}" class="btn-link link-r-margin" title="{l i='action_accept' gid='users_lists'}"><i class="icon-ok icon-big edge hover w"></i></a>
											<a href="{seolink module='users_lists' method=decline}{$item.user.id}" class="btn-link link-r-margin" title="{l i='action_decline' gid='users_lists'}"><i class="icon-remove icon-big edge hover w"></i></a>
										{/if}
										{if $method == 'friendlist' || $method == 'friends_requests'}
											<a href="{seolink module='users_lists' method=block}{$item.user.id}" class="btn-link link-r-margin" title="{l i='action_block' gid='users_lists'}"><i class="icon-user icon-big edge hover w zoom20"><i class="icon-mini-stack icon-lock"></i></i></a>
										{/if}
										{if $method != 'friends_requests'}
											<a href="{seolink module='users_lists' method=remove}{$item.user.id}" class="btn-link link-r-margin" title="{l i='action_remove' gid='users_lists'}"><i class="icon-trash icon-big edge hover w zoom30"></i></a>
										{/if}
									</div>
								</div>
								<a href="{seolink module='users' method=view data=$item.user}"><img alt="" src="{$item.user.media.user_logo.thumbs.great}" /></a>
								<div class="info">
									<div class="text-overflow"><a href="{seolink module='users' method='view' data=$item.user}">{$item.user.output_name}</a>, {$item.user.age}</div>
									{if $item.user.location}<div class="text-overflow">{$item.user.location}</div>{/if}
									{if ($method == 'friends_requests' || $method == 'friends_invites') && $item.comment}<div class="oh comment" title="{$item.comment|escape:html}">{$item.comment|nl2br}</div>{/if}
								</div>
							</div>
						</div>
					</div>
				{foreachelse}
					<div class="item empty">{l i='empty_result' gid='users_lists'}</div>
				{/foreach}
			</div>
			{if $list}<div id="pages_block_2">{pagination data=$page_data type='full'}</div>{/if}
		</div>
	</div>

</div>
<div class="clr"></div>
{/strip}

<script>{literal}
	$('.user-gallery').not('.w-descr').find('.photo')
		.off('mouseenter').on('mouseenter', function(){
			$(this).find('.info').stop().slideDown(100);
		}).off('mouseleave').on('mouseleave', function(){
			$(this).find('.info').stop(true).delay(100).slideUp(100);
		});
</script>{/literal}

{include file="footer.tpl"}
