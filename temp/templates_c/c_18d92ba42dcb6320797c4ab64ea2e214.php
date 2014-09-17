<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 01:21:49 CDT */ ?>

	<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first" colspan=2><?php echo l('stat_header_payments', 'payments', '', 'text', array()); ?></th>
	</tr>
	<tr>
		<td class="first"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/"><?php echo l('stat_header_all', 'payments', '', 'text', array()); ?></a></td>
		<td class="w30"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/"><?php echo $this->_vars['stat_payments']['all']; ?>
</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/approve"><?php echo l('stat_header_approved', 'payments', '', 'text', array()); ?></a></td>
		<td class="w30"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/approve"><?php echo $this->_vars['stat_payments']['approve']; ?>
</a></td>
	</tr>
	<tr>
		<td class="first"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/decline"><?php echo l('stat_header_declined', 'payments', '', 'text', array()); ?></a></td>
		<td class="w30"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/decline"><?php echo $this->_vars['stat_payments']['decline']; ?>
</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/wait"><?php echo l('stat_header_awaiting', 'payments', '', 'text', array()); ?></a></td>
		<td class="w30"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/wait"><?php echo $this->_vars['stat_payments']['wait']; ?>
</a></td>
	</tr>
	</table>

