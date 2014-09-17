{strip}
<div class="content-block load_content">
	<h1>{l i='field_user_logo' gid='users'}</h1>
	<div class="m20 oh">
		<div class="edit-media-content" id="image_content_avatar">
			<div class="pos-rel">
				{if $avatar_data.is_owner}
					<link rel="stylesheet" type="text/css" href="{$site_url}{$js_folder}jquery.imgareaselect/css/imgareaselect-default.css"></link>
					<div class="photo-edit">
						<div id="photo_source_recrop_box" class="photo-source-box lh0">
							<img id="photo_source_recrop" src="{if $avatar_data.user.user_logo_moderation}{$avatar_data.user.media.user_logo_moderation.file_url}{else}{$avatar_data.user.media.user_logo.file_url}{/if}?{''|time}">
						</div>
						<div class="ptb5 oh tab-submenu" id="media_menu">
							<ul class="fleft" id="photo_sizes" data-area="recrop"></ul>
							<ul class="fright">
								<li class="active"><span data-section="view">{l i="view" gid="media"}</span></li>
								<li><span data-section="recrop">{l i="recrop" gid="media"}</span></li>
							</ul>
						</div>
					</div>

					<form id="upload_avatar" action="" method="post" enctype="multipart/form-data" name="upload_video">
						<div class="pb5">
							<div id="dnd_upload_avatar" class="drag">
								<div id="dndfiles_avatar" class="drag-area">
									<div class="drag">{l i='drag_photos' gid='media'}</div>
								</div>
							</div>
							<div>
								<div class="upload-btn">
									<span data-role="filebutton">
										<s>{l i='btn_choose_file' gid='start'}</s>
										<input type="file" name="avatar" id="file_avatar" />
									</span>
									&nbsp;({l i='max' gid='start'} {$avatar_data.upload_config.max_size|bytes_format})
								</div>
								&nbsp;<span id="attach_error_avatar"></span>
								<div id="attach_warning_avatar"></div>
							</div>
						</div>
						<div><input type="button" value="{l i='btn_upload' gid='start' type='button'}" name="btn_upload" id="btn_upload_avatar"></div>
					</form>
					<script>{literal}
						$(function(){
							loadScripts(
								["{/literal}{js file='jquery.imgareaselect/jquery.imgareaselect.js' return='path'}{literal}", "{/literal}{js file='uploader.js' return='path'}{literal}"],
								function(){
									var upload_config = {/literal}{json_encode data=$avatar_data.upload_config}{literal};
									user_avatar_selections = {/literal}{json_encode data=$avatar_data.selections}{literal};
									user_avatar.uninit_imageareaselect();
									for(var i in user_avatar_selections) if(user_avatar_selections.hasOwnProperty(i)){
										user_avatar.add_selection(i, 0, 0, parseInt(user_avatar_selections[i].width), parseInt(user_avatar_selections[i].height));
									}
									{/literal}{if $avatar_data.have_avatar}
									user_avatar.init_imageareaselect();
									{/if}{literal}

									avatar_uploader = new uploader({
										siteUrl: site_url,
										uploadUrl: 'users/upload_avatar',
										zoneId: 'dndfiles_avatar',
										fileId: 'file_avatar',
										formId: 'upload_avatar',
										filebarId: 'filebar_avatar',
										sendType: 'file',
										sendId: 'btn_upload_avatar',
										multiFile: false,
										messageId: 'attach_error_avatar',
										warningId: 'attach_warning_avatar',
										maxFileSize: upload_config.max_size,
										mimeType: upload_config.allowed_mimes,
										createThumb: true,
										thumbWidth: 200,
										thumbHeight: 200,
										thumbCrop: true,
										thumbJpeg: false,
										thumbBg: 'transparent',
										fileListInZone: true,
										cbOnUpload: function(name, data){
											if(data.logo && !$.isEmptyObject(data.logo)){
												$('#image_content_avatar').find('.photo-edit').show();
												$('#photo_source_recrop').attr('src', '');
												user_avatar.uninit_imageareaselect();
												for(var i in user_avatar_selections) if(user_avatar_selections.hasOwnProperty(i)){
													user_avatar.add_selection(i, 0, 0, parseInt(user_avatar_selections[i].width), parseInt(user_avatar_selections[i].height));
												}
												$('#photo_source_recrop').attr('src', data.logo.file_url+'?'+new Date().getTime());
												$('#user_photo > img').attr('src', data.logo.thumbs.middle+'?'+new Date().getTime());
												$('img[id^=avatar_'+id_user+']').attr('src', data.logo.thumbs.small+'?'+new Date().getTime());
												user_avatar.init_imageareaselect();
												var images = $('img');
												if(data.old_logo && !$.isEmptyObject(data.old_logo)){
													for(var i in data.old_logo.thumbs) if(data.old_logo.thumbs.hasOwnProperty(i)){
														images.filter('[src^="'+data.old_logo.thumbs[i]+'"]').attr('src', data.logo.thumbs[i]+'?'+new Date().getTime());
													}
												}
												if(data.old_logo_moderation && !$.isEmptyObject(data.old_logo_moderation)){
													for(var i in data.old_logo_moderation.thumbs) if(data.old_logo_moderation.thumbs.hasOwnProperty(i)){
														images.filter('[src^="'+data.old_logo_moderation.thumbs[i]+'"]').attr('src', data.logo.thumbs[i]+'?'+new Date().getTime());
													}
												}
											}
										},
										cbOnComplete: function(data){
											if(data.errors.length){
												error_object.show_error_block(data.errors, 'error');
											}
										},
										jqueryFormPluginUrl: "{/literal}{js file='jquery.form.min.js' return='path'}{literal}"
									});
								},
								['user_avatar', 'avatar_uploader'],
								{async: false}
							);
						});
					</script>{/literal}
				{else}
					<div class="center lh0">
						<img src="{$avatar_data.user.media.user_logo.thumbs.grand}">
					</div>
				{/if}
				<div class="mt20">
					{block name=comments_form module=comments gid=user_avatar id_obj=$avatar_data.user.id hidden=0 count=$avatar_data.user.logo_comments_count}
				</div>
			</div>
		</div>
	</div>
</div>

{/strip}