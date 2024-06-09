<?php

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}

add_shortcode('The-Virtual-Study-Bible','buildStudyBiblePage');

$_vb = new virtual_bible();




function buildStudyBiblePage()
	{
	global $wpdb,$_GET,$_vb;
	$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));
	$page_name=virtual_bible_getMeta('page_name');
	$page_slug=sanitize_title($page_name);
	$page_url=site_url().'/'.$page_slug.'/';
	$style=virtual_bible_getUserMeta('style');
	if(!$style){$style='traditional';}
	$trad_checked='';$par_checked='';$read_checked='';
	if($style=='traditional')
		{
		$trad_checked='checked';
		}
	elseif($style=='paragraph')
		{
		$par_checked='checked';
		}
	else
		{
		$read_checked='checked';
		}

	wp_register_style('vb-bootstrap4-css', plugins_url().'/the-virtual-study-bible/css/bootstrap4b.css');
	wp_enqueue_style('vb-bootstrap4-css');
	wp_register_style('vb-bootstrap4-toggle-css', plugins_url().'/the-virtual-study-bible/css/bootstrap4-toggle.css');
	wp_enqueue_style('vb-bootstrap4-toggle-css');
	wp_register_style('vb-fonts-css', plugins_url().'/the-virtual-study-bible/css/fontawesome.css');
	wp_enqueue_style('vb-fonts-css');
	wp_register_style('vb-logofont-css', plugins_url().'/the-virtual-study-bible/css/logofont.css');
	wp_enqueue_style('vb-logofont-css');
	wp_register_style('vb-styles-css', plugins_url().'/the-virtual-study-bible/css/vb-styles.css');
	wp_enqueue_style('vb-styles-css');
	wp_register_style('vb-study-bible-css', plugins_url().'/the-virtual-study-bible/css/study-bible.css');
	wp_enqueue_style('vb-study-bible-css');
	
	wp_register_script('vb-jquery-js', plugins_url().'/the-virtual-study-bible/js/jquery-2.2.4.js');
	wp_enqueue_script('vb-jquery-js');
	wp_register_script('vb-popper-js', plugins_url().'/the-virtual-study-bible/js/popper-2.11.8.js');
	wp_enqueue_script('vb-popper-js');
	wp_register_script('vb-bootstrap-js', plugins_url().'/the-virtual-study-bible/js/bootstrap.js');
	wp_enqueue_script('vb-bootstrap-js');
	wp_register_script('vb-bootstrap4-toggle-js', plugins_url().'/the-virtual-study-bible/js/bootstrap4-toggle.js');
	wp_enqueue_script('vb-bootstrap4-toggle-js');
/*
	$virtual_bible_kjvs_installed=virtual_bible_is_module_installed('kjvs');
	$virtual_bible_strongs_installed=virtual_bible_is_module_installed('strongs');
	$virtual_bible_eastons_installed=virtual_bible_is_module_installed('eastons');
	$virtual_bible_hebrew_installed=virtual_bible_is_module_installed('hebrew');
	$virtual_bible_greek_installed=virtual_bible_is_module_installed('greek');
	$virtual_bible_holman_installed=virtual_bible_is_module_installed('holman');
*/
	$virtual_bible_traditional_select=virtual_bible_getMeta('style_traditional');
	$virtual_bible_paragraph_select=virtual_bible_getMeta('style_paragraph');
	$virtual_bible_reader_select=virtual_bible_getMeta('style_reader');
	$reference='';

	if(isset($_GET['keyword']))
		{
		$keyword=$_GET['keyword'];
		$keyword=str_replace('"','`',$keyword);
		$reference=$keyword;
		if(isset($_GET['scope']))
			{
			$scope=$_GET['scope'];
			}
		else
			{
			$scope=0;
			}
		if(isset($_GET['layout']))
			{
			$layout=$_GET['layout'];
			}
		else
			{
			$layout='trad';
			}
		if(isset($_GET['version']))
			{
			$version=$_GET['version'];
			}
		else
			{
			$version='kjvs';
			}
		// build page based on keyword, scope and style
		$virtual_bible_page = virtual_bible_buildResultsPage($keyword,$scope,$version,$layout);
		}
	else
		{
		// build initial page
		$virtual_bible_page = virtual_bible_buildStartPage();
		}

	return $virtual_bible_page;
	}


?>