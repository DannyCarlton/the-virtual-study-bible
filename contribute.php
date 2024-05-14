<?php

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}

wp_register_style('mystyle', plugins_url().'/the-virtual-study-bible/css/bootstrap.css');
wp_enqueue_style('mystyle');
wp_register_style('myfonts', plugins_url().'/the-virtual-study-bible/css/fontawesome.css');
wp_enqueue_style('myfonts');
wp_register_style('logofont', plugins_url().'/the-virtual-study-bible/css/logofont.css');
wp_enqueue_style('logofont');
$plugin_url=plugin_dir_url(__FILE__);

?>
<style>
	#wpcontent
		{
		background-color:#f0f0f1;
		min-height:100%;
		}
	#wpbody 
		{
		padding-right:20px;
		min-height:100vh;
		}
	#wpbody-content 
		{
		margin-right:20px;
		}
	.nav-tabs li.vb-nav-tab a
		{
		background-color:#ddd;
		border:1px solid #555;
		font-size:17px;
		}
	.nav-tabs li.vb-nav-tab a:hover
		{
		background-color:#fff;
		border:1px solid #555;
		}
	.nav-tabs li.vb-nav-tab.active a
		{
		background-color:#fff;	
		font-weight:bold;
		border:1px solid #555;
		border-bottom-color:transparent;
		font-size:17px;
		}
	.nav-tabs li.vb-nav-tab.active a:hover
		{
		border:1px solid #555;
		border-bottom-color:transparent;	
		}	
	.poppins-black {
		font-family: "Poppins", sans-serif;
		font-weight: 900;
		font-style: normal;
		}
</style>
<h2 class="poppins-black" style="color:#3a72d3;text-shadow: 1px 1px 0px #fff, 2px 2px 5px #333;">
	<img src="<?php echo $plugin_url;?>logo.png" style="width:45px;vertical-align: bottom;margin-bottom:-8px" /> 
	The Virtual Study Bible Plugin
</h2>
<ul class="nav nav-tabs">
	<li class="vb-nav-tab">
		<a href="?page=the-virtual-study-bible/settings.php">
			<span class="dashicons dashicons-admin-settings"></span>
			<span>Settings</span>
		</a>
	</li>
	<li class="vb-nav-tab">
		<a href="?page=the-virtual-study-bible/help.php">
			<span class="dashicons dashicons-editor-help"></span>
			<span>Help</span>
		</a>
	</li>
	<li class="active vb-nav-tab">
		<a href="?page=the-virtual-study-bible/contribute.php" style="color:#800;">
			<span class="dashicons dashicons-heart"></span>
			<span>Contribute</span>
		</a>
	</li>
</ul>
<div class="tab-content" style="box-shadow:rgba(0, 0, 0, 0.15) 2px 2px 6px;margin-top:-1px;">
	<div id="contribute" class="tab-pane fade in active" 
		style="border: 1px solid #555;border-radius: 0 4px 4px 4px;padding:10px;background-color:#fff">
		<h3>Contribute</h3>
		<p>Contribute page content.</p>
		<p>some fonts: dash-<i class="fas fa-tachometer-alt"></i> settings-<i class="fas fa-tools"></i> settings2<i class="fas fa-cogs"></i></p>
	</div>
</div>