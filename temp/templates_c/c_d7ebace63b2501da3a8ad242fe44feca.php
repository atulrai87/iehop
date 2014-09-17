<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 01:21:49 CDT */ ?>

	<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first" colspan=2><?php echo l('stat_header_users', 'users', '', 'text', array()); ?></th>
	</tr>
	<?php if ($this->_vars['stat_users']['index_method']): ?>
	<tr>
		<td class="first"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/"><?php echo l('stat_header_all', 'users', '', 'text', array()); ?></a></td>
		<td class="w30"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/"><?php echo $this->_vars['stat_users']['all']; ?>
</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/active"><?php echo l('stat_header_active', 'users', '', 'text', array()); ?></a></td>
		<td class="w30"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/active"><?php echo $this->_vars['stat_users']['active']; ?>
</a></td>
	</tr>
	<tr>
		<td class="first"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/not_active"><?php echo l('stat_header_blocked', 'users', '', 'text', array()); ?></a></td>
		<td class="w30"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/not_active"><?php echo $this->_vars['stat_users']['blocked']; ?>
</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><?php echo l('stat_header_unconfirmed', 'users', '', 'text', array()); ?></td>
		<td class="w30"><?php echo $this->_vars['stat_users']['unconfirm']; ?>
</td>
	</tr>
	<?php endif; ?>
	<?php if ($this->_vars['stat_users']['moderation_method']): ?>
	<tr>
		<td class="first"><a href="<?php echo $this->_vars['site_url']; ?>
admin/moderation/index/user_logo"><?php echo l('stat_header_moderation_icons', 'users', '', 'text', array()); ?></a></td>
		<td class="w30"><a href="<?php echo $this->_vars['site_url']; ?>
admin/moderation/index/user_logo"><?php echo $this->_vars['stat_users']['icons']; ?>
</a></td>
	</tr>
	<?php endif; ?>
	</table>
