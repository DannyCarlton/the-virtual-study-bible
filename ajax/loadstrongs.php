<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$counter=0;
$data='0% Loading data from cdn...';
write_file($data);
$file = fopen('https://cdn.virtualbible.org/virtual_bible_lex_hebrew.csv', "r");
$data='0% Data loaded. Processing...';
write_file($data);
$oldbook='';
while (($column = fgetcsv($file, 10000, ",")) !== FALSE) 
	{
	if($column[0]!='id')  #17270
		{
		$counter++;
		$word=$column[2];
		if(($counter/500) == floor($counter/500))
			{
			$progress=floor(($counter/14298)*100);
			$data="$progress"."% $word";
			write_file($data);
			echo "$progress"."% $word<br>\n";
			echo str_pad('',4096)."\n";    
			flush();
			usleep(500000);
			}
		}
	}

	
$file = fopen('https://cdn.virtualbible.org/virtual_bible_lex_greek.csv', "r");
while (($column = fgetcsv($file, 10000, ",")) !== FALSE) 
	{
	if($column[0]!='id')  #5624
		{
		$counter++;
		$word=$column[2];
		if(($counter/500) == floor($counter/500))
			{
			$progress=floor(($counter/14298)*100);
			$data="$progress"."% $word";
			write_file($data);
			echo "$progress"."% $word<br>\n";
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




function write_file($data)
	{	
	$filename='strongs.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
