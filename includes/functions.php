<?php


function virtual_bible_getMeta($key)
	{
	global $wpdb;
	$table_name = $wpdb->prefix . 'virtual_bible_meta';
	$Results = $wpdb->get_results("SELECT meta_value from $table_name WHERE meta_key = '$key' LIMIT 1;", ARRAY_A);
	if(isset($Results[0]['meta_value']))
		{
		return $Results[0]['meta_value'];
		}
	else
		{
		return FALSE;
		}
	}


?>