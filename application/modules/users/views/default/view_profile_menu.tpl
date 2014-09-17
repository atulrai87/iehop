<div class="tabs tab-size-15 noPrint">
	<ul>
		{depends module=wall_events}<li{if $profile_section eq 'wall'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='view' data=$seodata}/wall/">{l i='filter_section_wall' gid='users'}</a></li>{/depends}
		<li{if $profile_section eq 'profile'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='view' data=$seodata}/profile/">{l i='filter_section_profile' gid='users'}</a></li>
		{depends module=media}<li{if $profile_section eq 'gallery'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='view' data=$seodata}/gallery/">{l i='filter_section_gallery' gid='users'}</a></li>{/depends}
		<li{if $profile_section eq 'map_view'} class="active"{/if}><a href="javascript:void(0)" onclick="document.location='{seolink module='users' method='view' data=$seodata}/map_view/'">{l i='filter_section_map' gid='users'}</a></li>
	</ul>
</div>