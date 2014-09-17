<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-16 22:58:38 India Standard Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<span class="img-menu">
	<span>
		<i class="icon-cog icon-2x"></i>
		<dl id="settings_menu">
			<?php if (is_array($this->_vars['menu']) and count((array)$this->_vars['menu'])): foreach ((array)$this->_vars['menu'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<dt class="righted<?php if ($this->_vars['item']['active'] || $this->_vars['item']['in_chain']): ?> active<?php endif; ?>"><a href="<?php echo $this->_vars['item']['link']; ?>
"><?php echo $this->_vars['item']['value']; ?>
</a></dt>
			<?php endforeach; endif; ?>
		</dl>
	</span>
</span>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>