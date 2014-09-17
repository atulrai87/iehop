<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/modifier.truncate.php'); $this->register_modifier("truncate", "tpl_modifier_truncate");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/block.strip.php'); $this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-10 06:26:13 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if ($this->_vars['albums_list']): ?>
	<select name="album_id" id="album_id">
		<option value="0"><?php echo l('please_select', 'media', '', 'text', array()); ?></option>
		<?php if (is_array($this->_vars['albums_list']) and count((array)$this->_vars['albums_list'])): foreach ((array)$this->_vars['albums_list'] as $this->_vars['key'] => $this->_vars['item']): ?>
			<option value="<?php echo $this->_vars['item']['id']; ?>
"><?php echo $this->_run_modifier($this->_vars['item']['name'], 'truncate', 'plugin', 1, 21, '...', true); ?>
(<?php echo $this->_vars['item'][$this->_vars['albums_count_field']]; ?>
)</option>
		<?php endforeach; endif; ?>
	</select>
<?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>