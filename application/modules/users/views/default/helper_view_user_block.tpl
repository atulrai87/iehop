<div class="view-user">
{if $user.user_type eq '1'}
	<h2>{$user.output_name}</h2>
	{l i='no_information' gid='users' assign='no_info_str'}

	<div class="r">
		<div class="f">{l i='birth_date' gid='users'}:</div>
		<div class="v">{$user.birth_date|date_format:$date_format:'':$no_info_str}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_region' gid='users'}:</div>
		<div class="v">{if $user.location}{$user.location}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{ld_header i='relocation' gid='data_properties'}:</div>
		<div class="v">{ld_option i='relocation' gid='data_properties' option=$user.relocation default=$no_info_str}</div>
	</div>
	<div class="r">
		<div class="f">{ld_header i='marital_status' gid='data_properties'}:</div>
		<div class="v">{ld_option i='marital_status' gid='data_properties' option=$user.marital_status default=$no_info_str}</div>
	</div>
	<div class="r">
		<div class="f">{ld_header i='ethnicity' gid='data_properties'}:</div>
		<div class="v">{ld_option i='ethnicity' gid='data_properties' option=$user.ethnicity default=$no_info_str}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_languages_spoken' gid='users'}:</div>
		<div class="v">{if $user.languages_spoken}{$user.languages_spoken|nl2br}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{ld_header i='willing_to_travel' gid='data_properties'}:</div>
		<div class="v">{ld_option i='willing_to_travel' gid='data_properties' option=$user.willing_to_travel default=$no_info_str}</div>
	</div>
	

	<h2>{l i='table_header_contact' gid='users'}</h2>
	<div class="r">
		<div class="f">{l i='field_phone' gid='users'}:</div>
		<div class="v">{if $user.phone}{$user.phone}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_contact_email' gid='users'}:</div>
		<div class="v">{if $user.contact_email}{$user.contact_email}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_skype' gid='users'}:</div>
		<div class="v">{if $user.skype}{$user.skype}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>

{else}

	<h2>{if $user.company_name}{$user.company_name}{else}{l i='no_information' gid='users'}{/if}</h2>
	<div class="r">
		<div class="f">{l i='field_region' gid='users'}:</div>
		<div class="v">{if $user.location}{$user.location}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_company_discription' gid='users'}:</div>
		<div class="v">{if $user.company_discription}{$user.company_discription|nl2br}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>

	<h2>{l i='table_header_contact' gid='users'}</h2>
	<div class="r">
		<div class="f">{l i='field_phone' gid='users'}:</div>
		<div class="v">{if $user.phone}{$user.phone}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_contact_email' gid='users'}:</div>
		<div class="v">{if $user.contact_email}{$user.contact_email}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_address' gid='users'}:</div>
		<div class="v">{if $user.address}{$user.address}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_postal_code' gid='users'}:</div>
		<div class="v">{if $user.postal_code}{$user.postal_code}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_web_url' gid='users'}:</div>
		<div class="v">{if $user.web_url}{$user.web_url}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_skype' gid='users'}:</div>
		<div class="v">{if $user.skype}{$user.skype}{else}{l i='no_information' gid='users'}{/if}</div>
	</div>

{/if}
</div>