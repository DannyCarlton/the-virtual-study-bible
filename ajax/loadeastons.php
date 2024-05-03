<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$counter=0;
$data='0% Loading data from cdn...';
write_file($data);
$file = fopen('https://cdn.virtualbible.org/virtual_bible_easton_dictionary.csv', "r");
$data='0% Data loaded. Processing...';
write_file($data);
$oldbook='';
while (($column = fgetcsv($file, 10000, ",")) !== FALSE) 
	{
	if($column[0]!='id')  #3963
		{
		$counter++;
		$word=$column[1];
		if(($counter/100) == floor($counter/100))
			{
			$progress=floor(($counter/3963)*100);
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
	$filename='eastons.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
