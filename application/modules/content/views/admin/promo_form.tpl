{include file="header.tpl"}
<div class="menu-level2">
	<ul>
		{foreach item=item key=lang_id from=$languages}
		<li{if $lang_id eq $current_lang} class="active"{/if}><div class="l"><a href="{$site_url}admin/content/promo/{$lang_id}">{$item.name}</a></div></li>
		{/foreach}
	</ul>
	&nbsp;
</div>

<div class="actions">&nbsp;</div>

<form method="post" action="" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_promo_block_main' gid='content'}</div>
		<div class="row">
			<div class="h">{l i='field_promo_type' gid='content'}: </div>
			<div class="v">
				<select name="content_type">
				<option value="t"{if $promo_data.content_type eq 't'} selected{/if}>{l i='field_promo_type_text' gid='content'}</option>	
				<option value="f"{if $promo_data.content_type eq 'f'} selected{/if}>{l i='field_promo_type_flash' gid='content'}</option>	
				</select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_block_width' gid='content'}: </div>
			<div class="v">
				<select name="block_width_unit" class="units">
				<option value="auto"{if $promo_data.block_width_unit eq 'auto'} selected{/if}>{l i='field_block_unit_auto' gid='content'}</option>	
				<option value="px"{if $promo_data.block_width_unit eq 'px'} selected{/if}>{l i='field_block_unit_px' gid='content'}</option>	
				<option value="%"{if $promo_data.block_width_unit eq '%'} selected{/if}>{l i='field_block_unit_percent' gid='content'}</option>	
				</select>
				<input type="text" class="short unit_val" name="block_width" value="{$promo_data.block_width}" {if $promo_data.block_width_unit eq 'auto'} disabled{/if}>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_block_height' gid='content'}: </div>
			<div class="v">
				<select name="block_height_unit" class="units">
				<option value="auto"{if $promo_data.block_height_unit eq 'auto'} selected{/if}>{l i='field_block_unit_auto' gid='content'}</option>	
				<option value="px"{if $promo_data.block_height_unit eq 'px'} selected{/if}>{l i='field_block_unit_px' gid='content'}</option>	
				</select>
				<input type="text" class="short unit_val" name="block_height" value="{$promo_data.block_height}" {if $promo_data.block_height_unit eq 'auto'} disabled{/if}>
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save_settings" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<div class="clr"></div>
</form>

<div class="menu-level3">
	<ul>
		<li{if $content_type eq 't'} class="active"{/if}><div class="l"><a href="{$site_url}admin/content/promo/{$current_lang}/t">{l i='field_promo_type_text' gid='content'}</a></div></li>
		<li{if $content_type eq 'f'} class="active"{/if}><div class="l"><a href="{$site_url}admin/content/promo/{$current_lang}/f">{l i='field_promo_type_flash' gid='content'}</a></div></li>
	</ul>
	&nbsp;
</div>

<form method="post" action="{$site_url}admin/content/promo/{$current_lang}/{$content_type}" name="save_form"  enctype="multipart/form-data">
{if $content_type eq 't'}
	<div class="edit-form n150">
		<div class="row header">&nbsp;</div>
		<div class="row">
			<div class="h">{l i='field_promo_text' gid='content'}: </div>
			<div class="v">
				{$promo_data.promo_text_fck}
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_block_img_align_hor' gid='content'}: </div>
			<div class="v">
				<select name="block_align_hor">
				<option value="center"{if $promo_data.block_align_hor eq 'center'} selected{/if}>{l i='field_block_img_align_center' gid='content'}</option>
				<option value="left"{if $promo_data.block_align_hor eq 'left'} selected{/if}>{l i='field_block_img_align_left' gid='content'}</option>
				<option value="right"{if $promo_data.block_align_hor eq 'right'} selected{/if}>{l i='field_block_img_align_right' gid='content'}</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_block_img_align_ver' gid='content'}: </div>
			<div class="v">
				<select name="block_align_ver">
				<option value="center"{if $promo_data.block_align_ver eq 'center'} selected{/if}>{l i='field_block_img_align_center' gid='content'}</option>
				<option value="top"{if $promo_data.block_align_ver eq 'top'} selected{/if}>{l i='field_block_img_align_top' gid='content'}</option>
				<option value="bottom"{if $promo_data.block_align_ver eq 'bottom'} selected{/if}>{l i='field_block_img_align_bottom' gid='content'}</option>
				</select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_block_img_repeating' gid='content'}: </div>
			<div class="v">
				<select name="block_image_repeat">
				<option value="repeat"{if $promo_data.block_image_repeat eq 'repeat'} selected{/if}>{l i='field_block_img_repeat' gid='content'}</option>
				<option value="no-repeat"{if $promo_data.block_image_repeat eq 'no-repeat'} selected{/if}>{l i='field_block_img_no_repeat' gid='content'}</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_promo_img' gid='content'}: </div>
			<div class="v">
				<input type="file" name="promo_image">
				{if $promo_data.promo_image}<br><img class="maxwp100" src="{$promo_data.media.promo_image.file_url}">{/if}
			</div>
		</div>
		{if $promo_data.promo_image}
		<div class="row zebra">
			<div class="h">{l i='field_promo_image_delete' gid='content'}: </div>
			<div class="v"><input type="checkbox" name="promo_image_delete" value="1"></div>
		</div>
		{/if}
	</div>
{/if}
{if $content_type eq 'f'}
	<div class="edit-form n150">
		<div class="row header">&nbsp;</div>
		<div class="row">
			<div class="h">{l i='field_promo_flash' gid='content'}: </div>
			<div class="v">
				<input type="file" name="promo_flash"><br>
				{if $promo_data.promo_flash}<i>{l i='field_promo_flash_uploaded' gid='content'}</i>{/if}
			</div>
		</div>
		{if $promo_data.promo_flash}
		<div class="row zebra">
			<div class="h">{l i='field_promo_flash_delete' gid='content'}: </div>
			<div class="v"><input type="checkbox" name="promo_flash_delete" value="1"></div>
		</div>
		{/if}
	</div>
{/if}
	<div class="btn"><div class="l"><input type="submit" name="btn_save_content" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<div class="clr"></div>
</form>
<script type="text/javascript">{literal}
$(function(){
	$('.units').bind('change', function(){
		if($(this).val() == 'auto'){
			$(this).parent().find('input.unit_val').attr('disabled', 'disabled');
		}else{
			$(this).parent().find('input.unit_val').removeAttr('disabled');
		}	
	});
});
{/literal}</script>
<div class="clr"></div>
{include file="footer.tpl"}
