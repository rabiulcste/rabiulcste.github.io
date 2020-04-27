<?php /* Smarty version Smarty-3.1.14, created on 2016-05-03 07:05:06
         compiled from "/home/iamrabiul/public_html/wp-content/plugins/tsp-featured-posts/templates/default_form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:145544688157284da230d154-71272667%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b84f4c64b1e399c61f872f3ab93f70c6f1789cdd' => 
    array (
      0 => '/home/iamrabiul/public_html/wp-content/plugins/tsp-featured-posts/templates/default_form.tpl',
      1 => 1462259016,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '145544688157284da230d154-71272667',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'form_fields' => 0,
    'field' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_57284da23ddcb5_74237296',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57284da23ddcb5_74237296')) {function content_57284da23ddcb5_74237296($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['form_fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['EASY_DEV_FORM_FIELDS']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('field'=>$_smarty_tpl->tpl_vars['field']->value), 0);?>

<?php } ?><?php }} ?>