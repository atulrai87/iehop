<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-08 13:44:35 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="services">
	<?php 
$this->assign(data_alert_lng, l(service_activate_confirm, services, '', 'text', array()));
 ?>
	<?php if (is_array($this->_vars['services_block_services']) and count((array)$this->_vars['services_block_services'])): foreach ((array)$this->_vars['services_block_services'] as $this->_vars['item']): ?>
		<div class="service">
			<div class="table">
				<dl>
					<dt class="view">
						<div class="h2"><?php echo $this->_vars['item']['name'];  if ($this->_vars['item']['price']): ?> - <?php echo tpl_function_block(array('name' => currency_format_output,'module' => start,'value' => $this->_vars['item']['price']), $this); endif; ?></div>
						<div class="t-2">
							<?php if ($this->_vars['item']['description']): ?><span><?php echo $this->_vars['item']['description']; ?>
</span><?php endif; ?>
							<?php if (is_array($this->_vars['item']['template']['data_admin_array']) and count((array)$this->_vars['item']['template']['data_admin_array'])): foreach ((array)$this->_vars['item']['template']['data_admin_array'] as $this->_vars['setting_gid'] => $this->_vars['setting_options']): ?>
								<div><span><?php echo $this->_vars['setting_options']['name']; ?>
: <?php echo $this->_vars['item']['data_admin'][$this->_vars['setting_gid']]; ?>
</span></div>
							<?php endforeach; endif; ?>
						</div>
					</dt>
					<dt class="righted">
						<?php if ($this->_vars['item']['price'] || $this->_vars['item']['template']['price_type'] != 1): ?>
							<input type="button" onclick="locationHref('<?php echo tpl_function_seolink(array('module' => 'services','method' => 'form'), $this); echo $this->_vars['item']['gid']; ?>
');" value="<?php echo l('btn_buy_now', 'services', '', 'text', array()); ?>" />
						<?php else: ?>
							<input type="button" onclick="
								var href='<?php echo tpl_function_seolink(array('module' => 'services','method' => 'user_service_activate'), $this); echo $this->_vars['user_id']; ?>
/0/<?php echo $this->_vars['item']['gid']; ?>
';
								var alert='<?php echo $this->_run_modifier($this->_vars['data_alert_lng'], 'escape', 'plugin', 1); ?>
<br><?php echo $this->_run_modifier($this->_vars['item']['name'], 'escape', 'plugin', 1); ?>
<br>(<?php echo $this->_run_modifier($this->_vars['item']['description'], 'escape', 'plugin', 1); ?>
)';
								<?php echo '
								if(!parseInt(\'';  echo $this->_vars['item']['template']['alert_activate'];  echo '\')) {
									locationHref(href);
								} else {
									alerts.show({
										text: alert.replace(/<br>/g, \'\\n\'),
										type: \'confirm\',
										ok_callback: function(){
											locationHref(href);
										}
									});
								}'; ?>
" value="<?php echo l('btn_activate', 'services', '', 'text', array()); ?>" />
						<?php endif; ?>
					</dt>
				</dl>
			</div>
		</div>
	<?php endforeach; else: ?>
		<?php echo l('no_services', 'services', '', 'text', array()); ?>
	<?php endif; ?>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>