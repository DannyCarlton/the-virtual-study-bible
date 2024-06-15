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
	if($_l=='0')
		{
		$_l='H';
		$strnum=substr($strnum,1);
		}
	elseif($_l!='H')
		{
		$_l='G';
		}
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
		if($_l=='G' and $strnum>5625)
			{
			write_log("$_l $strnum");
			}
		else
			{
			$_strnum=str_replace($_l,'',$strnum);
			$Results = $wpdb->get_results("SELECT * from $table_name WHERE `id` = '$_strnum' LIMIT 1;", ARRAY_A);
			$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));
			$nonce_url_strongs=$plugin_url.'fillstrongs.php?_vbnonce=1234567';
			if($_l=='H'){$_strnum='0'.$_strnum;}
			$Strongs=$Results[0];
			$orig_word=$Strongs['orig_word_utf8'];
			$translit=$Strongs['translit'];
			$phonetic=$Strongs['phonetic'];
			$def=str_replace('\\','',$Strongs['st_def']);
			$temp=preg_replace('/«([0-9]+)»/','{$1}',$def);
			$temp=preg_replace('/«.*[a-xA-Z]+.*» /','',$temp);
			$temp=preg_replace('/{([0-9]+)}/','«$1»',$temp);
			$def=$temp;
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
				{$_strnum} &nbsp; <b class=\"popover-orig-word\">{$orig_word}</b>
				<span class=\"popover-strongs-close\" onclick=\"$(this).closest('div.popover').popover('hide');\">x</span>
			</div>
			<div class=\"popover-content-strongs\">
				<div class=\"lex-translit\">{$translit}, {$phonetic}</div>
				<div class=\"lex-definition\">{$def}</div>
			</div>";
			}

		}



	}
else
	{
	echo "Wrong nonce";
	}



?>
