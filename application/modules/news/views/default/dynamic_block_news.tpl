{strip}
{if $dynamic_block_news_data.news_count}
	<div{if !$dynamic_block_news_data.params.transparent} class="bg-html_bg"{/if}>
		<h1 class="text-overflow p0">{l i='header_news' gid='news'}</h1>
		<div class="dynamic-subsections" data-count="{$dynamic_block_news_data.news_count}">
			{foreach from=$dynamic_block_news_data.news item=item}
				<div class="box-sizing">
					<h2 class="text-overflow" title="{$item.name|escape}">{if $item.name}{$item.name}{else}&nbsp;{/if}</h2>
					<div>
						{if $item.img}<img class="fleft mr5 mb5" src="{$item.media.img.thumbs.small}" />{/if}
						{$item.annotation}
					</div>
					<div class="ptb10"><button class="inline-btn" onclick="locationHref('{seolink module='news' method='view' data=$item}');">{l i='btn_view_more' gid='start'}</button></div>
				</div>
			{/foreach}
		</div>
	</div>
{/if}
{/strip}