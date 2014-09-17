{if $template eq 'link'}
{if !$is_send}<a href="{$site_url}spam/mark_as_spam" data-id="{$object_id}" data-type="{$type.gid}" id="mark-as-span-{$rand}" class="link-r-margin">{l i='btn_mark_as_spam' gid='spam' type='button'}</a>{/if}
{elseif $template eq 'minibutton'}
<a {if !$is_send}href="{$site_url}spam/mark_as_spam"{/if} data-id="{$object_id}" data-type="{$type.gid}" id="mark-as-span-{$rand}" class="link-r-margin" title="{l i='btn_mark_as_spam' gid='spam' type='button'}"><ins class="icon-flag-alt pr5 {if $is_send}g{/if}"></ins></a>
{elseif $template eq 'whitebutton'}
<a {if !$is_send}href="{$site_url}spam/mark_as_spam"{/if} data-id="{$object_id}" data-type="{$type.gid}" id="mark-as-span-{$rand}" class="link-r-margin" title="{l i='btn_mark_as_spam' gid='spam' type='button'}"><ins class="icon-flag-alt icon-heart-empty edge w {if $is_send}g{/if}" id="{$type.gid}_{$object_id}"></ins></a>
{else}
<a {if !$is_send}href="{$site_url}spam/mark_as_spam"{/if} data-id="{$object_id}" data-type="{$type.gid}" id="mark-as-span-{$rand}" class="fright link-r-margin" title="{l i='btn_mark_as_spam' gid='spam' type='button'}"><ins class="icon-flag-alt icon-big edge hover {if $is_send}g{/if}"></ins></a>
{/if}
<script>{literal}
loadScripts(
	"{/literal}{js module=spam file='spam.js' return='path'}{literal}",
	function(){
		spam = new Spam({
			siteUrl: '{/literal}{$site_root}{literal}', 
			use_form: {/literal}{if $type.form_type!='checkbox'}true{else}false{/if}{literal},
			{/literal}{if $is_spam_owner}isOwner: true,{/if}{literal}
			is_send: '{/literal}{$is_send}{literal}', 
			error_is_send: '{/literal}{if $is_guest}ajax_login_link{else}{l i="error_is_send_"+$type.gid gid="spam"}{/if}{literal}', 
			mark_as_spam_btn: '{/literal}mark-as-span-{$rand}{literal}',
		});		
	},
	''
);
{/literal}</script>
