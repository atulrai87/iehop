{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_common_album_edit' gid='media'}{else}{l i='admin_common_album_add' gid='media'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='media'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_description' gid='media'}: </div>
			<div class="v"><textarea name="description">{$data.description}</textarea></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/media/common_albums/">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}