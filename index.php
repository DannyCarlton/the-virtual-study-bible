<?php
/**
* Plugin Name: The Virtual Study Bible
* Plugin URI: https://VirtualBible.org/
* Description: A complete study Bible on your Wordpress site!! (NOTE: Installation must be completed on the Settings page.)
* Version: 1.0
* Author: Danny Carlton
* Author URI: http://DannyCarlton.org/
**/


add_action( 'admin_menu', 'wpdocs_register_virtual_bible_menu_page' );

function wpdocs_register_virtual_bible_menu_page() 
	{
	add_menu_page
		( 
		'The Virtual Study Bible', 					#page title
		'Virtual Bible', 							#menu title
		'administrator',							#capability
		'virtual_bible_menu_slug', 					#menu slug
		'settings.php',								#callback?
		'dashicons-book'							#icon url
		);
	}

add_action('admin_menu','wpdocs_register_virtual_bible_submenu_page_admin');

function wpdocs_register_virtual_bible_submenu_page_admin()
	{
	add_submenu_page(
		'virtual_bible_menu_slug',
		'Virtual Bible Settings',
		'Settings',
		'administrator', 
		'the-virtual-study-bible/settings.php'
		);
	add_submenu_page(
		'virtual_bible_menu_slug',						#parent slug
		'Contribute',									#page title
		'Contribute',									#menu title
		'administrator', 								#capability
		'the-virtual-study-bible/contribute.php'		#callback
		);

	}

add_action('admin_menu','virtual_bible_clean_menus');

function virtual_bible_clean_menus()
	{
	remove_submenu_page('virtual_bible_menu_slug','virtual_bible_menu_slug'); #This removes the main menu title from the list of submenus
	}

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'virtual_bible_add_plugin_page_settings_link');

function virtual_bible_add_plugin_page_settings_link( $links)
	{
	$links[] = '<a href="'.
	admin_url('admin.php?page=the-virtual-study-bible%2Fsettings.php').
	'">'.__('Settings').'</a>';
	return $links;
	}

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'virtual_bible_add_plugin_page_donate_link');
	
function virtual_bible_add_plugin_page_donate_link( $links)
	{
	$links[] = '<b style="color:#f44"><a href="https://ko-fi.com/dannycarltonministrysites" target="_blank">'.__('Donate').'</a></b>';
	return $links;
	}


/*

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'virtual_bible_debug_link');
	
function virtual_bible_debug_link( $links)
	{
	$time_marker=date('h:i:s a', time());
	$links[] = '<span style="color:#000">'.$time_marker.'</span>';
	return $links;
	}
*/


register_activation_hook( __FILE__, 'virtual_bible_create_db_table');


function virtual_bible_create_db_table()
	{
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'virtual_bible_kjvs';
	$table2_name = $wpdb->prefix . 'virtual_bible_books';
	$Queries=[];
	array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			book tinyint(3) NOT NULL,
			chapter tinyint(3) NOT NULL,
			verse tinyint(3) NOT NULL,
			text text NOT NULL,
			PRIMARY KEY id (id),
			KEY ixb (book),
			KEY ixc (chapter),
			KEY ixv (verse),
			KEY ixbcv (book,chapter,verse)
			) $charset_collate ENGINE=MyISAM;");
	array_push($Queries, "CREATE TABLE IF NOT EXISTS $table2_name (
			id int(11) NOT NULL,
			book varchar(20) NOT NULL,
			chapters tinyint(3) NOT NULL,
			abbr text NOT NULL,
			longname text NOT NULL,
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
	}



register_activation_hook( __FILE__, 'virtual_bible_load_db_books');

function virtual_bible_load_db_books()
	{
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'virtual_bible_books';

#    $file = fopen('https://cdn.virtualbible.org/virtual_bible_books.csv', "r");

	$file = wp_remote_fopen('https://cdn.virtualbible.org/virtual_bible_books.csv');

	#$Rows=explode("\n",$file);
	$Rows=str_getcsv($file, "\n");

#	write_log($Rows);

	$Columns=[];
	foreach($Rows as $r=>$row)
		{
		if($r>0)
			{
			$Columns[$r]=explode('","',$row);
			$Columns[$r][0]=str_replace($r.',"','',$Columns[$r][0]);
			$Columns[$r][3]=str_replace('"','',$Columns[$r][3]);
			$dbRow=$wpdb->get_row($wpdb->prepare("SELECT * FROM %s WHERE id = %d LIMIT 1;", $table_name,$r)); //db call ok; no-cache ok
			if(isset($dbRow))
				{
#				write_log($dbRow);
				}
			else
				{
#				write_log("No data found for $r");
				$Column=$Columns[$r];
				$wpdb->insert
					( 
					$table_name,
					array
						( 
						'id'		=>  $r,
						'book'		=>  $Column[0],
						'chapters'	=>  $Column[1],
						'abbr'		=>  $Column[2],
						'longname'	=>  $Column[3]
						)
					); //db call ok
				}
			}
		}

#	write_log($Columns);

	}

/*

register_activation_hook( __FILE__, 'virtual_bible_load_db_text');

function virtual_bible_load_db_text()
	{
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'virtual_bible_kjvs';

	$file = fopen(ABSPATH . '/wp-content/plugins/the-virtual-study-bible/virtual_bible_kjvs.csv', "r");
	$vb_counter=0;
	while (($column = fgetcsv($file, 10000, ",")) !== FALSE) 
		{
		if($column[0]!='id')
			{
			$vb_counter++;
			$wpdb->insert
				( 
				$table_name,
				array
					( 
					'id'		=>  $vb_counter,
					'book'		=>  $column[1],
					'chapter'	=>  $column[2],
					'verse'		=>  $column[3],
					'text'		=>  $column[4]
					)
				);
			}
		}
	

	}
*/

register_uninstall_hook(__FILE__, 'virtual_bible_on_uninstall');

function virtual_bible_on_uninstall()
	{
	global $wpdb;
	$table_name = $wpdb->prefix . 'virtual_bible_kjvs';
	$table2_name = $wpdb->prefix . 'virtual_bible_books';
	$Queries=[];
	array_push($Queries, "DROP TABLE IF EXISTS $table_name;");
	array_push($Queries, "DROP TABLE IF EXISTS $table2_name;");
	if ( ! function_exists('dbDelta') )
		{
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		}
	foreach($Queries as $sql)
		{
		dbDelta ( $sql );
		}
	}

function write_log( $data ) 
	{
	if ( true === WP_DEBUG ) 
		{
		if ( is_array( $data ) || is_object( $data ) ) 
			{
			error_log( print_r( $data, true ) );
			} 
		else 
			{
			error_log( $data );
			}
		}
	}