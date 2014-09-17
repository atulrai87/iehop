{strip}
{if $media.upload_gid == 'gallery_video'}
	{if $media.media_video_data.status == 'start'}
		<div class="pos-rel">
			<div class="center lh0 pos-rel">
				<img data-image-src="{$media.video_content.thumbs.great}" src='{$media.video_content.thumbs.great}'>
				<div id="next_media" class="load_content_right media_view_scroller_right"></div>
				<div id="prev_media" class="load_content_left media_view_scroller_left"></div>
			</div>
			<div class="subinfo box-sizing">
				<p>{l i='video_wait_converting' gid='media'}</p>
				{if $media.id_parent || !$is_user_media_owner}
					{if $media.id_parent}
						{if $media.permissions == 0}<p>{l i='permissions_restrict' gid='media'}</p>{/if}
						{if $media.video_content && !$media.media_video}<p>{l i='media_deleted_by_owner' gid='media'}</p>{/if}
					{/if}
					<span>
						{l i='media_owner' gid='media'}:&nbsp;
						{if $media.owner_info.id}
							<a href="{seolink module='users' method='view' data=$media.owner_info}">{$media.owner_info.output_name}</a>
						{else}
							<span>{$media.owner_info.output_name}</span>
						{/if}
					</span>
				{/if}
			</div>
		</div>
	{else}
		<div class="plr50 pos-rel">
			<div style="width: {$media.video_content.width}px;" class="center-block">
				{$media.video_content.embed}
			</div>
			<div id="next_media" class="load_content_right media_view_scroller_right"></div>
			<div id="prev_media" class="load_content_left media_view_scroller_left"></div>
		</div>
		{if !$is_user_media_owner}
			<div>
				{l i='media_owner' gid='media'}:&nbsp;
				{if $media.owner_info.id}
					<a href="{seolink module='users' method='view' data=$media.owner_info}">{$media.owner_info.output_name}</a>
				{else}
					<span>{$media.owner_info.output_name}</span>
				{/if}
			</div>
		{/if}
	{/if}
{elseif $media.upload_gid == 'gallery_image'}
	<div class="pos-rel">
		<div class="center lh0">
			<div class="photo-edit hide" data-area="recrop">
				<div class="source-box">
					<div id="photo_source_recrop_box" class="photo-source-box">
						<img src="{$media.media.mediafile.file_url}" id="photo_source_recrop">
					</div>
					<div class="ptb5 oh tab-submenu" id="recrop_menu">
						<ul class="fleft" id="photo_sizes"></ul>
						<ul class="fright">
							<li><span data-section="view">{l i="view" gid="media"}</span></li>
						</ul>
					</div>
				</div>
			</div>

			<div data-area="view">
				<img data-image-src="{$media.media.mediafile.thumbs.grand}" src="{$media.media.mediafile.thumbs.grand}">
				<div id="next_media" class="load_content_right"></div>
				<div id="prev_media" class="load_content_left"></div>
			</div>
		</div>

		{if $media.id_parent || !$is_user_media_owner}
			<div class="subinfo box-sizing">
				{if $media.id_parent}
					{if $media.permissions == 0}<p>{l i='permissions_restrict' gid='media'}</p>{/if}
					{if $media.media && !$media.mediafile}<p>{l i='media_deleted_by_owner' gid='media'}</p>{/if}
				{/if}
				<span>
					{l i='media_owner' gid='media'}:&nbsp;
					{if $media.owner_info.id}
						<a href="{seolink module='users' method='view' data=$media.owner_info}">{$media.owner_info.output_name}</a>
					{else}
						<span>{$media.owner_info.output_name}</span>
					{/if}
				</span>
			</div>
		{/if}
	</div>
{/if}

<div class="media-preloader hide" id="media_preloader"></div>

<div>
	<div class="ptb5 oh tab-submenu" data-area="view">
		<div class="fleft">
			{$media.date_add|date_format:$date_formats.date_time_format}
			<span class="ml20">
				{block name=like_block module=likes gid='media'.$media.id type=button}
			</span>
			{if !$is_user_media_owner}
				<span class="ml20">
					<span title="{l i='favorites' gid='media'}" class="to_favorites pointer{if $in_favorites} active{/if}" data-id="{$default_album.id}">
						<i class="{if $in_favorites}icon-star{else}icon-star-empty{/if} pr5 status-icon"></i>
					</span>
				</span>
				<span class="ml20">
					{block name='mark_as_spam_block' module='spam' object_id=$media.id type_gid='media_object' template='minibutton'}
				</span>
			{/if}
			
		</div>
		<div class="fright">
			<ul id="media_menu">
				<li class="active"><span data-section="comments">{l i="comments" gid="media"}</span></li>
				{if $is_user_media_owner}<li><span data-section="access">{l i="access" gid="media"}</span></li>{/if}
				<li><span data-section="albums">{l i="albums" gid="media"}</span></li>
				{if $is_user_media_owner && $media.upload_gid == 'gallery_image'}<li><span data-section="recrop">{l i="recrop" gid="media"}</span></li>{/if}
			</ul>
		</div>
	</div>
	{if $is_user_media_owner}
		<div class="contenteditable mt5" title="{l i='edit_description' gid='media' type='button'}">
			<span contenteditable>
				{if $media.description}{$media.description|nl2br}{/if}
			</span>
			<i class="edge icon- hover active"></i>
		</div>
	{else}
		{if $media.description}
			<div>{$media.description|nl2br}</div>
		{/if}
	{/if}
</div>


<div id="media_sections" class="pt10">
	<div data-section="comments">
		{block name=comments_form module=comments gid=media id_obj=$media.id hidden=0 max_height=500}
	</div>

	{if $is_user_media_owner}
		<div data-section="access" class="hide">
			<div class="h2">{l i='field_permitted_for' gid='media'}</div>
			{if !$is_user_media_owner}
				<div class="h3 error-text">{l i='only_owner_access' gid='media'}</div>
			{/if}
			<div class="perm">
				{ld gid='media' i='permissions'}
				<ul>
					{foreach item=item key=key from=$ld_permissions.option}
						<li><label><input type="radio"{if !$is_user_media_owner} disabled{/if} name="permissions" id="permissions" value="{$key}" {if $media.permissions eq $key} checked{/if}> {$item}</label></li>
					{/foreach}
				</ul>
			</div>
			{if $is_user_media_owner}
				<input type="button" class="btn" value="{l i='btn_apply' gid='start'}" name="save_permissions" id="save_permissions">
			{/if}
		</div>
	{/if}

	<div data-section="albums" class="hide"></div>
</div>
{/strip}
