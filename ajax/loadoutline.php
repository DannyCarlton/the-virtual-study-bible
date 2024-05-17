<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include('../../../../wp-load.php');



if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'outline');

if($verify)
	{
	if(isset($_POST['disable']) and $_POST['disable'])
		{
		$table_name = $wpdb->prefix . 'virtual_bible_meta';
		$wpdb->update
			( 
			$table_name,
			array
				( 
				'meta_value'	=>  'disabled'
				),
			array
				(
				'meta_key'		=>  'module_outline'
				)
			);

		}
	elseif(isset($_POST['enable']) and $_POST['enable'])
		{
		$table_name = $wpdb->prefix . 'virtual_bible_meta';
		$wpdb->update
			( 
			$table_name,
			array
				( 
				'meta_value'	=>  'installed'
				),
			array
				(
				'meta_key'		=>  'module_outline'
				)
			);
		}
	else
		{
		$data='0% loading...';
		write_file($data);
		
		$table_name = $wpdb->prefix . 'virtual_bible_outline';

		$Rows=[];$r=1;$Outline=[];$_old_book='';
		$file = fopen('https://cdn.virtualbible.org/virtual_bible_outline.csv', 'r');
		while (($Rows = fgetcsv($file, 10000, ",")) !== FALSE) 
			{
			if($Rows[0]!='id')
				{
				$chapter=$Rows[1];
				$verse=$Rows[2];
				$text=$Rows[3];
				$Chapter=explode(' ',$chapter);
				array_pop($Chapter);
				$_book=implode(' ',$Chapter);
				$dbRow=$wpdb->get_results("SELECT * FROM $table_name WHERE `id` = $r;", ARRAY_A); //db call ok; no-cache ok
				if(isset($dbRow[0]['id']))
					{
					}
				else
					{
					$wpdb->insert
						( 
						$table_name,
						array
							( 
							'id'		=>  $r,
							'chapter'	=>  $chapter,
							'verse'		=>  $verse,
							'text'		=>  $text
							)
						); //db call ok
					}
				if($_old_book != $_book)
					{
					$_old_book=$_book;
					$progress=floor(($r/6676)*100);
					$data="$progress"."% $_book";
					write_file($data);
					}
				$r++;
				}
			}


		$table_name = $wpdb->prefix . 'virtual_bible_meta';
		$wpdb->insert
			( 
			$table_name,
			array
				( 
				'meta_key'		=>  'module_outline',
				'meta_value'	=>  'installed'
				)
			);

		$data='100% Done';
		write_file($data);
		}
	}



function write_file($data)
	{	
	$filename='outline.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
