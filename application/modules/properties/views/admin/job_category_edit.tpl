{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $id ne 0}{l i='admin_header_ds_item_change' gid='properties'}{else}{l i='admin_header_ds_item_add' gid='properties'}{/if}</div>
		{*<div class="row">
			<div class="h">{l i='field_gid' gid='properties'}: </div>
			<div class="v">{if $option_gid}{$option_gid}{else}{if $next_option_gid}<input type="hidden" value="{$next_option_gid}" name="option_gid">{$next_option_gid}{else}<input type="text" value="" name="option_gid">{/if}{/if}</div>
		</div>*}
		{foreach item=item key=lang_id from=$langs}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v"><input type="text" value="{$cat.lang_data[$lang_id]|escape}" name="lang_data[{$lang_id}]" class="long"></div>
		</div>
		{/foreach}
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/properties/job_categories/{$parent}/{$current_lang_id}">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>

<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}