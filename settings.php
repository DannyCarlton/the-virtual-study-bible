<?php

wp_register_style('vb-bootstrap-css', plugins_url().'/the-virtual-study-bible/css/bootstrap.css');
wp_enqueue_style('vb-bootstrap-css');
wp_register_style('vb-fonts-css', plugins_url().'/the-virtual-study-bible/css/fontawesome.css');
wp_enqueue_style('vb-fonts-css');
wp_register_style('vb-logofont-css', plugins_url().'/the-virtual-study-bible/css/logofont.css');
wp_enqueue_style('vb-logofont-css');
wp_register_style('vb-stepwizard-css', plugins_url().'/the-virtual-study-bible/css/stepwizard.css');
wp_enqueue_style('vb-stepwizard-css');
wp_register_style('vb-styles-css', plugins_url().'/the-virtual-study-bible/css/vb-styles.css');
wp_enqueue_style('vb-styles-css');
wp_register_style('vb-fade-css', plugins_url().'/the-virtual-study-bible/css/fade.css');
wp_enqueue_style('vb-fade-css');

wp_register_script('vb-jquery-js', plugins_url().'/the-virtual-study-bible/js/jquery-1.11.1.js');
wp_enqueue_script('vb-jquery-js');
wp_register_script('vb-bootstrap-js', plugins_url().'/the-virtual-study-bible/js/bootstrap.js');
wp_enqueue_script('vb-bootstrap-js');
wp_register_script('vb-stepwizard-js', plugins_url().'/the-virtual-study-bible/js/stepwizard.js');
wp_enqueue_script('vb-stepwizard-js');


include_once(plugin_dir_path(__FILE__).'includes/modules.php');

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
		font-size:15px;
		font-family: "Montserrat", sans-serif;
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
		font-family: "Poppins", sans-serif !important;
		font-weight: 900;
		font-style: normal;
		}
	.montserrat {
		font-family: "Montserrat", sans-serif !important;
		font-weight: 300;
		font-style: normal;
		}
	div.wizard.top-content
		{
		font-family: "Montserrat", sans-serif !important;
		color:#000;
		}

		

	.vb-progress {
		width: 85px;
		height: 85px;
		background: none;
		position: relative;
		}

	.vb-progress::after {
		content: "";
		width: 100%;
		height: 100%;
		border-radius: 50%;
		border: 6px solid #eee;
		position: absolute;
		top: 0;
		left: 0;
		}

	.vb-progress>span {
		width: 50%;
		height: 100%;
		overflow: hidden;
		position: absolute;
		top: 0;
		z-index: 1;
		}

	.vb-progress .progress-left {
		left: 0;
		}

	.vb-progress .progress-bar {
		width: 100%;
		height: 100%;
		background: none;
		border-width: 6px;
		border-style: solid;
		position: absolute;
		top: 0;
		}

	.vb-progress .progress-left .progress-bar {
		left: 100%;
		border-top-right-radius: 80px;
		border-bottom-right-radius: 80px;
		border-left: 0;
		-webkit-transform-origin: center left;
		transform-origin: center left;
		}

	.vb-progress .progress-right {
		right: 0;
		}

	.vb-progress .progress-right .progress-bar {
		left: -100%;
		border-top-left-radius: 80px;
		border-bottom-left-radius: 80px;
		border-right: 0;
		-webkit-transform-origin: center right;
		transform-origin: center right;
		}

	.vb-progress .progress-value {
		position: absolute;
		top: 10px;
		left: 0;
		left:0;
		right:0;
		}
</style>
<h2 class="poppins-black" style="color:#3a72d3;text-shadow: 1px 1px 0px #fff, 2px 2px 5px #333;">
	<img src="<?php echo plugin_dir_url(__FILE__);?>logo.png" style="width:45px;vertical-align: bottom;" /> 
	The Virtual Study Bible Plugin
</h2>
<ul class="nav nav-tabs">
	<li class="active vb-nav-tab">
		<a href="#">
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
	<li class="vb-nav-tab">
		<a href="?page=the-virtual-study-bible/contribute.php" style="color:#800;">
			<span class="dashicons dashicons-heart"></span>
			<span>Contribute</span>
		</a>
	</li>
</ul>
<div class="tab-content" style="box-shadow:rgba(0, 0, 0, 0.15) 2px 2px 6px;margin-top:-1px;">
	<div id="settings" class="tab-pane fade in active" 
		style="border: 1px solid #555;border-radius: 0 4px 4px 4px;background-color:#fff">
		<div class="top-content wizard">
            <div class="container">                
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1 col-md-12 col-md-offset-0 col-lg-12 col-lg-offset-0">
                    	<form role="form" action="" method="post" class="f1">
                    		<h3 class="poppins-black">Getting Started...</h3>
                    		<p style="font-size:15px">These are the steps to getting your Study Bible live on your site!</p>
                    		<div class="f1-steps">
                    			<div class="f1-progress">
                    			    <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3" style="width: 16.66%;"></div>
                    			</div>
                    			<div class="f1-step active">
                    				<div class="f1-step-icon">
										<i class="fa-solid fa-gear"></i>
									</div>
                    				<p>Step #1: Load Basic Modules</p>
                    			</div>
                    			<div class="f1-step">
                    				<div class="f1-step-icon"><i class="fa-solid fa-gears"></i></div>
                    				<p>Step #2: Load Additional Modules (optional)</p>
                    			</div>
                    		    <div class="f1-step">
                    				<div class="f1-step-icon"><i class="fa-solid fa-file"></i></div>
                    				<p>Step #3: Select Page Title and Style(s)</p>
                    			</div>
                    		    <div class="f1-step">
                    				<div class="f1-step-icon"><i class="fa-solid fa-rectangle-list"></i></div>
                    				<p>Step #4: Publish Your Study Bible!</p>
                    			</div>
                    		</div>
                    		
                    		<fieldset>
                    		    <h4>The Basic Modules:</h4>
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="block icon-block bg-secondary-faded w-border-2x border-secondary inner-space rounded-2x text-center module">
										<i class="fa-solid fa-list md-icon dp36 box-icon bg-secondary-faded border-secondary pill"></i>
										<h6 class="box-title poppins-black" style="color:#000">Books of the Bible</h6>
										<p class="box-description montserrat">This module was loaded when you installed the plugin. It includes the names of the books of the Bible, the most common abbreviations (for searching purposes) and the long name of each book (ex: The First Book of Moses called Genesis).</p>
									</div><!-- / icon-block -->
								</div><!-- / column -->

								<?php 
									$text="While this is an essential module, it takes a while to load, so rather than make you wait while installing the plugin, you can install it here, and watch the progress of the installation.";
									echo module_uninstalled_html('kjvs','danger','book-bible','The King James Bible',$text,plugin_dir_url(__FILE__)); ?>

									
								<?php 
									$text="This will load both the Hebrew and Greek lexicons (14,298 entries!). The King James text is keyed to these entries, so your site visitors can view the definitions for the words and phrases in the text.";
									echo module_uninstalled_html('strongs','info','language','Strong&rsquo;s Hebrew and Greek Lexicons',$text,plugin_dir_url(__FILE__)); ?>


                                <div class="f1-buttons" style="clear:both;padding-top:30px">
                                    <button type="button" class="btn btn-next" >Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>
								<div class="vertical-spacer" style="clear:both;width:100%;height:50px"></div>
                            </fieldset>

                            <fieldset>
                                <h4>Additional Modules*:</h4>     
								
								
									
								<?php 
									$text="This will load the Easton's Bible Dictionary (3,963 entries!). When selected, words in the text will be keyed to the matching Easton's definition and displayed when clicked.";
									echo module_uninstalled_html('eastons','info','arrow-down-a-z','Easton&rsquo;s Bible Dictionary',$text,plugin_dir_url(__FILE__)); ?>
									
								<?php 
									$text="This will load the entire Masoretic Hebrew (Old Testament) text. It can be displayed alongside the English text, and the verses will be matched by highlighting the corresponding verse.";
									echo module_uninstalled_html('hebrew','danger','&#1488;','Hebrew Text',$text,plugin_dir_url(__FILE__)); ?>

								<?php 
									$text="This will load the entire Textus Receptus Greek (New Testament) text. It can be displayed alongside the English text, and the verses will be matched by highlighting the corresponding verse.";
									echo module_uninstalled_html('greek','danger','&Sigma;','Greek Text',$text,plugin_dir_url(__FILE__)); ?>

								<?php 
									$text="This will load the Holman Crossreference, linking 3,992 verses to 61,307 related verses.";
									echo module_uninstalled_html('holman','info','arrows-turn-to-dots','Holman Cross-Reference',$text,plugin_dir_url(__FILE__)); ?>



                                <div class="f1-buttons" style="clear:both;padding-top:30px">
                                    <button type="button" class="btn btn-previous"><i class="fa-solid fa-left-long"></i>&nbsp; Previous</button>
                                    <button type="button" class="btn btn-next">Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>
								<div class=" montserrat" style="float:left;width:49%;font-size:13px;line-height:1.3;color:#000;padding-top:20px">*More modules will be added with future updates. <br><br>Planned modules include: Public Domain Brown/Thayer Lexicon, Outline/Paragraph Notes, 1611 Margin Notes, Book Introductions, American King James Version, Webster's Version, Young's Literal Translation, Commentaries, Thompson Chain References, Custom Styles, Illustrations and many more!</div>
                            </fieldset>

                            <fieldset>
                                <h4>Select Page Name:</h4>
								<p>Once fully installed, this plugin will create a new page to house your Virtual Study Bible. Here, you can choose the name (or Title) of your page that will serve the Virtual Study Bible to your site visitors or members. The default has already been entered which you can use, or select your own.</p>
                                <div class="form-group">
                                    <label class="sr-onlyx" for="f1-pagename">Page Title</label>
                                    <input type="text" name="f1-pagename" placeholder="Page Name or Title" class="form-control" id="f1-pagename" value="Study Bible">
                                </div>
                                <div class="form-group">
                                    <label class="sr-onlyx" for="f1-pageslug">Permalink</label>
                                    <input type="text" disabled name="f1-pageslug" placeholder="Page Slug" class="form-control" id="f1-pageslug" value="<?php echo site_url(); ?>/study-bible" style="background-color:#fff;color:#000;cursor:auto;">
                                </div>

								<div class="vertical-spacer" style="width:100%;height:30px"></div>
                                <h4>Select Page Style(s):</h4>
								<p>By default all three page styles are offered as options for your site's visitors. However, if you want, you can limit the choice to just one or two styles you prefer.</p>
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="block icon-block-style bg-secondary-faded w-border-2x border-secondary inner-space rounded-2x text-center module">
										<h6 class="box-title poppins-black" style="color:#000">Traditional</h6>
										<div style="width:100%;height:230px">
											<img class="card-img-top img-thumbnail" 
												src="<?php echo plugin_dir_url(__FILE__);?>images/style-traditional.jpg">
										</div>
										<p class="box-description montserrat">This is the traditional layout, where verses are separate and paragraphs are marked with the ¶ character. The first letter of the first verse is larger and the remaining text flows around it.</p>
										<button 
											type="button" 
											class="btn btn-info-faded montserrat"
											style="	padding:4px 20px;
													font-size:14px;
													line-height:1.0;
													border-radius:5px;
													box-shadow:1px 1px 3px  rgba(0, 0, 0, 0.76);
													font-weight:bold;
													letter-spacing:1px;
													color:#000;
													position:absolute;
													bottom:30px;
													left:0;
													right:0;
													margin-left:auto;
													margin-right:auto;
													width:160px;">Selected<small style="font-size:11px;font-weight:normal"><br>(Click to unselect)</small></button>
									</div><!-- / icon-block -->
								</div><!-- / column -->
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="block icon-block-style bg-secondary-faded w-border-2x border-secondary inner-space rounded-2x text-center module">
										<h6 class="box-title poppins-black" style="color:#000">Paragraph</h6>
										<div style="width:100%;height:230px">
											<img class="card-img-top img-thumbnail" 
												src="<?php echo plugin_dir_url(__FILE__);?>images/style-paragraph.jpg">
										</div>
										<p class="box-description montserrat">
											This is the more modern look where the text flows as paragraphs, verses are marked by a small superscript and the chapter number appears as a large red number.</p>
										<button 
											type="button" 
											class="btn btn-info-faded montserrat"
											style="	padding:4px 20px;
													font-size:14px;
													line-height:1.0;
													border-radius:5px;
													box-shadow:1px 1px 3px  rgba(0, 0, 0, 0.76);
													font-weight:bold;
													letter-spacing:1px;
													color:#000;
													position:absolute;
													bottom:30px;
													left:0;
													right:0;
													margin-left:auto;
													margin-right:auto;
													width:160px">Selected<small style="font-size:11px;font-weight:normal"><br>(Click to unselect)</small></button>
									</div><!-- / icon-block -->
								</div><!-- / column -->
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="block icon-block-style bg-secondary-faded w-border-2x border-secondary inner-space rounded-2x text-center module">
										<h6 class="box-title poppins-black" style="color:#000">The Reader's Bible</h6>
										<div style="width:100%;height:230px">
											<img class="card-img-top img-thumbnail" 
												src="<?php echo plugin_dir_url(__FILE__);?>images/style-reading.jpg">
										</div>
										<p class="box-description montserrat">For the Reader's Bible the text is laid out like a book, but with no verse markings. Book and Chapter headings alone are marked.</p>
										<button 
											type="button" 
											class="btn btn-info-faded montserrat"
											style="	padding:4px 20px;
													font-size:14px;
													line-height:1.0;
													border-radius:5px;
													box-shadow:1px 1px 3px  rgba(0, 0, 0, 0.76);
													font-weight:bold;
													letter-spacing:1px;
													color:#000;
													position:absolute;
													bottom:30px;
													left:0;
													right:0;
													margin-left:auto;
													margin-right:auto;
													width:160px">Selected<small style="font-size:11px;font-weight:normal"><br>(Click to unselect)</small></button>
									</div><!-- / icon-block -->
								</div><!-- / column -->

                                <div class="f1-buttons" style="clear:both;padding-top:30px">
                                    <button type="button" class="btn btn-previous"><i class="fa-solid fa-left-long"></i>&nbsp; Previous</button>
                                    <button type="button" class="btn btn-next">Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>
								<div class=" montserrat" style="float:left;width:49%;font-size:13px;line-height:1.3;color:#000;padding-top:20px">(More detailed styling choices wil be offered in future updates.)</div>

                            </fieldset>

							<fieldset>
                                <h4>Publish your Virtual Study Bible:</h4>
								<h6>Modules installed...</h6>
								<button type="button" class="btn btn-secondary-faded pill btn-module">
										<i class="fa-solid fa-list"></i> &nbsp;
										Books of the Bible
								</button>
								<button type="button" class="btn btn-danger-faded pill border-danger btn-module dark" style="text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56)">
										<i class="fa-solid fa-book-bible"></i> &nbsp;
										King James Text
								</button>
								<button type="button" class="btn btn-info-faded border-info pill btn-module dark" style="text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56)">
										<i class="fa-solid fa-language"></i> &nbsp;
										Strong's Hebrew and Greek Lexicons
								</button>
								<button type="button" class="btn btn-warning-faded border-warning pill btn-module dark" style="text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56)">
										<i class="fa-solid fa-arrow-down-a-z"></i> &nbsp;
										Easton's Bible Dictionary
								</button>
								<button type="button" class="btn btn-success-faded pill border-success btn-module dark" style="text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56)">
										<span style="font-size:23px;font-family:'Times New Roman';vertical-align:bottom">&#1488;</span> &nbsp;
										Masoretic Hebrew Text
								</button>
								<button type="button" class="btn btn-primary-faded border-primary pill btn-module dark" style="text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56)">
								<span style="font-size:21px;font-family:'Times New Roman';vertical-align:bottom">&Sigma;</span> &nbsp;
										Textus Receptus Greek Text
								</button>
								<h6>Page Title...</h6>
								<button type="button" class="btn btn-secondary-faded pill btn-module">
										Study Bible
								</button>
								<h6>Styles Selected...</h6>
								<button type="button" class="btn btn-secondary-faded pill btn-module">
										Traditional
								</button>
								<button type="button" class="btn btn-secondary-faded pill btn-module">
										Paragraph
								</button>
								<button type="button" class="btn btn-secondary-faded pill btn-module">
										The Reader's Bible
								</button>
                                <div class="f1-buttons">
                                    <button type="button" class="btn btn-previous"><i class="fa-solid fa-left-long"></i>&nbsp; Previous</button>
                                    <button type="submit" class="btn btn-submit">Submit & Create&nbsp; <i class="fa-solid fa-square-check"></i></button>
                                </div>
							</fieldset>
                    	
                    	</form>
                    </div>
                </div>
                    
            </div>
        </div>
	</div>
</div>
<script>
	

window.addEventListener('load',function()
	{

	<?php echo module_uninstalled_js('kjvs',plugin_dir_url(__FILE__)); ?>

	<?php echo module_uninstalled_js('strongs',plugin_dir_url(__FILE__)); ?>

	<?php echo module_uninstalled_js('eastons',plugin_dir_url(__FILE__)); ?>
		
	});

	
	function housekeeping(file)
		{
		var nonce_url = "<?php echo wp_nonce_url(plugin_dir_url(__FILE__).'ajax/housekeeping.php','housekeeping'); ?>";
		console.log(nonce_url);
		$.ajax(
			{
			type: 'GET',
			url: nonce_url,
			data: {file,file},
			success: function(data){
				}
			});
		}

	function rotate_progress(module) 
		{
		$("#progress-"+module+"-circle.vb-progress").each(function() 
			{
			var value = $(this).attr('data-value');
			var left = $(this).find('.progress-left .progress-bar');
			var right = $(this).find('.progress-right .progress-bar');

			if (value > 0) 
				{
				if (value <= 50) 
					{
					right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
					} 
				else 
					{
					right.css('transform', 'rotate(180deg)')
					left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
					}
				}
			})

		function percentageToDegrees(percentage) 
			{
			return percentage / 100 * 360
			}
		}
</script>