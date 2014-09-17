<li><s id="user_delete" class="icon-user icon-big edge hover zoom30" title="{l i='link_blacklist_add' gid='users_lists' type='button'}"><del></del></s></li>
<script>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js file='blacklist.js' module=users_lists return='path'}{literal}",
			function(){
				black_list = new blacklist({
					siteUrl: site_url,
					userId: {/literal}{$blacklist_helper_data.id_user}{literal},
				});
			},
			['black_list'],
			{async: false}
		);
	});
{/literal}</script>
