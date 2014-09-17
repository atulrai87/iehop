<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-12 00:16:58 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<link rel="stylesheet" href="<?php echo site_url();?>application/modules/start/views/default/cssnew/style.css" type="text/css">
<?php echo '
<style type="text/css" rel="stylesheet">
.mb20{display:none !important;}
.main{min-height:inherit;}
</style>
'; ?>

<!-- Adding Audio File-->
<?php echo '
<script type="text/javascript">
window.onload=function(){
var sdSound=document.getElementById(\'managemyprofilesound1\');
var sdTxt=document.getElementById(\'managemyprofile1\');
sdTxt.onmouseover=function(){
	sdSound.play();
	return false;
};
};
</script>
'; ?>

<audio id="managemyprofilesound1" controls="controls" preload="auto">
	<source src="<?php echo site_url();?>application/modules/start/views/default/audio/managemyprofile1.mp3"></source>
	<source src="<?php echo site_url();?>application/modules/start/views/default/audio/managemyprofile1.ogg"></source>
	Your Browser does not support please use (Firefox 3.5+, Chrome 3+, Opera 10.5+, Safari 4+, IE 9+) browsers.
</audio>
<!-- End Adding Audio File-->
<div class="services">
  <ul>
    <a href="<?php echo site_url();?>users/profile">
	    <li class="srv1"> <span class="img1"><img src="<?php echo site_url();?>application/modules/start/views/default/img/icon1.png" alt="" /></span>      
		MANAGE MY PROFILE
	    </li>
    </a>
    <a href="<?php echo site_url();?>start/myevents">
	    <li class="srv2"> <span><img src="<?php echo site_url();?>application/modules/start/views/default/img/icon3.png" alt="" /></span>      
		WATCH LIVE      
	    </li>
    </a>
    <a href="<?php echo site_url();?>start/myfamily">
	    <li class="srv3"> <span><img  src="<?php echo site_url();?>application/modules/start/views/default/img/icon4.png" alt="" /></span>      
		MY FAMILY PAGE
	    </li>
    </a>
    <a href="<?php echo site_url();?>start/mybuddies">
	    <li class="srv4"> <span ><img src="<?php echo site_url();?>application/modules/start/views/default/img/icon5.png" alt="" /></span>      
		MY BUDDIES PAGE
	    </li>
    </a>
    <a href="<?php echo site_url();?>start/homepage">
	    <li class="srv5"> <span><img src="<?php echo site_url();?>application/modules/start/views/default/img/icon6.png" alt="" /></span>      
		MY FRIENDS PAGE
	    </li>
    </a>
    <a href="<?php echo site_url();?>users/search/">
	    <li class="srv6"> <span><img  src="<?php echo site_url();?>application/modules/start/views/default/img/icon7.png" alt="" /></span>      
		FIND AN EGUIDE
	    </li>
    </a>
    <a href="<?php echo site_url();?>media/photo/">
	    <li class="srv7"> <span ><img src="<?php echo site_url();?>application/modules/start/views/default/img/icon8.png" alt="" /></span>      
		UPLOAD MEDIA
	    </li>
    </a>
    <a href="<?php echo site_url();?>start/myevents">
	    <li class="srv8"> <span ><img src="<?php echo site_url();?>application/modules/start/views/default/img/icon13.png" alt="" /></span>      
		UPCOMING EVENTS
	    </li>
    </a>
    <a href="<?php echo site_url();?>users/profile/map_view/">
	    <li class="srv9"> <span><img src="<?php echo site_url();?>application/modules/start/views/default/img/icon9.png" alt="" /></span>     
		MANAGE MAPS
	    </li>
    </a>
    <a href="#">
	    <li class="srv10"> <span><img  src="<?php echo site_url();?>application/modules/start/views/default/img/icon10.png" alt="" /></span>     
		POST A TOUR / REQUEST
	    </li>
    </a>
    <a href="<?php echo site_url();?>users/search/">
	    <li class="srv11"> <span ><img src="<?php echo site_url();?>application/modules/start/views/default/img/icon11.png" alt="" /></span>      
		FIND A BUSINESS
	    </li>
    </a>
    <a href="<?php echo site_url();?>start/howtouseiehop">
	    <li class="srv12"> <span><img src="<?php echo site_url();?>application/modules/start/views/default/img/icon12.png" alt="" /></span>      
		HOW TO USE IEHOP
	    </li>
    </a>
  </ul>
</div>
<div class="content-block"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>