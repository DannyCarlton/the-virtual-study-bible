<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$data='0% loading...';
write_file($data);

include('../../../../wp-load.php');
$verify = wp_verify_nonce($_GET['_wpnonce'], 'holman');

if($verify)
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
	$csv = file_get_contents('https://cdn.virtualbible.org/virtual_bible_xref_holman.csv');
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



function write_file($data)
	{	
	$filename='holman.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
