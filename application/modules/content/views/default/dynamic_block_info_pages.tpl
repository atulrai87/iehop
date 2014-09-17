{strip}
<div{if !$dynamic_block_info_pages_data.params.transparent} class="bg-html_bg"{/if}>
	{if $dynamic_block_info_pages_data.section.title}<h1 title="{$dynamic_block_info_pages_data.section.title|escape}">{$dynamic_block_info_pages_data.section.title}</h1>{/if}
	{if $dynamic_block_info_pages_data.section}
		<div>
			{$dynamic_block_info_pages_data.section.content}
			<div class="ptb10"><button onclick="locationHref('{seolink module='content' method='view' data=$dynamic_block_info_pages_data.section}');">{l i='btn_view_more' gid='start'}</button></div>
		</div>
	{/if}
	{if $dynamic_block_info_pages_data.pages}
		<div class="dynamic-subsections" data-count="{$dynamic_block_info_pages_data.pages|@count}">
			{foreach item=item key=key from=$dynamic_block_info_pages_data.pages}
				<div class="box-sizing">
					<h2 class="text-overflow" title="{$item.title|escape}">{if $item.title}{$item.title}{else}&nbsp;{/if}</h2>
					<div>{if $dynamic_block_info_pages_data.params.trim_subsections_text}{$item.short_content}{else}{$item.content}{/if}</div>
					<div class="ptb10"><button class="inline-btn" onclick="locationHref('{seolink module='content' method='view' data=$item}');">{l i='btn_view_more' gid='start'}</button></div>
				</div>
			{/foreach}
		</div>
	{/if}
</div>
{/strip}