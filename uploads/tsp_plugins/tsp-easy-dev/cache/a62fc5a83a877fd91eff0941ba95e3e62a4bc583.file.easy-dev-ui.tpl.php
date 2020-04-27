<?php /* Smarty version Smarty-3.1.14, created on 2016-05-05 07:23:00
         compiled from "/home/iamrabiul/public_html/wp-content/plugins/tsp-easy-dev/assets/templates/easy-dev-ui.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1365502821572af4d4d7c922-64245836%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a62fc5a83a877fd91eff0941ba95e3e62a4bc583' => 
    array (
      0 => '/home/iamrabiul/public_html/wp-content/plugins/tsp-easy-dev/assets/templates/easy-dev-ui.tpl',
      1 => 1462259042,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1365502821572af4d4d7c922-64245836',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'plugin_title' => 0,
    'plugin_links' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_572af4d4e86d77_20339590',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_572af4d4e86d77_20339590')) {function content_572af4d4e86d77_20339590($_smarty_tpl) {?><!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<div id="tsp_wrapper">
    <div id="tsp_header" class="row">
        <div id="tsp_logo" class="col-sm-4"></div>
        <div id="tsp_links" class="col-sm-8">
        	<h4><?php echo $_smarty_tpl->tpl_vars['plugin_title']->value;?>
</h4>
        	<span><?php echo $_smarty_tpl->tpl_vars['plugin_links']->value;?>
</span>
        </div>
    </div> <!-- tsp_title -->
    <div id="tsp_content" class="row">
        <div id="tsp_tabs_list">
          <ul>
            <li><a href="#tabs-1"><i class="fa fa-question"></i>What is Easy Dev?</a></li>
            <li><a href="#tabs-2"><i class="fa fa-download"></i>Why do I Need Easy Dev?</a></li>
            <li><a href="#tabs-3"><i class="fa fa-thumb-tack"></i>How can I use Easy Dev?</a></li>
          </ul>
        </div> <!-- tsp_tabs_list -->
        <div id="tsp_tabs_content">
            <div id="tsp_tabs_inner">
                <div id="tsp_tabs_controller">
                    <div id="tabs-1">
                    	<h2>What is Easy Dev?</h2>
                    	<p>The Software People's (TSP) Easy Dev is a framework for easy WordPress plugin development. Easy Dev makes object oriented development (OOD) hot again and it's the engine that powers all WordPress plugins created by The Software People!</p>
                    </div> <!-- tabs-1 -->
                    <div id="tabs-2">
                    	<h2>Why do I Need Easy Dev?</h2>
                    	<p>In order to use any of The Software People's plugins, you will first need to download TSP Easy Dev before any of them can be installed. Using TSP Easy Dev, allows us to embrace the DRY principle, or Don't Repeat Yourself. Using TSP Easy Dev makes plugin development so much easier because it reduces the code we have to write.</p>
                    </div> <!-- tabs-2 -->
                    <div id="tabs-3">
                    	<h2>How can I use Easy Dev?</h2>
                    	<p>Anyone can use TSP Easy Dev, to create their own plugins. Before you get started writing, you may want to first check out our documention. Once you've familiarized yourself with the documentation, you are ready to start writing your own plugins using our framework!</p>
                    	<p>
                    		Features Include:<br>
                    		<ul style="list-style-type:square; padding-left: 30px;">
								<li> Create functional plugins in as little as 5 lines of code</li>
								<li> Add additional `external URL links` to your plugins description</li>
								<li> Use on as many plugin projects as necessary</li>
								<li> Add a `settings page` menu for your plugin, and for future plugins</li>
								<li> Supports the usage of your own `custom shortcodes`</li>
								<li> Verify user `uses correct version of WordPress` for your plugin</li>
								<li> When ready to deploy, require users to install THIS plugin or bundle the code into YOUR plugin</li>
								<li> `Smarty` bundled into package (display html easily)</li>
								<li> Smarty `form field templates` included for creating forms on the fly</li>
								<li> Checks for browser type and version (currently supports Chrome, Firefox, Safari, Opera and IE detection)</li>
								<li> Add form fields (metadata) to posts</li>
								<li> Add form fields (metadata) to categories</li>
								<li> Check for `incompatible plugins` on activate</li>
								<li> Check for `required plugins` on activate</li>
								<li> Deregister annoying scripts</li>
								<li> Display `favicons` (ie Twitter, Facebook, GitHub, thumbs, folders, etc) easily in your html</li>
								<li> `Format your html` into columns on the fly</li>
								<li> Use Bootstrap to create beautifully crafted web forms</li>
								<li> Additional images and resources</li>
								<li> `Upgrades includes fixes and new functionality from our community of developers`</li>							
							</ul>
						</p>
                    </div> <!-- tabs-3 -->
               </div> <!-- tsp_tabs_controller -->
            </div> <!-- tsp_tabs_inner -->
        </div> <!-- tsp_tabs_content -->
    </div> <!-- tsp_content -->
</div> <!-- tsp_wrapper -->
<?php }} ?>