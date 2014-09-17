<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 01:21:50 CDT */ ?>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first" colspan=2><?php echo l('stat_header_spam', 'spam', '', 'text', array()); ?></th>
	</tr>
	<?php if ($this->_vars['stat_spam']['index_method']): ?>
	<?php if (is_array($this->_vars['stat_spam']['types']) and count((array)$this->_vars['stat_spam']['types'])): foreach ((array)$this->_vars['stat_spam']['types'] as $this->_vars['item']): ?>
	<?php $this->assign('stat_header', "stat_header_spam_" . $this->_vars['item']['gid'] . ""); ?>
	<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
	<tr <?php if (!($this->_vars['counter'] % 2)): ?>class="zebra"<?php endif; ?>>
		<td class="first"><a href="<?php echo $this->_vars['site_url']; ?>
admin/spam/index/<?php echo $this->_vars['item']['gid']; ?>
"><?php echo l($this->_vars['stat_header'], 'spam', '', 'text', array()); ?></a></td>
		<td class="w30"><a href="<?php echo $this->_vars['site_url']; ?>
admin/spam/index/<?php echo $this->_vars['item']['gid']; ?>
"><?php echo $this->_vars['item']['obj_count']; ?>
</a></td>
	</tr>
	<?php endforeach; endif; ?>
	<?php endif; ?>
</table>

