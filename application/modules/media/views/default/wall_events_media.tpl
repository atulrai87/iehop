{strip}
<div>{$event.date_update|date_format:$date_format}{if $event.id_poster != $user_id}<span class="ml10">{block name='mark_as_spam_block' module='spam' object_id=$event.id type_gid='wall_events_object' template='minibutton'}</span>{/if}</div>
<div class="ptb5">{if $event.event_type_gid == 'image_upload'}{l i='uploads_new_photos' gid='media'}{elseif $event.event_type_gid == 'video_upload'}{l i='uploads_new_videos' gid='media'}{/if} ({$event.media_count_all})</div>
<div class="user-gallery medium">
	{foreach from=$event.data item=edata key=key}
		<div class="item">
			<div class="user">
				<div class="photo">
					<span class="a" data-click="view-media" data-user-id="{$edata.id_owner}" data-id-media="{$edata.id}">
						{if $edata.video_content}<div class="overlay-icon pointer"><i class="icon-play-sign w icon-4x opacity60"></i></div>{/if}
						<img src="{if $edata.media}{$edata.media.mediafile.thumbs.big}{elseif $edata.video_content}{$edata.video_content.thumbs.big}{/if}" />
					</span>
					<div class="info">
						<div class="info-icons">
							{if $edata.id_parent && (($edata.media && !$edata.mediafile) || ($edata.video_content && !$edata.media_video))}<p>{l i='media_deleted_by_owner' gid='media'}</p>{/if}
							<div>
								<i class="icon-eye-open edge w">&nbsp;</i><span class="mr10">{$edata.views}</span>
								<span class="mr10">{block name=like_block module=likes gid='media'.$edata.id type=button btn_class="edge w"}</span>
								{if $edata.is_adult}<i class="icon-female edge w">&nbsp;</i><span>18+</span>{/if}
								{if $edata.id_user!=$user_id}<span style="float:right">{block name='mark_as_spam_block' module='spam' object_id=$edata.id type_gid='media_object' template='whitebutton'}</span>{/if}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	{/foreach}
</div>
{if $event.media_count_more}
	<div class="fright righted"><a class="hover-icon" href="{if $user_id == $event.id_poster}{seolink module='users' method='profile'}gallery{else}{seolink module='users' method='view' data=$users[$event.id_poster]}/gallery{/if}"><i class="icon-arrow-right edge hover"></i><span class="ml5">{l i='show_more' gid='media'}&nbsp;({$event.media_count_more})</span></a></div>
{/if}
{/strip}

<script>{literal}
	$(function(){
		if(!window.wall_mediagallery){
			loadScripts(
				"{/literal}{js file='media.js' module=media return='path'}{literal}", 
				function(){
					wall_mediagallery = new media({
						siteUrl: site_url,
						gallery_name: 'wall_mediagallery',
						galleryContentPage: 1,
						idUser: 0,
						all_loaded: 1,
						lang_delete_confirm: "{/literal}{l i='delete_confirm' gid='media'}{literal}",
						galleryContentDiv: 'wall_events',
						post_data: {filter_duplicate: 1},
						load_on_scroll: false,
						sorterId: '',
						direction: 'asc'
					});
				},
				'wall_mediagallery', 
				{async: false}
			);
		}
	});
</script>{/literal}
