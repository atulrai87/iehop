<span id="users_lists_links_{$id_dest_user}">
{foreach from=$buttons item=btn key=btn_name}
	<a href="{seolink module='users_lists' method=$btn.method}{$id_dest_user}" data-pjax="0" method="{$btn.method}" onclick="event.preventDefault();" class="link-r-margin" title="{l i='action_'+$btn_name gid='users_lists'}"><i class="icon-{$btn.icon} icon-big edge hover zoom30">{if $btn.icon_stack}<i class="icon-mini-stack icon-{$btn.icon_stack}"></i>{/if}</i></a>
{/foreach}
</span>

<script>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js module=users_lists file='lists_links.js' return='path'}{literal}", 
			function(){
				var id_dest_user = parseInt('{/literal}{$id_dest_user}{literal}');
				lists_links = new ListsLinks({
					siteUrl: site_url,
					id_dest_user: id_dest_user,
					url: 'users_lists/'
				});
			},
			'lists_links',
			{async: false}
		);
	});
</script>{/literal}