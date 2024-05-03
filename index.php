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
		'The Virtual Study Bible', 							#page title
		'Virtual Bible', 									#menu title
		'administrator',									#capability
		'virtual_bible_menu_slug', 							#menu slug
		'settings.php',										#function/callback
		plugins_url('the-virtual-study-bible/icon.png')		#icon url
		);
	}

add_action('admin_menu','wpdocs_register_virtual_bible_submenu_page_admin');

function wpdocs_register_virtual_bible_submenu_page_admin()
	{
	add_submenu_page(
		'virtual_bible_menu_slug',
		'Virtual Bible Settings',
		'<span class="dashicons dashicons-admin-settings" style="color:#709fef"></span>&nbsp;Settings',
		'administrator', 
		'the-virtual-study-bible/settings.php'
		);
	add_submenu_page(
		'virtual_bible_menu_slug',
		'Virtual Bible Settings',
		'<span class="dashicons dashicons-editor-help" style="color:#709fef"></span>&nbsp;Help',
		'administrator', 
		'the-virtual-study-bible/help.php'
		);
	add_submenu_page(
		'virtual_bible_menu_slug',										#parent slug
		'Contribute',													#page title
		'<span class="dashicons dashicons-heart" style="color:#ed0000"></span>&nbsp;Contribute',	#menu title
		'administrator', 												#capability
		'the-virtual-study-bible/contribute.php'						#callback
		);

	}

add_action('admin_menu','virtual_bible_clean_menus');

function virtual_bible_clean_menus()
	{
	remove_submenu_page('virtual_bible_menu_slug','virtual_bible_menu_slug'); #This removes the main menu title from the list of submenus
	}

	
add_action('admin_menu','virtual_bible_is_installed');

function virtual_bible_is_installed()
	{
	global $wpdb;
	$installed=true;
	# See if the required database tables exist...

	$table_name = $wpdb->prefix . 'virtual_bible_books';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
		$installed=false;
#		write_log($table_name. ' table does not exist');
		}
	else
		{
#		write_log($table_name.' table exists');
		$wpdb->get_results("SELECT id FROM $table_name"); //db call ok; no-cache ok
		if($wpdb->num_rows<66)
			{
			$installed=false;
			write_log($table_name.' has only '.$wpdb->num_rows.' rows!');
			}
		}

	$table_name = $wpdb->prefix . 'virtual_bible_kjvs'; #31102 rows
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
		$installed=false;
#		write_log($table_name. ' table does not exist');
		}
	else
		{
#		write_log($table_name.' table exists');
		$wpdb->get_results("SELECT id FROM $table_name"); //db call ok; no-cache ok
		if($wpdb->num_rows<31102)
			{
			$installed=false;
#			write_log($table_name.' has only '.$wpdb->num_rows.' rows!');
			}
		}

	$table_name = $wpdb->prefix . 'virtual_bible_lexicon_hebrew'; # 8673 rows
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
		$installed=false;
#		write_log($table_name. ' table does not exist');
		}
	else
		{
#		write_log($table_name.' table exists');
		$wpdb->get_results("SELECT id FROM $table_name"); //db call ok; no-cache ok
		if($wpdb->num_rows<8673)
			{
			$installed=false;
#			write_log($table_name.' has only '.$wpdb->num_rows.' rows!');
			}
		}


	$table_name = $wpdb->prefix . 'virtual_bible_lexicon_greek'; # 5624 rows
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
		$installed=false;
#		write_log($table_name. ' table does not exist');
		}
	else
		{
#		write_log($table_name.' table exists');
		$wpdb->get_results("SELECT id FROM $table_name"); //db call ok; no-cache ok
		if($wpdb->num_rows<5624)
			{
			$installed=false;
#			write_log($table_name.' has only '.$wpdb->num_rows.' rows!');
			}
		}

	$table_name = $wpdb->prefix . 'virtual_bible_meta';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
		$installed=false;
		}

	# See if they contain the correct data...

	if($installed)
		{
		add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'virtual_bible_add_plugin_page_settings_link_installed');
		}
	else
		{
		add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'virtual_bible_add_plugin_page_settings_link_uninstalled');
		}
	}



function virtual_bible_add_plugin_page_settings_link_installed( $links )
	{
	$links[] = '<a href="'.
	admin_url('admin.php?page=the-virtual-study-bible%2Fsettings.php').
	'">'.__('Settings').'</a>';
	return $links;
	}

function virtual_bible_add_plugin_page_settings_link_uninstalled( $links )
	{
	$links[] = '<b><a href="'.
	admin_url('admin.php?page=the-virtual-study-bible%2Fsettings.php').
	'" style="color:#a00">'.__('Click to finish installation!').'</a></b>';
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


register_activation_hook( __FILE__, 'virtual_bible_on_activation');

function virtual_bible_on_activation()
	{
	wpdocs_register_virtual_bible_menu_page();
	virtual_bible_create_db_table();
	}

register_activation_hook( __FILE__, 'virtual_bible_load_db_books');

register_activation_hook(__FILE__, 'virtual_bible_redirect_after_activation');

function virtual_bible_redirect_after_activation() 
	{
    add_option('virtual_bible_redirect_after_activation_option', true);
	}

add_action('admin_init', 'virtual_bible_activation_redirect');

function virtual_bible_activation_redirect() 
	{
    if (get_option('virtual_bible_redirect_after_activation_option', false)) 
		{
        delete_option('virtual_bible_redirect_after_activation_option');
        exit(wp_redirect(admin_url( 'admin.php?page=the-virtual-study-bible%2Fsettings.php' )));
		}
	}

function virtual_bible_create_db_table()
	{
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$Queries=[];

	$table_name = $wpdb->prefix . 'virtual_bible_meta';
	array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
			id int(11) NOT NULL,
			meta_key varchar(20) NOT NULL,
			meta_value text NOT NULL,
			PRIMARY KEY id (id)
			) $charset_collate ENGINE=MyISAM;");

	$table_name = $wpdb->prefix . 'virtual_bible_kjvs';
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

	$table_name = $wpdb->prefix . 'virtual_bible_books';
	array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
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


function virtual_bible_load_db_books()
	{
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'virtual_bible_books';

	$file = wp_remote_fopen('https://cdn.virtualbible.org/virtual_bible_books.csv');

	$Rows=str_getcsv($file, "\n");


	$Columns=[];$dbRow=[];
	foreach($Rows as $r=>$row)
		{
		if($r>0)
			{
			$Columns[$r]=explode('","',$row);
			$Columns[$r][0]=str_replace($r.',"','',$Columns[$r][0]);
			$Columns[$r][3]=str_replace('"','',$Columns[$r][3]);
			$dbRow=$wpdb->get_results("SELECT * FROM $table_name WHERE `id` = $r;", ARRAY_A); //db call ok; no-cache ok
			if(isset($dbRow[0]['id']))
				{
#				write_log('Row '.$r.' exists');
				}
			else
				{
#				write_log('$dbRow = ');
#				write_log($dbRow);
				$Column=$Columns[$r];
				write_log( 'Writing row '.$r.' to database: '.$row );
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
		$dbRow=[];
		}

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