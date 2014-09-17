{strip}
<div class="like_users">
	<div class="lh0{if $like_users|@count > 14} w-scroll{/if}">
		{foreach item=user from=$like_users}
			<a href="{$user.link}" title="{$user.output_name|escape}"><img src="{$user.media.user_logo.thumbs.small}" alt="{$user.output_name|escape}"></a>
		{/foreach}
	</div>
	{if $has_more}
		<ul class="centered">
			<li class="a like_more_btn">{l i='btn_view_more' gid='start'}</li>
		</ul>
	{/if}
</div>
{/strip}