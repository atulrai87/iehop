{strip}
{switch from=$dynamic_block_users_view}
	{case value='big_thumbs'}{assign var=block_class value='big'}{assign var=thumb_name value='great'}
	{case value='medium_thumbs'}{assign var=block_class value='medium'}{assign var=thumb_name value='big'}
	{case value='small_thumbs'}{assign var=block_class value='small'}{assign var=thumb_name value='middle'}
	{case value='small_thumbs_w_descr'}{assign var=block_class value='small w-descr'}{assign var=thumb_name value='middle'}
	{case value='carousel'}{assign var=block_class value='small'}{assign var=thumb_name value='middle'}
	{case value='carousel_w_descr'}{assign var=block_class value='small w-descr'}{assign var=thumb_name value='middle'}
	{case}{assign var=block_class value='medium'}{assign var=thumb_name value='big'}
{/switch}
<h1 class="text-overflow" title="{$dynamic_block_users_title|escape}">{$dynamic_block_users_title}</h1>
{if $dynamic_block_users_view == 'carousel' || $dynamic_block_users_view == 'carousel_w_descr'}
	{block name=users_carousel module=users users=$dynamic_block_users scroll=auto class=$block_class thumb_name=$thumb_name}
{else}
	<div class="user-gallery {$block_class}">
		{foreach item=item from=$dynamic_block_users}
			<div class="item">
				<div class="user">
					<div class="photo">
						<a href="{seolink module='users' method='view' data=$item}"><img alt="" src="{$item.media.user_logo.thumbs[$thumb_name]}" /></a>
						<div class="info">
							<div class="text-overflow"><a href="{seolink module='users' method='view' data=$item}" title="{$item.output_name|escape}">{$item.output_name}</a>, {$item.age}</div>
							{if $item.location}<div class="text-overflow" title="{$item.location|escape}">{$item.location}</div>{/if}
						</div>
					</div>
				</div>
				<div class="descr hide">
					<div><a href="{seolink module='users' method='view' data=$item}">{$item.output_name}</a>, {$item.age}</div>
					{if $item.location}<div>{$item.location}</div>{/if}
				</div>
			</div>
		{foreachelse}
			<div class="item empty">{l i='empty_search_results' gid='users'}</div>
		{/foreach}
	</div>
{/if}
{/strip}

<script>{literal}
	$('.user-gallery').not('.w-descr')
		.off('mouseenter', '.photo').on('mouseenter', '.photo', function(){
			$(this).find('.info').stop().slideDown(100);
		}).off('mouseleave', '.photo').on('mouseleave', '.photo', function(){
			$(this).find('.info').stop(true).delay(100).slideUp(100);
		});
</script>{/literal}