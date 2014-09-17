{if $count_active > 1}
	<ul>
		<li>
			<select onchange="location.href = '{$site_url}users/change_language/'+this.value">
				{foreach item=item from=$languages}{if $item.status eq '1'}<option value="{$item.id}" {if $item.id eq $current_lang} selected{/if}>{$item.name}</option>{/if}{/foreach}
			</select>
		</li>
	</ul>
{/if}