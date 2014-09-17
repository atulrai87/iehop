<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 01:16:35 CDT */ ?>

<div class="pages">
	<?php if ($this->_vars['page_data']['total_rows']): ?>
		<span class="total"><?php echo l('showing', 'start', '', 'text', array()); ?> <?php echo $this->_vars['page_data']['start_num']; ?>
 - <?php echo $this->_vars['page_data']['end_num']; ?>
 / <?php echo $this->_vars['page_data']['total_rows']; ?>
</span>
	<?php endif; ?>
	&nbsp;<?php echo $this->_vars['page_data']['nav']; ?>

</div>
