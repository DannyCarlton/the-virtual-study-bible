<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
error_reporting(E_ALL);
set_time_limit(2400);

include('../../../../wp-load.php');

echo "Starting...<br>\n";
echo str_pad('',4096)."\n";    
flush();

/*
if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
*/

if(isset($_GET['name']))
	{
	$module_name=$_GET['name'];
	$module_path=str_replace('ajax/','modules/',plugin_dir_url(__FILE__));
	$data_path=str_replace('ajax/','data/',plugin_dir_url(__FILE__));
	$module_data=simplexml_load_file("$module_path$module_name.xml", 'SimpleXMLElement', LIBXML_NOCDATA);
#	echo getPrintR($module_data);
	echo str_pad('',4096)."\n";    
	flush();


//	$verify = wp_verify_nonce($_GET['_wpnonce'], $module_name);
	$verify=1;

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
					'meta_key'		=>  'module_'.$module_name
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
					'meta_key'		=>  'module_'.$module_name
					)
				);
			}
		else
			{
			$data='0% downloading...';
			write_file($data,$module_name);

			if($module_data->load_books=='TRUE')
				{
				echo "Loading books.<br>";
				echo str_pad('',4096)."\n";    
				flush();
				$table_name = 'virtual_bible_books';
				$Books=dbFetch($table_name,'NULL','book');
				foreach($Books as $bid=>$Book)
					{
					$book=$Book['book'];
					unset($Books[$bid]['book']);
					$Books[$bid]=$book;
					}
				}

			$data_size=(int)$module_data->data_size;
			$counter=0;
			foreach($module_data->section as $Section)
				{
				$Rows=[];
				$section_size=(int)$Section->section_data_size;
				$section_counter=0;
				$section_name=$Section->section_name;
				$table_name = $wpdb->prefix . $Section->table_name;
				$charset_collate = $wpdb->get_charset_collate();
				$create_table=(string)$Section->create_table;
				$create_table=str_replace('{$table_name}','%1s',$create_table);
				$create_table=str_replace('{$charset_collate}','%1s',$create_table);
				$create_table=$wpdb->prepare($create_table,array($table_name,$charset_collate));

#				$wpdb->show_errors = true;
#				$wpdb->suppress_errors = false;
				$query=$wpdb->query($create_table);
#				echo getPrintR($wpdb->last_query);
#				echo $wpdb->last_error;
				$data_source=(string)$Section->data_source;
				$data_source=str_replace('{$data_path}',$data_path,$data_source);

				if($Section->use_fopen)
					{
					echo "Using fopen on {$data_source}<br>";
					echo str_pad('',4096)."\n";    
					flush();
				
					$file = fopen($data_source, "r");
#					$data='0% processing...';
#					write_file($data,$module_name);
					$vb_counter=0;
					$oldincrement=''; $colName=[];$Rows=[];
					while (($Rows = fgetcsv($file, 10000, ",")) !== FALSE) 
						{
						if($Rows[0]=='id')
							{
							echo "Row #1.<br>";
							echo str_pad('',4096)."\n";    
							flush();
						  
							foreach($Rows as $column)
								{
								$colName[]=$wpdb->prepare('%1s',$column);
								}	
#							echo getPrintR($colName);	
#							echo str_pad('',4096)."\n";    
#							flush();	
							}
						else
							{
							$counter++;
							$section_counter++;
							$insertArray=[];
							foreach($colName as $c=>$colname)
								{
								$insertArray[$colname]=$wpdb->prepare('%1s',$Rows[$c]);
								}
#							echo "<b>$table_name</b>".getPrintR($insertArray);	
#							echo str_pad('',4096)."\n";    
#							flush();	
							$wpdb->insert
								( 
								$table_name,
								$insertArray
								);
							if($module_data->incrementby=='book')
								{
								$bid=$Rows[1];
								$increment=$Books[$bid];
								if($increment!=$oldincrement)
									{
									$progress=floor(($counter/$data_size)*100);
									$data="$progress"."% $increment";
									write_file($data,$module_name);
#									echo "$data<br>\n";
#									echo str_pad('',4096)."\n";    
#									flush();
									$oldincrement=$increment;
									}
								}
							else
								{
								$_count=(int)$module_data->incrementby;	
								$_size=(int)$module_data->data_size;					
								if(($counter/$_count) == floor($counter/$_count))
									{
									$progress=floor(($counter/$_size)*100);
									$section_progress=floor(($section_counter/$section_size)*100);
									$data="$progress"."% ($section_progress"."% of $section_name)";
									write_file($data,$module_name);
									}
								}
							}
						}
					fclose($file);
					}
				}

/*
	

			$module_status=virtual_bible_getMeta("module_$module_name");
			$table_name = $wpdb->prefix . 'virtual_bible_meta';
			if($module_status=='disabled')
				{
				$wpdb->update
					( 
					$table_name,
					array
						( 
						'meta_value'	=>  'installed'
						),
					array
						(
						'meta_key'		=>  "module_$module_name"
						)
					);
				}
			elseif(!$module_status)
				{
				$wpdb->insert
					( 
					$table_name,
					array
						( 
						'meta_key'		=>  "module_$module_name",
						'meta_value'	=>  'installed'
						)
					);
				}

			$data='100% Done';
			write_file($data,$module_name);
*/
			}
		}
	}

echo "Done.";


function write_file($data,$module_name)
	{	
	$filename=$module_name.'.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
