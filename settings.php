<?php

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}

wp_register_style('vb-bootstrap-css', plugins_url().'/the-virtual-study-bible/css/bootstrap.css');
wp_enqueue_style('vb-bootstrap-css');
wp_register_style('vb-logofont-css', plugins_url().'/the-virtual-study-bible/css/logofont.css');
wp_enqueue_style('vb-logofont-css');
wp_register_style('vb-stepwizard-css', plugins_url().'/the-virtual-study-bible/css/stepwizard.css');
wp_enqueue_style('vb-stepwizard-css');
wp_register_style('vb-fade-css', plugins_url().'/the-virtual-study-bible/css/fade.css');
wp_enqueue_style('vb-fade-css');
wp_register_style('vb-styles-css', plugins_url().'/the-virtual-study-bible/css/vb-styles.css');
wp_enqueue_style('vb-styles-css');

wp_register_script('vb-jquery-js', plugins_url().'/the-virtual-study-bible/js/jquery-2.2.4.js');
wp_enqueue_script('vb-jquery-js');
wp_register_script('vb-bootstrap-js', plugins_url().'/the-virtual-study-bible/js/bootstrap.js');
wp_enqueue_script('vb-bootstrap-js');
wp_register_script('vb-stepwizard-js', plugins_url().'/the-virtual-study-bible/js/stepwizard.js');
wp_enqueue_script('vb-stepwizard-js');


include_once(plugin_dir_path(__FILE__).'includes/modules.php');
include_once(plugin_dir_path(__FILE__).'includes/functions.php');

$virtual_bible_page_name=$_vb->getMeta('page_name');
if(!$virtual_bible_page_name){$virtual_bible_page_name='Study Bible';}
$virtual_bible_page_name_slug=sanitize_title($virtual_bible_page_name);

$module_path=plugin_dir_path(__FILE__).'modules/';
$Modules=[];
$_handle=opendir($module_path);
while ($file = readdir($_handle))
	{ 
	if (strstr($file, '.xml'))
		{
		$_name=str_replace('.xml','',$file);
		$Modules[$_name]=[];
		$Modules[$_name]['file']=$file;
		$Modules[$_name]['installed']=$_vbm->is_module_installed($_name);
		$__moduleData=simplexml_load_file($module_path.$file, 'SimpleXMLElement', LIBXML_NOCDATA);
		$Modules[$_name]['type']=(string)$__moduleData->type;
		$Modules[$_name]['description']=(string)$__moduleData->description;
		$Modules[$_name]['color']=(string)$__moduleData->color;
		$Modules[$_name]['icon']=(string)$__moduleData->icon;
		$Modules[$_name]['title']=(string)$__moduleData->title;
		$Modules[$_name]['optional']=(string)$__moduleData->optional;
		}
	}
closedir($_handle);

$virtual_bible_traditional_select=$_vb->getMeta('style_traditional');
$virtual_bible_paragraph_select=$_vb->getMeta('style_paragraph');
$virtual_bible_reader_select=$_vb->getMeta('style_reader');

$virtual_bible_settings_nonce=wp_create_nonce('virtual bible settings');

/* This is run when settings are saved  */
if(isset($_POST['virtual-bible-post-submitted']))
	{
	$virtual_bible_verify = wp_verify_nonce($_POST['_wpnonce'], 'virtual bible settings');
	if($virtual_bible_verify)
		{
		$virtual_bible_pagename=sanitize_text_field($_POST['virtual-bible-pagename']);
		$virtual_bible_oldpagename=sanitize_text_field($_POST['virtual-bible-old-pagename']);
		
		if(($virtual_bible_pagename != $virtual_bible_oldpagename) and get_page_by_title($virtual_bible_oldpagename, 'OBJECT', 'page'))
			{
			/* delete old page */
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
		/* Create new page */
		/* But first, check if the page already exists */
		$check_page_exist = get_posts(array('post_type'=>'page','title'=>$virtual_bible_pagename,'post_author'=>1));
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
								<!-- 
								<span class="text-success" title="Packaged intros, notes and commentaries"><strong>Study Template</strong></span>, 
								<span class="text-warning" title="Matthew Henry, Scofield, etc. These can also be part of a Study Template"><strong>Commentary</strong></span>, 
								<span class="text-primary" title="Illustrations, Maps, etc."><strong>Images</strong></span> 
								-->
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
								<small style="display:block;text-align:center">Many of these modules will take more than a few seconds to load, so we left them to be installed here, rather than when the plugin itself was installed.</small>
                    		    <h4>The Basic Modules: 
									<small>These are the core modules needed for your installation to be an actual Study Bible.</small></h4>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
									<div class="block icon-block bg-secondary-faded w-border-2x border-secondary inner-space rounded-2x text-center module">
										<i class="fa-solid fa-university md-icon dp24 box-icon bg-secondary-faded border-secondary pill"></i>
										<h6 class="box-title poppins-black" style="color:#000">Books of the Bible<br />Book Introductions and Outlines</h6>
										<p class="box-description montserrat">These modules were loaded when you installed the plugin. It includes data about the Books of the Bible as well as book introductions and outlines by John MacArthur <small>(used by permission)</small>.</p>
										<div id="module-books-installed" class="module-installed light">Module Installed!</div>
									</div>
								</div>

								<?php 
								/* Load Basic Modules... */
									foreach($Modules as $_name=>$Module)
										{
										if($Module['optional']=='FALSE')
											{	
											if($Module['installed']!='')
												{
												echo $_vbm->module_installed_html($_name,$Module['color'],$Module['icon'],$Module['title'],$Module['description'],plugin_dir_url(__FILE__),$Module['installed']);
												}
											else
												{
												echo $_vbm->module_uninstalled_html($_name,$Module['color'],$Module['icon'],$Module['title'],$Module['description'],plugin_dir_url(__FILE__));
												}								 											
											}
										}
								?>


                                <div class="f1-buttons" style="clear:both;padding-top:30px">
                                    <button type="button" class="btn btn-next" >Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>
								<div class="vertical-spacer" style="clear:both;width:100%;height:50px"></div>
                            </fieldset>

                            <fieldset>
                                <h4 style="width:70%">Additional Modules*:
									<small>These modules are optional, in that you don't need them to run the basic Study Bible page. </small></h4>
                                <div class="f1-buttons" style="clear:both;margin-top:-40px">
                                    <button type="button" class="btn btn-previous" style="height:30px;line-height:1"><i class="fa-solid fa-left-long"></i>&nbsp; Previous</button>
                                    <button type="button" class="btn btn-next" style="height:30px;line-height:1">Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>

									
								<?php 
								/* Load Optional Modules... */
									foreach($Modules as $_name=>$Module)
										{
										if($Module['optional']=='TRUE')
											{
											if($Module['installed']!='')
												{
												echo $_vbm->module_installed_html($_name,$Module['color'],$Module['icon'],$Module['title'],$Module['description'],plugin_dir_url(__FILE__),$Module['installed']);
												}
											else
												{
												echo $_vbm->module_uninstalled_html($_name,$Module['color'],$Module['icon'],$Module['title'],$Module['description'],plugin_dir_url(__FILE__));
												}	
											}
										}

								?>



                                <div class="f1-buttons" style="clear:both;padding-top:30px">
                                    <button type="button" class="btn btn-previous"><i class="fa-solid fa-left-long"></i>&nbsp; Previous</button>
                                    <button type="button" class="btn btn-next">Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>
								<div class=" montserrat" style="float:left;width:49%;font-size:13px;line-height:1.3;color:#000;padding-top:20px">*More modules will be added with future updates. <br><br>Planned modules include: Public Domain Brown/Thayer Lexicon, 1611 Margin Notes, More Book Introductions, American King James Version, Webster's Version, Young's Literal Translation, Commentaries, Thompson Chain References, Custom Styles, Illustrations and many more!</div>
                            </fieldset>

                            <fieldset>
                                <h4>Select Page Name:</h4>
                                <div class="f1-buttons" style="clear:both;margin-top:-40px">
                                    <button type="button" class="btn btn-previous" style="height:30px;line-height:1"><i class="fa-solid fa-left-long"></i>&nbsp; Previous</button>
                                    <button type="button" class="btn btn-next" style="height:30px;line-height:1">Next &nbsp;<i class="fa-solid fa-right-long"></i></button>
                                </div>
								<p>Once fully installed, this plugin will create a new page to house your Virtual Study Bible. Here, you can choose the name (or Title) of your page that will serve the Virtual Study Bible to your site's visitors or members. The default has already been entered which you can use, or select your own.</p>
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
                                    <input type="text" disabled id="virtual-bible-pageslug" name="virtual-bible-pageslug" placeholder="Page Slug" class="form-control" id="virtual-bible-pageslug" value="<?php echo site_url(); ?>/<?php echo $virtual_bible_page_name_slug; ?>" style="background-color:#fff;color:#444;cursor:auto;font-weight:400">
                                </div>

								<div class="vertical-spacer" style="width:100%;height:30px"></div>
                                <h4>Select Page Style(s):</h4>
								<p>By default all three page styles are offered as options for your site's visitors. However, if you want, you can limit the choice to just one or two styles you prefer.</p>
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="block icon-block-style bg-secondary-faded w-border-2x border-secondary inner-space rounded-2x text-center module">
										<h6 class="box-title poppins-black" style="color:#000">Traditional</h6>
										<div style="width:100%;height:230px">
											<img class="card-img-top img-thumbnail" style="max-height:230px"
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
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="block icon-block-style bg-secondary-faded w-border-2x border-secondary inner-space rounded-2x text-center module">
										<h6 class="box-title poppins-black" style="color:#000">Paragraph</h6>
										<div style="width:100%;height:230px">
											<img class="card-img-top img-thumbnail"  style="max-height:230px"
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
									</div>
								</div>
								<div class="col-sm-12 col-md-6 col-lg-4">
									<div class="block icon-block-style bg-secondary-faded w-border-2x border-secondary inner-space rounded-2x text-center module">
										<h6 class="box-title poppins-black" style="color:#000">The Reader's Bible</h6>
										<div style="width:100%;height:230px">
											<img class="card-img-top img-thumbnail"  style="max-height:230px"
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
									</div>
								</div>

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
								<?php
								foreach($Modules as $_name=>$Module)
									{
									if($Module['installed']=='installed')
										{
										echo "
										<button type=\"button\" class=\"btn btn-{$Module['color']}-faded pill border-{$Module['color']} btn-module dark button-{$_name}\" style=\"text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56)\">
											<i class=\"fa-solid fa-{$Module['icon']}\"></i> &nbsp;
											{$Module['title']}
										</button>";
										}
									else
										{
										echo "
										<button type=\"button\" class=\"btn btn-{$Module['color']}-faded pill border-{$Module['color']} btn-module dark button-{$_name}\" style=\"text-shadow:1px 1px 5px rgba(0, 0, 0, 0.56);display:none\">
											<i class=\"fa-solid fa-{$Module['icon']}\"></i> &nbsp;
											{$Module['title']}
										</button>";										
										}
									}
								?>
	
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
                                    <button id="settings-submit" type="submit" class="btn btn-submit" disabled>Submit & Publish&nbsp; <i class="fa-solid fa-square-check"></i></button>
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

	<?php 
		foreach($Modules as $_name=>$Module)
			{
			if($Module['type']=='Basic')
				{
				echo $_vbm->module_uninstalled_js($_name,plugin_dir_url(__FILE__));
				}
			else
				{
				echo $_vbm->module_uninstalled_js($_name,plugin_dir_url(__FILE__));
				echo $_vbm->module_installed_js($_name,plugin_dir_url(__FILE__));

				}

			}
		
	 ?>

	$(".toggle-style").click(function(e) 
		{
		var nonce_url = "<?php echo wp_nonce_url(plugin_dir_url(__FILE__).'ajax/select-unselect-style.php','select-unselect-style'); ?>";
		e.preventDefault();
		var virtual_bible_style_selected=$(this).attr('id');
//		console.log(virtual_bible_style_selected);
		var target=virtual_bible_style_selected.replace('virtual-bible-style-','');
//		console.log('target='+target);
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


	var vb_watcher = setInterval
		(
		function()
			{
			var watcher_nonce_url = "<?php echo wp_nonce_url(plugin_dir_url(__FILE__).'ajax/worker.php','vb_watcher'); ?>";
			$.get
				(
				watcher_nonce_url, 
				{function:'settings_watch'},
				function(data) 
					{
					if(data=='installed')
						{
						$("#settings-submit").prop('disabled',false);
						}
					}
				);
			}, 1000
		); 


		
	});	//End window.addEventListener('load',function()

	
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






?>