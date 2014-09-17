<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 20:11:38 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<ul id="ajax_login_link_menu_<?php echo $this->_vars['dynamic_block_auth_links_data']['rand']; ?>
" class="index-menu<?php if ($this->_vars['dynamic_block_auth_links_data']['params']['right_align']): ?> righted<?php endif; ?>">
	<li><a href="<?php echo $this->_vars['site_url']; ?>
users/registration"><?php echo l('link_register', 'users', '', 'text', array()); ?></a></li>
	<li><a href="<?php echo $this->_vars['site_url']; ?>
users/login_form" onclick="return false;" id="ajax_login_link_<?php echo $this->_vars['dynamic_block_auth_links_data']['rand']; ?>
"><?php echo l('header_login', 'users', '', 'text', array()); ?></a></li>
</ul>

<script><?php echo '
	$(function(){
		var rand = \'';  echo $this->_vars['dynamic_block_auth_links_data']['rand'];  echo '\';
		user_ajax_login';  echo $this->_vars['dynamic_block_auth_links_data']['rand'];  echo ' = new loadingContent({
			loadBlockWidth: \'520px\',
			linkerObjID: \'ajax_login_link_menu_\'+rand,
			loadBlockLeftType: \'right\',
			loadBlockTopType: \'bottom\',
			closeBtnClass: \'w\'
		}).update_css_styles({\'z-index\': 2000}).update_css_styles({\'z-index\': 2000}, \'bg\');
		$(\'#ajax_login_link_\'+rand).unbind(\'click\').click(function(){
			$.ajax({
				url: site_url + \'users/ajax_login_form\',
				cache: false,
				success: function(data){
					user_ajax_login';  echo $this->_vars['dynamic_block_auth_links_data']['rand'];  echo '.show_load_block(data);
				}
			});
			return false;
		});
	});
</script>'; ?>

<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>