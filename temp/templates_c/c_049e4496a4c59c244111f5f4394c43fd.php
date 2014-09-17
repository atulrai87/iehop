<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 03:45:40 CDT */ ?>

<div class="tabs tab-size-15 noPrint">
	<ul>
		<li<?php if ($this->_vars['action'] == 'wall'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'profile'), $this);?>wall/"><?php echo l('filter_section_wall', 'users', '', 'text', array()); ?></a></li>		<li<?php if ($this->_vars['action'] == 'view'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'profile'), $this);?>view/"><?php echo l('filter_section_profile', 'users', '', 'text', array()); ?></a></li>
		<li<?php if ($this->_vars['action'] == 'gallery'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'profile'), $this);?>gallery/"><?php echo l('filter_section_gallery', 'users', '', 'text', array()); ?></a></li>				
		<li<?php if ($this->_vars['action'] == 'map_view'): ?> class="active"<?php endif; ?>><a  href="javascript:void(0)" onclick="document.location='<?php echo tpl_function_seolink(array('module' => 'users','method' => 'profile'), $this);?>map_view/'">View Map</a></li>
	</ul>
	<?php if ($this->_vars['action'] == 'wall'): ?>
	<div class="fright">
		<span id="wall_permissions_link" class="fright" title="<?php echo l('header_wall_settings', 'wall_events', '', 'text', array()); ?>" onclick="ajax_permissions_form(site_url+'wall_events/ajax_user_permissions/');">
			<i class="icon-cog icon-big edge hover zoom30"><i class="icon-mini-stack icon-lock"></i></i>
		</span>
	</div>
	<?php endif; ?>
</div>