<ul>
{foreach key=key item=item from=$menu}
<li {if $item.active}class="active"{/if}><a href="{$item.link}">{$item.value}</a></li>
{/foreach}
</ul>