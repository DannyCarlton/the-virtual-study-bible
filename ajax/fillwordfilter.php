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
		$output=$_vb->wordSearchFilters($keyword,$page_url);
		echo $output;
		}
	else
		{
		echo "No keyword sent.";
		}
	}




?>
