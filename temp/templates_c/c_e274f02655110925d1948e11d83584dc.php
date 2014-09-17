<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.js.php'); $this->register_function("js", "tpl_function_js");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-27 09:04:46 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="content-block">
	<h1><?php echo l('polls_results', 'polls', '', 'text', array()); ?></h1>
	<?php if (is_array($this->_vars['polls']) and count((array)$this->_vars['polls'])): foreach ((array)$this->_vars['polls'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<div id="poll_<?php echo $this->_vars['key']; ?>
" class="poll_question_link">
			<h2>
				<?php if ($this->_vars['language']): ?>
					<?php if ($this->_vars['item']['question'][$this->_vars['language']]): ?>
						<?php echo $this->_vars['item']['question'][$this->_vars['language']]; ?>

					<?php else: ?>
						<?php echo $this->_vars['item']['question']['default']; ?>

					<?php endif; ?>
				<?php else: ?>
					<?php if ($this->_vars['item']['question'][$this->_vars['cur_lang']]): ?>
						<?php echo $this->_vars['item']['question'][$this->_vars['cur_lang']]; ?>

					<?php else: ?>
						<?php echo $this->_vars['item']['question']['default']; ?>

					<?php endif; ?>
				<?php endif; ?>
				<div class="fright">
					<a data-role="expander" class="icon-chevron down icon-big edge hover zoom20"></a>
				</div>
			</h2>
		</div>
		<div class="poll_results_content"></div>
	<?php endforeach; endif; ?>
	<?php echo '
		<script type="text/javascript">
			$(function() {
				loadScripts(
					"';  echo tpl_function_js(array('module' => polls,'file' => 'polls.js','return' => 'path'), $this); echo '", 
					function(){
						if (\'undefined\' != typeof(PollsList)) {
							new PollsList({
								siteUrl: \'';  echo $this->_vars['site_url'];  echo '\'
							});
						}
					}
				);
			});
		</script>
	'; ?>

</div>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>