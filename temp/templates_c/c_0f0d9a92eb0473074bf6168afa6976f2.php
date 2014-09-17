<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 05:48:54 CDT */ ?>

<?php $this->assign('thumb_name', $this->_vars['recent_thumb']['name']); ?>
<div class="highlight p10 mb20 fltl">
    <h1>
		<span class="maxw230 ib text-overflow"><?php echo l('header_active_users', 'users', '', 'text', array()); ?></span>
		<span class="fright" id="refresh_active_users">
			<i class="icon-refresh icon-big edge hover"></i>
		</span>
	</h1>
    <?php if (is_array($this->_vars['active_users_block_data']['users']) and count((array)$this->_vars['active_users_block_data']['users'])): foreach ((array)$this->_vars['active_users_block_data']['users'] as $this->_vars['item']): ?>
       <div class="fleft small ml5 photo">
            <a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['item']), $this);?>"><img class="small" src="<?php echo $this->_vars['item']['media']['user_logo']['thumbs'][$this->_vars['thumb_name']]; ?>
" width="<?php echo $this->_vars['recent_thumb']['width']; ?>
" /></a>
		</div>
    <?php endforeach; endif; ?>
</div>
<script><?php echo '
	$(function(){
		$(\'#refresh_active_users\').unbind(\'click\').click(function(){
			$.ajax({
				url: site_url + \'users/ajax_refresh_active_users\',
				type: \'POST\',
				data: {count: 16},
				dataType : "html",
				cache: false,
				success: function(data){
					$(\'#active_users\').html(data);
				}
			});
			return false;
		});
	});
</script>'; ?>
