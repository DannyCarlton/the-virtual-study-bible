<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
/*
*/
include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}

$_vb = new virtual_bible();
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'word_filter_results');

if($verify)
	{
	if(isset($_GET['keyword']))
		{
		$keyword=$_GET['keyword'];
		$page_url=$_GET['pageurl'];
		$cache_file=base64_encode("$keyword");
		$cache_file=str_replace('=','',$cache_file);
		$cache_file='fwf-'.substr($cache_file,-30);
		$plugin_path=str_replace('ajax/','',plugin_dir_path(__FILE__));
		if(file_exists("$plugin_path/cache/$cache_file.dat"))
			{
			$output=file_get_contents("$plugin_path/cache/$cache_file.dat");	
			}
		else
			{
			$output=$_vb->wordSearchFilters($keyword,$page_url); // <= this should be scope!!
			write_cache($output,$cache_file);
			}
		echo $output;
		}
	else
		{
		echo "No keyword sent.";
		}
	}




?>
