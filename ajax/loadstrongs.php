<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('../../../../wp-load.php');

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}
	
$verify = wp_verify_nonce($_GET['_wpnonce'], 'strongs');

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
				'meta_key'		=>  'module_strongs'
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
				'meta_key'		=>  'module_strongs'
				)
			);

		}
	else
		{	
		$Queries=[];
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'virtual_bible_lexicon_hebrew';
		array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
			id 				int(11) 		NOT NULL,
			orig_word 		text 			NOT NULL,
			orig_word_utf8 	varchar(50) 	NOT NULL,
			orig_word_enc	text 			NOT NULL,
			word_orig		text 			NOT NULL,
			translit		text			NOT NULL,
			tdnt			text			NOT NULL,
			phonetic		text			NOT NULL,
			part_of_speech	text			NOT NULL,
			st_def			text			NOT NULL,
			ipd_def			text			NOT NULL,
			PRIMARY KEY id (id)
			) $charset_collate ENGINE=MyISAM;");

		$table_name = $wpdb->prefix . 'virtual_bible_lexicon_greek';
		array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
			id 				int(11) 		NOT NULL,
			orig_word 		text 			NOT NULL,
			orig_word_utf8 	varchar(75) 	NOT NULL,
			orig_word_enc	text 			NOT NULL,
			word_orig		text 			NOT NULL,
			translit		text			NOT NULL,
			tdnt			text			NOT NULL,
			phonetic		text			NOT NULL,
			part_of_speech	text			NOT NULL,
			st_def			text			NOT NULL,
			ipd_def			text			NOT NULL,
			PRIMARY KEY id (id)
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
		$file = fopen('https://cdn.virtualbible.org/virtual_bible_lex_hebrew.csv', "r");
		$data='0% Processing...';
		write_file($data);
		$table_name = $wpdb->prefix . 'virtual_bible_lexicon_hebrew';
		$lastword='';$osid='H1';
		while (($column = fgetcsv($file, 10000, ",")) !== FALSE) 
			{
			if($column[0]!='id') 
				{
				$counter++;
				$column[2]=str_replace("\n",'',$column[2]);
				$sid='H'.$column[0];
				$wpdb->insert
					( 
					$table_name,
					array
						( 
						'id'				=>  $column[0],
						'orig_word'			=>  $column[1],
						'orig_word_utf8'	=>  $column[2],
						'orig_word_enc'		=>  $column[3],
						'word_orig'			=>  $column[4],
						'translit'			=>  $column[5],
						'tdnt'				=>  $column[6],
						'phonetic'			=>  $column[7],
						'part_of_speech'	=>  $column[8],
						'st_def'			=>  $column[9],
						'IPD_def'			=>  $column[10]
						)
					);
				$word=$column[2];
				if(($counter/500) == floor($counter/500))
					{
					$progress=floor(($counter/14298)*100);
					$data="$progress"."% $osid...$sid";
					write_file($data);
					$lastword=$word;
					$osid=$sid;
					}
				}
			}

			
		$file = fopen('https://cdn.virtualbible.org/virtual_bible_lex_greek.csv', "r");
		$table_name = $wpdb->prefix . 'virtual_bible_lexicon_greek';
		while (($column = fgetcsv($file, 10000, ",")) !== FALSE) 
			{
			if($column[0]!='id')  
				{
				$counter++;
				$column[2]=str_replace("\n",'',$column[2]);
				$sid='G'.$column[0];
				$wpdb->insert
					( 
					$table_name,
					array
						( 
						'id'				=>  $column[0],
						'orig_word'			=>  $column[1],
						'orig_word_utf8'	=>  $column[2],
						'orig_word_enc'		=>  $column[3],
						'word_orig'			=>  $column[4],
						'translit'			=>  $column[5],
						'tdnt'				=>  $column[6],
						'phonetic'			=>  $column[7],
						'part_of_speech'	=>  $column[8],
						'st_def'			=>  $column[9],
						'IPD_def'			=>  $column[10]
						)
					);
				$word=$column[2];
				if(($counter/500) == floor($counter/500))
					{
					$progress=floor(($counter/14298)*100);
					$data="$progress"."% $osid...$sid";
					write_file($data);
					$lastword=$word;
					$osid=$sid;
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
				'meta_key'		=>  'module_strongs',
				'meta_value'	=>  'installed'
				)
			);
		$data='100% Done';
		write_file($data);
		}
	}




function write_file($data)
	{	
	$filename='strongs.log';
	$fp = fopen("./$filename", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}


?>
