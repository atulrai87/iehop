<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-09 22:56:41 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_area_change', 'dynamic_blocks', '', 'text', array());  else:  echo l('admin_header_area_add', 'dynamic_blocks', '', 'text', array());  endif; ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_gid', 'dynamic_blocks', '', 'text', array()); ?>:&nbsp;* </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['gid']; ?>
" name="gid"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_name', 'dynamic_blocks', '', 'text', array()); ?>:&nbsp;* </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['name']; ?>
" name="name"></div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
