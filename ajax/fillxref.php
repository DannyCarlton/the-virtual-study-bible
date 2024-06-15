<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
	
if(isset($_GET['_wpnonce']))
	{
	$verify = wp_verify_nonce($_GET['_wpnonce'], 'xref_popover');
	}
if(isset($_GET['_vbnonce']) and $_GET['_vbnonce']=='1234567')
	{
	$verify=1;
	}

if($verify)
	{
	$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));
	$page_name=$_vb->getMeta('page_name');
	$page_slug=sanitize_title($page_name);
	$page_url=site_url().'/'.$page_slug.'/';
	$_vb = new virtual_bible();
	$out=getPrintR($_GET);
	$out='';
	$ref=str_replace('+',' ',$_GET['ref']);
	$Ref=$_vb->getRefByKeyword($ref);
	$Verses=$_vb->getVerses($Ref['bid'],$Ref['chapter'],$Ref['Verses']);
	$sup=FALSE;
	if(count($Verses)>1){$sup=TRUE;}
	$bid=$Ref['bid'];$chapter=$Ref['chapter'];$clean_ref=$Ref['clean-ref'];
	$V=reset($Verses);
	$_v=$V['verse'];
	$context="context_$bid"."_$chapter"."_$_v";
	foreach($Verses as $Verse)
		{
		write_log($Verse);
		$v=$Verse['verse'];
		$text=$Verse['text'];
		$text=preg_replace('/\{(.*?)\}/','',$text);
		if(strstr($text,'|'))
			{
			list($toss,$text)=explode('|',$text);
			}
		$text=str_replace('¶','',$text);
		$text=$_vb->capFilter($text);
		if($sup)
			{
			$out.="<verse><sup>$v</sup>$text</verse>";			
			}
		else
			{
			$out.="<verse>$text</verse>";	
			}
		}
	$Cref=explode(':',$clean_ref);
	$clean_chapter=$Cref[0];
	$clean_chapter_url=str_replacE(' ','+',$clean_chapter);
	$clean_url=str_replace(' ','+',$clean_ref);
	$clean_verse=$Cref[1];

	echo "
		<div class=\"popover-content-xref\">
			<span class=\"popover-xref-close\" 
				onclick=\"$(this).closest('div.popover').popover('hide');$(this).closest('verse').css('background-color','#fff');\">x
			</span>
			&ldquo;{$out}&rdquo;&mdash;<a href=\"{$page_url}?keyword={$clean_chapter}#{$context}\" title=\"click for full chapter\">{$clean_chapter}</a>:<a href=\"{$page_url}?keyword={$clean_ref}\" title=\"click for verse\">{$clean_verse}</a>
		</div>
	";



	}
else
	{
	echo "Wrong nonce";
	}



?>
