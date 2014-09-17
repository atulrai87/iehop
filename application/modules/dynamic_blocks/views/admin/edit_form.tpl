{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_area_change' gid='dynamic_blocks'}{else}{l i='admin_header_area_add' gid='dynamic_blocks'}{/if}</div>
		<div class="row zebra">
			<div class="h">{l i='field_gid' gid='dynamic_blocks'}:&nbsp;* </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='dynamic_blocks'}:&nbsp;* </div>
			<div class="v"><input type="text" value="{$data.name}" name="name"></div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/dynamic_blocks">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
{include file="footer.tpl"}
