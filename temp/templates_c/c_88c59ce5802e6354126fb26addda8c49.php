<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/compiler.l.php'); $this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-06 20:47:50 CDT */ ?>

<div class="load_content_controller">
	<h1>
	<?php if ($this->_vars['type'] == 'country'):  echo l('header_country_select', 'countries', '', 'text', array()); ?>
	<?php elseif ($this->_vars['type'] == 'region'):  echo l('header_region_select', 'countries', '', 'text', array()); ?>
	<?php elseif ($this->_vars['type'] == 'city'):  echo l('header_city_select', 'countries', '', 'text', array()); ?>
	<?php endif; ?></h1>
	<div class="inside">
	<?php if ($this->_vars['type'] == 'region'): ?><div class="crumb"><?php echo $this->_vars['data']['country']['name']; ?>
</div>
	<?php elseif ($this->_vars['type'] == 'city'): ?><div class="crumb"><?php echo $this->_vars['data']['country']['name']; ?>
 > <?php echo $this->_vars['data']['region']['name']; ?>
</div>
	<?php endif; ?>
	<?php if ($this->_vars['type'] == 'city'): ?><input type="text" id="city_search" class="controller-search"><?php endif; ?>
		<ul class="controller-items" id="country_select_items"></ul>
	
		<div class="controller-actions">
			<div id="city_page" class="fright"></div>
			<div>
			<?php if ($this->_vars['type'] == 'region'): ?><a href="#" id="country_select_back" class="btn-link"><i class="icon-arrow-left icon-big edge"></i><i><?php echo l('link_select_another_country', 'countries', '', 'text', array()); ?></i></a>
			<?php elseif ($this->_vars['type'] == 'city'): ?><a href="#" id="country_select_back" class="btn-link"><i class="icon-arrow-left icon-big edge"></i><i><?php echo l('link_select_another_region', 'countries', '', 'text', array()); ?></i></a>
			<?php endif; ?>
			</div>
			<div class="fright">
				<a href="javascript:void(0);" id="country_select_close" class="btn-link"><i class="icon-arrow-left icon-big edge"></i><i><?php echo l('link_close', 'countries', '', 'text', array()); ?></i></a>
			</div>
		</div>

	</div>
</div>