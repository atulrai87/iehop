<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 20:11:38 CDT */ ?>

<?php if ($this->_vars['comments']): ?>
<p><?php echo l('do_you_comments', 'social_networking', '', 'text', array()); ?></p>
<?php echo $this->_vars['comments']; ?>

<?php endif; ?>
