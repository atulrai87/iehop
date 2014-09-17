{strip}
<span class="img-menu">
	<span>
		<i class="icon-cog icon-2x"></i>
		<dl id="settings_menu">
			{foreach key=key item=item from=$menu}
				<dt class="righted{if $item.active || $item.in_chain} active{/if}"><a href="{$item.link}">{$item.value}</a></dt>
			{/foreach}
		</dl>
	</span>
</span>
{/strip}