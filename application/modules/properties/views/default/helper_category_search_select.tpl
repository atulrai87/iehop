<div id="category_select_{$category_helper_data.rand}" class="controller-select">
	<a href="#" id="category_open_{$category_helper_data.rand}">
	{l i='link_select_categories' gid='properties'}</a>{if $category_helper_data.output eq 'max'} <i>(max. {$category_helper_data.max}){/if}
	</i>

	(<span id="category_text_{$category_helper_data.rand}">0</span> selected)

</div>

<script type='text/javascript'>
{if $category_helper_data.var_js_name}var {$category_helper_data.var_js_name};{/if}
{literal}
$(function(){
	loadScripts(
		"{/literal}{js module=properties file='job-category-search-select.js' return='path'}{literal}", 
		function(){
			{/literal}{if $category_helper_data.var_js_name}{$category_helper_data.var_js_name} = {/if}{literal}new jobCategorySearchSelect({
				siteUrl: '{/literal}{$site_url}{literal}',
				rand: '{/literal}{$category_helper_data.rand}{literal}',
				hidden_name: '{/literal}{$category_helper_data.var}{literal}',
				categories: {/literal}{$category_helper_data.selected_json}{literal},
				raw_data: {/literal}{$category_helper_data.raw_data_json}{literal}
			});
		},
		'{/literal}{$category_helper_data.var_js_name}{literal}'
	);
});
{/literal}</script>