<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('../../../../wp-load.php');

$verify = wp_verify_nonce($_GET['_wpnonce'], 'housekeeping');

$file=$_GET['file'];


if(strstr($file, '/'))
	{
	echo "Nice try, hacker scum.";
	exit();
	}

if($verify)
	{
	if(file_exists($file))
		{
		wp_delete_file($file);
		}
	}


?>
