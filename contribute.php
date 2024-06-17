<?php

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}


	wp_register_style('vb-bootstrap-css', plugins_url().'/the-virtual-study-bible/css/bootstrap.css');
	wp_enqueue_style('vb-bootstrap-css');
	wp_register_style('vb-logofont-css', plugins_url().'/the-virtual-study-bible/css/logofont.css');
	wp_enqueue_style('vb-logofont-css');
	wp_register_style('vb-fade-css', plugins_url().'/the-virtual-study-bible/css/fade.css');
	wp_enqueue_style('vb-fade-css');
	wp_register_style('vb-styles-css', plugins_url().'/the-virtual-study-bible/css/vb-styles.css');
	wp_enqueue_style('vb-styles-css');
	
	wp_register_script('vb-jquery-js', plugins_url().'/the-virtual-study-bible/js/jquery-2.2.4.js');
	wp_enqueue_script('vb-jquery-js');
	wp_register_script('vb-bootstrap-js', plugins_url().'/the-virtual-study-bible/js/bootstrap.js');
	wp_enqueue_script('vb-bootstrap-js');
	
	
	include_once(plugin_dir_path(__FILE__).'includes/modules.php');
	include_once(plugin_dir_path(__FILE__).'includes/functions.php');

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
	<div id="help" class="tab-pane fade in active" 
		style="border: 1px solid #555;border-radius: 0 4px 4px 4px;padding:10px;background-color:#fff">
		
		<div class="top-content" style="text-align:center">
            <div class="container">                
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1 col-md-12 col-md-offset-0 col-lg-12 col-lg-offset-0">

						<h3 class="poppins-black">Want to Contribute?</h3>

					</div>
				</div>
			</div>
		</div>
		
		<div class="main-content" style="font-family: 'Montserrat', sans-serif">
            <div class="container">                
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">

						<h4 class="poppins-black">Method #1: Pray!!</h4>
						
						<p style="text-align:justify;font-size:16px">
							This plugin is just one over over 20 ministry sites I've built and maintain. I've learn over the years that the single most important contribution any one can make to my efforts is prayer. Pray for wisdom, guidance, a well-tuned &ldquo;Spiritual Ear&rdquo; to hear and understand what God wants me to do. Strength, drive and passion to keep at it, even when things get hard. 
						</p>
						<p style="text-align:justify;font-size:16px">
							Here are the five things I pray each morning for God to give me for the day... 
						</p>
						<ul style="margin-left:20px;line-height:1;margin-top:-5px">
							<li>The time and money,</li>
							<li>The creativity and ingenuity,</li>
							<li>The passion and drive,</li>
							<li>The wisdom and insight and</li>
							<li>The patience and perseverance.</li>
						</ul>
						<p style="text-align:justify;font-size:16px">
							...to do what He wants me to do. So if you want to join me in those prayers, that would be awesome.							 
						</p>

						<p style="text-align:justify;font-size:16px">
							&nbsp;
						</p>

						<h4 class="poppins-black">Method #2: Opinions, Advice, Observations, Expertise!</h4>

						<p style="text-align:justify;font-size:16px">
							 The raw code of this plugin is available online at my <a href="https://github.com/DannyCarlton/the-virtual-study-bible" target="_blanl">GitHub repository</a> so  anyone can see it, review it, analyze it and see what I did wrong (or right, maybe?) I know I'm not the tightest coder so any help in improving the code would be welcome (GitHub offers links for issues and discussions, which get emailed to me.)
						</p>

						<p style="text-align:justify;font-size:16px">
							Even if you don't code, observations and advice on how to improve the plugin would be welcome (just use the discussion section at GitHub). And in some future update I'll be adding new styles (like the &ldquo;1798 Retro Style&rdquo; that you can see at my site <a href="https://kjBible.org/read-gen-1" target="_blank">kjBible.org</a>). There are tons of ways to style the Bible text and I'd love to be able to offer, user-created styles to be used across the network of sites using this plugin.
						</p>

						<p style="text-align:justify;font-size:16px">
							&nbsp;
						</p>

						<h4 class="poppins-black">Method #3: Financial</h4>

						<p style="text-align:justify;font-size:16px">
							I've been poor my whole life, so I know that financial help is difficult for a lot of people, which is why this is in third place. However, if you think you can help in that way &mdash; I ask that you first, make sure you've tithed to your local church. And then, only if you are sure it's what God wants you do do, send in a donation.
						</p>

						<p style="text-align:justify;font-size:16px">
							If you are a Christian, then it's not your money, it's God's, and you shouldn't be giving it to anyone (me included) unless you've prayed about it, and are certain that's where God wants you to send His money. 
						</p>

						<p style="text-align:justify;font-size:16px">
							I have an account for donations at <a href="https://ko-fi.com/dannycarltonministrysites" target="_blank">Ko-Fi-com</a>. That's generally the easiest way to send money. They've been very reliable over the years.
						</p>

						<p style="text-align:justify;font-size:16px">
							&nbsp;
						</p>

						<h4 class="poppins-black">Some of my other Ministry Sites...</h4>

						<p style="text-align:justify;font-size:16px">
							These, and more, are included in my Ministry Portfolio at <a href="https://DannyCarlton.org" target="_blank">DannyCarlton.org</a>.
						</p>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://phpBible.org" target="_blank">phpBible.org</a> 
								<span style="font-weight:300;font-size:12px;float:right">
									Bible
								</span>
							</div>
							<div class="panel-body">
								Created in 2004. KJV, Greek and Hebrew. Strong's, Easton dictionary, Commentaries and introductions. Revamped in 2023 with newer web technologies.
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://kjBible.org" target="_blank">kjBible.org</a>
								<span style="font-weight:300;font-size:12px;float:right">
									Bible
								</span>
							</div>
							<div class="panel-body">
								Created in 2014 as a replacement for phpBible.org, to specifically provide the KJV Bible. The plan was to make it as much like an older (1769ish) King James version as possible.
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://myOpenBible.org" target="_blank">myOpenBible.org</a>
								<span style="font-weight:300;font-size:12px;float:right">
									Bible
								</span>
							</div>
							<div class="panel-body">
								Created in 2016 as a more comprehensive Online Study Bible. It offers daily bible readings, 6 english translations and 7 foreign translations. Commentaries linked to each chapter. Hebrew/Greek lexicon. KJV verses linked to Strong's. And a Bible Verse Memorization app.
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://NavesTopicalBible.org" targt="_blank">NavesTopicalBible.org</a>
								<span style="font-weight:300;font-size:12px;float:right">
									Reference
								</span>
							</div>
							<div class="panel-body">
							Orville Naves amazing reference book entered into a WikiMedia platform. It still contains some errors, but the WikiMedia platform provides an easy means of editing the entries.
							</div>
						</div>



						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://StrongsConcordance.org" targt="_blank">StrongsConcordance.org</a>
								<span style="font-weight:300;font-size:10px;float:right">
									Reference
								</span>
							</div>
							<div class="panel-body">
								This site duplicates most of the original content of Strong's Concordance with a word search as well as a lexicon search. Words in the Concordance search are linked the lexicon entries
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://SystematicTheology.us" targt="_blank">SystematicTheology.us</a>
								<span style="font-weight:300;font-size:12px;float:right">
									Reference
								</span>
							</div>
							<div class="panel-body">
								Louis Berkhof's Systematic Theology. This has been the definitive book on Systematic Theology for almost 100 years. My goal is to add registration/login functionality so visitors can keep track of where they are at in reading. 
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:14px;padding:5px 15px">
								<a href="https://SpurgeonsMorningandEvening.org" targt="_blank">SpurgeonsMorningandEvening.org</a>
								<span style="font-weight:300;font-size:12px;float:right">
									
								</span>
							</div>
							<div class="panel-body">
								A Wordpress autoblog, which posts Charles Spurgeon's Morning and Evening devotions twice a day. The devotions are also automatically posted to the <a href="https://www.facebook.com/Spurgeon.Morning.and.Evening" target="_blank">Facebook page</a> linked to the site. 
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://CountryHymns.com" targt="_blank">CountryHymns.com</a>
								<span style="font-weight:300;font-size:12px;float:right">
									
								</span>
							</div>
							<div class="panel-body">
							Made in 2020 to combat the abandonment of great hymns by so many churches. 
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://MatthewHenry.uk" targt="_blank">MatthewHenry.uk</a>
								<span style="font-weight:300;font-size:12px;float:right">
									Reference
								</span>
							</div>
							<div class="panel-body">
								Matthew Henry's Complete Commentary on the Bible. It also includes an autoscroll for ease of reading.
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://myBibleMemes.com" targt="_blank">myBibleMemes.com</a>
								<span style="font-weight:300;font-size:12px;float:right">
									Utility
								</span>
							</div>
							<div class="panel-body">
							A page for creating memes or images from Bible verses.
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://Vocabularium.org" targt="_blank">Vocabularium.org</a>
								<span style="font-weight:300;font-size:12px;float:right">
									Study &bull; Reference
								</span>
							</div>
							<div class="panel-body">
								Lesser-known theological words useful for the Bible student.
							</div>
						</div>



						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://xmlBible.org" targt="_blank">xmlBible.org</a>
								<span style="font-weight:300;font-size:12px;float:right">
									Bible &bull; In Progress
								</span>
							</div>
							<div class="panel-body">
								Began in 2022. The goal is to provide a comprehensive XML of the Bible. It currently provides XML for a simple KJV and KJV keyed to Strong's number. (The domain link redirects to the GitHub repository)
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://osBible.org" targt="_blank">osBible.org</a>
								<span style="font-weight:300;font-size:12px;float:right">
									Bible &bull; In Progress
								</span>
							</div>
							<div class="panel-body">
								<b>The Open Source Bible Project</b>. Began in 2022. Provides resources and data for creating your own online Bible. Basic example are provided for retrieving and displaying verses and passages, decoding abbreviations and misspelling of book names, Ajax response,etc. (link redirects to GitHub repository)
							</div>
						</div>

						<div class="col-md-4 panel panel-default" style="width:30%;padding:0;height:250px;max-height:250px;margin-right:5px">
							<div class="panel-heading" style="font-weight:600;font-size:15px;padding:5px 15px">
								<a href="https://jsonBible.com" targt="_blank">jsonBible.com</a>
								<span style="font-weight:300;font-size:12px;float:right">
									Bible &bull; In Progress
								</span>
							</div>
							<div class="panel-body">
								A Bible utility still in progress. My goal is to make a Bible API, delivering verses and passages in json format. API can become very resource intensive, so it's only experimental for now.
							</div>
						</div>

						<p style="text-align:justify;font-size:16px">
							&nbsp;
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>