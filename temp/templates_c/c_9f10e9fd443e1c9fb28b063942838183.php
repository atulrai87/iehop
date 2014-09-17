<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 03:45:40 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="view small">
	<div class="image">
		<div id="user_photo" class="pos-rel dimp100 pointer">
			<?php if ($this->_vars['data']['user_logo_moderation']): ?>
				<img src="<?php echo $this->_vars['data']['media']['user_logo_moderation']['thumbs']['middle']; ?>
" alt="<?php echo $this->_run_modifier($this->_vars['data']['nickname'], 'escape', 'plugin', 1); ?>
" />
			<?php else: ?>
				<img src="<?php echo $this->_vars['data']['media']['user_logo']['thumbs']['middle']; ?>
" alt="<?php echo $this->_run_modifier($this->_vars['data']['nickname'], 'escape', 'plugin', 1); ?>
" />
			<?php endif; ?>
		</div>
	</div>
	<div class="info">
		<div class="body">
			<h1>
				<span style="font-size:30px;line-height:28px;"><?php echo $this->_vars['data']['output_name']; ?>
</span>
				<span data-role="online_status" class="fright online-status"><s class="<?php echo $this->_vars['data']['statuses']['online_status_text']; ?>
"><?php echo $this->_vars['data']['statuses']['online_status_lang']; ?>
</s></span>
			</h1>
			<div>
				<div class="fright"><?php echo l('views', 'users', '', 'text', array()); ?>: <?php echo $this->_vars['data']['views_count']; ?>
</div>
				<?php echo l('field_age', 'users', '', 'text', array()); ?>: <?php echo $this->_vars['data']['age'];  if ($this->_vars['data']['location']): ?><i class="delim-alone"></i><span class=""><?php echo $this->_vars['data']['location']; ?>
</span><?php endif; ?>
			</div>
		</div>
		<div class="actions noPrint">
			<a class="link-r-margin" title="<?php echo l('edit_my_profile', 'start', '', 'text', array()); ?>" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'profile'), $this);?>personal"><i class="icon-pencil icon-big edge hover"></i></a>
			<?php if ($this->_vars['data']['services_status']['highlight_in_search']['status']): ?>
				<span class="link-r-margin" title="<?php echo $this->_run_modifier($this->_vars['data']['services_status']['highlight_in_search']['name'], 'escape', 'plugin', 1); ?>
" onclick="highlight_in_search_available_view.check_available();"><i class="icon-sun icon-big edge hover zoom20"></i></span>
			<?php endif; ?>
			<?php if ($this->_vars['data']['services_status']['up_in_search']['status']): ?>
				<span class="link-r-margin" title="<?php echo $this->_run_modifier($this->_vars['data']['services_status']['up_in_search']['name'], 'escape', 'plugin', 1); ?>
" onclick="up_in_search_available_view.check_available();"><i class="icon-level-up icon-big edge hover zoom20"></i></span>
			<?php endif; ?>
			<?php if ($this->_vars['data']['services_status']['hide_on_site']['status']): ?>
				<span class="link-r-margin" title="<?php echo $this->_run_modifier($this->_vars['data']['services_status']['hide_on_site']['name'], 'escape', 'plugin', 1); ?>
" onclick="hide_on_site_available_view.check_available();"><i class="icon-eye-close icon-big edge hover zoom20"></i></span>
			<?php endif; ?>
			<script type='text/javascript'><?php echo '
				$(function(){
					loadScripts(
						["';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '", "';  echo tpl_function_js(array('file' => 'users-avatar.js','module' => 'users','return' => 'path'), $this); echo '"],
						function(){
							highlight_in_search_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: \'users/ajax_available_highlight_in_search/\',
								buyAbilityAjaxUrl: \'users/ajax_activate_highlight_in_search/\',
								buyAbilityFormId: \'ability_form\',
								buyAbilitySubmitId: \'ability_form_submit\',
								success_request: function(message) {error_object.show_error_block(message, \'success\'); locationHref(\'\');},
								fail_request: function(message) {error_object.show_error_block(message, \'error\');},
							});
							up_in_search_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: \'users/ajax_available_up_in_search/\',
								buyAbilityAjaxUrl: \'users/ajax_activate_up_in_search/\',
								buyAbilityFormId: \'ability_form\',
								buyAbilitySubmitId: \'ability_form_submit\',
								success_request: function(message) {error_object.show_error_block(message, \'success\'); locationHref(\'\');},
								fail_request: function(message) {error_object.show_error_block(message, \'error\');},
							});
							hide_on_site_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: \'users/ajax_available_hide_on_site/\',
								buyAbilityAjaxUrl: \'users/ajax_activate_hide_on_site/\',
								buyAbilityFormId: \'ability_form\',
								buyAbilitySubmitId: \'ability_form_submit\',
								success_request: function(message) {error_object.show_error_block(message, \'success\'); locationHref(\'\');},
								fail_request: function(message) {error_object.show_error_block(message, \'error\');},
							});

							user_avatar = new avatar({site_url: site_url});
						},
						[\'highlight_in_search_available_view\', \'up_in_search_available_view\', \'hide_on_site_available_view\', \'user_avatar\'],
						{async: false}
					);
				});
			</script>'; ?>

		</div>
	</div>
</div>

<?php if ($this->_vars['data']['approved'] && $this->_vars['data']['confirm'] && ! $this->_vars['data']['activity'] && $this->_vars['data']['available_activation']['status']): ?>
	<div class="bg-highlight_bg mtb10 p10">
		<script type='text/javascript'><?php echo '
			$(function(){
				loadScripts(
					"';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '", 
					function(){
						activate_available_view = new available_view({
							siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
							checkAvailableAjaxUrl: \'users/ajax_available_user_activate_in_search/\',
							buyAbilityAjaxUrl: \'users/ajax_activate_user_activate_in_search/\',
							buyAbilityFormId: \'ability_form\',
							buyAbilitySubmitId: \'ability_form_submit\',
							success_request: function(message) {error_object.show_error_block(message, \'success\'); locationHref(\'';  echo tpl_function_seolink(array('module' => 'users','method' => 'profile'), $this); echo '\')},
							fail_request: function(message) {error_object.show_error_block(message, \'error\');},
						});
					},
					\'activate_available_view\',
					{async: false}
				);
			});
		</script>'; ?>

		<div>
			<input type="button" class="inline-btn" onclick="activate_available_view.check_available();" value="<?php echo l('link_activate_profile', 'users', '', 'text', array()); ?>" />
			<span class="ml10"><?php echo l('text_activate_profile', 'users', '', 'text', array()); ?></span>
		</div>
	</div>
<?php endif; ?>

<div class="edit_block" id="profile_tab_sections">
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "profile_menu.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	<div class="view-user">
		<?php if (! $this->_vars['action'] || $this->_vars['action'] == 'view'): ?>
			<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "view_my_profile.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
		<?php elseif ($this->_vars['action'] == 'wall'): ?>
			<?php echo tpl_function_block(array('name' => wall_block,'module' => wall_events,'place' => 'myprofile','id_wall' => $this->_vars['user_id']), $this);?>
		<?php elseif ($this->_vars['action'] == 'gallery'): ?>
			<?php echo tpl_function_block(array('name' => media_block,'module' => media,'param' => $this->_vars['subsection'],'page' => '1','location_base_url' => $this->_vars['location_base_url']), $this);?>
		<?php endif; ?>
	</div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>