<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 01:21:50 CDT */ ?>

	<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first" colspan=2><?php echo l('stat_header_banners', 'banners', '', 'text', array()); ?></th>
	</tr>
	<tr>
		<td class="first"><a href="<?php echo $this->_vars['site_url']; ?>
admin/banners/index/user"><?php echo l('stat_header_moderation_banners', 'banners', '', 'text', array()); ?></a></td>
		<td class="w30"><a href="<?php echo $this->_vars['site_url']; ?>
admin/banners/index/user"><?php echo $this->_vars['stat_banners']['users']; ?>
</a></td>
	</tr>
	</table>

