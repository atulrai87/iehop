<ul>
	{foreach key=key item=item from=$menu}
		<li {if $item.active || $item.in_chain}class="active"{/if}><a href="{$item.link}"><span class="a">{$item.value}{if $item.indicator}<span class="num">{$item.indicator}</span>{/if}</span></a>
			{if $item.sub}
				<ul class="submenu noPrint">
					{foreach key=key item=s from=$item.sub}
						<li {if $s.active}class="active"{/if}><a href="{$s.link}">{$s.value}{if $s.indicator}<span class="num">{$s.indicator}</span>{/if}</a></li>
					{/foreach}
				</ul>
			{/if}
		</li>
	{/foreach}
</ul>