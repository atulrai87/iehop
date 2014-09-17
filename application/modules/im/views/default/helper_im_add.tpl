<a href="" data-pjax="0" onclick="event.preventDefault(); addImContact();" class="link-r-margin" title="{l i='im_chat' gid='im'}"><i class="icon-comment icon-big edge hover"></i></a>

<script>{literal}
	function addImContact(waiting_im_sec){
		waiting_im_sec = waiting_im_sec || 0;
		if(!window.im && waiting_im_sec < 30){
			setTimeout(function(){addImContact(waiting_im_sec);}, 100);
			return;
		}
		var data = {/literal}{$im_json_data}{literal};
		im.openContact(data.contact_list);
	}
</script>{/literal}