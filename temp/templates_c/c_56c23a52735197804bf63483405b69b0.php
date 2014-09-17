<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-10 23:55:41 CDT */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
">
	<div class="filter-form">
		<h3>Panel settings</h3>
		<div class="form">
			<div class="row">
				<div class="h">Permissions for config file:</div>
				<div class="v"><?php if ($this->_vars['data']['config_writeable']): ?><font class="success">file <b>'<?php echo $this->_vars['data']['config_file']; ?>
'</b> <br>is writeable</font><?php else: ?><font class="error">Please change file permissions to 777 <br><b>'<?php echo $this->_vars['data']['config_file']; ?>
'</b></font><?php endif; ?></div>
			</div>
			<br>
			<div class="row">
				<div class="h">Login: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['config_login']; ?>
" name="install_login"></div>
			</div>
			<div class="row">
				<div class="h">Password: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['config_password']; ?>
" name="install_password"></div>
			</div>
		</div>
		<?php if ($this->_vars['data']['ftp']): ?>
		<br>
		<h3>FTP access info</h3>
		<div class="form">
			<i>(necessary to update modules)</i><br>
			<div class="row">
				<div class="h">FTP host: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['ftp_host']; ?>
" name="ftp_host"></div>
			</div>
			<div class="row">
				<div class="h">FTP path to site subfolder: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['ftp_path']; ?>
" name="ftp_path"></div>
			</div>
			<div class="row">
				<div class="h">FTP user: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['ftp_user']; ?>
" name="ftp_user"></div>
			</div>
			<div class="row">
				<div class="h">FTP password: </div>
				<div class="v"><input type="password" value="<?php echo $this->_vars['data']['ftp_password']; ?>
" name="ftp_password"></div>
			</div>
		</div>
		<?php endif; ?>
		<br>
		<h3>Limit access to installation by IP address</h3>
		<div class="form">
			<div class="row">
				<div class="h">Enable IP limit: </div>
				<div class="v"><input type="checkbox" value="1" name="installer_ip_protect" <?php if ($this->_vars['data']['installer_ip_protect']): ?>checked<?php endif; ?>></div>
			</div>
			<div class="row">
				<div class="h">IP address:<br><i>(delimiter ',')</i> </div>
				<div class="v"><textarea name="installer_ip"><?php echo $this->_vars['data']['installer_ip']; ?>
</textarea></div>
			</div>
		</div>
	</div>
	<?php if ($this->_vars['data']['config_writeable']): ?>
	<div class="btn"><div class="l"><input type="submit" name="save_install_login" value="Save data"></div></div>
	<div class="btn gray fright"><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/install/installer_settings">Refresh</a></div></div>
	<?php else: ?>
	<div class="btn gray"><div class="l"><input type="button" name="save_install_login" value="Save data" disabled></div></div>
	<div class="btn fright"><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/install/installer_settings">Refresh</a></div></div>
	<?php endif; ?>
</form>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array());
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>