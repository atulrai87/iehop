<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-16 23:03:36 India Standard Time */ ?>

<div class="region-box">
	<div class="button-input-wrapper">
		<button id="country_open_<?php echo $this->_vars['country_helper_data']['rand']; ?>
" name="submit"><i class="icon-caret-down"></i></button>
		<input type="text" class="box-sizing" name="region_name" id="country_text_<?php echo $this->_vars['country_helper_data']['rand']; ?>
" autocomplete="off" value="<?php echo $this->_vars['country_helper_data']['location_text']; ?>
" placeholder="<?php echo $this->_vars['country_helper_data']['placeholder']; ?>
">
	</div>
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
		"';  echo tpl_function_js(array('module' => countries,'file' => 'country-input.js','return' => 'path'), $this); echo '",
		function(){
			var region_';  echo $this->_vars['country_helper_data']['rand'];  echo ' = new countryInput({
				siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
				rand: \'';  echo $this->_vars['country_helper_data']['rand'];  echo '\',
				id_country: \'';  echo $this->_vars['country_helper_data']['country']['code'];  echo '\',
				id_region: \'';  echo $this->_vars['country_helper_data']['region']['id'];  echo '\',
				id_city: \'';  echo $this->_vars['country_helper_data']['city']['id'];  echo '\',
				select_type: \'';  echo $this->_vars['country_helper_data']['select_type'];  echo '\'
			});
		},
		\'region_';  echo $this->_vars['country_helper_data']['rand'];  echo '\'
	);
});
'; ?>
</script>