<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/**************************************
 * 	This script is used to fill the popover request from highlighted (keyed) words in the passage display.
 */


include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
	
if(isset($_GET['_wpnonce']))
	{
	$verify = wp_verify_nonce($_GET['_wpnonce'], 'strongs_popover');
	}

if(isset($_GET['_vbnonce']) and $_GET['_vbnonce']=='1234567')	//This is used internally, so that the ajax loaded content can in turn 
	{															// request data from links within the popover.
	$verify=1;
	}

if($verify)
	{
	$strnum=$_GET['strongs'];
	
	$_l=substr($strnum,0,1);
	if($_l=='0'){$_l='H';}
	if($_l=='H')
		{
		$table_name = $wpdb->prefix . 'virtual_bible_lexicon_hebrew';		
		}
	if($_l=='G')
		{
		$table_name = $wpdb->prefix . 'virtual_bible_lexicon_greek';	
		}

	if(isset($table_name))
		{
		$strnum=substr($strnum,1);
		$Results = $wpdb->get_results("SELECT * from $table_name WHERE `id` = '$strnum' LIMIT 1;", ARRAY_A);
		$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));
		$nonce_url_strongs=$plugin_url.'fillstrongs.php?_vbnonce=1234567';
#		$nonce_url_strongs=$plugin_url.'dummyajax.php';
		if($_l=='H'){$strnum='0'.$strnum;}
		$Strongs=$Results[0];
		$orig_word=$Strongs['orig_word_utf8'];
		$translit=$Strongs['translit'];
		$phonetic=$Strongs['phonetic'];
		$def=$Strongs['st_def'];
		preg_match_all('/«(.*?)»/',$def,$Links);
		foreach($Links[0] as $link)
			{
			$k=$link;
			$v=str_replace('«','',$k);
			$v=str_replace('»','',$v);
			$get_url="$nonce_url_strongs&strongs=$v";
			$def=str_replace($k,"<lex class=\"strongs\" strongs=\"$v\" data-toggle=\"popover\" data-placement=\"right\" onclick=\"
			$.get
				(
				'{$get_url}',
				function(data, status)
					{
					$('.popover-content').html(data);				
					}
				)
			\" style=\"color:#800;font-weight:500;cursor:pointer\">$k</lex>",$def);
			}

		echo "
		<div class=\"popover-title-strongs\" style=\"width:100%\">
			{$strnum} &nbsp; <b class=\"popover-orig-word\">{$orig_word}</b> <em>{$translit}, {$phonetic}</em>
			<span class=\"popover-strongs-close\" onclick=\"$(this).closest('div.popover').popover('hide');\">x</span>
		</div>
		<div class=\"popover-content-strongs\">
			{$def}
		</div>";

		}



	}
else
	{
	echo "Wrong nonce";
	}



?>
