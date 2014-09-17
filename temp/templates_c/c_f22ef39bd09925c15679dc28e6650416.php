<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.math.php'); $this->register_function("math", "tpl_function_math");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.helper.php'); $this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-04 00:36:33 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => get_admin_level1_menu,'helper_name' => menu,'func_param' => 'admin_countries_menu'), $this);?>
<div class="actions">&nbsp;</div>
<?php echo $this->_vars['regions_list_length']; ?>


<div class="filter-form">
	<div class="install_main_window">
		<div class="pad">
			<h3><?php echo $this->_vars['country']['name']; ?>
: <?php echo l('cities_install_progress', 'countries', '', 'text', array()); ?></h3>
			<div class="bar-level1" id="overall_bar"><div class="bar">0%</div></div>
			<br>
			<div id="region_reload">
				<div class="region-block">
				<?php echo tpl_function_counter(array('start' => 0,'print' => false,'assign' => counter), $this);?>
				<?php echo tpl_function_math(array('assign' => 'divby','equation' => "ceil(x/4)",'x' => $this->_run_modifier($this->_vars['regions_list'], 'count', 'PHP', 0)), $this);?>
				<?php if (is_array($this->_vars['regions_list']) and count((array)$this->_vars['regions_list'])): foreach ((array)$this->_vars['regions_list'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<?php if ($this->_vars['counter'] > 0):  if (!($this->_vars['counter'] % $this->_vars['divby'])): ?></div><div class="region-block"><?php endif;  endif; ?>
				<span id="region_<?php echo $this->_vars['counter']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</span><br>
				<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
				<?php endforeach; endif; ?>
				</div>
				<div class="clr"></div>
			</div>
		
		</div>

	</div>
</div>
<div class="btn hide" id="back_btn"><div class="l"><a href="<?php echo $this->_vars['back_link']; ?>
"><?php echo l('btn_back', 'start', '', 'text', array()); ?></a></div></div>

<div class="clr"></div>

<?php echo '
<script>
var country_install;
$(function(){
	country_install=new adminCountries({
		siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
		regions: [';  if (is_array($this->_vars['regions']) and count((array)$this->_vars['regions'])): foreach ((array)$this->_vars['regions'] as $this->_vars['key'] => $this->_vars['item']):  if ($this->_vars['key']): ?>, <?php endif; ?>'<?php echo $this->_vars['item']; ?>
'<?php endforeach; endif;  echo '],
		country_code: \'';  echo $this->_vars['country']['code'];  echo '\'
	});

	country_install.start_city_install();
});
</script>
'; ?>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>