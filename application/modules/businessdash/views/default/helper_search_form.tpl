{strip}
<script type="text/javascript">
	selects = [];
	checkboxes = [];
	hlboxes = [];
</script>
<div class="search-box {$form_settings.type}">
	{if $form_settings.show_tabs}
		<div class="tabs tab-size-15">
			<ul>
				{depends module=vacancies}<li{if $form_settings.object eq 'vacancy'} class="active"{/if} id="vacancy-form-tab"><a href="#">{l i='find_job_title' gid='start'}</a></li>{/depends}
				{depends module=resumes}<li{if $form_settings.object eq 'resume'} class="active"{/if} id="resume-form-tab"><a href="#">{l i='find_stuff_title' gid='start'}</a></li>{/depends}
				{depends module=vacancies}{if $form_settings.show_vacancy_button}<li class="no-tab fright post-btn"><a class="plus">+</a>&nbsp;<a href="{$form_settings.post_vacancy_link}">{l i='post_job_title' gid='start'}</a></li>{/if}{/depends}
				{depends module=resumes}{if $form_settings.show_resume_button}<li class="no-tab fright post-btn"><a class="plus">+</a>&nbsp;<a href="{$form_settings.post_resume_link}">{l i='post_resume_title' gid='start'}</a></li>{/if}{/depends}
			</ul>
		</div>
	{/if}
	<div id="search-form-block_{$form_settings.form_id}">{/strip}{$form_block}{strip}</div>
</div>
<script type="text/javascript">{literal}
	$(function(){
		loadScripts(
			[
				"{/literal}{js module=start file='search.js' return='path'}{literal}",
				"{/literal}{js module=start file='selectbox.js' return='path'}{literal}",
				"{/literal}{js module=start file='checkbox.js' return='path'}{literal}",
				"{/literal}{js module=start file='hlbox.js' return='path'}{literal}"
			],
			function(){
				{/literal}{$form_settings.object}{$form_settings.type}{literal} = new search({
					siteUrl: '{/literal}{$site_url}{literal}',
					currentForm: '{/literal}{$form_settings.object}{literal}',
					currentFormType: '{/literal}{$form_settings.type}{literal}',
					hide_popup: {/literal}{if $form_settings.hide_popup}true{else}false{/if}{literal},
					popup_autoposition: {/literal}{if $form_settings.popup_autoposition}true{else}false{/if}{literal}
				});
			},
			'{/literal}{$form_settings.object}{$form_settings.type}{literal}',
			{async: false}
		);
	});
{/literal}</script>
{/strip}