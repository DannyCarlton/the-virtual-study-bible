<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'hebrew');

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
				'meta_key'		=>  'module_hebrew'
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
				'meta_key'		=>  'module_hebrew'
				)
			);
		}
	else
		{
		$books = fopen('https://cdn.virtualbible.org/virtual_bible_books.csv', "r");
		$Books=[];$r=1;
		while (($Book = fgetcsv($books, 10000, ",")) !== FALSE) 
			{
			if($Book[0]!='id')
				{
				$book=$Book[1];
				$Books[$r]=$book;
				$r++;
				}
			}

		
		$Queries=[];
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'virtual_bible_hebrew';
		array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
				id 			int(11) 	NOT NULL AUTO_INCREMENT,
				book 		tinyint(3) 	NOT NULL,
				chapter 	tinyint(3) 	NOT NULL,
				verse 		tinyint(3) 	NOT NULL,
				text 		text 		NOT NULL,
				PRIMARY KEY id 			(id),
				KEY 		ixb 		(book),
				KEY 		ixc 		(chapter),
				KEY 		ixv 		(verse),
				KEY 		ixbcv 		(book,chapter,verse)
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
		$file = fopen('https://cdn.virtualbible.org/virtual_bible_text_hebrew.csv', "r");
		$data='0% Processing...';
		write_file($data);
		$oldbook='';
		while (($column = fgetcsv($file, 10000, ",")) !== FALSE) 
			{
			if($column[0]!='id')  #23216
				{
				$counter++;

				$hebrew=html_entity_decode($column[4]);
				$wpdb->insert
					( 
					$table_name,
					array
						( 
						'id'		=>  $column[0],
						'book'		=>  $column[1],
						'chapter'	=>  $column[2],
						'verse'		=>  $column[3],
						'text'		=>  $hebrew
						)
					);

				$bid=$column[1];
				$book=$Books[$bid];
				if($book!=$oldbook)
					{
					$progress=floor(($counter/23216)*100);
					$data="$progress"."% $book";
					write_file($data);
					$oldbook=$book;
					}

				}
			}



		$table_name = $wpdb->prefix . 'virtual_bible_meta';
		$wpdb->insert
			( 
			$table_name,
			array
				( 
				'meta_key'		=>  'module_hebrew',
				'meta_value'	=>  'installed'
				)
			);
		

		$data='100% Done';
		write_file($data);
		}
	}



function write_file($data)
	{	
	$filename='hebrew.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
