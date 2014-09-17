{include file="header.tpl" load_type='editable|ui'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/properties/job_category_edit/0/{$parent}">{l i='link_add_ds_item' gid='properties'}</a></div></li>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false;">{l i='link_resort_items' gid='properties'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		{foreach item=item key=lang_id from=$langs}
		<li class="{if $lang_id eq $current_lang_id}active{/if}"><a href="{$site_url}admin/properties/job_categories/{$parent}/{$lang_id}/">{$item.name}</a></li>
		{/foreach}
	</ul>
	&nbsp;
</div>
<div class="filter-form" id="ds_items">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
		{foreach item=item key=key from=$job_categories}
		<li id="item_{$item.id}">
			<div class="icons">
				{if $parent eq 0}
					<a href="{$site_url}admin/properties/job_categories/{$item.id}/{$current_lang_id}"><img src="{$site_root}{$img_folder}icon-list.png" width="16" height="16" alt="{l i='edit' gid='start'}" title="{l i='edit' gid='start'}"></a>
				{/if}
				<a href="{$site_url}admin/properties/job_category_edit/{$item.id}/{$parent}/{$current_lang_id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='edit' gid='start'}" title="{l i='edit' gid='start'}"></a>
				<a href='#' onclick="if (confirm('{l i='note_delete_ds_item' gid='properties' type='js'}')) mlSorter.deleteItem('{$item.id}');return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='delete' gid='start'}" title="{l i='delete' gid='start'}"></a>
			</div>
			<div class="editable" id="{$item.id}">{$item.name}</div>
		</li>
		{/foreach}
	</ul>
</div>
<script>
{literal}

var mlSorter;

$(function(){
	mlSorter = new multilevelSorter({
		siteUrl: '{/literal}{$site_url}{literal}', 
		itemsBlockID: 'pages',
		urlSaveSort: 'admin/properties/ajax_job_categories_save_sorter/{/literal}{$parent}{literal}/',
		urlDeleteItem: 'admin/properties/ajax_job_category_item_delete/',
	});
});
{/literal}</script>
{include file="footer.tpl"}
