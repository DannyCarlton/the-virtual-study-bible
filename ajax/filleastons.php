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

	$page_name=virtual_bible_getMeta('page_name');
	$page_slug=sanitize_title($page_name);
	$page_url=site_url().'/'.$page_slug.'/';
	$dicWords=explode(' ',$dic_word);
	foreach($dicWords as $dic_word)
		{
		$_def='<div class="word-tools-eastons-def">';
		$table_name = $wpdb->prefix . 'virtual_bible_eastons';
		$Results = $wpdb->get_results("SELECT * from $table_name WHERE `reference` = '$dic_word' LIMIT 1;", ARRAY_A);
		if(isset($Results[0]))
			{
			$regex=file_get_contents('ref-regex.tpl');
			$Eastons=$Results[0];
			$def=$Eastons['definition'];
			$ref=$Eastons['reference'];
			$def=str_replace("<br>","%%",$def);
			$def=str_replace('%%%%','</p><p>',$def);
			$def="<p><b>$ref &mdash; </b>$def</p>";
			$def=preg_replace($regex,'<a href="'.$page_url.'?keyword=$1+$2">$1 $2</a>',$def);
			}
		else
			{
			$def="No entry found for the word &ldquo;$dic_word&rdquo;.";
			}
		$_def.="\n$def\n</div>";
		echo $_def;
		}

	}




?>
