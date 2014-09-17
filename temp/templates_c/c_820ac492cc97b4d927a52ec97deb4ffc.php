<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-12 00:35:59 CDT */ ?>

<?php if ($this->_vars['count_active'] > 1): ?>
	<ul>
		<li>
			<select onchange="location.href = '<?php echo $this->_vars['site_url']; ?>
users/change_language/'+this.value">
				<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['item']):  if ($this->_vars['item']['status'] == '1'): ?><option value="<?php echo $this->_vars['item']['id']; ?>
" <?php if ($this->_vars['item']['id'] == $this->_vars['current_lang']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']['name']; ?>
</option><?php endif;  endforeach; endif; ?>
			</select>
		</li>
	</ul>
<?php endif; ?>