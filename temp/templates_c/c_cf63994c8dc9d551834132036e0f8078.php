<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 05:47:37 CDT */ ?>

<a href="" data-pjax="0" onclick="event.preventDefault(); addImContact();" class="link-r-margin" title="<?php echo l('im_chat', 'im', '', 'text', array()); ?>"><i class="icon-comment icon-big edge hover"></i></a>

<script><?php echo '
	function addImContact(waiting_im_sec){
		waiting_im_sec = waiting_im_sec || 0;
		if(!window.im && waiting_im_sec < 30){
			setTimeout(function(){addImContact(waiting_im_sec);}, 100);
			return;
		}
		var data = ';  echo $this->_vars['im_json_data'];  echo ';
		im.openContact(data.contact_list);
	}
</script>'; ?>
