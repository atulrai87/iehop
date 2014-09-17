<div class="tabs tab-size-15 noPrint">
	<ul>
		{depends module=wall_events}<li{if $action eq 'wall'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='profile'}wall/">{l i='filter_section_wall' gid='users'}</a></li>{/depends}
		<li{if $action eq 'view'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='profile'}view/">{l i='filter_section_profile' gid='users'}</a></li>
		{depends module=media}<li{if $action eq 'gallery'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='profile'}gallery/">{l i='filter_section_gallery' gid='users'}</a></li>{/depends}				
		<li{if $action eq 'map_view'} class="active"{/if}><a  href="javascript:void(0)" onclick="document.location='{seolink module='users' method='profile'}map_view/'">View Map</a></li>
	</ul>
	{if $action eq 'wall'}
	<div class="fright">
		<span id="wall_permissions_link" class="fright" title="{l i='header_wall_settings' gid='wall_events'}" onclick="ajax_permissions_form(site_url+'wall_events/ajax_user_permissions/');">
			<i class="icon-cog icon-big edge hover zoom30"><i class="icon-mini-stack icon-lock"></i></i>
		</span>
	</div>
	{/if}
</div>