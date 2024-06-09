<?php
/**
* Plugin Name: The Virtual Study Bible
* Plugin URI: https://VirtualBible.org/
* Description: A complete study Bible on your Wordpress site!! (Activation must be completed in plugin settings page.)
* Version: 1.0
* Author: Danny Carlton Ministries
* Author URI: http://DannyCarlton.org/
* Developer: Danny Carlton
* Text Domain: the-virtual-study-bible
**/

if(!defined('ABSPATH')) 
	{
	exit; // Die, hacker scum, die!!
	}



/* 
	Hi there,

	I assume if you're seeing this, you're perusing my code. 
	
	Welcome.

	I have to admit, I'm probably not the best coder and I have a bad habit of not documenting my code very well. 

	I'm pretty much old school. My first taste of coding was in 1982, on a Unix mainframe, using basic, until I started annoying the engineering students by slowing down the system. It was suggested I buy a floppy and use the Apple ]['s instead. I did, and never looked back. I eventually moved to the IBM PC's and in the early 90's was finally able to save up enough to buy my first computer (an IBM clone with a monochrome moniter and a hercules graphics card!).

	In other words, I'm 100% self-taught.

	And, yes, I know that there's a lot of inefficiencies in this coding. I did it all myself, and was in a little bit of a rush. My focus was on getting it functional and looking nice. Streamlining the coding was secondary to that. I plan on making it more efficient as I improved it for updates.

	So, a bit about my coding style... 

	I like being able to tell arrays apart from simple variables, so I always used camelCase for arrays and snake_case for regular variables. If it's just one word then the regular variable is in all lowercase and at least one letter in the array name is Capitalized. However, some of my coding I reuse, and sometimes I will copy older code, before I began that practice, which won't use that naming convention. However, I've tried to make all the coding consistent.

	Variables that function as a sort of pseudo-constant (for example $_mysql) that more or less remain unchanged throughout the script, I generally begin with a single underscore. And more recently I began formatting the variable names for objects with a capital letter and two underscores at the beginning (for example $__moduleData).

	Also, it's easier for me to see the logic in a function or statement if I spread it out a bit vertically. This applies to both PHP and JavaScript. For example where typically coders would do this... 

		if($some_choice){
			$then_do_this;
		}

	I would structure it... 

		if($some_choice)
			{
			$then_do_this;
			} 

	...and even in JavaScript, I repeat the pattern even for parentheses (most of the time) so you'd see... 
			
		$("#some-element-trigger").on
			(
			"click", function(e)
				{
				$.ajax
					(
						{
						type: "GET",
						url: nonce_url,
						data: {keyword:keyword},
						success: function(data)
							{
							$("#some-element-content").html(data);
							}
						}
					);
				}
			);

	Yes, not an effcient use of vertical space, but, to me, much easier to read when I'm scanning through thousands of lines of code.

	---Danny Carlton
*/


/* BEGIN: Stuff to do every time */

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
		'Virtual Bible Help',
		'<span class="dashicons dashicons-editor-help" style="color:#709fef"></span>&nbsp;Help',
		'administrator', 
		'the-virtual-study-bible/help.php'
		);
	add_submenu_page(
		'virtual_bible_menu_slug',										#parent slug
		'Virtual Bible Contribute',										#page title
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
		}
	else
		{
		$wpdb->get_results("SELECT id FROM $table_name"); //db call ok; no-cache ok
		if($wpdb->num_rows<66)
			{
			$installed=false;
			}
		}

	$table_name = $wpdb->prefix . 'virtual_bible_kjvs'; #31102 rows
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
		$installed=false;
		}
	else
		{
		$wpdb->get_results("SELECT id FROM $table_name"); //db call ok; no-cache ok
		if($wpdb->num_rows<31102)
			{
			$installed=false;
			}
		}

	$table_name = $wpdb->prefix . 'virtual_bible_lexicon_hebrew'; # 8673 rows
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
		$installed=false;
		}
	else
		{
		$wpdb->get_results("SELECT id FROM $table_name"); //db call ok; no-cache ok
		if($wpdb->num_rows<8673)
			{
			$installed=false;
			}
		}


	$table_name = $wpdb->prefix . 'virtual_bible_lexicon_greek'; # 5624 rows
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
		$installed=false;
		}
	else
		{
		$wpdb->get_results("SELECT id FROM $table_name"); //db call ok; no-cache ok
		if($wpdb->num_rows<5624)
			{
			$installed=false;
			}
		}

	$table_name = $wpdb->prefix . 'virtual_bible_meta';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
		{
		$installed=false;
		}


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


include_once(plugin_dir_path(__FILE__).'includes/modules.php');
include_once(plugin_dir_path(__FILE__).'includes/functions.php');
include_once(plugin_dir_path(__FILE__).'includes/study-bible.php');

/* END: Stuff to do every time */


/* BEGIN: Stuff to do only when plugin is activated */

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
			id int(11) NOT NULL AUTO_INCREMENT,
			meta_key varchar(50) NOT NULL,
			meta_value text NOT NULL,
			PRIMARY KEY id (id)
			) $charset_collate ENGINE=MyISAM;");

	$table_name = $wpdb->prefix . 'virtual_bible_books';
	array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			book varchar(20) NOT NULL,
			chapters int(11) NOT NULL,
			abbr text NOT NULL,
			longname text NOT NULL,
			PRIMARY KEY id (id)
			) $charset_collate ENGINE=MyISAM;");

	$table_name = $wpdb->prefix . 'virtual_bible_gty_intro_outline';
	array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			book int(11) NOT NULL,
			text text NOT NULL,
			PRIMARY KEY id (id),
			KEY ixb (book)
			) $charset_collate ENGINE=MyISAM;");	
			

	$table_name = $wpdb->prefix . 'virtual_bible_users';
	array_push($Queries, "CREATE TABLE IF NOT EXISTS $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			user_key varchar(50) NOT NULL,
			user_value text NOT NULL,
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


	$table_name = $wpdb->prefix . 'virtual_bible_meta';
	$wpdb->insert
		( 
		$table_name,
		array
			( 
			'meta_key'		=>  'style_traditional',
			'meta_value'	=>  'selected'
			)
		);
	$wpdb->insert
		( 
		$table_name,
		array
			( 
			'meta_key'		=>  'style_paragraph',
			'meta_value'	=>  'selected'
			)
		);
	$wpdb->insert
		( 
		$table_name,
		array
			( 
			'meta_key'		=>  'style_reader',
			'meta_value'	=>  'selected'
			)
		);
	$wpdb->insert
		( 
		$table_name,
		array
			( 
			'meta_key'		=>  'page_name',
			'meta_value'	=>  'Study Bible'
			)
		);

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
				}
			else
				{
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
		$dbRow=[];
		}


		
	$table_name = $wpdb->prefix . 'virtual_bible_gty_intro_outline';

	$Rows=[];$r=1;
	$file = fopen('https://cdn.virtualbible.org/virtual_bible_intro_gty.csv', 'r');
	while (($Rows = fgetcsv($file, 10000, ",")) !== FALSE) 
		{
		if($Rows[0]!='id')
			{
			$book=$Rows[1];
			$text=$Rows[2];
			$dbRow=$wpdb->get_results("SELECT * FROM $table_name WHERE `id` = $r;", ARRAY_A); //db call ok; no-cache ok
			if(isset($dbRow[0]['id']))
				{
				}
			else
				{
				$wpdb->insert
					( 
					$table_name,
					array
						( 
						'id'		=>  $r,
						'book'		=>  $book,
						'text'		=>  $text
						)
					); //db call ok
				}
			$r++;
			}
		}


		


	}

/* END: Stuff to do only when plugin is activated */



/* BEGIN: Stuff to do when plugin is deleted */

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

/* END: Stuff to do when plugin is deleted */



/* For debugging purposes. If this is still here in production version, then I screwed up. But don't worry; it's basically harmless. */
function write_log( $data ) 
	{
	if ( true === WP_DEBUG ) 
		{
		if ( is_array( $data ) || is_object( $data ) ) 
			{
			error_log( print_r( $data, true ) );
			} 
		elseif($data==NULL)
			{
			error_log('NULL');
			}
		else 
			{
			error_log( $data );
			}
		}
	}