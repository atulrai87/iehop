<div class="region-box">
	<div class="button-input-wrapper">
		<button id="country_open_{$country_helper_data.rand}" name="submit"><i class="icon-caret-down"></i></button>
		<input type="text" class="box-sizing" name="region_name" id="country_text_{$country_helper_data.rand}" autocomplete="off" value="{$country_helper_data.location_text}" placeholder="{$country_helper_data.placeholder}">
	</div>
	<input type="hidden" name="{$country_helper_data.var_country_name}" id="country_hidden_{$country_helper_data.rand}" value="{$country_helper_data.country.code}">
	<input type="hidden" name="{$country_helper_data.var_region_name}" id="region_hidden_{$country_helper_data.rand}" value="{$country_helper_data.region.id}">
	<input type="hidden" name="{$country_helper_data.var_city_name}" id="city_hidden_{$country_helper_data.rand}" value="{$country_helper_data.city.id}">
</div>

<script type='text/javascript'>
{if $country_helper_data.var_js_name}var {$country_helper_data.var_js_name};{/if}
{literal}
$(function(){
	loadScripts(
		"{/literal}{js module=countries file='country-input.js' return='path'}{literal}",
		function(){
			var region_{/literal}{$country_helper_data.rand}{literal} = new countryInput({
				siteUrl: '{/literal}{$site_url}{literal}',
				rand: '{/literal}{$country_helper_data.rand}{literal}',
				id_country: '{/literal}{$country_helper_data.country.code}{literal}',
				id_region: '{/literal}{$country_helper_data.region.id}{literal}',
				id_city: '{/literal}{$country_helper_data.city.id}{literal}',
				select_type: '{/literal}{$country_helper_data.select_type}{literal}'
			});
		},
		'region_{/literal}{$country_helper_data.rand}{literal}'
	);
});
{/literal}</script>