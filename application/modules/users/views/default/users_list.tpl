{include file="header.tpl"}
<h1>{seotag tag="header_text"}</h1>

<div class="pos-rel">
	{start_search_form type='advanced' show_data='1' object='user'}
</div>
<div class="content-block">
	<div id="main_users_results">
		{$block}
	</div>

	<script type="text/javascript">{literal}
		$(function(){
			loadScripts("{/literal}{js module=users file='users-list.js' return='path'}{literal}",
				function(){
					users_list = new usersList({
						siteUrl: '{/literal}{$site_url}{literal}',
						viewUrl: 'users/search',
						viewAjaxUrl: 'users/ajax_search',
						listBlockId: 'main_users_results',
						tIds: ['pages_block_1', 'pages_block_2', 'sorter_block']
					});
				},
				'users_list'
			);
		});
	{/literal}</script>		
</div>

{include file="footer.tpl"}