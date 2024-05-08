<?php

wp_register_style('vb-bootstrap-css', plugins_url().'/the-virtual-study-bible/css/bootstrap.css');
wp_enqueue_style('vb-bootstrap-css');
wp_register_style('vb-fonts-css', plugins_url().'/the-virtual-study-bible/css/fontawesome.css');
wp_enqueue_style('vb-fonts-css');
wp_register_style('vb-logofont-css', plugins_url().'/the-virtual-study-bible/css/logofont.css');
wp_enqueue_style('vb-logofont-css');
wp_register_style('vb-stepwizard-css', plugins_url().'/the-virtual-study-bible/css/stepwizard.css');
wp_enqueue_style('vb-stepwizard-css');
wp_register_style('vb-fade-css', plugins_url().'/the-virtual-study-bible/css/fade.css');
wp_enqueue_style('vb-fade-css');
wp_register_style('vb-styles-css', plugins_url().'/the-virtual-study-bible/css/vb-styles.css');
wp_enqueue_style('vb-styles-css');

wp_register_script('vb-jquery-js', plugins_url().'/the-virtual-study-bible/js/jquery-1.11.1.js');
wp_enqueue_script('vb-jquery-js');
wp_register_script('vb-bootstrap-js', plugins_url().'/the-virtual-study-bible/js/bootstrap.js');
wp_enqueue_script('vb-bootstrap-js');
wp_register_script('vb-stepwizard-js', plugins_url().'/the-virtual-study-bible/js/stepwizard.js');
wp_enqueue_script('vb-stepwizard-js');


include_once(plugin_dir_path(__FILE__).'includes/modules.php');
include_once(plugin_dir_path(__FILE__).'includes/functions.php');

$virtual_bible_page_name=virtual_bible_getMeta('page_name');
if(!$virtual_bible_page_name){$virtual_bible_page_name='Study Bible';}
$virtual_bible_page_name_slug=sanitize_title($virtual_bible_page_name);

$virtual_bible_kjvs_installed=virtual_bible_is_module_installed('kjvs');
$virtual_bible_strongs_installed=virtual_bible_is_module_installed('strongs');
$virtual_bible_eastons_installed=virtual_bible_is_module_installed('eastons');
$virtual_bible_hebrew_installed=virtual_bible_is_module_installed('hebrew');
$virtual_bible_greek_installed=virtual_bible_is_module_installed('greek');
$virtual_bible_holman_installed=virtual_bible_is_module_installed('holman');
$virtual_bible_traditional_select=virtual_bible_getMeta('style_traditional');
$virtual_bible_paragraph_select=virtual_bible_getMeta('style_paragraph');
$virtual_bible_reader_select=virtual_bible_getMeta('style_reader');


$virtual_bible_settings_nonce=wp_create_nonce('virtual bible settings');

if(isset($_POST['virtual-bible-post-submitted']))
	{
	$vb_post=getPrintR($_POST);
	$virtual_bible_verify = wp_verify_nonce($_POST['_wpnonce'], 'virtual bible settings');
/****************************************************************
 * 	Settings Submitted... 
 * 		if page name != old page name... 
 * 			delete old page 
 * 		if page name !exist... 
 * 			create page.
 * 			content = '[The-Virtual-Study-Bible]' 
 * 		Go to page... 
 * 			If admin, show link to settings and help.
 * 
 ****************************************************************/
	if($virtual_bible_verify)
		{
		$virtual_bible_pagename=sanitize_text_field($_POST['virtual-bible-pagename']);
		$virtual_bible_oldpagename=sanitize_text_field($_POST['virtual-bible-old-pagename']);
		
		if(($virtual_bible_pagename != $virtual_bible_oldpagename) and get_page_by_title($virtual_bible_oldpagename, 'OBJECT', 'page'))
			{
			# delete old page
			$wpdb->delete
				(
				$wpdb->prefix.'posts',
				array
					(
					'post_type' => 'page',
					'post_author' => 1,
					'post_title' => $virtual_bible_oldpagename
					)
				);
			}
		# Create new page 
		$check_page_exist = get_posts(array('post_type'=>'page','title'=>$virtual_bible_pagename,'post_author'=>1));
		// Check if the page already exists
		if(empty($check_page_exist)) 
			{
			$page_id = wp_insert_post
				(
				array
					(
					'comment_status' => 'close',
					'ping_status'    => 'close',
					'post_author'    => 1,
					'post_title'     => $virtual_bible_pagename,
					'post_name'      => strtolower(str_replace(' ', '-', $virtual_bible_pagename)),
					'post_status'    => 'publish',
					'post_content'   => '[The-Virtual-Study-Bible]',
					'post_type'      => 'page'
					)
				);
			}
		}
	}
else
	{
	$vb_post='';
	}

?>
<!-- The Virtual Study Bible Plugin: Settings Start -->
<h2 class="poppins-black" style="color:#3a72d3;text-shadow: 1px 1px 0px #fff, 2px 2px 5px #333;">
	<img src="<?php echo plugin_dir_url(__FILE__);?>logo.png" style="width:45px;" /> 
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
                    	<form role="form" method="POST" class="f1" style="padding:10px;">
							<input type="hidden" name="_wpnonce" value="<?php echo $virtual_bible_settings_nonce; ?>" />
							<input type="hidden" name="virtual-bible-post-submitted" value="true" />
                    		<h3 class="poppins-black">Getting Started...</h3>
                    		<p style="font-size:15px">These are the steps to getting your Study Bible live on your site!</p>
                    		<p style="margin-top:-10px;font-size:12px;text-shadow:1px 1px 2px rgba(255,255,255,1),1px 1px 10px rgba(0,0,0,0.5)">Color guide: 
								<span class="text-danger" title="Bible text in versions, translation and foreign."><strong>Bible Text</strong></span>, 
								<span class="text-info" title="Dictionaries, Lexicons, Margin Notes, etc."><strong>Reference</strong></span>
								<!-- , 
								<span class="text-success" title="Packaged intros, notes and commentaries"><strong>Study Template</strong></span>, 
								<span class="text-warning" title="Matthew Henry, Scofield, etc. These can also be part of a Study Template"><strong>Commentary</strong></span>, 
								<span class="text-primary" title="Illustrations, Maps, etc."><strong>Images</strong></span> -->
							</p>
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
                    		    <h4>The Basic Modules: 
									<small>Many of these modules will take more than a few seconds to load, so we left them to be installed here, rather than when the plugin itself was installed.</small></h4>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
									<div class="block icon-block bg-secondary-faded w-border-2x border-secondary inner-space rounded-2x text-center module">
										<i class="fa-solid fa-list md-icon dp36 box-icon bg-secondary-faded border-secondary pill"></i>
										<h6 class="box-title poppins-black" style="color:#000">Books of the Bible</h6>
										<p class="box-description montserrat">This module was loaded when you installed the plugin. It includes the names of the books of the Bible, the most common abbreviations (for searching purposes) and the long name of each book.</p>
										<div id="module-books-installed"
										style="	padding:4px 20px;
												font-size:14px;
												line-height:1.1;
												letter-spacing:1px;
												color:#000;
												height:40px;
												position:absolute;
												bottom:30px;
												left:0;right:0;
												margin-left:auto;
												margin-right:auto;">Module Installed!</div>
									</div><!-- / icon-block -->
								</div><!-- / column -->

								<?php 
									$virtual_bible_text="This will install the full King James Authorized Text (Cambridge variant), with words keyed to Strong's.<small style=\"display:block;margin-top:-4px\">[size: 8.8M]</small>";
									if($virtual_bible_kjvs_installed)
										{
										echo virtual_bible_module_installed_html('kjvs','danger','book-bible','The King James Bible',$virtual_bible_text,plugin_dir_url(__FILE__),$virtual_bible_kjvs_installed); 
										}
									else
										{
										echo virtual_bible_module_uninstalled_html('kjvs','danger','book-bible','The King James Bible',$virtual_bible_text,plugin_dir_url(__FILE__)); 
										}
								?>

									
								<?php 
									$virtual_bible_text="This will load both the Hebrew and Greek lexicons (14,298 entries!). Many of the Bible texts will be keyed to the corresponding entries, to provide quick access to the definition.<small style=\"display:block;margin-top:-4px\">[size: 5.4M]</small>";
									if($virtual_bible_strongs_installed)
										{
										echo virtual_bible_module_installed_html('strongs','info','language','Strong&rsquo;s Hebrew and Greek Lexicons',$virtual_bible_text,plugin_dir_url(__FILE__),$virtual_bible_strongs_installed);
										}
									else
										{
										echo virtual_bible_module_uninstalled_html('strongs','info','language','Strong&rsquo;s Hebrew and Greek Lexicons',$virtual_bible_text,plugin_dir_url(__FILE__));
										}
								 ?>


                                <div class="f1-buttons" style="clear:both;padding-top:30px">
                                    <button type="button" class="btn btn-next" >Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>
								<div class="vertical-spacer" style="clear:both;width:100%;height:50px"></div>
                            </fieldset>

                            <fieldset>
                                <h4>Additional Modules*:</h4>
                                <div class="f1-buttons" style="clear:both;margin-top:-40px">
                                    <button type="button" class="btn btn-previous" style="height:30px;line-height:1"><i class="fa-solid fa-left-long"></i>&nbsp; Previous</button>
                                    <button type="button" class="btn btn-next" style="height:30px;line-height:1">Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>
								<?php 
									$text="This will load the Easton's Bible Dictionary (3,963 entries!). When selected, words in the text will be keyed to the matching Easton's definition and displayed when clicked.<small style=\"display:block;margin-top:-4px\">[size: 2.6M]</small>";
									if($virtual_bible_eastons_installed)
										{
										echo virtual_bible_module_installed_html('eastons','info','arrow-down-a-z','Easton&rsquo;s Bible Dictionary',$virtual_bible_text,plugin_dir_url(__FILE__),$virtual_bible_eastons_installed);
										}
									else
										{
										echo virtual_bible_module_uninstalled_html('eastons','info','arrow-down-a-z','Easton&rsquo;s Bible Dictionary',$virtual_bible_text,plugin_dir_url(__FILE__));
										}
								?>
									
								<?php 
									$virtual_bible_text="This will load the entire Masoretic (Leningrad Codex) Hebrew (Old Testament) text. It can be displayed alongside the English text, and the verses will be matched by highlighting the corresponding verse.<small style=\"display:block;margin-top:-4px\">[size: 5.9M]</small>";
									if($virtual_bible_hebrew_installed)
										{
										echo virtual_bible_module_installed_html('hebrew','danger','&#1488;','Hebrew Text',$virtual_bible_text,plugin_dir_url(__FILE__),$virtual_bible_hebrew_installed);
										}
									else
										{
										echo virtual_bible_module_uninstalled_html('hebrew','danger','&#1488;','Hebrew Text',$virtual_bible_text,plugin_dir_url(__FILE__));
										}
								 ?>

								<?php 
									$virtual_bible_text="This will load the entire Textus Receptus Greek (New Testament) text. It can be displayed alongside the English text, and the verses, words and phrases will be matched by highlighting the corresponding text.<small style=\"display:block;margin-top:-4px\">[size: 8.7M]</small>";
									if($virtual_bible_greek_installed)
										{
										echo virtual_bible_module_installed_html('greek','danger','&Sigma;','Greek Text',$virtual_bible_text,plugin_dir_url(__FILE__),$virtual_bible_greek_installed);
										}
									else
										{
										echo virtual_bible_module_uninstalled_html('greek','danger','&Sigma;','Greek Text',$virtual_bible_text,plugin_dir_url(__FILE__));
										} 
								?>

								<?php 
									$virtual_bible_text="This will load the Holman Crossreference, linking 3,992 verses to 57,812 related verses.<small style=\"display:block;margin-top:-4px\">[size: 3.5M]</small>";
									if($virtual_bible_holman_installed)
										{
										echo virtual_bible_module_installed_html('holman','info','arrows-turn-to-dots','Holman Cross-Reference',$virtual_bible_text,plugin_dir_url(__FILE__),$virtual_bible_holman_installed);
										}
									else
										{
										echo virtual_bible_module_uninstalled_html('holman','info','arrows-turn-to-dots','Holman Cross-Reference',$virtual_bible_text,plugin_dir_url(__FILE__));
										}  
								?>



                                <div class="f1-buttons" style="clear:both;padding-top:30px">
                                    <button type="button" class="btn btn-previous"><i class="fa-solid fa-left-long"></i>&nbsp; Previous</button>
                                    <button type="button" class="btn btn-next">Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>
								<div class=" montserrat" style="float:left;width:49%;font-size:13px;line-height:1.3;color:#000;padding-top:20px">*More modules will be added with future updates. <br><br>Planned modules include: Public Domain Brown/Thayer Lexicon, Outline/Paragraph Notes, 1611 Margin Notes, Book Introductions, American King James Version, Webster's Version, Young's Literal Translation, Commentaries, Thompson Chain References, Custom Styles, Illustrations and many more!</div>
                            </fieldset>

                            <fieldset>
                                <h4>Select Page Name:</h4>
                                <div class="f1-buttons" style="clear:both;margin-top:-40px">
                                    <button type="button" class="btn btn-previous" style="height:30px;line-height:1"><i class="fa-solid fa-left-long"></i>&nbsp; Previous</button>
                                    <button type="button" class="btn btn-next" style="height:30px;line-height:1">Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>
								<p>Once fully installed, this plugin will create a new page to house your Virtual Study Bible. Here, you can choose the name (or Title) of your page that will serve the Virtual Study Bible to your site visitors or members. The default has already been entered which you can use, or select your own.</p>
                                <div class="form-group" id="virtual-bible-pagename-group">
                                    <label for="virtual-bible-pagename">Page Title <small style="font-weight:normal;font-size:11px">(If the page has already been created, and you are changing the title, and thus the slug, a new page will be created and the old page will be deleted.)</small></label>
                                    <input type="text" name="virtual-bible-pagename" placeholder="Page Name or Title" class="form-control" id="virtual-bible-pagename" value="<?php echo $virtual_bible_page_name; ?>" 
										onkeyUp="
											$('#virtual-bible-pageslug').val(virtual_bible_convertToSlug(this.value));
											$('#fvirtual-bible-pagename-final').html($(this).val());
											">
									<input type="hidden" name="virtual-bible-old-pagename" value="<?php echo $virtual_bible_page_name; ?>" />
                                </div>
                                <div class="form-group" style="clear:both">
                                    <label for="virtual-bible-pageslug">Permalink</label>
                                    <input type="text" disabled id="virtual-bible-pageslug" name="virtual-bible-pageslug" placeholder="Page Slug" class="form-control" id="virtual-bible-pageslug" value="<?php echo site_url(); ?>/<?php echo $virtual_bible_page_name_slug; ?>" style="background-color:#fff;color:#000;cursor:auto;">
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
											type="button" id="virtual-bible-style-traditional" 
											class="btn btn-info-faded montserrat toggle-style"
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
													width:160px;">
										<?php
										if($virtual_bible_traditional_select=='selected')
											{
											?>Selected<small style="font-size:11px;font-weight:normal"><br>(Click to unselect)</small>
											<?php
											}
										else
											{
											?>Unselected<small style="font-size:11px;font-weight:normal"><br>(Click to select)</small>
											<?php
											}
										?></button>
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
											type="button"  id="virtual-bible-style-paragraph" 
											class="btn btn-info-faded montserrat toggle-style"
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
													width:160px">
										<?php
										if($virtual_bible_paragraph_select=='selected')
											{
											?>Selected<small style="font-size:11px;font-weight:normal"><br>(Click to unselect)</small>
											<?php
											}
										else
											{
											?>Unselected<small style="font-size:11px;font-weight:normal"><br>(Click to select)</small>
											<?php
											}
										?></button>
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
											type="button"  id="virtual-bible-style-reader" 
											class="btn btn-info-faded montserrat toggle-style"
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
													width:160px">
										<?php
										if($virtual_bible_reader_select=='selected')
											{
											?>Selected<small style="font-size:11px;font-weight:normal"><br>(Click to unselect)</small>
											<?php
											}
										else
											{
											?>Unselected<small style="font-size:11px;font-weight:normal"><br>(Click to select)</small>
											<?php
											}
										?></button>
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
									Strong's Lexicons
								</button>
								<button type="button" id="eastons-selected" class="btn btn-info-faded border-info pill btn-module dark module-selected <?php echo $virtual_bible_eastons_installed;?>" style="text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56)">
									<i class="fa-solid fa-arrow-down-a-z"></i> &nbsp;
									Easton Dictionary
								</button>
								<button type="button" id="hebrew-selected" class="btn btn-danger-faded pill border-danger btn-module dark module-selected <?php echo $virtual_bible_hebrew_installed;?>" style="text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56)">
									<span style="font-size:23px;font-family:'Times New Roman';vertical-align:bottom">&#1488;</span> &nbsp;
									Hebrew Text
								</button>
								<button type="button" id="greek-selected" class="btn btn-danger-faded border-danger pill btn-module dark module-selected <?php echo $virtual_bible_greek_installed;?>" style="text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56)">
									<span style="font-size:21px;font-family:'Times New Roman';vertical-align:bottom">&Sigma;</span> &nbsp;
									Greek Text
								</button>
								<button type="button" id="holman-selected" class="btn btn-info-faded border-info pill btn-module dark module-selected <?php echo $virtual_bible_holman_installed;?>" style="text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56)">
									<i class="fa-solid fa-arrows-turn-to-dots"></i> &nbsp;
									Holman Xref
								</button>
								<h6>Page Title...</h6>
								<button type="button" id="virtual-bible-pagename-final" class="btn btn-secondary-faded pill btn-module">
									Study Bible
								</button>
								<h6>Styles Selected...</h6>
								<?php
								if($virtual_bible_traditional_select=='selected')
									{
								?>
								<button type="button" id="traditional-selected" class="btn btn-secondary-faded pill btn-module">
									Traditional
								</button>
								<?php
									}
								else
									{
								?>
								<button type="button" id="traditional-selected" class="btn btn-secondary-faded pill btn-module" style="display:none">
									Traditional
								</button>
								<?php
									}
								?>
								<?php
								if($virtual_bible_paragraph_select=='selected')
									{
								?>
								<button type="button" id="paragraph-selected" class="btn btn-secondary-faded pill btn-module">
									Paragraph
								</button>
								<?php
									}
								else
									{
								?>
								<button type="button" id="paragraph-selected" class="btn btn-secondary-faded pill btn-module" style="display:none">
									Paragraph
								</button>
								<?php
									}
								?>
								<?php
								if($virtual_bible_reader_select=='selected')
									{
								?>
								<button type="button" id="reader-selected" class="btn btn-secondary-faded pill btn-module">
									The Reader's Bible
								</button>
								<?php
									}
									else
										{
								?>
									<button type="button" id="reader-selected" class="btn btn-secondary-faded pill btn-module" style="display:none">
										The Reader's Bible
									</button>
								<?php
										}
								?>
                                <div class="f1-buttons">
                                    <button type="button" class="btn btn-previous"><i class="fa-solid fa-left-long"></i>&nbsp; Previous</button>
                                    <button type="submit" class="btn btn-submit">Submit & Publish&nbsp; <i class="fa-solid fa-square-check"></i></button>
                                </div>
							</fieldset>
                    	
                    	</form>
                    </div>
                </div>
                    
            </div>
        </div>
	</div>
</div>
<div style="margin:10px">
	Debug:
<?php
	if($vb_post){echo $vb_post;}
?>
</div>
<script>
	

window.addEventListener('load',function()
	{

	<?php echo virtual_bible_module_uninstalled_js('kjvs',plugin_dir_url(__FILE__)); ?>

	<?php echo virtual_bible_module_uninstalled_js('strongs',plugin_dir_url(__FILE__)); ?>

	<?php echo virtual_bible_module_uninstalled_js('eastons',plugin_dir_url(__FILE__)); ?>

	<?php echo virtual_bible_module_installed_js('eastons',plugin_dir_url(__FILE__)); ?>

	<?php echo virtual_bible_module_uninstalled_js('hebrew',plugin_dir_url(__FILE__)); ?>

	<?php echo virtual_bible_module_installed_js('hebrew',plugin_dir_url(__FILE__)); ?>

	<?php echo virtual_bible_module_uninstalled_js('greek',plugin_dir_url(__FILE__)); ?>

	<?php echo virtual_bible_module_installed_js('greek',plugin_dir_url(__FILE__)); ?>

	<?php echo virtual_bible_module_uninstalled_js('holman',plugin_dir_url(__FILE__)); ?>

	<?php echo virtual_bible_module_installed_js('holman',plugin_dir_url(__FILE__)); ?>

	$(".toggle-style").click(function(e) 
		{
		var nonce_url = "<?php echo wp_nonce_url(plugin_dir_url(__FILE__).'ajax/select-unselect-style.php','select-unselect-style'); ?>";
		e.preventDefault();
		var virtual_bible_style_selected=$(this).attr('id');
		console.log(virtual_bible_style_selected);
		var target=virtual_bible_style_selected.replace('virtual-bible-style-','');
		console.log('target='+target);
		$.ajax(
			{
			type: 'POST',
			url: nonce_url,
			data: {style:virtual_bible_style_selected},
			success: function(data)
				{
				if(data=='selected')
					{
					$("#"+virtual_bible_style_selected).html('Selected<small style="font-size:11px;font-weight:normal"><br>(Click to unselect)</small>');					
					$("#"+virtual_bible_style_selected).blur();
					console.log('1. '+data);
					$("#"+target+"-selected").css('display','inline-block');
					}
				else if(data=='unselected')
					{
					$("#"+virtual_bible_style_selected).html('Unselected<small style="font-size:11px;font-weight:normal"><br>(Click to select)</small>');
					console.log('2. '+data);
					$("#"+virtual_bible_style_selected).blur();
					$("#"+target+"-selected").css('display','none');
					}
				else
					{
					console.log('3. '+data);
					}
				}
			});
		});
		
	});

	
	function virtual_bible_housekeeping(file)
		{
		var nonce_url = "<?php echo wp_nonce_url(plugin_dir_url(__FILE__).'ajax/housekeeping.php','housekeeping'); ?>";
		$.ajax(
			{
			type: 'GET',
			url: nonce_url,
			data: {file,file},
			success: function(data){}
			});
		}

	function virtual_bible_rotate_progress(module) 
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
					right.css('transform', 'rotate(' + virtual_bible_percentageToDegrees(value) + 'deg)')
					} 
				else 
					{
					right.css('transform', 'rotate(180deg)')
					left.css('transform', 'rotate(' + virtual_bible_percentageToDegrees(value - 50) + 'deg)')
					}
				}
			})

		function virtual_bible_percentageToDegrees(percentage) 
			{
			return percentage / 100 * 360
			}

		}

		
	function virtual_bible_convertToSlug(Text) 
		{
		return Text.toLowerCase()
			.replace(/ /g, "-")
			.replace(/[^\w-]+/g, "");
		}
</script>
<!-- The Virtual Study Bible Plugin: Settings End -->

<?php



function getPrintR($array)
    {
    //hold on to the output
    ob_start();
    print_r($array);
    //store the output in a string
    $out =ob_get_contents();
    //delete the output, because we only wanted it in the string
    ob_clean();

    return "<pre style=\"margin-top:0px\">$out</pre>";
    }




?>