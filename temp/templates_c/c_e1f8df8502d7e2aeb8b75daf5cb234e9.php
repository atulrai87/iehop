<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.seolink.php'); $this->register_function("seolink", "tpl_function_seolink");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-10 02:04:39 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="tabs tab-size-15 noPrint">
	<ul>
		<li<?php if ($this->_vars['write_message']): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo $this->_vars['site_url']; ?>
mailbox/write"><?php echo l('write_message', 'mailbox', '', 'text', array()); ?></a></li>
		<li<?php if ($this->_vars['folder'] == 'inbox'): ?> class="active"<?php endif; ?> id="inbox"><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'mailbox','method' => 'inbox'), $this);?>"><?php echo l('inbox', 'mailbox', '', 'text', array()); ?> <span class="ind_inbox_new_message <?php if (! $this->_vars['inbox_new_message']): ?>hide<?php endif; ?>">(<?php echo $this->_vars['inbox_new_message']; ?>
)</span></a></li>
		<li<?php if ($this->_vars['folder'] == 'outbox'): ?> class="active"<?php endif; ?> id="outbox"><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'mailbox','method' => 'outbox'), $this);?>"><?php echo l('outbox', 'mailbox', '', 'text', array()); ?></a></li>
		<li<?php if ($this->_vars['folder'] == 'drafts'): ?> class="active"<?php endif; ?> id="drafts"><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'mailbox','method' => 'drafts'), $this);?>"><?php echo l('drafts', 'mailbox', '', 'text', array()); ?></a></li>
		<li<?php if ($this->_vars['folder'] == 'trash'): ?> class="active"<?php endif; ?> id="trash"><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'mailbox','method' => 'trash'), $this);?>"><?php echo l('trash', 'mailbox', '', 'text', array()); ?> <?php if ($this->_vars['trash_new_message'] > 0): ?>(<?php echo $this->_vars['trash_new_message']; ?>
)<?php endif; ?></a></li>
		<li<?php if ($this->_vars['folder'] == 'spam'): ?> class="active"<?php endif; ?> id="spam"><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'mailbox','method' => 'spam'), $this);?>"><?php echo l('spam', 'mailbox', '', 'text', array()); ?> <?php if ($this->_vars['spam_new_message'] > 0): ?>(<?php echo $this->_vars['spam_new_message']; ?>
)<?php endif; ?></a></li>
	</ul>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
