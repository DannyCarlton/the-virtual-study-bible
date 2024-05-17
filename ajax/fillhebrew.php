<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'hebrew');

if($verify)
	{
	echo "So far, so good.";
	}



function write_file($data)
	{	
	$filename='hebrew.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
