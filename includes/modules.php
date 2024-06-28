<?php

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}


	
class virtual_bible_modules
	{

/*********************************************************************************************
      _                             _       _          _           _        _ _          _  __
     (_)___     _ __ ___   ___   __| |_   _| | ___    (_)_ __  ___| |_ __ _| | | ___  __| |/ /
     | / __|   | '_ ` _ \ / _ \ / _` | | | | |/ _ \   | | '_ \/ __| __/ _` | | |/ _ \/ _` | | 
     | \__ \   | | | | | | (_) | (_| | |_| | |  __/   | | | | \__ \ || (_| | | |  __/ (_| | | 
     |_|___/___|_| |_| |_|\___/ \__,_|\__,_|_|\___|___|_|_| |_|___/\__\__,_|_|_|\___|\__,_| | 
          |_____|                                |_____|                                   \_\

***********************************************************************************************/

	function is_module_installed($name)
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



		


/*********************************************************************************************
                          _       _          _     _             _  __
      _ __ ___   ___   __| |_   _| | ___    | |__ | |_ _ __ ___ | |/ /
     | '_ ` _ \ / _ \ / _` | | | | |/ _ \   | '_ \| __| '_ ` _ \| | | 
     | | | | | | (_) | (_| | |_| | |  __/   | | | | |_| | | | | | | | 
     |_| |_| |_|\___/ \__,_|\__,_|_|\___|___|_| |_|\__|_| |_| |_|_| | 
                                       |_____|                     \_\

***********************************************************************************************/
	


	function module_html($name,$type,$fa_icon,$title,$text,$plugin_url,$status='installed')
		{
		global $_vb;
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

		$module=$_vb->getTemplate('admin-module');
		$module=str_replace('{$icon_text}',$icon_text,$module);
		$module=str_replace('{$icon_style}',$icon_style,$module);
		$module=str_replace('{$fa_icon}',$fa_icon,$module);
		$module=str_replace('{$title}',$title,$module);
		$module=str_replace('{$name}',$name,$module);
		$module=str_replace('{$type}',$type,$module);
		$module=str_replace('{$text}',$text,$module);
		if(!$disable)
			{
			$module=str_replace('Module installed and enabled!','Module installed!',$module);
			$module=str_replace('id="disable-'.$name.'"','id="xdisable-'.$name.'" style="display:none"',$module);
			$module=str_replace('class="notice-disabled"','class="notice-disabled" style="display:none"',$module);
			}
		if($status=='disabled')
			{
			$module=str_replace('class="module-block','class="module-block disabled',$module);
			}
		elseif($status=='installed')
			{
			$module=str_replace('class="module-block','class="module-block enabled',$module);			
			}
		else
			{
			$module=str_replace('class="module-block','class="module-block uninstalled',$module);			
			}
		return $module;
		}




/*********************************************************************************************
                          _       _                     _           _        _ _          _        _      __
      _ __ ___   ___   __| |_   _| | ___    _   _ _ __ (_)_ __  ___| |_ __ _| | | ___  __| |      (_)___ / /
     | '_ ` _ \ / _ \ / _` | | | | |/ _ \  | | | | '_ \| | '_ \/ __| __/ _` | | |/ _ \/ _` |      | / __| | 
     | | | | | | (_) | (_| | |_| | |  __/  | |_| | | | | | | | \__ \ || (_| | | |  __/ (_| |      | \__ \ | 
     |_| |_| |_|\___/ \__,_|\__,_|_|\___|___\__,_|_| |_|_|_| |_|___/\__\__,_|_|_|\___|\__,_|____ _/ |___/ | 
                                       |_____|                                            |_____|__/     \_\

***********************************************************************************************/
		
	function module_uninstalled_js($name,$plugin_url)
		{		
		$nonce_url = wp_nonce_url($plugin_url.'ajax/loadmodule.php',$name);
		$raw_js = <<<EOD
		$("#load-{$name}").click(function(e) 
			{
			e.preventDefault();
			$("#load-{$name}").css('display','none');
			$("#loading-{$name}").css('display','block');
			$("#module-{$name}-icon").css('display','none');
			$("#progress-{$name}-circle").css('display','');
			$("#loading-{$name}").html('<small>loading...<br></small>');
			var ajax_timer = setInterval
				(
				function()
					{
					$.get
						(
						"{$plugin_url}ajax/{$name}.log", 
						function(data) 
							{
							var index = data.indexOf("% ");
							var progress = data.slice(0, index);
							$("#progress-{$name}-circle").attr('data-value',progress);
							$("#progress-{$name}-circle-text").html(progress+'%');
							virtual_bible_rotate_progress('{$name}');
							var book = data.slice(index+1);
							$("#loading-{$name}").html('<small>loading...<br></small>'+book);
							}
						).error();
					}, 250
				); 
			$.ajax(
				{
				type: 'GET',
				url: "{$nonce_url}",
				data: {name:'{$name}'},
				success: function(data){
						clearInterval(ajax_timer);
						$("#progress-{$name}-circle").attr('data-value','100');
						virtual_bible_rotate_progress('{$name}');
						$("#module-{$name}-icon").css('display','');
						$("#progress-{$name}-circle").css('display','none');
						$("#disable-{$name}").css('display','block');
						$("#module-{$name}-installed").css('display','block');
						$("#load-{$name}").css('display','none');
						$("#loading-{$name}").css('display','none');
						$("#{$name}-selected").css('display','inline-block');
						$("#pill-{$name}").removeClass("_not_installed");
						$("#pill-{$name}").addClass("_installed");
						virtual_bible_housekeeping("{$name}.log");
					}
				});
			});
		EOD;
		return $raw_js;
		}



/*********************************************************************************************
                          _       _          _           _        _ _          _        _      __
      _ __ ___   ___   __| |_   _| | ___    (_)_ __  ___| |_ __ _| | | ___  __| |      (_)___ / /
     | '_ ` _ \ / _ \ / _` | | | | |/ _ \   | | '_ \/ __| __/ _` | | |/ _ \/ _` |      | / __| | 
     | | | | | | (_) | (_| | |_| | |  __/   | | | | \__ \ || (_| | | |  __/ (_| |      | \__ \ | 
     |_| |_| |_|\___/ \__,_|\__,_|_|\___|___|_|_| |_|___/\__\__,_|_|_|\___|\__,_|____ _/ |___/ | 
                                       |_____|                                 |_____|__/     \_\

***********************************************************************************************/

	function module_installed_js($name,$plugin_url)
		{		
		$nonce_url = wp_nonce_url($plugin_url.'ajax/loadmodule.php',$name);
		$raw_js = <<<EOD
		$("#disable-{$name}").click(function(e) 
			{
			e.preventDefault();
			$.ajax(
				{
				type: 'POST',
				url: "{$nonce_url}",
				data: {disable:1,name:'{$name}'},
				success: function(data)
					{
					$("#module-block-{$name}").removeClass("enabled");
					$("#module-block-{$name}").addClass("disabled");
					$("#pill-{$name}").removeClass("_installed");
					$("#pill-{$name}").removeClass("_not_installed");
					$("#pill-{$name}").addClass("_disabled");
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
				data: {enable:1,name:'{$name}'},
				success: function(data)
					{
					$("#module-block-{$name}").removeClass("disabled");
					$("#module-block-{$name}").addClass("enabled");
					$("#pill-{$name}").removeClass("_not_installed");
					$("#pill-{$name}").removeClass("_disabled");
					$("#pill-{$name}").addClass("_installed");
					}
				});
			});
		EOD;
		return $raw_js;

		}
	}



?>