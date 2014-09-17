<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-16 23:10:41 India Standard Time */ ?>

<?php if ($this->_vars['breadcrumbs']): ?>
<div class="breadcrumb">
<a href="<?php echo $this->_vars['site_url']; ?>
"><?php echo l('header_start_page', 'start', '', 'text', array()); ?></a>
<?php if (is_array($this->_vars['breadcrumbs']) and count((array)$this->_vars['breadcrumbs'])): foreach ((array)$this->_vars['breadcrumbs'] as $this->_vars['item']): ?>
&nbsp;>&nbsp;<?php if ($this->_vars['item']['url']): ?><a href="<?php echo $this->_vars['item']['url']; ?>
"><?php echo $this->_vars['item']['text']; ?>
</a><?php else:  echo $this->_vars['item']['text'];  endif;  endforeach; endif; ?>
</div>
<?php endif; ?>