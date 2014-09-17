			</div>
		</div>
		{helper func_name=show_banner_place module=banners func_param='bottom-banner'}
		<div class="footer">
			<div class="content">
				{menu gid='user_footer_menu'}
				<div class="copyright">&copy;&nbsp;2014&nbsp;<a href="http://www.ieHop.com">ieHop.com</a> Owned by <a href="http://www.ieHop.com/">Shibha Corporation, Torrance, CA, USA, Patent Pending</a></div>
			</div>
		</div>
		{helper func_name=lang_editor module=languages}
		{helper func_name=seo_traker helper_name=seo_module_helper module=seo func_param='footer'}
{if !$is_pjax}
</div>
</body>
</html>
{/if}
