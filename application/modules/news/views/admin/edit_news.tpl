{include file="header.tpl"}

{if $data.id}
{depends module=seo}
<div class="menu-level3">
	<ul>
		<li class="{if $section_gid eq 'text'}active{/if}"><a href="{$site_url}admin/news/edit/{$data.id}/text">{l i='filter_section_text' gid='news'}</a></li>
		<li class="{if $section_gid eq 'seo'}active{/if}"><a href="{$site_url}admin/news/edit/{$data.id}/seo">{l i='filter_section_seo' gid='seo'}</a></li>
	</ul>
	&nbsp;
</div>
{/depends}
{/if}

{switch from=$section_gid}
	{case value='text'}
		<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
			<div class="edit-form n150">
				<div class="row header">{if $data.id}{l i='admin_header_news_change' gid='news'}{else}{l i='admin_header_news_add' gid='news'}{/if}</div>
				<div class="row">
					<div class="h">{l i='field_name' gid='news'}: </div>
					<div class="v"><input type="text" value="{$data.name}" name="name" class="long"></div>
				</div>
				<div class="row">
					<div class="h">{l i='field_gid' gid='news'}: </div>
					<div class="v"><input type="text" value="{$data.gid}" name="gid" class="long"></div>
				</div>
				<div class="row">
					<div class="h">{l i='field_news_lang' gid='news'}: </div>
					<div class="v"><select name="id_lang">{foreach item=item from=$languages}<option value="{$item.id}"{if $item.id eq $data.id_lang} selected{/if}>{$item.name}</option>{/foreach}</select></div>
				</div>
				<div class="row">
					<div class="h">{l i='field_icon' gid='news'}: </div>
					<div class="v">
						<input type="file" name="news_icon">
						{if $data.img}
						<br><img src="{$data.media.img.thumbs.small}"  hspace="2" vspace="2" />
						<br><input type="checkbox" name="news_icon_delete" value="1" id="uichb"><label for="uichb">{l i='field_icon_delete' gid='news'}</label>
						{/if}
					</div>
				</div>
				<div class="row">
					<div class="h">{l i='field_video' gid='news'}: </div>
					<div class="v">
						<input type="file" name="news_video">
						{if $data.video}
							<br>{l i='field_video_status' gid='news'}: 
							{if $data.video_data.status eq 'end' && $data.video_data.errors}
								<font color="red">{foreach item=item from=$data.video_data.errors}{$item}<br>{/foreach}</font>
							{elseif $data.video_data.status eq 'end'}	<font color="green">{l i='field_video_status_end' gid='news'}</font><br>
							{elseif $data.video_data.status eq 'images'}	<font color="yellow">{l i='field_video_status_images' gid='news'}</font><br>
							{elseif $data.video_data.status eq 'waiting'} <font color="yellow">{l i='field_video_status_waiting' gid='news'}</font><br>
							{elseif $data.video_data.status eq 'start'} <font color="yellow">{l i='field_video_status_start' gid='news'}</font><br>
							{/if}
							{if $data.video_content.thumbs.small}
							<br><img src="{$data.video_content.thumbs.small}"  hspace="2" vspace="2" />
							{/if}
							<br><input type="checkbox" name="news_video_delete" value="1" id="uvchb"><label for="uvchb">{l i='field_video_delete' gid='news'}</label>
						{/if}
					</div>
				</div>
				<div class="row">
					<div class="h">{l i='field_annotation' gid='news'}: </div>
					<div class="v"><textarea name="annotation">{$data.annotation}</textarea><br><i>{l i='field_annotation_text' gid='news'}</i></div>
				</div>
				<div class="row">
					<div class="h">{l i='field_content' gid='news'}: </div>
					<div class="v">{$data.content_fck}&nbsp;</div>
				</div>
			</div>
			<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
			<a class="cancel" href="{$site_url}admin/news">{l i='btn_cancel' gid='start'}</a>
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
		<a class="cancel" href="{$site_url}admin/news">{l i='btn_cancel' gid='start'}</a>	
		<input type="hidden" name="btn_save" value="1">
		</form>
		<div class="clr"></div>
		{/foreach}
		{block name=lang_inline_editor module=start}
		{/depends}
{/switch}
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}
