<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-12 00:35:59 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php echo tpl_function_block(array('name' => users_carousel,'module' => users,'users' => $this->_vars['helper_featured_users_data']['users'],'scroll' => auto,'class' => small,'thumb_name' => middle), $this);?>
<?php if ($this->_vars['helper_featured_users_data']['buy_ability']): ?>
	<script><?php echo '
		$(function(){
			loadScripts(
				"';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '", 
				function(){
					users_featured_available_view = new available_view({
						siteUrl: site_url,
						checkAvailableAjaxUrl: \'users/ajax_available_users_featured/\',
						buyAbilityAjaxUrl: \'users/ajax_activate_users_featured/\',
						buyAbilityFormId: \'ability_form\',
						buyAbilitySubmitId: \'ability_form_submit\',
						formType: \'list\',
						success_request: function(message) {
							error_object.show_error_block(message, \'success\');
							locationHref(\'\');
						},
						fail_request: function(message) {error_object.show_error_block(message, \'error\');},
					});
			
					var rand = \'';  echo $this->_vars['helper_featured_users_data']['rand'];  echo '\';
					$(\'#featured_add_\'+rand).off(\'click\').on(\'click\', function(e){
						users_featured_available_view.check_available();
						return false;
					});
				},
				[\'users_featured_available_view\'],
				{async: false}
			);
			
		});
	</script>'; ?>

<?php endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>