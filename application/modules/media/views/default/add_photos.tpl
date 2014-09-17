{strip}
<div class="content-block load_content">
	<h1>{l i='add_photos' gid='media'}</h1>
	<div class="m10 oh popup-form">
		<form id="item_form" onsubmit="return" action="{$site_root}/{$seo.myprofile}" method="post" enctype="multipart/form-data" name="item_form">
			<div class="r">
				<div class="f">{l i='field_files' gid='media'}:</div>
				<div class="v">
					<div id="dnd_upload" class="drag">
						<div id="dndfiles" class="drag-area">
							<div class="drag">
								<p>{l i='drag_photos' gid='media'}</p>
							</div>
						</div>
					</div>
					<div>
						<div class="upload-btn">
							<span data-role="filebutton">
								<s>{l i='btn_choose_file' gid='start'}</s>
								<input type="file" name="multiUpload" id="multiUpload" multiple />
							</span>
							{if $media_config.max_size}&nbsp;({l i='max' gid='start'} {$media_config.max_size|bytes_format}){/if}
						</div>
						&nbsp;<span id="attach-input-error"></span>
						<div id="attach-input-warning"></div>
					</div>
				</div>
			</div>
			{if $user_albums}
				<div class="r">
					<div class="f">{l i='albums' gid='media'}:</div>
					<div class="v">
						<select class="wp100 box-sizing" name="id_album">
							<option value="0">{l i='please_select' gid='media'}</option>
							{foreach item=item key=key name=f from=$user_albums}
								<option value="{$item.id}"{if $item.id == $id_album} selected{/if}>{$item.name}</option>
							{/foreach}
						</select>
					</div>
				</div>
			{/if}
			<div class="r">
				<div class="f">{l i='field_permitted_for' gid='media'}:</div>
				<div class="v">
					{ld gid='media' i='permissions'}
					<select class="wp100 box-sizing" name="permissions">
						{foreach item=item key=key name=f from=$ld_permissions.option}
							<option value="{$key}"{if $key == 4} selected{/if}>{$item}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="r">
				<div class="f">{l i='field_description' gid='media'}:</div>
				<div class="v text"><textarea class="box-sizing" name="description">{$data.description}</textarea></div>
			</div>
			<div class="v"><input type="button" value="{l i='btn_save' gid='start' type='button'}" name="btn_upload" id="btn_upload"></div>
		</form>
	</div>
	<div class="clr"></div>
</div>
{/strip}

<script type='text/javascript'>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js file='uploader.js' return='path'}{literal}", 
			function(){
				var allowed_mimes = {/literal}{json_encode data=$media_config.allowed_mimes}{literal};
				mu = new uploader({
					siteUrl: site_url,
					uploadUrl: 'media/save_image',
					zoneId: 'dndfiles',
					fileId: 'multiUpload',
					formId: 'item_form',
					sendType: 'file',
					sendId: 'btn_upload',
					messageId: 'attach-input-error',
					warningId: 'attach-input-warning',
					maxFileSize: '{/literal}{$media_config.max_size}{literal}',
					mimeType:  allowed_mimes,
					cbOnQueueComplete: function(data){
						if(window.sitegallery){
							sitegallery.reload();
						}else if(window.mediagallery){
							mediagallery.reload();
						}
					},
					createThumb: true,
					thumbWidth: 60,
					thumbHeight: 60,
					thumbCrop: true,
					thumbJpeg: false,
					thumbBg: 'transparent',
					fileListInZone: true,
					filebarHeight: 200,
					jqueryFormPluginUrl: "{/literal}{js file='jquery.form.min.js' return='path'}{literal}"
			   });
			},
			['mu'],
			{async: false}
		);
	});
{/literal}</script>