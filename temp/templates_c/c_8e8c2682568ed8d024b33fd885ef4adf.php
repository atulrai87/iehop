<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-11 01:16:41 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form">
	<div class="edit-form n150">
		<div class="row header">Module info</div>
		<div class="row">
			<div class="h"><b>Module:</b></div>
			<div class="v"><?php echo $this->_vars['module']['module']; ?>
&nbsp;</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Description:</b></div>
			<div class="v"><b><?php echo $this->_vars['module']['install_name']; ?>
</b><br><?php echo $this->_vars['module']['install_descr']; ?>
&nbsp;</div>
		</div>
		<div class="row">
			<div class="h"><b>Version:</b></div>
			<div class="v"><?php echo $this->_vars['module']['version']; ?>
&nbsp;</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Added:</b></div>
			<div class="v"><?php echo $this->_vars['install_data']['date_add']; ?>
&nbsp;</div>
		</div>
		<div class="row">
			<div class="h"><b>Latest update:</b></div>
			<div class="v"><?php if ($this->_vars['install_data']['date_update'] != '0000-00-00 00:00:00'):  echo $this->_vars['install_data']['date_update'];  else: ?>No updates<?php endif; ?>&nbsp;</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Files:</b></div>
			<div class="v">
				<?php if (is_array($this->_vars['module']['files']) and count((array)$this->_vars['module']['files'])): foreach ((array)$this->_vars['module']['files'] as $this->_vars['item']): ?>
				<?php echo $this->_vars['item']['2']; ?>
<br>
				<?php endforeach; endif; ?>&nbsp;
			</div>
		</div>
		<div class="row">
			<div class="h"><b>Depends on:</b></div>
			<div class="v">
				<?php if (is_array($this->_vars['module']['dependencies']) and count((array)$this->_vars['module']['dependencies'])): foreach ((array)$this->_vars['module']['dependencies'] as $this->_vars['key'] => $this->_vars['item']):  echo $this->_vars['key']; ?>
<br>
				<?php endforeach; else: ?>No dependencies<br><?php endif; ?>&nbsp;
			</div>
		</div>
		<div class="row zebra">
			<div class="h"><b>Dependent modules:</b></div>
			<div class="v">
				<?php if (is_array($this->_vars['depend_modules']) and count((array)$this->_vars['depend_modules'])): foreach ((array)$this->_vars['depend_modules'] as $this->_vars['item']): ?><b><?php echo $this->_vars['item']['module']; ?>
</b> (<?php echo $this->_vars['item']['install_name']; ?>
)<br>
				<?php endforeach; else: ?>No dependencies<br><?php endif; ?>&nbsp;
			</div>
		</div>
		<div class="row">
			<div class="h"><b>Save module settings:</b></div>
			<div class="v">
				If default settings (e.g. languages) were changed, you can back up your changes.<br>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/install/generate_install_module_settings/<?php echo $this->_vars['module']['module']; ?>
">Backup</a>
			</div>
		</div>
	</div>
</form>
<div class="clr"></div>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>