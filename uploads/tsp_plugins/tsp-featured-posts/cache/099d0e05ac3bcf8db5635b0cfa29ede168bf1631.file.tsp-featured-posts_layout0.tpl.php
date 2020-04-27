<?php /* Smarty version Smarty-3.1.14, created on 2016-05-03 07:05:59
         compiled from "/home/iamrabiul/public_html/wp-content/plugins/tsp-featured-posts/templates/tsp-featured-posts_layout0.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13605495857284dd724d398-71764817%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '099d0e05ac3bcf8db5635b0cfa29ede168bf1631' => 
    array (
      0 => '/home/iamrabiul/public_html/wp-content/plugins/tsp-featured-posts/templates/tsp-featured-posts_layout0.tpl',
      1 => 1462259016,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13605495857284dd724d398-71764817',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ID' => 1,
    'post_class' => 1,
    'plugin_key' => 1,
    'style' => 1,
    'show_thumb' => 1,
    'target' => 1,
    'permalink' => 1,
    'long_title' => 1,
    'media' => 1,
    'show_author' => 1,
    'show_date' => 1,
    'author_first_name' => 1,
    'author_last_name' => 1,
    'publish_date' => 1,
    'text' => 1,
    'wp_link_pages' => 1,
    'edit_post_link' => 1,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_57284dd7384562_18791393',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57284dd7384562_18791393')) {function content_57284dd7384562_18791393($_smarty_tpl) {?><!-- // Side bar featured item with title -->
<article id="post-<?php echo $_smarty_tpl->tpl_vars['ID']->value;?>
" class="<?php echo $_smarty_tpl->tpl_vars['post_class']->value;?>
">
	<div id="<?php echo $_smarty_tpl->tpl_vars['plugin_key']->value;?>
_article" class="layout_default layout0" style="<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
">
		<div id="full">
			<?php if ($_smarty_tpl->tpl_vars['show_thumb']->value){?>
				<a target="<?php echo $_smarty_tpl->tpl_vars['target']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['permalink']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['long_title']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['media']->value;?>
</a>
			<?php }?>
			<header class="entry-header">
				<span class="entry-title"><a target="<?php echo $_smarty_tpl->tpl_vars['target']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['permalink']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['long_title']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['long_title']->value;?>
</a></span>
			</header><!-- .entry-header -->
			<span class="entry-summary">
				<?php if ($_smarty_tpl->tpl_vars['show_author']->value=='Y'||$_smarty_tpl->tpl_vars['show_date']->value=='Y'){?>
					<div id="article_about">
						<?php if ($_smarty_tpl->tpl_vars['show_author']->value=='Y'){?>By: <?php echo $_smarty_tpl->tpl_vars['author_first_name']->value;?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['author_last_name']->value;?>
&nbsp;<?php }?> <?php if ($_smarty_tpl->tpl_vars['show_date']->value=='Y'){?>Published On: <?php echo $_smarty_tpl->tpl_vars['publish_date']->value;?>
<?php }?>
					</div>
				<?php }?>
				<?php echo $_smarty_tpl->tpl_vars['text']->value;?>

			</span>
		</div>
		<footer class="entry-meta">
			<?php echo $_smarty_tpl->tpl_vars['wp_link_pages']->value;?>

			<?php echo $_smarty_tpl->tpl_vars['edit_post_link']->value;?>

		</footer><!-- .entry-meta -->
	</div>   
</article><!-- #post-<?php echo $_smarty_tpl->tpl_vars['ID']->value;?>
 -->
<?php }} ?>