<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-02 03:32:02 CDT */ ?>

<div class="load_content" id="users_lists_links_request_<?php echo $this->_vars['id_dest_user']; ?>
">
	<h1><?php echo l('header_request', 'users_lists', '', 'text', array()); ?></h1>
	<div class="popup-form">
		<div class="text"><textarea name="comment" class="box-sizing" placeholder="<?php echo l('message', 'users_lists', '', 'text', array()); ?>" maxcount="<?php echo $this->_vars['request_max_chars']; ?>
"></textarea></div>
		<div class="button"><span class="char-counter fleft"><?php echo $this->_vars['request_max_chars']; ?>
</span><input type="button" value="<?php echo l('btn_send', 'start', '', 'text', array()); ?>" /></div>
	</div>
</div>
