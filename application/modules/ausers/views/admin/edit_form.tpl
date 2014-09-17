{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_ausers_change' gid='ausers'}{else}{l i='admin_header_ausers_add' gid='ausers'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_nickname' gid='ausers'}: </div>
			<div class="v"><input type="text" value="{$data.nickname}" name="nickname"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_email' gid='ausers'}: </div>
			<div class="v"><input type="text" value="{$data.email}" name="email"></div>
		</div>
		{if $data.id}
		<div class="row">
			<div class="h">{l i='field_change_password' gid='ausers'}: </div>
			<div class="v"><input type="checkbox" value="1" name="update_password" id="pass_change_field"></div>
		</div>
		{/if}
		<div class="row">
			<div class="h">{l i='field_password' gid='ausers'}: </div>
			<div class="v"><input type="password" value="" name="password" id="pass_field" {if $data.id}disabled{/if}></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_repassword' gid='ausers'}: </div>
			<div class="v"><input type="password" value="" name="repassword" id="repass_field"{if $data.id}disabled{/if}></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_user_type' gid='ausers'}: </div>
			<div class="v">
				<select name="user_type" id="user_type_select">
				<option value="admin"{if $data.user_type eq 'admin'} selected{/if}>{l i='field_user_type_admin' gid='ausers'}</option>
				<option value="moderator"{if $data.user_type eq 'moderator'} selected{/if}>{l i='field_user_type_moderator' gid='ausers'}</option>
				</select>			
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='ausers'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_description' gid='ausers'}: </div>
			<div class="v"><textarea name="description">{$data.description}</textarea></div>
		</div>
{*		<div class="row">
			<div class="h">{l i='field_lang' gid='ausers'}: </div>
			<div class="v"><select name="lang_id">{foreach item=item from=$langs}<option value="{$item.id}"{if $item.id eq $data.lang_id} selected{/if}>{$item.name}</option>{/foreach}</select></div>
		</div>*}
		<div class="row" id="permissions_block" {if $data.user_type ne 'moderator'}style="display: none"{/if}>
			<div class="h">{l i='field_permissions' gid='ausers'}: </div>
			<div class="v">
				{foreach item=module from=$methods key=key}
				<div class="permissions">
					<input type="checkbox" name="permission_data[{$key}][{$module.main.method}]" value=1 {if $module.main.checked}checked{/if} id="pd_{$key}"> <label for="pd_{$key}"><b>{$module.module.module_name}</b></label><br>
					<ul>
					{foreach item=item from=$module.methods key=index}
					{if $index !== 'main'}<li><input type="checkbox" name="permission_data[{$key}][{$item.method}]" value=1 {if $item.checked}checked{/if} id="pd_{$key}_{$item.method}" {if !$module.main.checked}disabled{/if}> <label for="pd_{$key}_{$item.method}">{$item.name}</label></li>{/if}					
					{/foreach}					
					</ul>	
				</div>			
				{/foreach}
			</div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/ausers">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
	$("#pass_change_field").click(function(){
		if(this.checked){
			$("#pass_field").removeAttr("disabled"); $("#repass_field").removeAttr("disabled");
		}else{
			$("#pass_field").attr('disabled', 'disabled'); $("#repass_field").attr('disabled', 'disabled');
		}
	});
	
	$('#user_type_select').bind('change', function(){
		if($(this).val() == 'admin'){
			$('#permissions_block').hide();		
		}else{
			$('#permissions_block').show();		
		}
	});
	
	$('.permissions > input[type=checkbox]').bind('click', function(){
		if($(this).is(':checked')){
			$(this).parent().find('input[id^='+$(this).attr('id')+'_]').removeAttr("disabled");
		}else{
			$(this).parent().find('input[id^='+$(this).attr('id')+'_]').attr('disabled', 'disabled');
		}
	});
});
{/literal}</script>

{include file="footer.tpl"}