	<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first" colspan=2>{l i='stat_header_payments' gid='payments'}</th>
	</tr>
	<tr>
		<td class="first"><a href="{$site_url}admin/payments/index/">{l i='stat_header_all' gid='payments'}</a></td>
		<td class="w30"><a href="{$site_url}admin/payments/index/">{$stat_payments.all}</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a href="{$site_url}admin/payments/index/approve">{l i='stat_header_approved' gid='payments'}</a></td>
		<td class="w30"><a href="{$site_url}admin/payments/index/approve">{$stat_payments.approve}</a></td>
	</tr>
	<tr>
		<td class="first"><a href="{$site_url}admin/payments/index/decline">{l i='stat_header_declined' gid='payments'}</a></td>
		<td class="w30"><a href="{$site_url}admin/payments/index/decline">{$stat_payments.decline}</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a href="{$site_url}admin/payments/index/wait">{l i='stat_header_awaiting' gid='payments'}</a></td>
		<td class="w30"><a href="{$site_url}admin/payments/index/wait">{$stat_payments.wait}</a></td>
	</tr>
	</table>

