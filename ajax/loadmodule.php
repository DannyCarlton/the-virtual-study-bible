<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
error_reporting(E_ALL);
set_time_limit(2400);

include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}

if(isset($_POST['name'])){$_GET['name']=$_POST['name'];}

if(isset($_GET['name']))
	{
	$module_name=$_GET['name'];
	$module_path=str_replace('ajax/','modules/',plugin_dir_path(__FILE__));
	$data_path=str_replace('ajax/','data/',plugin_dir_path(__FILE__));
	$__moduleData=simplexml_load_file("$module_path$module_name.xml", 'SimpleXMLElement', LIBXML_NOCDATA);


	$verify = wp_verify_nonce($_GET['_wpnonce'], $module_name);

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

			if($__moduleData->load_books=='TRUE')
				{
				echo "Loading books.<br>";
				echo str_pad('',4096)."\n";    
				flush();
				$table_name = 'virtual_bible_books';
				$Books=[];
				$_Books=$_vb->dbFetch($table_name);
				foreach($_Books as $Book)
					{
					$bookname=$Book['book'];
					$bid=$Book['id'];
					$Books[$bid]=$bookname;
					}
				}

			$data_size=(int)$__moduleData->data_size;
			$counter=0;
			foreach($__moduleData->section as $__Section)
				{
				$Rows=[];
				$section_size=(int)$__Section->section_data_size;
				$section_counter=0;
				$section_name=$__Section->section_name;
				$table_name = $wpdb->prefix . $__Section->table_name;
				$charset_collate = $wpdb->get_charset_collate();
				$create_table=(string)$__Section->create_table;
				$create_table=str_replace('{$table_name}','%1s',$create_table);
				$create_table=str_replace('{$charset_collate}','%1s',$create_table);
				$create_table=$wpdb->prepare($create_table,array($table_name,$charset_collate));

#				$wpdb->show_errors = true;
#				$wpdb->suppress_errors = false;
				$query=$wpdb->query($create_table);
#				echo getPrintR($wpdb->last_query);
#				echo $wpdb->last_error;
				$data_source=(string)$__Section->data_source;
				$data_source=str_replace('{$data_path}',$data_path,$data_source);

				if($__Section->use_fopen)
					{				
					if(($file = fopen($data_source, "r")) !== false)
						{
						$vb_counter=0;
						$oldincrement=''; $colName=[];$Rows=[];
						while (($Rows = fgetcsv($file, 10000, ",")) !== FALSE) 
							{
							if($Rows[0]=='id')
								{
								foreach($Rows as $column)
									{
									$colName[]=$wpdb->prepare('%1s',$column);
									}	
								}
							elseif(isset($Rows[1]))
								{
								$counter++;
								$section_counter++;
								$insertArray=[];
								foreach($colName as $c=>$colname)
									{
									$insertArray[$colname]=$wpdb->prepare('%1s',$Rows[$c]);
									}
								$wpdb->insert
									( 
									$table_name,
									$insertArray
									);
								if($__moduleData->incrementby=='book')
									{
									$bid=$Rows[1];
									$increment=$Books[$bid];
									if($increment!=$oldincrement)
										{
										$progress=floor(($counter/$data_size)*100);
										$data="$progress"."% $increment";
										write_file($data,$module_name);
										$oldincrement=$increment;
										}
									}
								else
									{
									$_count=(int)$__moduleData->incrementby;	
									$_size=(int)$__moduleData->data_size;					
									if(($counter/$_count) == floor($counter/$_count))
										{
										$progress=floor(($counter/$_size)*100);
										$section_progress=floor(($section_counter/$section_size)*100);
										$data="$progress"."% ($section_progress"."% of $section_name)";
										write_file($data,$module_name);
										}
									}
								}
							else
								{
								write_log($Rows);
								}
							}
						fclose($file);
						}
					else
						{
						write_log('Error in fgetcsv.');
						}
					}
				}


	

			$module_status=$_vb->getMeta("module_$module_name");
#			write_log($module_status);
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
