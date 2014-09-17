<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-08 13:44:43 CDT */ ?>

<div class="fright">
	<ul>
		<?php if ($this->_vars['action'] == 'banners'): ?>
				<li>
			<s id="add_banner" class="a btn-link">
				<a href="<?php echo $this->_vars['site_url']; ?>
banners/edit">
					<i class="icon-file-text-alt icon-big edge hover">
						<i class="icon-mini-stack icon-plus bottomright"></i>
					</i>
				</a>
				<i><a href="<?php echo $this->_vars['site_url']; ?>
banners/edit"><?php echo l('link_add_banner', 'banners', '', 'text', array()); ?></a></i>
			</s>
		</li>
				<?php endif; ?>
	</ul>
</div>

<div class="tabs tab-size-15 noPrint">
	<ul>
		
			<li<?php if ($this->_vars['action'] == 'services'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account'), $this);?>services/"><?php echo l('header_services', 'users', '', 'text', array()); ?></a></li>
			<li<?php if ($this->_vars['action'] == 'my_services'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account'), $this);?>my_services/"><?php echo l('header_my_services', 'users', '', 'text', array()); ?></a></li>
		
					<li<?php if ($this->_vars['action'] == 'update'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account'), $this);?>update/"><?php echo l('header_account_update', 'users', '', 'text', array()); ?></a></li>
							<li<?php if ($this->_vars['action'] == 'payments_history'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account'), $this);?>payments_history/"><?php echo l('header_my_payments_statistic', 'payments', '', 'text', array()); ?></a></li>
							<li<?php if ($this->_vars['action'] == 'banners'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account'), $this);?>banners/my"><?php echo l('header_my_banners', 'banners', '', 'text', array()); ?></a></li>
			</ul>
</div>

