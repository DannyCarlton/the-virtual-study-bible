<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include('../../../../wp-load.php');



/*

	$table_name = $wpdb->prefix . 'virtual_bible_outline';

	$Rows=[];$r=1;$Outline=[];
	$file = fopen('https://cdn.virtualbible.org/virtual_bible_outline.csv', 'r');
	while (($Rows = fgetcsv($file, 10000, ",")) !== FALSE) 
		{
		if($Rows[0]!='id')
			{
#			"id","chapter","verse","text"
			$chapter=$Rows[1];
			$verse=$Rows[2];
			$text=$Rows[3];
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
			$r++;
			}
		}
*/

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'holman');

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
				'meta_key'		=>  'module_holman'
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
				'meta_key'		=>  'module_holman'
				)
			);
		}
	else
		{
		$data='0% loading...';
		write_file($data);
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
		$table_name = $wpdb->prefix . 'virtual_bible_xref_holman';
		array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
				id 			int(11) 	NOT NULL AUTO_INCREMENT,
				book 		tinyint(3) 	NOT NULL,
				chapter 	tinyint(3) 	NOT NULL,
				verse 		tinyint(3) 	NOT NULL,
				word 		varchar(3) 	NOT NULL,
				ref 		text 		NOT NULL,
				PRIMARY KEY id 			(id),
				KEY 		book 		(book,chapter,verse)
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
		# For some reason the CSV kept breaking, so I had to use a different method
		$wp_remote_get = wp_remote_get('https://cdn.virtualbible.org/virtual_bible_xref_holman.csv');
		$csv=$wp_remote_get['body'];		

		$Data=explode("\n",$csv);
		$data='0% Processing...';
		write_file($data);
		$oldbook='';$bookmarker='';
		foreach($Data as $row)
			{
			$row=trim($row);
			$column=explode('","',$row);
			$column[0]=str_replace('"','',$column[0]);
			$column[5]=str_replace('"','',$column[5]);
			if($column[0]!='id' and $column[0]!='') 
				{
				$counter++;
				$wpdb->insert
					( 
					$table_name,
					array
						( 
						'id'		=>  $column[0],
						'book'		=>  $column[1],
						'chapter'	=>  $column[2],
						'verse'		=>  $column[3],
						'word'		=>  $column[4],
						'ref'		=>  $column[5]
						)
					);

				$bid=$column[1];
				$book=$Books[$bid];
				$chapter=$column[2];
				if(($counter/500) == floor($counter/500))
					{
					if($book!=$oldbook)
						{
						$oldbook=$book;
						$bookmarker='';
						}
					$progress=floor(($counter/57811)*100);
					$data="$progress"."% $book$bookmarker";
					write_file($data);
					$bookmarker.='.';
					}
				}
			}


		$table_name = $wpdb->prefix . 'virtual_bible_meta';
		$wpdb->insert
			( 
			$table_name,
			array
				( 
				'meta_key'		=>  'module_holman',
				'meta_value'	=>  'installed'
				)
			);

		$data='100% Done';
		write_file($data);
		}
	}



function write_file($data)
	{	
	$filename='holman.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
