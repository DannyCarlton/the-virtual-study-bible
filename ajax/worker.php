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
			$virtual_bible_page_name=$_vb->getMeta('page_name');
#			write_log($virtual_bible_page_name);
			$__Page=$_vb->get_page_by_title($virtual_bible_page_name);
#			write_log($__Page);
			$page_exists=false;
			if($__Page != NULL  and $__Page->post_title == $virtual_bible_page_name)
				{
				$page_exists=true;
#				write_log($virtual_bible_page_name);
#				write_log($__Page);
				}
			if($_vbm->is_module_installed('kjvs')=='installed' and $_vbm->is_module_installed('strongs')=='installed')
				{
				if($page_exists)
					{
					echo 'installed';
					}
				else
					{
					echo 'not submitted';
					}
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
					}
				else
					{
					$key=$_GET['user_key'];
					$value=$_GET['user_value'];
					$_vb->putUserMeta($key,$value);
					}
				}
			}
		else
			{
			}
		}
	}


?>
