<h1>{l i='header_last_registered' gid='users'}</h1>
<div class="last-reg-block">
	{foreach item=item from=$users}
	<div class="small-user-block">
		{if $item.media.user_logo.thumbs.small}<div class="logo-img"><a href="{seolink module='users' method='view' data=$item}"><img src="{$item.media.user_logo.thumbs.small}" alt="{$item.nickname|escape}" title="{$item.nickname|escape}" /></a></div><div class="clr"></div>{/if}
	</div>
	{/foreach}
	<div class="clr"></div>
	<div><a href="{seolink module='users' method='listing' data='all'}">{l i='view_all' gid='start'}</a></div>
</div>