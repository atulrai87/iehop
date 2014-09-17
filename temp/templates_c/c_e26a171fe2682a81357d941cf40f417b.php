<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-08-27 09:06:06 CDT */ ?>

<ul>
<?php echo tpl_function_counter(array('start' => 0,'print' => false), $this); if (is_array($this->_vars['content_tree']) and count((array)$this->_vars['content_tree'])): foreach ((array)$this->_vars['content_tree'] as $this->_vars['key'] => $this->_vars['item']): ?>
<li<?php if ($this->_vars['item']['active']): ?>  class="active"<?php endif; ?>><a href="<?php echo tpl_function_seolink(array('module' => 'content','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo $this->_vars['item']['title']; ?>
</a></li>
<?php endforeach; endif; ?>
</ul>