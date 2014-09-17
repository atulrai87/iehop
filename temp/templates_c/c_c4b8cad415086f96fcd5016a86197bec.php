<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 01:13:28 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="content-block">
<div class="view_events">
   	
   	
   	 <?php if (is_array($this->_vars['testlight']) and count((array)$this->_vars['testlight'])): foreach ((array)$this->_vars['testlight'] as $this->_vars['key'] => $this->_vars['item']): ?>
   	 	  
		<li>
		<?php echo $this->_vars['item']['event_name']; ?>

		</li>
		<?php endforeach; endif; ?>
   	 
   	 
    
	
</div>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>