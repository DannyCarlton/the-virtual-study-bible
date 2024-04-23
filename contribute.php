<?php

wp_register_style('mystyle', plugins_url().'/the-virtual-study-bible/css/bootstrap.css');
wp_enqueue_style('mystyle');

?>
<style>
	#wpcontent
		{
		background:#fff;
		}
</style>
<h2>
	<img src="<?=plugin_dir_url(__FILE__);?>logo.png" style="width:45px;vertical-align: bottom;margin-bottom:-8px" /> 
	The Virtual Study Bible Plugin 
	<small>(Settings)</small>
</h2>
<ul class="nav nav-tabs">
	<li><a href="?page=the-virtual-study-bible/settings.php">Settings</a></li>
	<li class="active"><a href="#">Contribute</a></li>
</ul>
<p>The page content</p>