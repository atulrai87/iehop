<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-16 22:58:38 India Standard Time */ ?>

<ul>
	<li><?php echo l('on_account_header', 'users_payments', '', 'text', array()); ?>: <a href="<?php echo $this->_vars['site_url']; ?>
users/account/update"><?php echo tpl_function_block(array('name' => currency_format_output,'module' => start,'value' => $this->_vars['user_account'],'cur_gid' => $this->_vars['base_currency']['gid']), $this);?></a></li>
</ul>