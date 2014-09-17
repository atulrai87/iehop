<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 00:00:00 CDT */ ?>

<div class="menu-level2">
	<ul>
	<?php if (is_array($this->_vars['menu']) and count((array)$this->_vars['menu'])): foreach ((array)$this->_vars['menu'] as $this->_vars['item']): ?>
		<li<?php if ($this->_vars['item']['active'] == 1): ?> class="active"<?php endif; ?>>
			<div class="l">
				<a href="<?php echo $this->_vars['item']['link']; ?>
"><?php echo $this->_vars['item']['value']; ?>

					<?php if ($this->_vars['item']['indicator']): ?><span class="num"><?php echo $this->_vars['item']['indicator']; ?>
</span><?php endif; ?>
				</a>
			</div>
		</li>
	<?php endforeach; endif; ?>
	</ul>
	&nbsp;
</div>