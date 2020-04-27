<?php /* Smarty version Smarty-3.1.14, created on 2016-05-05 07:23:13
         compiled from "/home/iamrabiul/public_html/wp-content/plugins/tsp-easy-dev/assets/templates/easy-dev-parent-page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:867003235572af4e19c93a3-48800786%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a37f66a56475e01ff1f2458159ec16f47241d703' => 
    array (
      0 => '/home/iamrabiul/public_html/wp-content/plugins/tsp-easy-dev/assets/templates/easy-dev-parent-page.tpl',
      1 => 1462259042,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '867003235572af4e19c93a3-48800786',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'pro_total' => 0,
    'pro_active_count' => 0,
    'pro_active_plugins' => 0,
    'pro_active' => 0,
    'pro_installed_count' => 0,
    'pro_installed_plugins' => 0,
    'pro_installed' => 0,
    'pro_recommend_count' => 0,
    'pro_recommend_plugins' => 0,
    'pro_recommend' => 0,
    'free_total' => 0,
    'free_active_count' => 0,
    'free_active_plugins' => 0,
    'free_active' => 0,
    'free_installed_count' => 0,
    'free_installed_plugins' => 0,
    'free_installed' => 0,
    'free_recommend_count' => 0,
    'free_recommend_plugins' => 0,
    'free_recommend' => 0,
    'contact_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_572af4e1bd4b70_25792223',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_572af4e1bd4b70_25792223')) {function content_572af4e1bd4b70_25792223($_smarty_tpl) {?><div class="wrap">
	<div class="icon32 icon32-tsp" id="icon-options-general"></div>
	<h2><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h2>
	<?php if ($_smarty_tpl->tpl_vars['pro_total']->value>0){?>
		<h3 style="font-size:1.5em;">Professional Plugins</h3>
		<?php if ($_smarty_tpl->tpl_vars['pro_active_count']->value>0){?>
			<div>
				<h4 class="icon-ok" style="font-size:1.25em; color:#28CA00;">&nbsp;Activated Plugins</h4>
				<?php  $_smarty_tpl->tpl_vars['pro_active'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pro_active']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pro_active_plugins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pro_active']->key => $_smarty_tpl->tpl_vars['pro_active']->value){
$_smarty_tpl->tpl_vars['pro_active']->_loop = true;
?>
				<dl style="padding-bottom:10px;">
					<dt><strong><?php echo $_smarty_tpl->tpl_vars['pro_active']->value['title'];?>
</strong> - <em><?php echo $_smarty_tpl->tpl_vars['pro_active']->value['desc'];?>
</em></dt> 
					<dd>
						<a href="<?php echo $_smarty_tpl->tpl_vars['pro_active']->value['more_url'];?>
" target="_blank">Read More</a>
						<a href="<?php echo $_smarty_tpl->tpl_vars['pro_active']->value['settings'];?>
">Settings</a>
					</dd>
				</dl>
				<?php } ?>
			</div>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['pro_installed_count']->value>0){?>
			<div>
				<h4 class="icon-off" style="font-size:1.25em; color:#CA0000;">&nbsp;Installed Plugins</h4>
				<?php  $_smarty_tpl->tpl_vars['pro_installed'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pro_installed']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pro_installed_plugins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pro_installed']->key => $_smarty_tpl->tpl_vars['pro_installed']->value){
$_smarty_tpl->tpl_vars['pro_installed']->_loop = true;
?>
				<dl style="padding-bottom:10px;">
					<dt><strong><?php echo $_smarty_tpl->tpl_vars['pro_installed']->value['title'];?>
</strong> - <em><?php echo $_smarty_tpl->tpl_vars['pro_installed']->value['desc'];?>
</em></dt>
					<dd>
						<a href="<?php echo $_smarty_tpl->tpl_vars['pro_installed']->value['more_url'];?>
" target="_blank">Read More</a>
					</dd>
				</dl>
				<?php } ?>
			</div>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['pro_recommend_count']->value>0){?>
			<div>
				<h4 class="icon-thumbs-up-alt" style="font-size:1.25em; color:#0020CA;">&nbsp;Recommended Plugins</h4>
				<?php  $_smarty_tpl->tpl_vars['pro_recommend'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pro_recommend']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pro_recommend_plugins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pro_recommend']->key => $_smarty_tpl->tpl_vars['pro_recommend']->value){
$_smarty_tpl->tpl_vars['pro_recommend']->_loop = true;
?>
				<dl style="padding-bottom:10px;">
					<dt><strong><?php echo $_smarty_tpl->tpl_vars['pro_recommend']->value['title'];?>
</strong> - <em><?php echo $_smarty_tpl->tpl_vars['pro_recommend']->value['desc'];?>
</em></dt> 
					<dd>
						<a href="<?php echo $_smarty_tpl->tpl_vars['pro_recommend']->value['more_url'];?>
" target="_blank">Read More</a>
						<a href="<?php echo $_smarty_tpl->tpl_vars['pro_recommend']->value['store_url'];?>
" target="_blank">Purchase</a>
					</dd>
				</dl>
				<?php } ?>
			</div>
		<?php }?>
		<br />
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['free_total']->value>0){?>
		<h3 style="font-size:1.5em;">Free Plugins</h3>
		<?php if ($_smarty_tpl->tpl_vars['free_active_count']->value>0){?>
			<div>
				<h4 class="icon-ok" style="font-size:1.25em; color:#28CA00;">&nbsp;Activated Plugins</h4>
				<?php  $_smarty_tpl->tpl_vars['free_active'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['free_active']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['free_active_plugins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['free_active']->key => $_smarty_tpl->tpl_vars['free_active']->value){
$_smarty_tpl->tpl_vars['free_active']->_loop = true;
?>
				<dl style="padding-bottom:10px;">
					<dt><strong><?php echo $_smarty_tpl->tpl_vars['free_active']->value['title'];?>
</strong> - <em><?php echo $_smarty_tpl->tpl_vars['free_active']->value['desc'];?>
</em></dt> 
					<dd>
						<a href="<?php echo $_smarty_tpl->tpl_vars['free_active']->value['more_url'];?>
" target="_blank">Read More</a>
						<a href="<?php echo $_smarty_tpl->tpl_vars['free_active']->value['settings'];?>
">Settings</a>
					</dd>
				</dl>
				<?php } ?>
			</div>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['free_installed_count']->value>0){?>
			<div>
				<h4 class="icon-off" style="font-size:1.25em; color:#CA0000;">&nbsp;Installed Plugins</h4>
				<?php  $_smarty_tpl->tpl_vars['free_installed'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['free_installed']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['free_installed_plugins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['free_installed']->key => $_smarty_tpl->tpl_vars['free_installed']->value){
$_smarty_tpl->tpl_vars['free_installed']->_loop = true;
?>
				<dl style="padding-bottom:10px;">
					<dt><strong><?php echo $_smarty_tpl->tpl_vars['free_installed']->value['title'];?>
</strong> - <em><?php echo $_smarty_tpl->tpl_vars['free_installed']->value['desc'];?>
</em></dt>
					<dd>
						<a href="<?php echo $_smarty_tpl->tpl_vars['free_installed']->value['more_url'];?>
" target="_blank">Read More</a>
					</dd>
				</dl>
				<?php } ?>
			</div>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['free_recommend_count']->value>0){?>
			<div>
				<h4 class="icon-thumbs-up-alt" style="font-size:1.25em; color:#0020CA;">&nbsp;Recommended Plugins</h4>
				<?php  $_smarty_tpl->tpl_vars['free_recommend'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['free_recommend']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['free_recommend_plugins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['free_recommend']->key => $_smarty_tpl->tpl_vars['free_recommend']->value){
$_smarty_tpl->tpl_vars['free_recommend']->_loop = true;
?>
				<dl style="padding-bottom:10px;">
					<dt><strong><?php echo $_smarty_tpl->tpl_vars['free_recommend']->value['title'];?>
</strong> - <em><?php echo $_smarty_tpl->tpl_vars['free_recommend']->value['desc'];?>
</em></dt> 
					<dd>
						<a href="<?php echo $_smarty_tpl->tpl_vars['free_recommend']->value['more_url'];?>
" target="_blank">Read More</a>
						<a href="<?php echo $_smarty_tpl->tpl_vars['free_recommend']->value['settings'];?>
">Download</a>
					</dd>
				</dl>
				<?php } ?>
			</div>
		<?php }?>
		<br />
	<?php }?>
	<span style="color: rgb(136, 136, 136); font-size: 10px;">If you have any questions, please contact us via <a target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['contact_url']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['contact_url']->value;?>
</a></span>
</div>
<?php }} ?>