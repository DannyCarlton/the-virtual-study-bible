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
	<img src="<?php echo plugin_dir_url(__FILE__);?>logo.png" style="width:45px;vertical-align: bottom;margin-bottom:-8px" /> 
	The Virtual Study Bible Plugin
</h2>
<ul class="nav nav-tabs">
	<li class="vb-nav-tab">
		<a href="?page=the-virtual-study-bible/settings.php">
			<span class="dashicons dashicons-admin-settings"></span>
			<span>Settings</span>
		</a>
	</li>
	<li class="active vb-nav-tab">
		<a href="?page=the-virtual-study-bible/help.php">
			<span class="dashicons dashicons-editor-help"></span>
			<span>Help</span>
		</a>
	</li>
	<li class="vb-nav-tab">
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

						<h3 class="poppins-black">How to use this plugin...</h3>

					</div>
				</div>
			</div>
		</div>
		
		<div class="main-content" style="font-family: 'Montserrat', sans-serif">
            <div class="container">                
                <div class="row">
                    <div class="col-sm-12 col-md-5">

						<h4 class="poppins-black">As the Site Admin...</h4>
						
						<p style="text-align:justify;font-size:16px">
							Once the Plugin has been installed, you will be taken to the Settings Page to then install the various Modules.
						</p>
						<p style="text-align:justify;font-size:16px">
							One Module (<b>The Books of the Bible, Introductions and Outlines</b>) will already be installed.
						</p>
						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">Required Modules</h5>
						<p style="text-align:justify;font-size:16px">
							Two required modules (<b>The King James Bible</b> and <b>Strong's Hebrew and Greek Lexicons</b>) will then need to be installed. Installing these modules is as simple as clicking the &ldquo;<span><i class="fa fa-cloud-download"></i>&nbsp;Load Module...</span>&rdquo; button and wait for it to download and install. (You will be able to see the progress of the installation, which will only take a few minutes for each module)
						</p>
						<p style="text-align:justify;font-size:16px">
							The reason these modules are required is because they form the essential basics of a Study Bible. 
						</p>
						<p style="text-align:justify;font-size:16px">
							By clicking the &ldquo;Next &nbsp;<i class="fa-solid fa-right-long"></i>&rdquo; button you will be taken to the Optional Modules.
						</p>
						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">Optional Modules</h5>
						<p style="text-align:justify;font-size:16px">
							Currently, the Optional Modules include <b>Easton&rsquo;s Bible Dictionary</b>, <b>The Holman Cross-Reference</b>, <b>The Interlinear Bible</b> and <b>The Passage Outlines</b>. It is up to you whether your site offers these modules to your users. You can learn more about each of these by expanding the headings below.
						</p>
						<p style="text-align:justify;font-size:16px">
							&bull; <a data-toggle="collapse" href="#eastons" aria-expanded="false" aria-controls="eastons" style="text-decoration:none;font-weight:600;color:#000">Easton&rsquo;s Bible Dictionary</a>							
						</p>
						<div class="collapse" id="eastons">
							<p style="text-align:justify;font-size:16px">The Easton&rsquo;s Bible Dictionary was compiled in the late 19th century by Matthew George Easton, a scottish minister and writer. It contains 3,963 entries of Bible words and concepts. These entries will be available in the Tools Pane when the User does a word search.</p>
						</div>
						
						<p style="text-align:justify;font-size:16px">
							&bull; <a data-toggle="collapse" href="#holman" aria-expanded="false" aria-controls="holman" style="text-decoration:none;font-weight:600;color:#000">The Holman Cross-Reference</a>							
						</p>
						<div class="collapse" id="holman">
							<p style="text-align:justify;font-size:16px">The Holman Cross-Reference is a collection of links from 3,992 verse to 57,812 related verses. I have compiled these from various sources and it is not yet complete, but functions as a handy cross-reference. </p>
						</div>
						
						<p style="text-align:justify;font-size:16px">
							&bull; <a data-toggle="collapse" href="#interlinear" aria-expanded="false" aria-controls="interlinear" style="text-decoration:none;font-weight:600;color:#000">The Interlinear Bible</a>							
						</p>
						<div class="collapse" id="interlinear">
							<p style="text-align:justify;font-size:16px">This module contains the Hebrew and Greek text, with the word linked to the keyed Strong's definition as well as the English translation, pronunciation and parts of speech. It will be displayed in the Tools Pane, with the information formatted for easy of access. The Hebrew text is taken from the Masoretic, and the Greek text from the Textus Receptus. </p>
						</div>
						
						<p style="text-align:justify;font-size:16px">
							&bull; <a data-toggle="collapse" href="#outlines" aria-expanded="false" aria-controls="outlines" style="text-decoration:none;font-weight:600;color:#000">Passage Outlines</a>							
						</p>
						<div class="collapse" id="outlines">
							<p style="text-align:justify;font-size:16px">The Passage Outlines contains notes keyed to specific chapters and verse to aid in following the narrative of the Bible. Along with the cross-reference, these comprise the standard additional material found in virtually every Study Bible. </p>
						</div>
						
						<p style="text-align:justify;font-size:16px">
							&bull; <a data-toggle="collapse" href="#planned" aria-expanded="false" aria-controls="planned" style="text-decoration:none;font-weight:600;color:#000">Future Modules</a>							
						</p>
						<div class="collapse" id="planned">
							<p style="text-align:justify;font-size:16px">Modules planned for future updates... 
								<ul style="margin-left:10px">
									<li>The Brown/Thayer Hebrew/Greek Lexicon (public domain)</li>
									<li>The 1611 Margin Notes</li>
									<li>The American King James Version (Keyed to Strong's)</li>
									<li>The Webster's Bible Version (Keyed to Strong's)</li>
									<li>Young's Literal Translation</li>
									<li>Thompson's Chain References</li>
									<li>Book Introductions and commontaries from... 
										<ul style="margin-left:10px;font-size:14px">
											<li>Albert Barnes</li>
											<li>Adam Clarke</li>
											<li>John Nelson Darby</li>
											<li>John Gill</li>
											<li>Matthew Henry</li>
											<li>Jamieson, Fausset & Brown</li>
											<li>C. I. Scofield</li>
										</ul> 
									</li>
								</ul>
							</p>
						</div>

						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">Page Name and Styles</h5>
						
						<p style="text-align:justify;font-size:16px">
							The next section of the Settings Page allows you to choose your page name and Styles offered.
						</p>
						
						<p style="text-align:justify;font-size:16px">
							<b>Page Name:</b> To house your Study Bible, your WordPress installation needs a page to display it. This page will provide all the data your users will need to access the Study Bible you've provided. The default, already set, is simple &ldquo;Study Bible&rdquo;. However, you can change this if you want. 
						</p>
						
						<p style="text-align:justify;font-size:16px">
							<b>Passage Styles:</b> Three Passage Styles can be offered. <b>Traditional</b>, <b>Paragraph</b> and <b>The Reader's Bible</b>. Expand the headings below to see mmore about each style.
						</p>
						
						<p style="text-align:justify;font-size:16px">
							&bull; <a data-toggle="collapse" href="#traditional" aria-expanded="false" aria-controls="traditional" style="text-decoration:none;font-weight:600;color:#000">Traditional</a>							
						</p>
						<div class="collapse" id="traditional">
							<p style="text-align:justify;font-size:16px">This layout is what has been used for centuries. The verses are separate and paragraphs are marked with the ¶ character. The first letter of the first verse is larger and the remaining text flows around it.</p><img class="card-img-top img-thumbnail"
							src="<?php echo plugin_dir_url(__FILE__);?>images/style-traditional.jpg">
						</div>
						
						<p style="text-align:justify;font-size:16px">
							&bull; <a data-toggle="collapse" href="#paragraph" aria-expanded="false" aria-controls="paragraph" style="text-decoration:none;font-weight:600;color:#000">Paragraph</a>							
						</p>
						<div class="collapse" id="paragraph">
							<p style="text-align:justify;font-size:16px">This is the more modern look where the text flows as paragraphs, verses are marked by a small superscript and the chapter number appears as a large red number.</p><img class="card-img-top img-thumbnail"  
							src="<?php echo plugin_dir_url(__FILE__);?>images/style-paragraph.jpg">
						</div>
						
						<p style="text-align:justify;font-size:16px">
							&bull; <a data-toggle="collapse" href="#readers" aria-expanded="false" aria-controls="readers" style="text-decoration:none;font-weight:600;color:#000">The Reader&rsquo;s Bible</a>							
						</p>
						<div class="collapse" id="readers">
							<p style="text-align:justify;font-size:16px">For the Reader's Bible the text is laid out like a book, but with no verse markings. Book and Chapter headings alone are marked.</p><img class="card-img-top img-thumbnail"
							src="<?php echo plugin_dir_url(__FILE__);?>images/style-reading.jpg">
						</div>

						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">Publishing</h5>
						
						<p style="text-align:justify;font-size:16px">
							Your Study Bible cannot be accessed until you've installed the required modules, installed which optional modules you want (and set whatever other options are available) then submitted your choices. This will then create the page and your users will be able to access the Study Bible on your site.
						</p>
						
						<p style="text-align:justify;font-size:16px">
							
						</p>


					</div>
                    <div class="col-sm-12 col-md-5 col-md-offset-1">

						<h4 class="poppins-black">As a Site User...</h4>

						<p style="text-align:justify;font-size:16px">
							The basic instructions for using the Study Bible are made available on the initial page. I have tried to make its use either easily explained or as intuitive as possible. However, I'll mention some of the features available for you.
						</p>

						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">The Tools</h5>
						
						<p style="text-align:justify;font-size:16px">
							In the Start Page Tools Help section, the various tools available will be explained. If any of these tools are optional, and you did not choose them, then the explanation won't be shown. The same for any Styles you've chosen not to include.
						</p>

						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">The Books</h5>
						
						<p style="text-align:justify;font-size:16px">
							Access to quick links to the Books of the Bible is provided on the Start Page (in the Helps section) as well as (on any page) as a modal activated by the "books" link above the search form. 
						</p>
						
						<p style="text-align:justify;font-size:16px">
							(It should so be noted that on the <b>Reference Search</b> page, the Chapter number is a link to another modal, displaying links to all the chapters for that book.)
						</p>

						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">The Styles</h5>
						
						<p style="text-align:justify;font-size:16px">
							If the user is logged in, the their personal Style choice is rememberd and applied until they change it. If they are not logged in, then it is rememberd only for the immediate session, and when they return to the page, the default (Traditional) will be set. As other Bible versions are added (in future updates) this choice will also be remembered in the same way.
						</p>

						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">Strong&rsquo;s Lexicon</h5>
						
						<p style="text-align:justify;font-size:16px">
							The &ldquo;<b>Link Keyed</b>&rdquo; toggle will activate access to the Strong&rsquo;s Lexicons. One clicked, it will turn all the keyed text <b style="color:#800">red and bold</b>. Clicking on a keyed word, then activates a popover, displaying the Strong&rsquo;s definition for that word. Occasionally a definition wil reference another definition. That is also linked, so that the user can click on it and see the second definition.
						</p>

						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">Book Introductions and Outlines</h5>
						
						<p style="text-align:justify;font-size:16px">
							Included in the primary module are the book introductions and outlines by John MacArthur's Grace to You ministry. They have made these available to the public, and I have found them to be very useful. However, in future updates I will be offering other introductions which you can choose instead, if you want. 
						</p>

						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">The Interlinear Bible</h5>
						
						<p style="text-align:justify;font-size:16px">
							This module (if installed and activated) offers the Hebrew or Greek text alongside the English. In the Tools Pane, when selected, the Interlinear text will show the original word above the English translation and when moused over, will show more information for that specific word. I tried to optimise the balance between ease of access and total content, so the user can have access to as much standard information as possible without being overwhelmed. 
						</p>

						<h5 style="font-weight:600;letter-spacing:.5px;font-size:19px">Reference Search vs Word Search</h5>
						
						<p style="text-align:justify;font-size:16px">
							The plugin offers three types of pages: The Start Page, the Reference Search and the Word Search. The Start Page is mostly self explanitory. The Reference Search we've been through with the examples above. The Word Search, however, requires a different approach than the Reference Search.
						</p>
						
						<p style="text-align:justify;font-size:16px">
							<b>The Word Search</b> returns a list of verses containing the word requested. The requested word is noted (in <b>bold</b>) within each verse listed. The Strong&rsquo;s toggle still works, but the tools pane displays different tools.
						</p>
						
						<p style="text-align:justify;font-size:16px">
							<b>The Word Filter</b> displays a list of sections and books contaning the keyword and a count of the occurance for each. The section is a link to display just those results in that section. (NOTE: This function is resource intensive, therefore the results are cached, stored in a data file, so it can be quickly reused, preventing too much load on your server. Currently the cached results only last one hour.)
						</p>
						
						<p style="text-align:justify;font-size:16px">
							Also in the <b>Word Search Tools</b>, is the <b>Easton&rsquo;s Bible Dictionary</b> (assuming you have it enbled). If the keyword has a matching entry in the Easton&rsquo;s Dictioary, it will be displayed in the Tool Pane.
						</p>
						
						<p style="text-align:justify;font-size:16px">
							Lastly, the final Tools Pane will display every Strong&rsquo;s Lexicon definition for that keyword, both Old Testament (Hebrew) and New (Greek). Clicking on the definition title, highlights the verses in the left pane which that definition matches.
						</p>

					</div>
				</div>
			</div>
		</div>

	</div>
</div>