<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-01 03:24:10 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_field_change', 'field_editor', '', 'text', array());  else:  echo l('admin_header_field_add', 'field_editor', '', 'text', array());  endif; ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_gid', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><?php echo $this->_vars['data']['gid']; ?>
</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_section_data', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><?php echo $this->_vars['section_data']['name']; ?>
</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_field_type', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
			<?php if ($this->_vars['data']['id']): ?>
				<?php if (is_array($this->_vars['field_type_lang']['option']) and count((array)$this->_vars['field_type_lang']['option'])): foreach ((array)$this->_vars['field_type_lang']['option'] as $this->_vars['key'] => $this->_vars['item']):  if ($this->_vars['key'] == $this->_vars['data']['field_type']):  echo $this->_vars['item'];  endif;  endforeach; endif; ?>
			<?php else: ?>
				<select name="field_type">
					<?php if (is_array($this->_vars['field_type_lang']['option']) and count((array)$this->_vars['field_type_lang']['option'])): foreach ((array)$this->_vars['field_type_lang']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['data']['field_type']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
				</select>
			<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_name', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="text" value="<?php if ($this->_vars['validate_lang']):  echo $this->_vars['validate_lang'][$this->_vars['cur_lang']];  else:  echo $this->_vars['data']['name'];  endif; ?>" name="langs[<?php echo $this->_vars['cur_lang']; ?>
]">
				<?php if ($this->_vars['languages_count'] > 1): ?>
				&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs'); return false;"><?php echo l('others_languages', 'field_editor', '', 'text', array()); ?></a><br>
				<div id="name_langs" class="hide p-top2">
					<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['item']):  if ($this->_vars['lang_id'] != $this->_vars['cur_lang']): ?>
					<input type="text" value="<?php if ($this->_vars['validate_lang']):  echo $this->_vars['validate_lang'][$this->_vars['lang_id']];  else:  echo $this->_vars['data']['name'];  endif; ?>" name="langs[<?php echo $this->_vars['lang_id']; ?>
]">&nbsp;|&nbsp;<?php echo $this->_vars['item']['name']; ?>
<br>
					<?php endif;  endforeach; endif; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php if ($this->_vars['type_settings']['fulltext_use']): ?>
		<div class="row">
			<div class="h"><?php echo l('field_fts', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><input type="checkbox" value="1" name="fts" <?php if ($this->_vars['data']['fts']): ?>checked<?php endif; ?>></div>
		</div>
		<?php endif; ?>
		<div id="type_block">
		<?php echo $this->_vars['type_block_content']; ?>

		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/field_editor/fields/<?php echo $this->_vars['type']; ?>
/<?php echo $this->_vars['section']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<script><?php echo '
$(function(){
	$("div.row:odd").addClass("zebra");
});
function showLangs(divId){
	$(\'#\'+divId).slideToggle();
}

'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>