<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
/***************************************************
 * This script is used to fill the right-pane tools section labeled "Strongs' Lexicon Entries..."
 */

include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}

$_vb = new virtual_bible();
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'word_lexicon_results');

if($verify)
	{
	if(isset($_GET['keyword']))
		{
		$keyword=$_GET['keyword'];
		$scope=$_GET['scope'];
		$keyword=preg_replace("/[^A-Za-z ]/",'',$keyword);
		$Keywords=explode(' ',$keyword);
		foreach($Keywords as $keyword)
			{
			$Entries=getLexEntriesByKeyword($keyword);
			$word_count=count($Entries);
			$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));
			$nonce_url_strongs = wp_nonce_url($plugin_url.'fillstrongs.php','strongs_popover');
			$nonce_url_strongs=$plugin_url.'fillstrongs.php?_vbnonce=1234567';
			echo "<div class=\"lexicon-results-count\" >$word_count lexicon entries for &ldquo;$keyword&rdquo;</div>";
			foreach($Entries as $Entry)
				{
				$strongs=$Entry['strongs'];
				$word=$Entry['word'];
				$lex=substr($strongs,0,1);
				if($lex=='0')
					{
					$lan='Hebrew';$_l='H';
					$table_name='virtual_bible_lexicon_hebrew';
					$strongs=substr($strongs,1);
					}
				else
					{
					$lan='Greek';$_l='G';
					$table_name='virtual_bible_lexicon_greek';
					}
				$lexData=$_vb->dbFetch1($table_name,array('id'=>$strongs));
				$orig_word=$lexData['orig_word_utf8'];
				$translit=$lexData['translit'];
				$phonetic=$lexData['phonetic'];
				$pos=str_replace('\"','"',$lexData['part_of_speech']);
				$st_def=str_replace('\"','"',$lexData['st_def']);
				$st_def=str_replace('\\','',$st_def);
				preg_match_all('/«(.*?)»/',$st_def,$Links);
				foreach($Links[0] as $link)
					{
					$k=$link;
					$v=str_replace('«','',$k);
					$v=str_replace('»','',$v);
					$st_def=str_replace($k,"<lex class=\"strongs\" strongs=\"$v\" data-toggle=\"popover\" data-placement=\"bottom\" data-container=\"#study-bible\" onclick=\"
					$(this).popover
						(
							{
							placement : 'bottom', 
							html: true,
							'content' : function()
								{
								return $.ajax(
										{
										type: 'GET',
										url: '{$nonce_url_strongs}',
										data: {strongs:strongs_num,rnd:Math.random()},
										dataType: 'html',
										async: false
										}).responseText;
								}
							}
						)
					\">$k</lex>",$st_def);
					}
				echo "
					<div class=\"lexicon-results-item\" >
						<div class=\"lexicon-results-item-header\"
						onclick=\"
							$('#lexicon-results h4 small').css('display','inline');
							$('.lexicon-results-item').css('background-color','');
							$(this).parent().css('background-color','#ffffee');
							$('.word-results').css('background-color','');
							$('*[strongs=$_l$strongs]').parent().parent().css('background-color','#ffffee')\">
							<b>$strongs.</b> 
							<span class=\"orig-word\">$orig_word</span> 
							<span class=\"translit\">$translit,</span>
							<em class=\"phonetic\">$phonetic;</em> 
							<span class=\"pos\">[$pos] </span>
						</div>
						<span class=\"def\">&mdash;$st_def</span>
					</div>";
				}

			}

		}
	else
		{
		echo "No keyword sent.";
		}
	}

function getLexEntriesByKeyword($keyword)
	{
	global $wpdb;
	$search_key='';$total_records=0;
	$kw=$keyword;
	$kw=str_replace('#','',$kw);
	$kw=str_replace('~','',$kw);
	$kw=str_replace('*','%',$kw);
	$search_key="`word` LIKE '".$kw."'";
	$table_name = $wpdb->prefix . 'virtual_bible_lexwords';
	$querytext = "SELECT * FROM `$table_name` WHERE $search_key ORDER BY `strongs`";
	
	$Results = $wpdb->get_results($querytext, ARRAY_A);
	return $Results;
	}


?>
