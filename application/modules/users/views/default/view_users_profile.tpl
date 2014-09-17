<h2 class="line top bottom linked">
	{l i='table_header_personal' gid='users'}
</h2>
<div class="view-section">
	{l i='no_information' gid='users' assign='no_info_str'}
	<div class="r">
		<div class="f">{l i='field_user_type' gid='users'}:</div>
		<div class="v">{$data.user_type_str}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_looking_user_type' gid='users'}:</div>
		<div class="v">{if $data.looking_user_type_str}{$data.looking_user_type_str}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_partner_age' gid='users'} {l i='from' gid='users'}:</div>
		<div class="v">{if $data.age_min}{$data.age_min}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_partner_age' gid='users'} {l i='to' gid='users'}:</div>
		<div class="v">{if $data.age_max}{$data.age_max}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_nickname' gid='users'}:</div>
		<div class="v">{$data.nickname}</div>
	</div>
	{if $data.fname}
		<div class="r">
			<div class="f">{l i='field_fname' gid='users'}:</div>
			<div class="v">{$data.fname}</div>
		</div>
	{/if}
	{if $data.sname}
		<div class="r">
			<div class="f">{l i='field_sname' gid='users'}:</div>
			<div class="v">{$data.sname}</div>
		</div>
	{/if}
	<div class="r">
		<div class="f">{l i='birth_date' gid='users'}:</div>
		<div class="v">{$data.birth_date|date_format:$page_data.date_format:'':$no_info_str}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_region' gid='users'}:</div>
		<div class="v">{if $data.location}{$data.location}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
</div>
{foreach item=item from=$sections}
<h2 class="line top bottom linked">
	{$item.name}
</h2>
<div class="view-section">
	{include file="custom_view_fields.tpl" fields_data=$item.fields module="users"}
</div>
{/foreach}
