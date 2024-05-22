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
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'word_lexicon_results');

if($verify)
	{
	if(isset($_GET['keyword']))
		{
		$keyword=$_GET['keyword'];
		$Entries=getLexEntriesByKeyword($keyword);
		$word_count=count($Entries);
		echo "<div class=\"lexicon-results-count\">$word_count lexicon entires for &ldquo;$keyword&rdquo;</div>";
		foreach($Entries as $Entry)
			{
			$strongs=$Entry['strongs'];
			$word=$Entry['word'];
			$lex=substr($strongs,0,1);
			if($lex=='0')
				{
				$lan='Hebrew';
				$table_name='virtual_bible_lexicon_hebrew';
				$strongs=substr($strongs,1);
				}
			else
				{
				$lan='Greek';
				$table_name='virtual_bible_lexicon_greek';
				}
			$lexData=dbFetch1($table_name,array('id'=>$strongs));
#			write_log("$word - $strongs - $lan\n");
#			write_log($lexData);
			$orig_word=$lexData['orig_word_utf8'];
			$translit=$lexData['translit'];
			$phonetic=$lexData['phonetic'];
			$pos=str_replace('\"','"',$lexData['part_of_speech']);
			$st_def=$lexData['st_def'];
			echo "
				<div class=\"lexicon-results-item\">
					<b>$strongs</b> 
					<span class=\"orig-word\">$orig_word</span> 
					<span class=\"translit\">$translit </span>
					<em class=\"phonetic\">$phonetic</em> 
					<span class=\"pos\">[$pos] </span>
					<span class=\"def\">$st_def</span>
				</div>";
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
	$querytext = "SELECT * FROM `$table_name` WHERE $search_key";
	
	$Results = $wpdb->get_results($querytext, ARRAY_A);
	return $Results;
	}


?>
