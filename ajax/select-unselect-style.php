<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('../../../../wp-load.php');
$verify = wp_verify_nonce($_GET['_wpnonce'], 'select-unselect-style');

if($verify)
	{
	if(isset($_POST['style']))
		{
		$style=str_replace('virtual-bible-style-','',$_POST['style']);
		$table_name = $wpdb->prefix . 'virtual_bible_meta';
		$Results = $wpdb->get_results("SELECT meta_value from $table_name WHERE meta_key = 'style_$style' LIMIT 1;", ARRAY_A);
		if($Results[0]['meta_value']=='selected')
			{
			$wpdb->update
				( 
				$table_name,
				array
					( 
					'meta_value'	=>  ''
					),
				array
					(
					'meta_key'		=>  'style_'.$style
					)
				);
			echo 'unselected';
			}
		else
			{
			$wpdb->update
				( 
				$table_name,
				array
					( 
					'meta_value'	=>  'selected'
					),
				array
					(
					'meta_key'		=>  'style_'.$style
					)
				);
			echo 'selected';
			}
		}
	else
		{
		echo "No data sent.";
		}

	}





?>
