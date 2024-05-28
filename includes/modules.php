<?php

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}


function virtual_bible_is_module_installed($name)
	{
	global $wpdb;
	$table_name = $wpdb->prefix . 'virtual_bible_meta';
	$Results = $wpdb->get_results("SELECT meta_value from $table_name WHERE meta_key = 'module_$name' LIMIT 1;", ARRAY_A);
	if(isset($Results[0]['meta_value']) and $Results[0]['meta_value']!='')
		{
		return $Results[0]['meta_value'];
		}
	else
		{
		return FALSE;
		}
	}


function virtual_bible_module_uninstalled_html($name,$type,$fa_icon,$title,$text,$plugin_url)
	{
	$icon_style='';$icon_text='';
	if($name=='strongs')
		{
		$icon_style='padding-left:20px;padding-right:20px';
		}
	if($fa_icon=='&#1488;')
		{
		$icon_style="padding-top:7px;
		padding-bottom:13px;
		padding-left:27px;
		padding-right:27px;
		font-size:56px;
		font-family:'Times New Roman';
		line-height:1.4";
		$icon_text=$fa_icon;
		$fa_icon='';
		}
	if($fa_icon=='&Sigma;')
		{
		$icon_style="padding-top:7px;
		padding-bottom:13px;
		padding-left:30px;
		padding-right:30px;
		font-size:66px;
		font-family:'Times New Roman';
		line-height:1.9";
		$icon_text=$fa_icon;
		$fa_icon='';
		}

	$raw_html = <<<EOD
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
		<div class="block icon-block bg-{$type}-faded w-border-2x border-{$type} dark inner-space rounded-2x text-center module"  style="text-shadow:1px 1px 10px rgba(0, 0, 0, 0.56)">

			<!-- Progress bar for this module -->
			<div id="progress-{$name}-circle" class="vb-progress mx-auto" data-value='50' 
				style="margin:25px auto;color:#000;display:none;line-height:3.5;background-color:#fff; border-radius:50px;">
				<span class="progress-left">
					<span class="progress-bar border-primary"></span>
				</span>
				<span class="progress-right">
					<span class="progress-bar border-primary"></span>
				</span>
				<div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
					<div id="progress-{$name}-circle-text" class="h3 font-weight-bold">00%</div>
				</div>
			</div>
				<!-- END -->
			<i id="module-{$name}-icon" class="fa-solid fa-{$fa_icon} md-icon dp24 box-icon bg-{$type}-faded border-{$type} text-white pill" style="{$icon_style}">{$icon_text}</i>
			<h6 id="module-{$name}-title" class="box-title poppins-black">{$title}</h6>
			<p id="module-{$name}-text" class="box-description montserrat">{$text}</p>
			<button id="load-{$name}"
				type="button" 
				class="btn btn-info-faded montserrat"
				style="	padding:4px 20px;
						font-size:14px;
						line-height:1.1;
						border-radius:5px;
						box-shadow:1px 1px 3px  rgba(0, 0, 0, 0.76);
						letter-spacing:1px;
						color:#000;"><i class="fa fa-cloud-download"></i>&nbsp;Load Module...</button>
			<button id="loading-{$name}"
				type="button" 
				class="btn btn-info-faded montserrat"
				style="	padding:4px 20px;
						font-size:14px;
						line-height:1.1;
						border-radius:5px;
						box-shadow:1px 1px 3px  rgba(0, 0, 0, 0.76);
						letter-spacing:1px;
						color:#000;
						display:none;"></button>
			<div id="module-{$name}-installed"
			style="	padding:4px 20px;
					font-size:14px;
					line-height:1.1;
					letter-spacing:1px;
					color:#fff;
					height:40px;
					position:absolute;
					bottom:30px;
					left:0;right:0;margin-left:auto;margin-right:auto;
					display:none;">Module Installed!</div>
		</div><!-- / icon-block -->
	</div><!-- / column -->
	EOD;
	return $raw_html;
	}

	


function virtual_bible_module_installed_html($name,$type,$fa_icon,$title,$text,$plugin_url,$status='installed')
	{
	$icon_style='';$icon_text='';$disable=TRUE;
	if($name=='strongs')
		{
		$icon_style='padding-left:20px;padding-right:20px';
		$disable=FALSE;
		}
	if($name=='kjvs')
		{
		$disable=FALSE;
		}
	if($fa_icon=='&#1488;')
		{
		$icon_style="padding-top:7px;
		padding-bottom:13px;
		padding-left:27px;
		padding-right:27px;
		font-size:56px;
		font-family:'Times New Roman';
		line-height:1.4";
		$icon_text=$fa_icon;
		$fa_icon='';
		}
	if($fa_icon=='&Sigma;')
		{
		$icon_style="padding-top:7px;
		padding-bottom:13px;
		padding-left:23px;
		padding-right:23px;
		font-size:48px;
		font-family:'Times New Roman';
		line-height:1.6";
		$icon_text=$fa_icon;
		$fa_icon='';
		}

	if($status=='disabled')
		{
		$button = <<<EOD
		<div id="module-{$name}-disabled"
			style="	padding:4px 20px;
				font-size:12px;
				line-height:1.1;
				letter-spacing:1px;
				color:#fff;
				height:40px;
				position:absolute;
				bottom:35px;
				left:0;right:0;margin-left:auto;margin-right:auto;">Module installed but disabled!</div>
		<button id="enable-{$name}"
			type="button" 
			class="btn btn-info-faded montserrat"
			style="	padding:4px 10px;
					font-size:14px;
					line-height:1.1;
					border-radius:5px;
					box-shadow:1px 1px 3px  rgba(0, 0, 0, 0.76);
					letter-spacing:1px;
					color:#000;
					height:25px;
					width:100px;
					margin-bottom:-6px;">Enable</button>
		<div id="module-{$name}-installed"
			style="	padding:4px 20px;
				font-size:12px;
				line-height:1.1;
				letter-spacing:1px;
				color:#fff;
				height:40px;
				position:absolute;
				bottom:35px;
				left:0;right:0;margin-left:auto;margin-right:auto;display:none">Module installed but disabled!</div>
		<button id="disable-{$name}" title="This will disable the module, but leave the data in the database."
			type="button" 
			class="btn btn-info-faded montserrat"
			style="	padding:4px 10px;
					font-size:14px;
					line-height:1.1;
					border-radius:5px;
					box-shadow:1px 1px 3px  rgba(0, 0, 0, 0.76);
					letter-spacing:1px;
					color:#000;
					height:25px;
					width:100px;
					margin-bottom:-6px;display:none">Disable</button>
		EOD;
		}
	else
		{
		if($disable)
			{
			$button = <<<EOD
			<div id="module-{$name}-installed" class="module-installed">Module installed and enabled!</div>
			<button id="disable-{$name}" title="This will disable the module, but leave the data in the database."
				type="button" 
				class="btn btn-info-faded montserrat"
				style="	padding:4px 10px;
						font-size:14px;
						line-height:1.1;
						border-radius:5px;
						box-shadow:1px 1px 3px  rgba(0, 0, 0, 0.76);
						letter-spacing:1px;
						color:#000;
						height:25px;
						width:100px;
						margin-bottom:-6px;">Disable</button>
			<div id="module-{$name}-disabled"
				style="	padding:4px 20px;
					font-size:12px;
					line-height:1.1;
					letter-spacing:1px;
					color:#fff;
					height:40px;
					position:absolute;
					bottom:35px;
					left:0;right:0;margin-left:auto;margin-right:auto;display:none">Module installed but disabled.</div>
			<button id="enable-{$name}"
				type="button" 
				class="btn btn-info-faded montserrat"
				style="	padding:4px 10px;
						font-size:14px;
						line-height:1.1;
						border-radius:5px;
						box-shadow:1px 1px 3px  rgba(0, 0, 0, 0.76);
						letter-spacing:1px;
						color:#000;
						height:25px;
						width:100px;
						margin-bottom:-6px;display:none">Enable</button>
			EOD;
			}
		else
			{
			$button = <<<EOD
			<div id="module-{$name}-installed" class="module-installed">Module installed!</div>
			EOD;
			}
		}

	$raw_html = <<<EOD
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
		<div class="block icon-block bg-{$type}-faded w-border-2x border-{$type} dark inner-space rounded-2x text-center module"  style="text-shadow:1px 1px 10px rgba(0, 0, 0, 0.56)">
			<!-- Progress bar for this module -->
			<div id="progress-{$name}-circle" class="vb-progress mx-auto" data-value='50' 
				style="margin:25px auto;color:#000;display:none;line-height:3.5;background-color:#fff; border-radius:50px;">
				<span class="progress-left">
					<span class="progress-bar border-primary"></span>
				</span>
				<span class="progress-right">
					<span class="progress-bar border-primary"></span>
				</span>
				<div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
					<div id="progress-{$name}-circle-text" class="h3 font-weight-bold">00%</div>
				</div>
			</div>
				<!-- END -->
			<i id="module-{$name}-icon" class="fa-solid fa-{$fa_icon} md-icon dp24 box-icon bg-{$type}-faded border-{$type} text-white pill" style="{$icon_style}">{$icon_text}</i>
			<h6 id="module-{$name}-title" class="box-title poppins-black">{$title}</h6>
			<p id="module-{$name}-text" class="box-description montserrat">{$text}</p>
			$button
		</div><!-- / icon-block -->
	</div><!-- / column -->
	EOD;
	return $raw_html;
	}



	
function virtual_bible_module_uninstalled_js($name,$plugin_url)
	{		
	$nonce_url = wp_nonce_url($plugin_url.'ajax/load'.$name.'.php',$name);
	$raw_js = <<<EOD
	$("#load-{$name}").click(function(e) 
		{
		e.preventDefault();
		$("#load-{$name}").css('display','none');
		$("#loading-{$name}").css('display','');
		$("#module-{$name}-icon").css('display','none');
		$("#progress-{$name}-circle").css('display','');
		$("#loading-{$name}").html('<small>loading...<br></small>');
		var ajax_timer = setInterval(
			function(){
				$.get("{$plugin_url}ajax/{$name}.log", function(data) 
					{
					var index = data.indexOf("% ");
					var progress = data.slice(0, index);
					$("#progress-{$name}-circle").attr('data-value',progress);
					$("#progress-{$name}-circle-text").html(progress+'%');
					virtual_bible_rotate_progress('{$name}');
					var book = data.slice(index+1);
					$("#loading-{$name}").html('<small>loading...<br></small>'+book);
					});
			}, 250); 
		$.ajax(
			{
			type: 'POST',
			url: "{$nonce_url}",
			data: {},
			success: function(data){
					clearInterval(ajax_timer);
					$("#progress-{$name}-circle").attr('data-value','100');
					virtual_bible_rotate_progress('{$name}');
					$("#module-{$name}-icon").css('display','');
					$("#progress-{$name}-circle").css('display','none');
					$("#module-{$name}-installed").css('display','block');
					$("#load-{$name}").css('display','none');
					$("#loading-{$name}").css('display','none');
					$("#{$name}-selected").css('display','inline-block');
					virtual_bible_housekeeping("{$name}.log");
				}
			});
		});
	EOD;
	return $raw_js;
	}


function virtual_bible_module_installed_js($name,$plugin_url)
	{		
	$nonce_url = wp_nonce_url($plugin_url.'ajax/load'.$name.'.php',$name);
	$raw_js = <<<EOD
	$("#disable-{$name}").click(function(e) 
		{
		e.preventDefault();
		$.ajax(
			{
			type: 'POST',
			url: "{$nonce_url}",
			data: {disable:1},
			success: function(data)
				{
				$("#disable-{$name}").css('display','none');
				$("#module-{$name}-installed").css('display','none');
				$("#enable-{$name}").css('display','');
				$("#module-{$name}-disabled").css('display','');
				$("#{$name}-selected").css('display','none');
				}
			});
		});
	$("#enable-{$name}").click(function(e) 
		{
		e.preventDefault();
		$.ajax(
			{
			type: 'POST',
			url: "{$nonce_url}",
			data: {enable:1},
			success: function(data)
				{
				$("#disable-{$name}").css('display','');
				$("#module-{$name}-installed").css('display','');
				$("#enable-{$name}").css('display','none');
				$("#module-{$name}-disabled").css('display','none');
				$("#{$name}-selected").css('display','inline-block');
				}
			});
		});
	EOD;
	return $raw_js;

	}




?>