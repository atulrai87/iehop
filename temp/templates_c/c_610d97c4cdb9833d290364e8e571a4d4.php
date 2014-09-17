<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.block.php'); $this->register_function("block", "tpl_function_block");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.json_encode.php'); $this->register_function("json_encode", "tpl_function_json_encode");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.js.php'); $this->register_function("js", "tpl_function_js");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.bytes_format.php'); $this->register_modifier("bytes_format", "tpl_modifier_bytes_format");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/block.strip.php'); $this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-10 02:04:57 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block load_content">
	<h1><?php echo l('field_user_logo', 'users', '', 'text', array()); ?></h1>
	<div class="m20 oh">
		<div class="edit-media-content" id="image_content_avatar">
			<div class="pos-rel">
				<?php if ($this->_vars['avatar_data']['is_owner']): ?>
					<link rel="stylesheet" type="text/css" href="<?php echo $this->_vars['site_url'];  echo $this->_vars['js_folder']; ?>
jquery.imgareaselect/css/imgareaselect-default.css"></link>
					<div class="photo-edit">
						<div id="photo_source_recrop_box" class="photo-source-box lh0">
							<img id="photo_source_recrop" src="<?php if ($this->_vars['avatar_data']['user']['user_logo_moderation']):  echo $this->_vars['avatar_data']['user']['media']['user_logo_moderation']['file_url'];  else:  echo $this->_vars['avatar_data']['user']['media']['user_logo']['file_url'];  endif; ?>?<?php echo $this->_run_modifier('', 'time', 'PHP', 1); ?>
">
						</div>
						<div class="ptb5 oh tab-submenu" id="media_menu">
							<ul class="fleft" id="photo_sizes" data-area="recrop"></ul>
							<ul class="fright">
								<li class="active"><span data-section="view"><?php echo l("view", "media", '', 'text', array()); ?></span></li>
								<li><span data-section="recrop"><?php echo l("recrop", "media", '', 'text', array()); ?></span></li>
							</ul>
						</div>
					</div>

					<form id="upload_avatar" action="" method="post" enctype="multipart/form-data" name="upload_video">
						<div class="pb5">
							<div id="dnd_upload_avatar" class="drag">
								<div id="dndfiles_avatar" class="drag-area">
									<div class="drag"><?php echo l('drag_photos', 'media', '', 'text', array()); ?></div>
								</div>
							</div>
							<div>
								<div class="upload-btn">
									<span data-role="filebutton">
										<s><?php echo l('btn_choose_file', 'start', '', 'text', array()); ?></s>
										<input type="file" name="avatar" id="file_avatar" />
									</span>
									&nbsp;(<?php echo l('max', 'start', '', 'text', array()); ?> <?php echo $this->_run_modifier($this->_vars['avatar_data']['upload_config']['max_size'], 'bytes_format', 'plugin', 1); ?>
)
								</div>
								&nbsp;<span id="attach_error_avatar"></span>
								<div id="attach_warning_avatar"></div>
							</div>
						</div>
						<div><input type="button" value="<?php echo l('btn_upload', 'start', '', 'button', array()); ?>" name="btn_upload" id="btn_upload_avatar"></div>
					</form>
					<script><?php echo '
						$(function(){
							loadScripts(
								["';  echo tpl_function_js(array('file' => 'jquery.imgareaselect/jquery.imgareaselect.js','return' => 'path'), $this); echo '", "';  echo tpl_function_js(array('file' => 'uploader.js','return' => 'path'), $this); echo '"],
								function(){
									var upload_config = ';  echo tpl_function_json_encode(array('data' => $this->_vars['avatar_data']['upload_config']), $this); echo ';
									user_avatar_selections = ';  echo tpl_function_json_encode(array('data' => $this->_vars['avatar_data']['selections']), $this); echo ';
									user_avatar.uninit_imageareaselect();
									for(var i in user_avatar_selections) if(user_avatar_selections.hasOwnProperty(i)){
										user_avatar.add_selection(i, 0, 0, parseInt(user_avatar_selections[i].width), parseInt(user_avatar_selections[i].height));
									}
									';  if ($this->_vars['avatar_data']['have_avatar']): ?>
									user_avatar.init_imageareaselect();
									<?php endif;  echo '

									avatar_uploader = new uploader({
										siteUrl: site_url,
										uploadUrl: \'users/upload_avatar\',
										zoneId: \'dndfiles_avatar\',
										fileId: \'file_avatar\',
										formId: \'upload_avatar\',
										filebarId: \'filebar_avatar\',
										sendType: \'file\',
										sendId: \'btn_upload_avatar\',
										multiFile: false,
										messageId: \'attach_error_avatar\',
										warningId: \'attach_warning_avatar\',
										maxFileSize: upload_config.max_size,
										mimeType: upload_config.allowed_mimes,
										createThumb: true,
										thumbWidth: 200,
										thumbHeight: 200,
										thumbCrop: true,
										thumbJpeg: false,
										thumbBg: \'transparent\',
										fileListInZone: true,
										cbOnUpload: function(name, data){
											if(data.logo && !$.isEmptyObject(data.logo)){
												$(\'#image_content_avatar\').find(\'.photo-edit\').show();
												$(\'#photo_source_recrop\').attr(\'src\', \'\');
												user_avatar.uninit_imageareaselect();
												for(var i in user_avatar_selections) if(user_avatar_selections.hasOwnProperty(i)){
													user_avatar.add_selection(i, 0, 0, parseInt(user_avatar_selections[i].width), parseInt(user_avatar_selections[i].height));
												}
												$(\'#photo_source_recrop\').attr(\'src\', data.logo.file_url+\'?\'+new Date().getTime());
												$(\'#user_photo > img\').attr(\'src\', data.logo.thumbs.middle+\'?\'+new Date().getTime());
												$(\'img[id^=avatar_\'+id_user+\']\').attr(\'src\', data.logo.thumbs.small+\'?\'+new Date().getTime());
												user_avatar.init_imageareaselect();
												var images = $(\'img\');
												if(data.old_logo && !$.isEmptyObject(data.old_logo)){
													for(var i in data.old_logo.thumbs) if(data.old_logo.thumbs.hasOwnProperty(i)){
														images.filter(\'[src^="\'+data.old_logo.thumbs[i]+\'"]\').attr(\'src\', data.logo.thumbs[i]+\'?\'+new Date().getTime());
													}
												}
												if(data.old_logo_moderation && !$.isEmptyObject(data.old_logo_moderation)){
													for(var i in data.old_logo_moderation.thumbs) if(data.old_logo_moderation.thumbs.hasOwnProperty(i)){
														images.filter(\'[src^="\'+data.old_logo_moderation.thumbs[i]+\'"]\').attr(\'src\', data.logo.thumbs[i]+\'?\'+new Date().getTime());
													}
												}
											}
										},
										cbOnComplete: function(data){
											if(data.errors.length){
												error_object.show_error_block(data.errors, \'error\');
											}
										},
										jqueryFormPluginUrl: "';  echo tpl_function_js(array('file' => 'jquery.form.min.js','return' => 'path'), $this); echo '"
									});
								},
								[\'user_avatar\', \'avatar_uploader\'],
								{async: false}
							);
						});
					</script>'; ?>

				<?php else: ?>
					<div class="center lh0">
						<img src="<?php echo $this->_vars['avatar_data']['user']['media']['user_logo']['thumbs']['grand']; ?>
">
					</div>
				<?php endif; ?>
				<div class="mt20">
					<?php echo tpl_function_block(array('name' => comments_form,'module' => comments,'gid' => user_avatar,'id_obj' => $this->_vars['avatar_data']['user']['id'],'hidden' => 0,'count' => $this->_vars['avatar_data']['user']['logo_comments_count']), $this);?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>