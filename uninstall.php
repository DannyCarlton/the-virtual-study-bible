<?php

if(!defined('WP_UNINSTALL_PLUGIN')) 
	{
    exit; // Die, hacker scum, die!!
	}



global $wpdb,$_vb;
$virtual_bible_pagename=getMeta('page_name');
if(_get_page_by_title($virtual_bible_pagename, 'OBJECT', 'page'))
	{
	/* delete page */
	$wpdb->delete
		(
		$wpdb->prefix.'posts',
		array
			(
			'post_type' => 'page',
			'post_author' => 1,
			'post_title' => $virtual_bible_pagename
			)
		);
	}

$Queries=[];
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_kjvs';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_books';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_meta';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_users';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_gty_intro_outline';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_eastons';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_interlinear';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_lexicon_greek';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_lexicon_hebrew';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_lexwords';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_outline';
$Queries[]='DROP TABLE IF EXISTS '.$wpdb->prefix . 'virtual_bible_xref_holman';
foreach($Queries as $sql)
	{
	$wpdb->query($sql);
	}


function getMeta($key)
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

function _get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) 
	{
	$query = new WP_Query
		(
		array
			(
			'post_type' => $post_type,
			'title' => $page_title,
			'post_status' => 'all',
			'posts_per_page' => 1,
			'no_found_rows' => true,
			'ignore_sticky_posts' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'orderby' => 'date',
			'order' => 'ASC',
			)
		);
	
	if ( ! empty( $query->post ) ) 
		{
		$_post = $query->post;		
		if ( ARRAY_A === $output ) 
			{
			return $_post->to_array();
			} 
		elseif ( ARRAY_N === $output ) 
			{
			return array_values( $_post->to_array() );
			}
		return $_post;
		}		
	return null;
	}
?>