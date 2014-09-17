{strip}
{foreach item=item key=key from=$media}
	{if $item.status != 1 || $item.permissions == 0 || ($item.id_parent && (($item.media && !$item.mediafile) || ($item.video_content && !$item.media_video)))}
		{assign var='is_active' value='0'}
	{else}
		{assign var='is_active' value='1'}
	{/if}
	<div class="item">
		<div class="user{if $item.id_user != $item.id_owner} not-owner{/if}">
			<div class="photo{if !$is_active} inactive{/if}">
				<img class="pointer" data-click="view-media" data-id-media="{$item.id}" src="{if $item.media}{$item.media.mediafile.thumbs.big}{elseif $item.video_content}{$item.video_content.thumbs.big}{/if}" />
				{if $item.status == 0}
					<div class="overlay-icon{if $is_active} pointer{/if}" title="{l i='moderation_wait' gid='media'}"><i class="icon-time w icon-4x opacity60"></i></div>
				{elseif $item.status == -1}
					<div class="overlay-icon{if $is_active} pointer{/if}" title="{l i='moderation_decline' gid='media'}"><i class="icon-remove-sign w icon-4x opacity60"></i></div>
				{elseif $item.video_content}
					<div class="overlay-icon {if $is_active} pointer{/if}" data-click="view-media" data-id-media="{$item.id}"><i class="icon-play-sign w icon-4x opacity60"></i></div>
				{/if}
				<a href="{$site_url}media/delete_media/{$item.id}" class="delete-media plr5"><i class="icon-remove w icon-big"></i></a>
				<div class="info">
					<div class="info-icons">
						{if $item.permissions == 0}<p>{l i='permissions_restrict' gid='media'}</p>{/if}
						{if $item.id_parent && (($item.media && !$item.mediafile) || ($item.video_content && !$item.media_video))}<p>{l i='media_deleted_by_owner' gid='media'}</p>{/if}
						<div class="table-div wp100 box-sizing">
							<div>
								<i class="icon-eye-open edge w">&nbsp;</i><span class="mr10">{$item.views}</span>
								<span class="mr10">{block name=like_block module=likes gid='media'.$item.id type=button btn_class="edge w"}</span>
								{if $item.is_adult}<i class="icon-female edge w">&nbsp;</i><span>18+</span>{/if}
								{if $item.id_user != $item.id_owner}<span style="float:right">{block name='mark_as_spam_block' module='spam' object_id=$item.id type_gid='media_object' template='whitebutton'}</span>{/if}
							</div>
							<div class="righted w30">
								<span class="a" data-click="view-media" data-id-media="{$item.id}"><i class="icon-pencil edge w"></i></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{foreachelse}
	<div class="fixmargin">{l i='no_media' gid='media'}</div>
{/foreach}
{/strip}