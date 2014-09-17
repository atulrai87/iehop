<div class="load_content_controller">
	<h1>{l i='header_category_select' gid='properties'}</h1>
	<div class="inside">
		<ul class="controller-items" id="category_select_items"></ul>
	
		<div class="controller-actions">
			<input type="button" id="category_close_link" name="close_btn" value="{l i='btn_close' gid='start'}" class="fleft">
			{if $data.max > 1}
			<a href="#" id="category_select_back" class="btn-link link-margin"><i class="icon-remove icon-big"></i><i>{l i='link_reset_all' gid='properties'}</i></a>
			{/if}
		</div>
		{if $data.max > 1}
		<div class=" line top">{l i='text_availbale_select_categories' gid='properties'} <span id="category_max_left_block"></span></div>
		{/if}
	</div>
</div>