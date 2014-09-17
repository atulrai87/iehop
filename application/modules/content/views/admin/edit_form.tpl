{include file="header.tpl"}

{if $data.id}
{depends module=seo}
<div class="menu-level3">
	<ul>
		<li class="{if $section_gid eq 'text'}active{/if}"><a href="{$site_url}admin/content/edit/{$current_lang}/{$parent_id}/{$data.id}/text">{l i='filter_section_text' gid='content'}</a></li>
		<li class="{if $section_gid eq 'seo'}active{/if}"><a href="{$site_url}admin/content/edit/{$current_lang}/{$parent_id}/{$data.id}/seo">{l i='filter_section_seo' gid='seo'}</a></li>
	</ul>
	&nbsp;
</div>
{/depends}
{/if}

{switch from=$section_gid}
	{case value='text'}
		<form method="post" action="" name="save_form">
			<div class="edit-form n150">
				<div class="row header">{if $data.id}{l i='admin_header_page_change' gid='content'}{else}{l i='admin_header_page_add' gid='content'}{/if}</div>
				{if $data.id}
				<div class="row">
					<div class="h">{l i='field_view_link' gid='content'}: </div>
					<div class="v"><a href="{$site_url}content/view/{$data.gid}">{$site_url}content/view/{$data.gid}</a>&nbsp;</div>
				</div>
				{/if}
				<div class="row zebra">
					<div class="h">{l i='field_lang' gid='content'}: </div>
					<div class="v">{$languages[$current_lang].name}</div>
				</div>
				<div class="row">
					<div class="h">{l i='field_gid' gid='content'}: </div>
					<div class="v"><input type="text" value="{$data.gid}" name="gid"></div>
				</div>
				<div class="row zebra">
					<div class="h">{l i='field_title' gid='content'}: </div>
					<div class="v"><input type="text" value="{$data.title}" name="title" class="long"></div>
				</div>
				<div class="row content">
					{$data.content_fck}
				</div>
			</div>
			<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
			<a class="cancel" href="{$site_url}admin/content/index/{$current_lang}">{l i='btn_cancel' gid='start'}</a>
		</form>
		<div class="clr"></div>
	{case value='seo'}
		{depends module=seo}
		{foreach item=section key=key from=$seo_fields}
		<form method="post" action="{$data.action|escape}" name="seo_{$section.gid}_form">
		<div class="edit-form n150">
			<div class="row header">{$section.name}</div>		
			{if $section.tooltip}
			<div class="row">
				<div class="h">&nbsp;</div>
				<div class="v">{$section.tooltip}</div>
			</div>
			{/if}
			{foreach item=field from=$section.fields}
			<div class="row">
				<div class="h">{$field.name}: </div>
				<div class="v">
					{assign var='field_gid' value=$field.gid}
					{switch from=$field.type}
						{case value='checkbox'}
							<input type="hidden" name="{$section.gid}[{$field_gid}]" value="0">
							<input type="checkbox" name="{$section.gid}[{$field_gid}]" value="1" {if $seo_settings[$field_gid]}checked{/if}>
						{case value='text'}
							{foreach item=lang_item key=lang_id from=$languages}
							{assign var='section_gid' value=$section.gid+'_'+$lang_id}
							<input type="{if $lang_id eq $current_lang_id}text{else}hidden{/if}" name="{$section.gid}[{$field_gid}][{$lang_id}]" value="{$seo_settings[$section_gid][$field_gid]|escape}" class="long" lang-editor="value" lang-editor-type="{$section.gid}_{$field_gid}" lang-editor-lid="{$lang_id}">
							{/foreach}
							<a href="#" lang-editor="button" lang-editor-type="{$section.gid}_{$field_gid}"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16" alt="{l i='note_types_translate' gid='reviews' type='button'}" title="{l i='note_types_translate' gid='reviews' type='button'}"></a>
						{case value='textarea'}
							{foreach item=lang_item key=lang_id from=$languages}
								{assign var='section_gid' value=$section.gid+'_'+$lang_id}
								{if $lang_id eq $current_lang_id}
								<textarea name="{$section.gid}[{$field_gid}][{$lang_id}]" rows="5" cols="80" class="long" lang-editor="value" lang-editor-type="{$section.gid}_{$field_gid}" lang-editor-lid="{$lang_id}">{$seo_settings[$section_gid][$field_gid]|escape}</textarea>
								{else}
								<input type="hidden" name="{$section.gid}[{$field_gid}][{$lang_id}]" value="{$seo_settings[$section_gid][$field_gid][$lang_id]|escape}">
								{/if}
							{/foreach}
							<a href="#" lang-editor="button" lang-editor-type="{$section.gid}_{$field.gid}" lang-field-type="textarea"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16" alt="{l i='note_types_translate' gid='reviews' type='button'}" title="{l i='note_types_translate' gid='reviews' type='button'}"></a>					
					{/switch}<br>{$field.tooltip}					
				</div>
			</div>
			{/foreach}	
		</div>	
		<div class="btn"><div class="l"><input type="submit" name="btn_save_{$section.gid}" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/content/index/{$current_lang}">{l i='btn_cancel' gid='start'}</a>	
		<input type="hidden" name="btn_save" value="1">
		</form>
		<div class="clr"></div>
		{/foreach}
		{block name=lang_inline_editor module=start}
		{/depends}
{/switch}
{include file="footer.tpl"}
