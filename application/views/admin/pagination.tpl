<div class="pages">
	{if $page_data.total_rows}
		<span class="total">{l i='showing' gid='start'} {$page_data.start_num} - {$page_data.end_num} / {$page_data.total_rows}</span>
	{/if}
	&nbsp;{$page_data.nav}
</div>
