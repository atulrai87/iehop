{strip}
<div class="content-block">
	<div class="edit_block">
		<form action="{$site_url}users_payments/save_payment/" method="post" id="payment_form" >
			<div class="ptb20">
				<span class="h2">{l i='field_enter_amount' gid='users_payments'}:</span>
				&nbsp;<input type="text" name="amount" class="short"> {block name=currency_format_output module=start cur_gid=$base_currency.gid}
			</div>
			{if $billing_systems}
				<div class="r">
				<input type="hidden" value="" name="system_gid" id="system_gid" />
				{foreach item=item from=$billing_systems}
					{if $item.logo_url}
						<input type="image" data-pjax-submit="0" class="mrb10 fltl h100 box-sizing" src="{$item.logo_url}" onclick="$('#system_gid').val('{$item.gid}');" title="{$item.name}" alt="{$item.name}" />
					{else}
						<input type="submit" data-pjax-submit="0" class="mrb10 h100 box-sizing" value="{$item.name}" onclick="$('#system_gid').val('{$item.gid}');" />
					{/if}
				{/foreach}
				</div>
			{else}
				<div class="r">
					<i>{l i='error_empty_billing_system_list' gid='users_payments'}</i>
				</div>
			{/if}
		</form>
	</div>
</div>
{/strip}