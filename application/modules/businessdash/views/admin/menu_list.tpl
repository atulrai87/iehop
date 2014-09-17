{include file="header.tpl"}
{foreach item=item from=$options}
<div class="settings-block{if $item.gid} with-{$item.gid}{/if}" onclick="javascript: location.href='{$item.link}';">
	<a href="{$item.link}"><h6>{$item.value}</h6></a>
	<div>{$item.tooltip}</div>
</div>
{/foreach}
{include file="footer.tpl"}