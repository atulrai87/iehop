<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 14:29:42 CDT */ ?>

<div  class="r">
	<div class="f"><?php echo l('reg_subscriptions', 'subscriptions', '', 'text', array()); ?>: </div>
</div>
<?php if (is_array($this->_vars['subscriptions_list']) and count((array)$this->_vars['subscriptions_list'])): foreach ((array)$this->_vars['subscriptions_list'] as $this->_vars['key'] => $this->_vars['item']): ?>
	<div class="r">
		<div class="v">
			<input <?php if ($this->_vars['item']['subscribed']): ?>checked<?php endif; ?> type="checkbox" name="user_subscriptions_list[]" value="<?php echo $this->_vars['item']['id']; ?>
" id="sub<?php echo $this->_vars['item']['id']; ?>
">
			<label for="sub<?php echo $this->_vars['item']['id']; ?>
"><?php echo l($this->_vars['item']['name_i'], 'subscriptions', '', 'text', array()); ?></label>
		</div>
	</div>
<?php endforeach; endif; ?>
