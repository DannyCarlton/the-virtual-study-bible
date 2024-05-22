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
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'word_eastons_results');

if($verify)
	{
	$dic_word=$_GET['keyword'];
	$dic_word=preg_replace("/[^A-Za-z ]/",'',$dic_word);

	$table_name = $wpdb->prefix . 'virtual_bible_eastons';
	$Results = $wpdb->get_results("SELECT * from $table_name WHERE `reference` = '$dic_word' LIMIT 1;", ARRAY_A);
#	write_log($Results);
	if(isset($Results[0]))
		{
		$Eastons=$Results[0];
		$def=$Eastons['definition'];
		$ref=$Eastons['reference'];
		$def=str_replace("<br>","%%",$def);
		$def=str_replace('%%%%','</p><p>',$def);
		$def="<p><b>$ref &mdash; </b>$def</p>";
		}
	else
		{
		$def="No entry found for the word &ldquo;$dic_word&rdquo;.";
		}

	echo $def;

	}




?>
