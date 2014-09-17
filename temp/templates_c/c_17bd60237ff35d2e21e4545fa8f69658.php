<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-12 00:35:59 CDT */ ?>

			</div>
		</div>
		<?php echo tpl_function_helper(array('func_name' => show_banner_place,'module' => banners,'func_param' => 'bottom-banner'), $this);?>
		<div class="footer">
			<div class="content">
				<?php echo tpl_function_menu(array('gid' => 'user_footer_menu'), $this);?>
				<div class="copyright">&copy;&nbsp;2014&nbsp;<a href="http://www.ieHop.com">ieHop.com</a> Owned by <a href="http://www.ieHop.com/">Shibha Corporation, Torrance, CA, USA, Patent Pending</a></div>
			</div>
		</div>
		<?php echo tpl_function_helper(array('func_name' => lang_editor,'module' => languages), $this);?>
		<?php echo tpl_function_helper(array('func_name' => seo_traker,'helper_name' => seo_module_helper,'module' => seo,'func_param' => 'footer'), $this); if (! $this->_vars['is_pjax']): ?>
</div>
</body>
</html>
<?php endif; ?>
