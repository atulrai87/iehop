{include file="header.tpl"}
<div class="content-block">
	<h1>
		{seotag tag='header_text'}
		<div class="fright">
			<a target="_blank" class="icon-rss icon-big edge hover zoom20" href="{$site_url}news/rss"></a>
		</div>
	</h1>

	{foreach item=item from=$news}
		<div class="news">
			<h3><a href="{seolink module='news' method='view' data=$item}">{$item.name}</a></h3>
			{if $item.img}
			<img src="{$item.media.img.thumbs.small}" align="left" />
			{/if}
			<span class="date">{$item.date_add|date_format:$page_data.date_format}</span><br>
			<span class="annotation">{$item.annotation}</span><br>
			<div class="links">
				{if $item.feed}{l i='feed_source' gid='news'}: <a href="{$item.feed.site_link}">{$item.feed.title}</a><br>{/if}
				<a href="{seolink module='news' method='view' data=$item}">{l i='link_view_more' gid='news'}</a>
			</div>
			<div>
				{block name=comments_form module=comments gid=news id_obj=$item.id hidden=1 count=$item.comments_count}
			</div>
			<div class="clr"></div>
		</div>
	{foreachelse}
		<div class="empty">{l i="no_news_yet_header" gid='news'}</div>
	{/foreach}
	<div class="clr"></div>
	{if $news}<div class="line top">{pagination data=$page_data type='full'}</div>{/if}
</div>
<div class="clr"></div>
{include file="footer.tpl"}
