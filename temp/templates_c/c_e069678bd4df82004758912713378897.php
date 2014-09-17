<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.ld_header.php'); $this->register_function("ld_header", "tpl_function_ld_header");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-22 02:31:33 CDT */ ?>

<?php if (is_array($this->_vars['lds_data']) and count((array)$this->_vars['lds_data'])): foreach ((array)$this->_vars['lds_data'] as $this->_vars['item']): ?>
		<div class="row">
			<div class="h"><?php echo tpl_function_ld_header(array('i' => $this->_vars['item']['ds'],'gid' => $this->_vars['item']['module']), $this);?>: </div>
			<div class="v">
				<select name="lds[<?php echo $this->_vars['item']['ds']; ?>
]">
					<option value="0"></option>
					<?php if (is_array($this->_vars['item']['reference']['option']) and count((array)$this->_vars['item']['reference']['option'])): foreach ((array)$this->_vars['item']['reference']['option'] as $this->_vars['ds_key'] => $this->_vars['ds_item']): ?><option value="<?php echo $this->_vars['ds_key']; ?>
"<?php if ($this->_vars['ds_key'] == $this->_vars['item']['value']): ?> selected<?php endif; ?>><?php echo $this->_vars['ds_item']; ?>
</option><?php endforeach; endif; ?>
				
				</select>
			</div>
		</div>
<?php endforeach; endif; ?>