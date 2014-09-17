<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-05 05:47:37 CDT */ ?>

<span id="users_lists_links_<?php echo $this->_vars['id_dest_user']; ?>
">
<?php if (is_array($this->_vars['buttons']) and count((array)$this->_vars['buttons'])): foreach ((array)$this->_vars['buttons'] as $this->_vars['btn_name'] => $this->_vars['btn']): ?>
	<a href="<?php echo tpl_function_seolink(array('module' => 'users_lists','method' => $this->_vars['btn']['method']), $this); echo $this->_vars['id_dest_user']; ?>
" data-pjax="0" method="<?php echo $this->_vars['btn']['method']; ?>
" onclick="event.preventDefault();" class="link-r-margin" title="<?php echo l('action_'.$this->_vars['btn_name'], 'users_lists', '', 'text', array()); ?>"><i class="icon-<?php echo $this->_vars['btn']['icon']; ?>
 icon-big edge hover zoom30"><?php if ($this->_vars['btn']['icon_stack']): ?><i class="icon-mini-stack icon-<?php echo $this->_vars['btn']['icon_stack']; ?>
"></i><?php endif; ?></i></a>
<?php endforeach; endif; ?>
</span>

<script><?php echo '
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('module' => users_lists,'file' => 'lists_links.js','return' => 'path'), $this); echo '", 
			function(){
				var id_dest_user = parseInt(\'';  echo $this->_vars['id_dest_user'];  echo '\');
				lists_links = new ListsLinks({
					siteUrl: site_url,
					id_dest_user: id_dest_user,
					url: \'users_lists/\'
				});
			},
			\'lists_links\',
			{async: false}
		);
	});
</script>'; ?>
