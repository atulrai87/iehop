<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 05:47:37 CDT */ ?>

<a id="btn_write_message" href="javascript:void(0);" class="link-r-margin" title="<?php echo l('link_message_send', 'mailbox', '', 'button', array()); ?>"><i class="icon-envelope icon-big edge hover"></i></a>
<script><?php echo '
	var access_available_view;
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '", 
			function(){
				access_available_view = new available_view({
					siteUrl: site_url,
					checkAvailableAjaxUrl: \'mailbox/ajax_available_access_mailbox/\',
					buyAbilityAjaxUrl: \'mailbox/ajax_activate_send_message/\',
					buyAbilityFormId: \'ability_form\',
					buyAbilitySubmitId: \'ability_form_submit\',
					success_request: function(message){mb.write_message(';  echo $this->_vars['user_id'];  echo ', \'short\');},
					fail_request: function(message){error_object.show_error_block(message, \'error\');},
				});
			},
			[\'access_message_available_view\'],
			{async: false}
		);
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'mailbox.js','module' => mailbox,'return' => 'path'), $this); echo '", 
			function(){
				mb = new mailbox({
					siteUrl: site_url,
					contactId: \'';  echo $this->_vars['user_id'];  echo '\',
					accessAvailableView: access_available_view,
					loadContent: true,
				});
			},
			[\'mb\'],
			{async: false}
		);	
	});
'; ?>
</script>
