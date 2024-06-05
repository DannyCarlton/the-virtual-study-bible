<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}

if(isset($_GET['function']))
	{
	$verify = wp_verify_nonce($_GET['_wpnonce'], 'vb_watcher');

	if($verify)
		{
		if($_GET['function']=='settings_watch')
			{
			if(virtual_bible_is_module_installed('kjvs') and virtual_bible_is_module_installed('strongs'))
				{
				echo 'installed';
				}
			else
				{
				echo 'not installed';
				}
			}
		elseif($_GET['function']=='user_data_set')
			{
			if($_GET['user_key']=='style')
				{
				$style=$_GET['user_value'];
				if($_GET['user']=='0')
					{
					setcookie('__vb_style',$style,time()+3600,"/");
					#		write_log($current_user);
					}
				else
					{
					$key=$_GET['user_key'];
					$value=$_GET['user_value'];
					virtual_bible_putUserMeta($key,$value);
					}
				}
			}
		else
			{
			write_log($_GET);
			}
#		write_log($current_user);
		}
	}


?>
