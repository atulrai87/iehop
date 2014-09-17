{strip}
{block name=users_carousel module=users users=$helper_featured_users_data.users scroll=auto class=small thumb_name=middle}
{if $helper_featured_users_data.buy_ability}
	<script>{literal}
		$(function(){
			loadScripts(
				"{/literal}{js file='available_view.js' return='path'}{literal}", 
				function(){
					users_featured_available_view = new available_view({
						siteUrl: site_url,
						checkAvailableAjaxUrl: 'users/ajax_available_users_featured/',
						buyAbilityAjaxUrl: 'users/ajax_activate_users_featured/',
						buyAbilityFormId: 'ability_form',
						buyAbilitySubmitId: 'ability_form_submit',
						formType: 'list',
						success_request: function(message) {
							error_object.show_error_block(message, 'success');
							locationHref('');
						},
						fail_request: function(message) {error_object.show_error_block(message, 'error');},
					});
			
					var rand = '{/literal}{$helper_featured_users_data.rand}{literal}';
					$('#featured_add_'+rand).off('click').on('click', function(e){
						users_featured_available_view.check_available();
						return false;
					});
				},
				['users_featured_available_view'],
				{async: false}
			);
			
		});
	</script>{/literal}
{/if}
{/strip}