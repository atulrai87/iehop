<?php require_once('/home/iehop/public_html/system/libraries/template_lite/plugins/function.counter.php'); $this->register_function("counter", "tpl_function_counter");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2014-09-08 13:44:35 CDT */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="packages">
	<?php if (isset($this->_sections['s'])) unset($this->_sections['s']);
$this->_sections['s']['loop'] = is_array($this->_vars['block_packages']) ? count($this->_vars['block_packages']) : max(0, (int)$this->_vars['block_packages']);
$this->_sections['s']['name'] = 's';
$this->_sections['s']['show'] = true;
$this->_sections['s']['max'] = $this->_sections['s']['loop'];
$this->_sections['s']['step'] = 1;
$this->_sections['s']['start'] = $this->_sections['s']['step'] > 0 ? 0 : $this->_sections['s']['loop']-1;
if ($this->_sections['s']['show']) {
	$this->_sections['s']['total'] = $this->_sections['s']['loop'];
	if ($this->_sections['s']['total'] == 0)
		$this->_sections['s']['show'] = false;
} else
	$this->_sections['s']['total'] = 0;
if ($this->_sections['s']['show']):

		for ($this->_sections['s']['index'] = $this->_sections['s']['start'], $this->_sections['s']['iteration'] = 1;
			 $this->_sections['s']['iteration'] <= $this->_sections['s']['total'];
			 $this->_sections['s']['index'] += $this->_sections['s']['step'], $this->_sections['s']['iteration']++):
$this->_sections['s']['rownum'] = $this->_sections['s']['iteration'];
$this->_sections['s']['index_prev'] = $this->_sections['s']['index'] - $this->_sections['s']['step'];
$this->_sections['s']['index_next'] = $this->_sections['s']['index'] + $this->_sections['s']['step'];
$this->_sections['s']['first']	  = ($this->_sections['s']['iteration'] == 1);
$this->_sections['s']['last']	   = ($this->_sections['s']['iteration'] == $this->_sections['s']['total']);
?>
		<?php echo tpl_function_counter(array('assign' => fe_count), $this);?>
		<?php if (( $this->_vars['fe_count'] -1 ) % 4 == 0): ?><dl class="inline-flex"><?php endif; ?>
			<dt<?php if ($this->_vars['stretch']): ?> class="stretch"<?php endif; ?>>
				<div class="h2"><?php echo $this->_vars['block_packages'][$this->_sections['s']['index']]['name']; ?>
</div>
				<ul class="package-services">
					<div class="h3"><?php echo l('field_available_days', 'packages', '', 'text', array()); ?>:&nbsp;<?php echo $this->_vars['block_packages'][$this->_sections['s']['index']]['available_days']; ?>
</div>
					<?php if (is_array($this->_vars['block_packages'][$this->_sections['s']['index']]['services_list']) and count((array)$this->_vars['block_packages'][$this->_sections['s']['index']]['services_list'])): foreach ((array)$this->_vars['block_packages'][$this->_sections['s']['index']]['services_list'] as $this->_vars['service']): ?>
						<li class="view">
							<?php echo $this->_vars['service']['name']; ?>
&nbsp;(<?php echo $this->_vars['service']['service_count']; ?>
)
							<?php if (is_array($this->_vars['service']['template']['data_admin_array']) and count((array)$this->_vars['service']['template']['data_admin_array'])): foreach ((array)$this->_vars['service']['template']['data_admin_array'] as $this->_vars['setting_gid'] => $this->_vars['setting_options']): ?>
								<div class="t-1 pl20"><span><?php echo $this->_vars['setting_options']['name']; ?>
:&nbsp;<?php echo $this->_vars['service']['data_admin'][$this->_vars['setting_gid']]; ?>
</span></div>
							<?php endforeach; endif; ?>
						</li>
					<?php endforeach; endif; ?>
				</ul>
				<div class="bottom ptb20 box-sizing">
					<div class="price"><?php echo tpl_function_block(array('name' => currency_format_output,'module' => start,'value' => $this->_vars['block_packages'][$this->_sections['s']['index']]['price']), $this);?></div>
					<?php if (! $this->_vars['hide_btn']): ?>
						<div class="center">
							<input type="button" onclick="locationHref('<?php echo tpl_function_seolink(array('module' => 'packages','method' => 'package'), $this); echo $this->_vars['block_packages'][$this->_sections['s']['index']]['gid']; ?>
')" value="<?php echo l('btn_buy_now', 'services', '', 'text', array()); ?>" />
						</div>
					<?php endif; ?>
				</div>
			</dt>
		<?php if ($this->_vars['fe_count'] % 4 == 0 || $this->_sections['s']['last']): ?></dl><?php endif; ?>
	<?php endfor; endif; ?>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>