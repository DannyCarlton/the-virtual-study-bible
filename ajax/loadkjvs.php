<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$data='0% loading...';
write_file($data);

include('../../../../wp-load.php');
$verify = wp_verify_nonce($_GET['_wpnonce'], 'kjvs');

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


	$counter=0;
#	$data='0% ';
#	write_file($data);
	$file = fopen('https://cdn.virtualbible.org/virtual_bible_kjvs.csv', "r");
	$data='0% ';
	write_file($data);
	$vb_counter=0;
	$oldbook='';
	while (($column = fgetcsv($file, 10000, ",")) !== FALSE) 
		{
		if($column[0]!='id')  #31104
			{
			$counter++;
			$bid=$column[1];
			$book=$Books[$bid];
			if($book!=$oldbook)
				{
				$progress=floor(($counter/31104)*100);
				$data="$progress"."% $book";
				write_file($data);
				$oldbook=$book;
				echo "$progress"."% $book<br>\n";
				echo str_pad('',4096)."\n";    
				flush();
				usleep(500000);
				}
			}
		}

	echo "Done<br>\n";
	echo str_pad('',4096)."\n";    
	flush();
	$data='100% Done';
	write_file($data);
	}




function write_file($data)
	{	
	$filename='kjvs.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
