<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-08 13:44:43 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block">
	<div class="edit_block">
		<form action="<?php echo $this->_vars['site_url']; ?>
users_payments/save_payment/" method="post" id="payment_form" >
			<div class="ptb20">
				<span class="h2"><?php echo l('field_enter_amount', 'users_payments', '', 'text', array()); ?>:</span>
				&nbsp;<input type="text" name="amount" class="short"> <?php echo tpl_function_block(array('name' => currency_format_output,'module' => start,'cur_gid' => $this->_vars['base_currency']['gid']), $this);?>
			</div>
			<?php if ($this->_vars['billing_systems']): ?>
				<div class="r">
				<input type="hidden" value="" name="system_gid" id="system_gid" />
				<?php if (is_array($this->_vars['billing_systems']) and count((array)$this->_vars['billing_systems'])): foreach ((array)$this->_vars['billing_systems'] as $this->_vars['item']): ?>
					<?php if ($this->_vars['item']['logo_url']): ?>
						<input type="image" data-pjax-submit="0" class="mrb10 fltl h100 box-sizing" src="<?php echo $this->_vars['item']['logo_url']; ?>
" onclick="$('#system_gid').val('<?php echo $this->_vars['item']['gid']; ?>
');" title="<?php echo $this->_vars['item']['name']; ?>
" alt="<?php echo $this->_vars['item']['name']; ?>
" />
					<?php else: ?>
						<input type="submit" data-pjax-submit="0" class="mrb10 h100 box-sizing" value="<?php echo $this->_vars['item']['name']; ?>
" onclick="$('#system_gid').val('<?php echo $this->_vars['item']['gid']; ?>
');" />
					<?php endif; ?>
				<?php endforeach; endif; ?>
				</div>
			<?php else: ?>
				<div class="r">
					<i><?php echo l('error_empty_billing_system_list', 'users_payments', '', 'text', array()); ?></i>
				</div>
			<?php endif; ?>
		</form>
	</div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>