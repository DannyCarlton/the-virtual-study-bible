<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'strongs_popover');

if($verify)
	{
	$strnum=$_GET['strongs'];
	$_l=substr($strnum,0,1);
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
		if($_l=='H'){$strnum='0'.$strnum;}
/*    [0] => Array
        (
            [id] => 7225
            [orig_word] => tyXar
            [orig_word_utf8] => רֵאשִׁית 
            [orig_word_enc] => "u05e8u05b5u05d0u05e9u05c1u05b4u05d9u05ea"
            [word_orig] => from the same as (07218)
            [translit] => re'shiyth
            [tdnt] => TWOT - 2097e
            [phonetic] => ray-sheeth'
            [part_of_speech] => Noun Feminine
            [st_def] => from the same as «07218»; the first, in place, time, order or rank (specifically, a firstfruit):--beginning, chief(-est), first(-fruits, part, time), principal thing.
            [ipd_def] => <OL TYPE="1"><LI>first, beginning, best, chief<OL TYPE="a"><LI>beginning<LI>first<LI>chief<LI>choice part</OL></OL>
        )
*/		
#		write_log($Results);
		$Strongs=$Results[0];
		$orig_word=$Strongs['orig_word_utf8'];
		$translit=$Strongs['translit'];
		$phonetic=$Strongs['phonetic'];
		$def=$Strongs['st_def'];

		echo "
		<div class=\"popover-title-strongs\" style=\"width:100%\">
			{$strnum} &nbsp; <b class=\"popover-orig-word\">{$orig_word}</b> <em>{$translit}, {$phonetic}</em>
			<span class=\"popover-strongs-close\" onclick=\"$(this).closest('div.popover').popover('hide');\">x</span>
		</div>
		<div class=\"popover-content-strongs\">
			{$def}}
		</div>";

		}



	}




?>
