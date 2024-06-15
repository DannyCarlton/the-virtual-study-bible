<?php

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}

add_shortcode('The-Virtual-Study-Bible','buildStudyBiblePage');





function buildStudyBiblePage()
	{
	global $wpdb,$_GET,$_vb;
	$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));
	$page_name=$_vb->getMeta('page_name');
	$page_slug=sanitize_title($page_name);
	$page_url=site_url().'/'.$page_slug.'/';
	$style=$_vb->getUserMeta('style');
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

	wp_register_style('vb-bootstrap4-css', plugins_url().'/the-virtual-study-bible/css/bootstrap4sb.css');
	wp_enqueue_style('vb-bootstrap4-css');
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
	$virtual_bible_traditional_select=$_vb->getMeta('style_traditional');
	$virtual_bible_paragraph_select=$_vb->getMeta('style_paragraph');
	$virtual_bible_reader_select=$_vb->getMeta('style_reader');
	$reference='';

	if(isset($_GET['keyword']))
		{
		$keyword=$_GET['keyword'];
		$keyword=str_replace('"','`',$keyword);
		$keyword=str_replace('\\','',$keyword);
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
		$virtual_bible_page = $_vb->buildResultsPage($keyword,$scope,$version,$layout);
		}
	else
		{
		// build initial page
		$virtual_bible_page = $_vb->buildStartPage();
		}

	return $virtual_bible_page;
	}


?>