<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-06 20:47:58 CDT */ ?>

<div id="country_select_<?php echo $this->_vars['country_helper_data']['rand']; ?>
" class="controller-select">
	<span id="country_text_<?php echo $this->_vars['country_helper_data']['rand']; ?>
">
	<?php if ($this->_vars['country_helper_data']['country']):  echo $this->_vars['country_helper_data']['country']['name'];  endif; ?>
	<?php if ($this->_vars['country_helper_data']['region']): ?>, <?php echo $this->_vars['country_helper_data']['region']['name'];  endif; ?>
	<?php if ($this->_vars['country_helper_data']['city']): ?>, <?php echo $this->_vars['country_helper_data']['city']['name'];  endif; ?>
	</span>&nbsp;&nbsp;
	<a href="#" id="country_open_<?php echo $this->_vars['country_helper_data']['rand']; ?>
"><?php echo l('link_select_region', 'countries', '', 'text', array()); ?></a>
	<input type="hidden" name="<?php echo $this->_vars['country_helper_data']['var_country_name']; ?>
" id="country_hidden_<?php echo $this->_vars['country_helper_data']['rand']; ?>
" value="<?php echo $this->_vars['country_helper_data']['country']['code']; ?>
">
	<input type="hidden" name="<?php echo $this->_vars['country_helper_data']['var_region_name']; ?>
" id="region_hidden_<?php echo $this->_vars['country_helper_data']['rand']; ?>
" value="<?php echo $this->_vars['country_helper_data']['region']['id']; ?>
">
	<input type="hidden" name="<?php echo $this->_vars['country_helper_data']['var_city_name']; ?>
" id="city_hidden_<?php echo $this->_vars['country_helper_data']['rand']; ?>
" value="<?php echo $this->_vars['country_helper_data']['city']['id']; ?>
">
</div>

<script type='text/javascript'>
<?php if ($this->_vars['country_helper_data']['var_js_name']): ?>var <?php echo $this->_vars['country_helper_data']['var_js_name']; ?>
;<?php endif;  echo '
$(function(){
	loadScripts(
		"';  echo tpl_function_js(array('module' => countries,'file' => 'country-select.js','return' => 'path'), $this); echo '", 
		function(){
			';  if ($this->_vars['country_helper_data']['var_js_name']):  echo $this->_vars['country_helper_data']['var_js_name']; ?>
 = <?php endif;  echo 'new countrySelect({
				siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
				rand: \'';  echo $this->_vars['country_helper_data']['rand'];  echo '\',
				id_country: \'';  echo $this->_vars['country_helper_data']['country']['code'];  echo '\',
				id_region: \'';  echo $this->_vars['country_helper_data']['region']['id'];  echo '\',
				id_city: \'';  echo $this->_vars['country_helper_data']['city']['id'];  echo '\',
				select_type: \'';  echo $this->_vars['country_helper_data']['select_type'];  echo '\'
			});
		},
		';  if ($this->_vars['country_helper_data']['var_js_name']):  echo $this->_vars['country_helper_data']['var_js_name'];  else: ?>''<?php endif;  echo '
	);
});
'; ?>
</script>