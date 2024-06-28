<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'ref_guesses');

if($verify)
	{
	if(isset($_GET['q']))
		{
		$q=$_GET['q'];
		$out='';
		$table_name = $wpdb->prefix . 'virtual_bible_books';
		$Results = $wpdb->get_results
			(
			$wpdb->prepare
				(
				"SELECT * FROM $table_name WHERE `book` LIKE '%s' OR `abbr` LIKE '%%%s%%';", array($q,$q)
				),
			ARRAY_A
			);
		foreach($Results as $b=>$Book)
			{
			$tabindex=$b+1;
			if($Book['book']!==$q)	# We don't want to offer somethign they've alreayd got.
				{
				$out.="<span class=\"guess\" onclick=\"$('#search-input-field').val('{$Book['book']} ');$('#my-guesses-div').css('display','none');$('#search-input-field').focus();\" tabindex=\"$tabindex\">{$Book['book']}</span><br>";
				}
			}
		echo $out;
		}
	}




?>
