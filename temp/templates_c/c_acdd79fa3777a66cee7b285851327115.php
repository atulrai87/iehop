<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.block.php'); $this->register_function("block", "tpl_function_block");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 03:05:12 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo '
<style type="text/css" rel="stylesheet">
.ml5{display:none !important;}
.highlight{height:319px; width:265px;}
.carousel-wrapper .jcarousel li{display:none !important;}
</style>
'; ?>

<div class="content-block">
    <div class="content-value fltl w650">
		<div class="view-user"><?php echo tpl_function_block(array('name' => 'wall_block','module' => 'wall_events','place' => 'mybuddies','id_wall' => $this->_vars['user_id']), $this);?></div>
    </div>
    <div class="fltr active_block">
		<?php echo tpl_function_block(array('name' => 'shoutbox_form','module' => 'shoutbox'), $this);?>
        <div id="active_users"><?php echo tpl_function_block(array('name' => 'active_users_block','module' => 'users','count' => 16), $this);?></div>
        <div id="recent_photo"><?php echo tpl_function_block(array('name' => 'recent_media_block','module' => 'media','upload_gid' => 'photo','count' => 16), $this);?></div>
    </div>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

