<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.json_encode.php'); $this->register_function("json_encode", "tpl_function_json_encode");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.escape.php'); $this->register_modifier("escape", "tpl_modifier_escape");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-10 02:30:44 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui|editable'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_seo_settings_editing', 'seo', '', 'text', array()); ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_default_link', 'seo', '', 'text', array()); ?>: </div>
			<div class="v"><?php echo $this->_vars['site_url'];  echo $this->_vars['module_gid']; ?>
/<?php echo $this->_vars['method'];  if (is_array($this->_vars['user_settings']['url_vars']) and count((array)$this->_vars['user_settings']['url_vars'])): foreach ((array)$this->_vars['user_settings']['url_vars'] as $this->_vars['key'] => $this->_vars['item']): ?>/[<?php echo $this->_vars['key']; ?>
]<?php endforeach; endif; ?></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('url_manager', 'seo', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="hidden" name="url_template_data" id="url_data" value="">
				<ul class="url-creator" id="url_block"></ul>
				<div class="clr"></div>
				<div id="url_text_edit" class="url-form hide">
					<b><?php echo l('action_edit_url_block', 'seo', '', 'text', array()); ?>:</b>
					<div class="row zebra">
						<div class="h"><?php echo l('field_url_block_type', 'seo', '', 'text', array()); ?>:</div>
						<div class="v"><?php echo l('field_url_block_type_text', 'seo', '', 'text', array()); ?></div>
					</div>
					<div class="row zebra">
						<div class="h"><?php echo l('field_url_block_value', 'seo', '', 'text', array()); ?></div>
						<div class="v"><input type="text" value="" id="text_block_value_edit"></div>
					</div>
					<div class="row zebra">
						<div class="h">&nbsp;</div>
						<div class="v">
							<input type="button" id="text_block_save" name="add-block" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>">
							<input type="button" id="text_block_delete" name="delete-block" value="<?php echo l('btn_delete', 'start', '', 'button', array()); ?>">
						</div>
					</div>
				</div>
				<br>
				<div id="url_tpl_edit" class="url-form hide">
					<b><?php echo l('action_edit_url_block', 'seo', '', 'text', array()); ?>:</b>
					<div class="row zebra">
						<div class="h"><?php echo l('field_url_block_type', 'seo', '', 'text', array()); ?>:</div>
						<div class="v"><?php echo l('field_url_block_type_tpl', 'seo', '', 'text', array()); ?></div>
					</div>
					<div class="row zebra">
						<div class="h"><?php echo l('field_url_block_replacement', 'seo', '', 'text', array()); ?>:</div>
						<div class="v" id="tpl_block_var_name">&nbsp;</div>
					</div>
					<div class="row zebra">
						<div class="h"><?php echo l('field_url_block_default', 'seo', '', 'text', array()); ?>:</div>
						<div class="v"><input type="text" value="" id="tpl_block_var_default"></div>
					</div>
					<div class="row zebra">
						<div class="h">&nbsp;</div>
						<div class="v">
							<input type="button" id="tpl_block_save" name="add-block" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>">
							<input type="button" id="tpl_block_delete" name="delete-block" value="<?php echo l('btn_delete', 'start', '', 'button', array()); ?>">
						</div>
					</div>
				</div>

				<div class="url-form">
					<div class="row">
						<div class="h"><?php echo l('link_add_url_block_text', 'seo', '', 'text', array()); ?>:</div>
						<div class="v"><input type="text" id="text_block_value"> <input type="button" name="add-block" value="<?php echo l('btn_add', 'start', '', 'button', array()); ?>"  onclick="javascript: urlCreator.save_block('', 'text', $('#text_block_value').val(), '', '', '');"></div>
					</div>
				</div>
				<?php if ($this->_vars['default_settings']['url_vars']): ?>
				<div class="url-form">
					<div class="row">
						<div class="h"><?php echo l('link_add_url_block_tpl', 'seo', '', 'text', array()); ?>:</div>
						<div class="v">
						<?php if (is_array($this->_vars['default_settings']['url_vars']) and count((array)$this->_vars['default_settings']['url_vars'])): foreach ((array)$this->_vars['default_settings']['url_vars'] as $this->_vars['key'] => $this->_vars['item']):  if ($this->_vars['defcounter'] > 0): ?><br><span class="or"><?php echo l('link_or', 'seo', '', 'text', array()); ?></span><br><?php endif; ?>
						<?php echo tpl_function_counter(array('id' => 'mandatory','print' => false,'assign' => defcounter), $this);?>
						[<select id="var-<?php echo $this->_vars['key']; ?>
"><?php if (is_array($this->_vars['item']) and count((array)$this->_vars['item'])): foreach ((array)$this->_vars['item'] as $this->_vars['varname'] => $this->_vars['type']): ?><option value="<?php echo $this->_vars['type']; ?>
"><?php echo $this->_vars['varname']; ?>
</option><?php endforeach; endif; ?></select>|<input type="text" class="short" id="vardef-<?php echo $this->_vars['key']; ?>
">]
						<input type="button" name="add-block" value="<?php echo l('btn_add', 'start', '', 'button', array()); ?>" onclick="javascript: urlCreator.save_block('', 'tpl', $('#vardef-<?php echo $this->_vars['key']; ?>
').val(), '<?php echo $this->_vars['defcounter']; ?>
', $('#var-<?php echo $this->_vars['key']; ?>
').val(), $('#var-<?php echo $this->_vars['key']; ?>
 > option:selected').text());">
						<?php endforeach; endif; ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<?php if ($this->_vars['default_settings']['optional']): ?>
				<div class="url-form">
					<div class="row">
						<div class="h"><?php echo l('link_add_url_block_opt', 'seo', '', 'text', array()); ?>:</div>
						<div class="v">
						<?php if (is_array($this->_vars['default_settings']['optional']) and count((array)$this->_vars['default_settings']['optional'])): foreach ((array)$this->_vars['default_settings']['optional'] as $this->_vars['key'] => $this->_vars['item']):  if ($this->_vars['optcounter'] > 0): ?><br><span class="or"><?php echo l('link_or', 'seo', '', 'text', array()); ?></span><br><?php endif; ?>
						<?php echo tpl_function_counter(array('id' => 'optional','print' => false,'assign' => optcounter), $this);?>
						[<select id="opt-<?php echo $this->_vars['key']; ?>
"><?php if (is_array($this->_vars['item']) and count((array)$this->_vars['item'])): foreach ((array)$this->_vars['item'] as $this->_vars['varname'] => $this->_vars['type']): ?><option value="<?php echo $this->_vars['type']; ?>
"><?php echo $this->_vars['varname']; ?>
</option><?php endforeach; endif; ?></select>|<input type="text" class="short" id="optdef-<?php echo $this->_vars['key']; ?>
">]
						<input type="button" name="add-block" value="<?php echo l('btn_add', 'start', '', 'button', array()); ?>" onclick="javascript: urlCreator.save_block('', 'opt', $('#optdef-<?php echo $this->_vars['key']; ?>
').val(), '<?php echo $this->_vars['optcounter']; ?>
', $('#opt-<?php echo $this->_vars['key']; ?>
').val(), $('#opt-<?php echo $this->_vars['key']; ?>
 > option:selected').text());">
						<?php endforeach; endif; ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<div class="clr"></div>
		</div>

		<div class="row">
			<div class="h">&nbsp;</div>
			<div class="v"><?php echo l('url_manager_text', 'seo', '', 'text', array()); ?></div>
		</div>

	</div>
	<br>

	<div class="edit-form n150">
		<?php if ($this->_vars['default_settings']['templates']): ?>
		<div class="row zebra">
			<div class="h"><?php echo l('field_templates', 'seo', '', 'text', array()); ?>: </div>
			<div class="v">
				<?php if (is_array($this->_vars['default_settings']['templates']) and count((array)$this->_vars['default_settings']['templates'])): foreach ((array)$this->_vars['default_settings']['templates'] as $this->_vars['item']): ?><b>[<?php echo $this->_vars['item']; ?>
<span class="hide_text">|<?php echo l('default_value', 'seo', '', 'text', array()); ?></span>]</b> <?php endforeach; endif; ?><br><br><i><?php echo l('field_templates_text', 'seo', '', 'text', array()); ?></i>
			</div>
		</div>
		<br>
		<?php endif; ?>
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_title_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_title', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'meta_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
:</div>
			<div class="v"><input type="text" value="<?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['title'], 'escape', 'plugin', 1); ?>
" name="title[<?php echo $this->_vars['key']; ?>
]" class="long"></div>
		</div>
		<?php endforeach; endif; ?>
		<br>

		<div class="row zebra">
			<div class="h"><b><?php echo l('field_keyword_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_keyword', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'meta_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<div class="v"><textarea name="keyword[<?php echo $this->_vars['key']; ?>
]" rows="5" cols="80"><?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['keyword'], 'escape', 'plugin', 1); ?>
</textarea></div>
		</div>
		<?php endforeach; endif; ?>
		<br>
	
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_description_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_description', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'meta_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo l('field_description', 'seo', '', 'text', array()); ?>(<?php echo $this->_vars['item']['name']; ?>
): </div>
			<div class="v"><textarea name="description[<?php echo $this->_vars['key']; ?>
]" rows="5" cols="80"><?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['description'], 'escape', 'plugin', 1); ?>
</textarea></div>
		</div>
		<?php endforeach; endif; ?>
		<br>
	
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_header_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_header', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'meta_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<div class="v"><input type="text" name="header[<?php echo $this->_vars['key']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['header'], 'escape', 'plugin', 1); ?>
" class="long"></div>
		</div>
		<?php endforeach; endif; ?>
		<br>
		
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_noindex_use', 'seo', '', 'text', array()); ?></b>: </div>
			<div class="v"><input type="checkbox" value="1" name="noindex" <?php if ($this->_vars['user_settings']['noindex']): ?>checked<?php endif; ?> id="default_noindex" class="checked-tags" checked-param="noindex"></div>
		</div>
		<br>	
		
		<div class="row zebra">
			<div class="h"><b><?php echo l('header_section_og', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_og', 'seo', '', 'text', array()); ?></div>
		</div>
		<br>
		
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_og_title_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_og_title', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'og_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<div class="v"><input type="text" name="og_title[<?php echo $this->_vars['key']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['og_title'], 'escape', 'plugin', 1); ?>
" class="long"></div>
		</div>
		<?php endforeach; endif; ?>
		<br>
			
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_og_type_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_og_type', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'og_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
:</div>
			<div class="v"><input type="text" name="og_type[<?php echo $this->_vars['key']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['og_type'], 'escape', 'plugin', 1); ?>
" class="long"></div>
		</div>
		<?php endforeach; endif; ?>
		<br>
			
		<div class="row zebra">
			<div class="h"><b><?php echo l('field_og_description_default', 'seo', '', 'text', array()); ?></b></div>
			<div class="v"><?php echo l('text_help_og_description', 'seo', '', 'text', array()); ?></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php $this->assign('section_gid', 'og_'.$this->_vars['key']); ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
:</div>
			<div class="v"><textarea name="og_description[<?php echo $this->_vars['key']; ?>
]" rows="5" cols="80"><?php echo $this->_run_modifier($this->_vars['user_settings'][$this->_vars['section_gid']]['og_description'], 'escape', 'plugin', 1); ?>
</textarea></div>
		</div>
		<?php endforeach; endif; ?>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/seo/listing/<?php echo $this->_vars['module_gid']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<script>
<?php echo '
	var urlCreator;
	$(function(){
		urlCreator = new seoUrlCreator({
			siteUrl: \'';  echo $this->_vars['site_root'];  echo '\', 
			data: ';  echo tpl_function_json_encode(array('data' => $this->_vars['user_settings']['url_template_data']), $this); echo '
		});
	});
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
