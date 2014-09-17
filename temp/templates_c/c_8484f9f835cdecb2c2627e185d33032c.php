<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-16 23:03:36 India Standard Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if ($this->_vars['dynamic_block_rich_text_data']['title']): ?><h1><?php echo $this->_vars['dynamic_block_rich_text_data']['title']; ?>
</h1><?php endif; ?>
<div class="wysiwyg-content"><?php echo $this->_vars['dynamic_block_rich_text_data']['html']; ?>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>