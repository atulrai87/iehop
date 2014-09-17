<div id="category_select_{$category_helper_data.rand}" class="controller-select">

	{if $category_helper_data.max eq '1'}
	<span id="category_text_{$category_helper_data.rand}">{$category_helper_data.selected_data}</span>
	<a href="#" id="category_open_{$category_helper_data.rand}">{l i='link_select_category' gid='properties'}</a>
	{else}
	<a href="#" id="category_open_{$category_helper_data.rand}">{l i='link_select_categories' gid='properties'}</a>
	<i>(<span id="category_text_{$category_helper_data.rand}">{$category_helper_data.selected_data}</span> from {$category_helper_data.max} selected)</i>
	{/if}

	{foreach item=item key=key from=$category_helper_data.selected_all}
	<input type="hidden" name="{$category_helper_data.var}{if $category_helper_data.max > 1}[]{/if}" value="{$key}" id="sel_{$category_helper_data.rand}_{$key}" >
	{/foreach}

</div>

<script type='text/javascript'>
{if $category_helper_data.var_js_name}var {$category_helper_data.var_js_name};{/if}
{literal}
$(function(){
	loadScripts(
		"{/literal}{js module=properties file='job-category-select.js' return='path'}{literal}",
		function(){
			{/literal}{if $category_helper_data.var_js_name}{$category_helper_data.var_js_name} = {/if}{literal}new jobCategorySelect({
				siteUrl: '{/literal}{$site_url}{literal}',
				rand: '{/literal}{$category_helper_data.rand}{literal}',
				hidden_name: '{/literal}{$category_helper_data.var}{literal}',
				categories: {/literal}{$category_helper_data.selected_all_json}{literal},
				raw_data: {/literal}{$category_helper_data.raw_data_json}{literal},
				max: {/literal}{$category_helper_data.max}{literal},
				level: {/literal}{$category_helper_data.level}{literal},
				output: '{/literal}{$category_helper_data.output}{literal}'
			});
		},
		'{/literal}{$category_helper_data.var_js_name}{literal}'
	);
});
{/literal}</script>