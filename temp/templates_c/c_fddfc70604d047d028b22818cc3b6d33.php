<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.start_search_form.php'); $this->register_function("start_search_form", "tpl_function_start_search_form");  require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.seotag.php'); $this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-01 01:56:46 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>

<div class="pos-rel">
	<?php echo tpl_function_start_search_form(array('type' => 'full','show_data' => '1','object' => 'perfect_match'), $this);?>
</div>
<div class="content-block">
	<div id="main_users_results">
		<?php echo $this->_vars['block']; ?>

	</div>

	<script type="text/javascript"><?php echo '
		$(function(){
			loadScripts("';  echo tpl_function_js(array('module' => users,'file' => 'users-list.js','return' => 'path'), $this); echo '",
				function(){
					users_list = new usersList({
						siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
						viewUrl: \'users/perfect_match\',
						viewAjaxUrl: \'users/ajax_perfect_match\',
						listBlockId: \'main_users_results\',
						tIds: [\'pages_block_1\', \'pages_block_2\', \'sorter_block\']
					});
				},
				\'users_list\'
			);
		});
	'; ?>
</script>		
</div>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>