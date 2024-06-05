<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'eastons');

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
				'meta_key'		=>  'module_eastons'
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
				'meta_key'		=>  'module_eastons'
				)
			);

		}
	else
		{
		$Queries=[];
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'virtual_bible_eastons';
		array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
				id 			int(11) 	NOT NULL AUTO_INCREMENT,
				reference 		text 		NOT NULL,
				definition 		text 		NOT NULL,
				PRIMARY 		KEY id 		(id),
				KEY 			reference	(reference)
				) $charset_collate ENGINE=MyISAM;");
		if ( ! function_exists('dbDelta') )
			{
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			}
		foreach($Queries as $sql)
			{
			dbDelta ( $sql );
			}
		
		$counter=0;
		$file = fopen('https://cdn.virtualbible.org/virtual_bible_easton_dictionary.csv', "r");
		$data='0% Processing...';
		write_file($data);
		$oldbook='';
		while (($column = fgetcsv($file, 10000, ",")) !== FALSE) 
			{
			if($column[0]!='id')  #3963
				{
				$counter++;
				$wpdb->insert
					( 
					$table_name,
					array
						( 
						'id'			=>  $column[0],
						'reference'		=>  $column[1],
						'definition'	=>  $column[2]
						)
					);
				$word=$column[1];
				if(($counter/100) == floor($counter/100))
					{
					$progress=floor(($counter/3963)*100);
					$letter=substr($word,0,1);
					$letter=strtoupper($letter);
					$data="$progress"."% ... $letter&rsquo;s ...";
					write_file($data);				
					}
				}
			}


		$table_name = $wpdb->prefix . 'virtual_bible_meta';
		$wpdb->insert
			( 
			$table_name,
			array
				( 
				'id'			=>  NULL,
				'meta_key'		=>  'module_eastons',
				'meta_value'	=>  'installed'
				)
			);

		$data='100% Done';
		write_file($data);
		}
	}



function write_file($data)
	{	
	$filename='eastons.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
