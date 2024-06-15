<?php

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}

$_vb = new virtual_bible();
$_vbm = new virtual_bible_modules();

$ScopeKey[0]='';
$ScopeKey[1]='&& `book` < "40" ';
$ScopeKey[2]='&& `book` > "39" ';
$ScopeKey[3]='&& `book` < "6"';
$ScopeKey[4]='&& `book` > "5" && `book` < "18" ';
$ScopeKey[5]='&& `book` > "17" && `book` < "23" ';
$ScopeKey[6]='&& `book` > "22" && `book` < "28" ';
$ScopeKey[7]='&& `book` > "27" && `book` < "40" ';
$ScopeKey[8]='&& `book` > "39" && `book` < "44" ';
$ScopeKey[9]='&& `book` > "44" && `book` < "59" ';
$ScopeKey[10]='&& `book` > "58" && `book` < "66" ';


$page_name=$_vb->getMeta('page_name');
$page_slug=sanitize_title($page_name);
$page_url=site_url().'/'.$page_slug.'/';
$reference='';
$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));









		
/********************************************************************************************
       _                       _      _               _     _     _ _     _      
   ___| | __ _ ___ ___  __   _(_)_ __| |_ _   _  __ _| |   | |__ (_) |__ | | ___ 
  / __| |/ _` / __/ __| \ \ / / | '__| __| | | |/ _` | |   | '_ \| | '_ \| |/ _ \
 | (__| | (_| \__ \__ \  \ V /| | |  | |_| |_| | (_| | |   | |_) | | |_) | |  __/
  \___|_|\__,_|___/___/   \_/ |_|_|   \__|\__,_|\__,_|_|___|_.__/|_|_.__/|_|\___|
                                                      |_____|                    
               __
              / /
             | | 
            < <  
             | | 
              \_\


*********************************************************************************************/

class virtual_bible 
	{


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
	




		
/********************************************************************************************
                  _   __  __      _         __
      _ __  _   _| |_|  \/  | ___| |_ __ _ / /
     | '_ \| | | | __| |\/| |/ _ \ __/ _` | | 
     | |_) | |_| | |_| |  | |  __/ || (_| | | 
     | .__/ \__,_|\__|_|  |_|\___|\__\__,_| | 
     |_|                                   \_\

*********************************************************************************************/

	function putMeta($key,$value)
		{
		global $wpdb;
		$table_name = $wpdb->prefix . 'virtual_bible_meta';
		$Results = $wpdb->get_results("SELECT meta_value from $table_name WHERE meta_key = '$key' LIMIT 1;", ARRAY_A);
		if(isset($Results[0]['meta_value']))
			{
			$wpdb->update
				( 
				$table_name,
				array
					( 
					'meta_value'	=>  $value
					),
				array
					(
					'meta_key'		=>  $key
					)
				);
			}
		else
			{
			$wpdb->insert
				( 
				$table_name,
				array
					( 
					'meta_key'		=>  $key,
					'meta_value'	=>  $value
					)
				);
			}
		}




		
/********************************************************************************************
                 _   _   _               __  __      _         __
       __ _  ___| |_| | | |___  ___ _ __|  \/  | ___| |_ __ _ / /
      / _` |/ _ \ __| | | / __|/ _ \ '__| |\/| |/ _ \ __/ _` | | 
     | (_| |  __/ |_| |_| \__ \  __/ |  | |  | |  __/ || (_| | | 
      \__, |\___|\__|\___/|___/\___|_|  |_|  |_|\___|\__\__,_| | 
      |___/                                                   \_\

*********************************************************************************************/


	function getUserMeta($key)
		{
		global $wpdb,$current_user;
		$user_id=$current_user->ID;
		if($user_id!=0)
			{
			$table_name = $wpdb->prefix . 'virtual_bible_users';
			$Results = $wpdb->get_results("SELECT user_value from $table_name WHERE user_key = '$key' LIMIT 1;", ARRAY_A);
			if(isset($Results[0]['user_value']))
				{
				return $Results[0]['user_value'];
				}
			else
				{
				return FALSE;
				}
			}
		}


		
/********************************************************************************************
                  _   _   _               __  __      _         __
      _ __  _   _| |_| | | |___  ___ _ __|  \/  | ___| |_ __ _ / /
     | '_ \| | | | __| | | / __|/ _ \ '__| |\/| |/ _ \ __/ _` | | 
     | |_) | |_| | |_| |_| \__ \  __/ |  | |  | |  __/ || (_| | | 
     | .__/ \__,_|\__|\___/|___/\___|_|  |_|  |_|\___|\__\__,_| | 
     |_|                                                       \_\

*********************************************************************************************/

	function putUserMeta($key,$value)
		{
		global $wpdb,$current_user;
		$user_id=$current_user->ID;
		if($user_id!=0)
			{
			$table_name = $wpdb->prefix . 'virtual_bible_users';
			$Results = $wpdb->get_results("SELECT user_value from $table_name WHERE user_id=$user_id AND user_key = '$key' LIMIT 1;", ARRAY_A);
			if(isset($Results[0]['user_value']))
				{
				$wpdb->update
					( 
					$table_name,
					array
						( 
						'user_value'	=>  $value
						),
					array
						(
						'user_key'		=>  $key
						)
					);
				}
			else
				{
				$wpdb->insert
					( 
					$table_name,
					array
						( 
						'user_id'		=>	$user_id,
						'user_key'		=>  $key,
						'user_value'	=>  $value
						)
					);
				}
			}
		}
	
	

		
/********************************************************************************************
                 _  _____           _      __
       __ _  ___| ||_   _|__   ___ | |___ / /
      / _` |/ _ \ __|| |/ _ \ / _ \| / __| | 
     | (_| |  __/ |_ | | (_) | (_) | \__ \ | 
      \__, |\___|\__||_|\___/ \___/|_|___/ | 
      |___/                               \_\

*********************************************************************************************/


	function getTools()
		{
		global $_vbm;
		$virtual_bible_interlinear_installed=$_vbm->is_module_installed('interlinear');
		$virtual_bible_holman_installed=$_vbm->is_module_installed('holman');
		$virtual_bible_eastons_installed=$_vbm->is_module_installed('eastons');
			
		$tools=$this->getTemplate('tools');
		$tools.= "
			<style>
			";
		if($virtual_bible_interlinear_installed!='installed')
			{
			$tools.= "#virtual-bible-splash-tools-interlinear {display:none;}\n";
			}
		if($virtual_bible_holman_installed!='installed')
			{
			$tools.= "#virtual-bible-splash-tools-holman {display:none;}\n";
			}
		if($virtual_bible_eastons_installed!='installed')
			{
			$tools.= "#virtual-bible-splash-tools-eastons {display:none;}\n";
			}
		$tools.="</style>";
		return $tools;
		}


		
/********************************************************************************************
                 _   ____  _         _            __
       __ _  ___| |_/ ___|| |_ _   _| | ___  ___ / /
      / _` |/ _ \ __\___ \| __| | | | |/ _ \/ __| | 
     | (_| |  __/ |_ ___) | |_| |_| | |  __/\__ \ | 
      \__, |\___|\__|____/ \__|\__, |_|\___||___/ | 
      |___/                    |___/             \_\

*********************************************************************************************/

	function getStyles()
		{
		$virtual_bible_traditional_select=$this->getMeta('style_traditional');
		$virtual_bible_paragraph_select=$this->getMeta('style_paragraph');
		$virtual_bible_reader_select=$this->getMeta('style_reader');
		$style=$this->getUserMeta('style');
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
		$styles = '';
		if($virtual_bible_traditional_select)
			{
			$styles.= <<<EOD
				<div class="form-check">
					<label class="form-check-label">
						<input type="radio" name="layout" value="trad" class="form-check-input" {$trad_checked} 
							onclick="
								$('#ref-results').removeClass('paragraph');
								$('#ref-results').removeClass('reading');
								$('#ref-results').addClass('traditional');
								saveUserData('style','traditional');
								"> Traditional
					</label>
				</div>
			EOD;
			}
		if($virtual_bible_paragraph_select)
			{
			$styles .= <<<EOD
				<div class="form-check">
					<label class="form-check-label">
						<input type="radio" name="layout" value="par" class="form-check-input" {$par_checked}
						onclick="
							$('#ref-results').removeClass('reading');
							$('#ref-results').removeClass('traditional');
							$('#ref-results').addClass('paragraph');
							saveUserData('style','paragraph');
							"> Paragraph
					</label>
				</div>
			EOD;
			}
		if($virtual_bible_reader_select)
			{
			$styles.= <<<EOD
				<div class="form-check">
					<label class="form-check-label">
						<input type="radio" name="layout" value="read" class="form-check-input" {$read_checked}
						onclick="
							$('#ref-results').removeClass('paragraph');
							$('#ref-results').removeClass('traditional');
							$('#ref-results').addClass('reading');
							saveUserData('style','reading');
							"> Reading 
					</label>
				</div>
			EOD;
			}
		return $styles;
		}


		
/********************************************************************************************
                 _   ____              _    _     _     _    __
       __ _  ___| |_| __ )  ___   ___ | | _| |   (_)___| |_ / /
      / _` |/ _ \ __|  _ \ / _ \ / _ \| |/ / |   | / __| __| | 
     | (_| |  __/ |_| |_) | (_) | (_) |   <| |___| \__ \ |_| | 
      \__, |\___|\__|____/ \___/ \___/|_|\_\_____|_|___/\__| | 
      |___/                                                 \_\

*********************************************************************************************/

	function getBookList($column_width=3)
		{
		global $wpdb,$page_url;
		$table_name = $wpdb->prefix . 'virtual_bible_books';
		$Books = $wpdb->get_results("SELECT * from $table_name;", ARRAY_A);
		$book_list='';$r=0;
		$cols=12/$column_width;
		$rows_count=ceil(66/$cols);
		$_book_list='';
		$book_list.="<div class=\"col-sm-$column_width\" style=\"white-space:nowrap\" title=\"$rows_count\">";
		foreach($Books as $n=>$Book)
			{
			if($r==$rows_count)
				{
				$book_list.="</div><div class=\"col-md-$column_width\" style=\"white-space:nowrap\">";
				$r=0;
				}
			if(isset($Book['book']))
				{
				$book=$Book['book'];
				$abbr=sanitize_title($book);
				$_book_list.="<div class=\"col-xs col-md-$column_width\"><a href=\"$page_url?keyword=$abbr+1\">$book</a></div>\n";
				$book_list.="<a href=\"$page_url?keyword=$abbr+1\">$book</a><br>";
				}
			$r++;
			}
		$book_list.="</div>";
		return $_book_list;
		}


		
/********************************************************************************************
                 _    ___        _   _ _             __
       __ _  ___| |_ / _ \ _   _| |_| (_)_ __   ___ / /
      / _` |/ _ \ __| | | | | | | __| | | '_ \ / _ \ | 
     | (_| |  __/ |_| |_| | |_| | |_| | | | | |  __/ | 
      \__, |\___|\__|\___/ \__,_|\__|_|_|_| |_|\___| | 
      |___/                                         \_\

*********************************************************************************************/

	function getOutline($bid,$chapter)
		{
		global $wpdb,$_vbm;
		$status=$_vbm->is_module_installed('outline');
		$Outline=[];
		if($status=='installed')
			{
			$bookname=$this->getBookTitleFromId($bid);
			$where="$bookname $chapter";
			$Response=$this->dbFetch('virtual_bible_outline',array('chapter'=>$where));
			foreach($Response as $Item)
				{
				$verse=$Item['verse'];
				$text=str_replace("\'","'",$Item['text']);
				$Text=explode(';',$text);
				$last=count($Text)-1;
				$new_text='';
				foreach($Text as $l=>$line)
					{
					$line="<span class=\"avoid-break\">$line</span>";
					$new_text.=$line;		
					if($l<$last){$new_text.=';';}			
					}
				$Outline[$verse]=$new_text;
				}
			}
		return $Outline;
		}


		
/********************************************************************************************
                 _  _____                    _       _        __
       __ _  ___| ||_   _|__ _ __ ___  _ __ | | __ _| |_ ___ / /
      / _` |/ _ \ __|| |/ _ \ '_ ` _ \| '_ \| |/ _` | __/ _ \ | 
     | (_| |  __/ |_ | |  __/ | | | | | |_) | | (_| | ||  __/ | 
      \__, |\___|\__||_|\___|_| |_| |_| .__/|_|\__,_|\__\___| | 
      |___/                           |_|                    \_\

*********************************************************************************************/



	function getTemplate($template_name)
		{
		if(substr($template_name,-4)!='.tpl')
			{
			$template_name.='.tpl';
			}
		$plugin_path=str_replace('includes/','',plugin_dir_path(__FILE__));
		return file_get_contents($plugin_path.'templates/'.$template_name);	
		}



		
/********************************************************************************************
      _           _ _     _ _____                     __
     | |__  _   _(_) | __| |  ___|__  _ __ _ __ ___  / /
     | '_ \| | | | | |/ _` | |_ / _ \| '__| '_ ` _ \| | 
     | |_) | |_| | | | (_| |  _| (_) | |  | | | | | | | 
     |_.__/ \__,_|_|_|\__,_|_|  \___/|_|  |_| |_| |_| | 
                                                     \_\

*********************************************************************************************/

	function buildForm($reference)
		{
		global $page_url;
		if($reference)
			{
			$reference=str_replace('`','&quot;',$reference);
			$reference=str_replace('\\','',$reference);
			}
		$styles=$this->getStyles();
		$form=$this->getTemplate('form');
		$form=str_replace('{$page_url}',$page_url,$form);
		$form=str_replace('{$reference}',$reference,$form);
		$form=str_replace('{$styles}',$styles,$form);
		return $form;
		}




	function buildBookListModal()
		{
		$book_list=$this->getBookList();
		$book_list_modal = <<<EOD
		<div id="vb-book-list-modal" class="modal fade-scale" style="display:none;padding-top:50px;z-index:1000;position:fixed;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,0.4);font-family: 'Montserrat';">
			<div class="modal-content" style="border-radius:10px;border:1px solid:#555;width:600px;margin:auto;background-color:#fff;position:relative;padding:20px;outline:0;;font-family: 'Montserrat';box-shadow:1px 1px 30px rgba(0,0,0,0.5)">
				<span onclick="document.getElementById('vb-book-list-modal').style.display='none'" style="cursor:pointer;position:absolute;top:0;right:10px;font-size:19px">&times;</span>
				<h4 style="clear:both;font-family: 'Poppins', sans-serif;margin-top:0;margin-bottom:10px;font-weight:bold">Books of the Bible</h4>
				<div class="row" style="font-size:13px;font-family: 'Montserrat';'">
					{$book_list}
				</div class="row">
			</div>
		</div>
		EOD;
		return $book_list_modal;
		}



		
/********************************************************************************************
      _           _ _     _  ____ _                 _            _     _     _   __  __           _       _  __
     | |__  _   _(_) | __| |/ ___| |__   __ _ _ __ | |_ ___ _ __| |   (_)___| |_|  \/  | ___   __| | __ _| |/ /
     | '_ \| | | | | |/ _` | |   | '_ \ / _` | '_ \| __/ _ \ '__| |   | / __| __| |\/| |/ _ \ / _` |/ _` | | | 
     | |_) | |_| | | | (_| | |___| | | | (_| | |_) | ||  __/ |  | |___| \__ \ |_| |  | | (_) | (_| | (_| | | | 
     |_.__/ \__,_|_|_|\__,_|\____|_| |_|\__,_| .__/ \__\___|_|  |_____|_|___/\__|_|  |_|\___/ \__,_|\__,_|_| | 
                                             |_|                                                            \_\
                                                                                    
*********************************************************************************************/


	function buildChapterListModal($bookname)
		{
		global $page_url;
		$chapnum=$this->getChaptersInBook($bookname);
		$chapters='';
		$chapname='Chapter';
		if($bookname=='Psalms'){$chapname='Psalm';}
		for($c=1;$c<=$chapnum;$c++)
			{
			$chapters.="<a href=\"{$page_url}?keyword=$bookname+$c\" style=\"float:left;margin:2px 10px;width:80px\">$chapname $c</a> ";
			}
		$chapter_list_modal = <<<EOD
		<div id="chapters-modal" class="modal" style="display:none;padding-top:50px;z-index:1000;position:fixed;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,0.4);font-family: 'Montserrat';">
			<div class="modal-content" style="border-radius:10px;border:1px solid:#555;width:600px;margin:auto;background-color:#fff;position:relative;padding:20px;outline:0;;font-family: 'Montserrat';box-shadow:1px 1px 30px rgba(0,0,0,0.5)">
				<span onclick="document.getElementById('chapters-modal').style.display='none'" style="cursor:pointer;position:absolute;top:0;right:10px;font-size:19px">&times;</span>
				<h4 style="clear:both;font-family: 'Poppins', sans-serif;margin-top:0;margin-bottom:10px;font-weight:bold">Chapters in $bookname</h4>
				<div class="row" style="font-size:13px;font-family: 'Montserrat';'">
					{$chapters}
				</div class="row">
			</div>
		</div>
		EOD;
		return $chapter_list_modal;
		}



		
/********************************************************************************************
      _           _ _     _ ____  _             _   _   _      _        __
     | |__  _   _(_) | __| / ___|| |_ __ _ _ __| |_| | | | ___| |_ __  / /
     | '_ \| | | | | |/ _` \___ \| __/ _` | '__| __| |_| |/ _ \ | '_ \| | 
     | |_) | |_| | | | (_| |___) | || (_| | |  | |_|  _  |  __/ | |_) | | 
     |_.__/ \__,_|_|_|\__,_|____/ \__\__,_|_|   \__|_| |_|\___|_| .__/| | 
                                                                |_|    \_\
                                                                                                
*********************************************************************************************/



	function buildStartHelp()
		{
		global $page_url,$plugin_url;
		$tools=$this->getTools();
		$book_list=$this->getBookList();
		$start_help = <<<EOD
		<section class="study-bible-tab-wrap">
			<div class="container">
				<ul class="nav nav-tabs" style="margin-top:40px;font-family: 'Montserrat';">
					<li class="active nav-item">
						<a id="how-to-search-tab" class="nav-link active" data-toggle="tab" href="#how-to-search" style="text-decoration:none">
							<h4 style="margin:0;font-family: 'Poppins', sans-serif">
								<i class="fas fa-search fa-flip-horizontal"></i> 
								<span class="help-title">How to Search</span>
							</h4>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#tools" style="text-decoration:none">
							<h4 style="margin:0;font-family: 'Poppins', sans-serif">
								<i class="fas fa-wrench"></i>
								<span class="help-title">Tools</span>
							</h4>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#styles" style="text-decoration:none">
							<h4 style="margin:0;font-family: 'Poppins', sans-serif">
								<i class="fa-solid fa-swatchbook"></i>
								<span class="help-title">Passage Styles</span>
							</h4>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#books" style="text-decoration:none">
							<h4 style="margin:0;font-family: 'Poppins', sans-serif">
								<i class="fas fa-book"></i>
								<span class="help-title">Books</span>
							</h4>
						</a>
					</li class="nav-item">
				</ul>
				<div class="tab-content" role="tablist"> 
					<div id="how-to-search" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="how-to-search-tab">	
						<div id="how-to-search-collapse" data-parent="#tab-content" role="tabpanel" 
							aria-labelledby="how-to-search-heading">
							<div class="card-body new-card-body">
								<p class="fontcolor-medium" style="margin-top:0;margin-left:10px;font-size:13px">
									(Note: The example links have all parameter set to show the correct results.)
								</p>
								<h5 style="margin-bottom:5px;margin-top:15px;color:#000">The Basic Passage Search</h5>
								<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
									Find exact chapters and/or verses by typing the book, chapter, and/or verse in the search box.<br>
									<em>Examples: <a href="{$page_url}?keyword=John+3:16">John 3:16</a> <small>(for a single verse)</small> or <a href="{$page_url}?keyword=John+3">John 3</a> <small>(for the entire chapter)</small> or <a href="{$page_url}?keyword=John+3:14-18">John 3:14-18</a> <small>(for a select passage within a chapter)</small></em><br />
									Many, commonly-used abbreviations can be recognized, so <a href="{$page_url}?keyword=Jn+3:16">Jn 3:16</a> will be recongized as John 3:16.<br />
									<small>Note: The verse list will be included automatically when you select just the chapter, so <em><a href="{$page_url}?keyword=John+3">John 3</a></em> will become <em><a href-="{$page_url}?keyword=John+3:1-36">John 3:1-36</a></em></small></p>
								<h5 style="margin-bottom:5px;margin-top:15px;color:#000">The Basic Keyword Search</h4>
								<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
									Find verses containing a specific word.<br>
									<em>Example: <a href="{$page_url}?keyword=love">love</a></em></p>
								<h5 style="margin-bottom:5px;margin-top:15px;color:#000">More Advanced Keyword Search</h4>
								<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
									Find verses containing two or more words you want by typing them in, separated by a space.<br>
									<em>Example: <a href="{$page_url}?keyword=christ+mercy">Christ mercy</a></em></p>
								<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
									Find verses containing partial words by using the wildcard * (an asterisk).<br>
									<em>Example: <a href="{$page_url}?keyword=love*">love*</a> <small>(returns love, loves, loved, lovely, etc.)</small></em></p>
								<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
									Find verses containing specific sets of words<br>
									<em>Example: <a href="{$page_url}?keyword=&quot;Jesus answered&quot;">&quot;Jesus answered&quot;</a> <small><br>(PLEASE NOTE: Due to the complexity of the database table containing the scriptures, the plugin currently can only hand a two-word combination. I hope to improve this in later updates.)</small></em></p>
								<h5 style="margin-bottom:5px;margin-top:15px;color:#000">Using the Scope Option</h4>
								<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
									Use the scope drop-down menu (default is set to the Whole Bible) to limit your word search to a specific section of the Bible<br>
									<em>Example: <a href="{$page_url}?keyword=love&scope=2">love</a> <small>(returns only instances of the word &ldquo;love&rdquo; in the New Testament)</small></em><br>
									<small><em>Scope will be ignored for reference searches (i.e. John 3:16)</em></small>
								</p>
							</div>
						</div>
					</div>		
					<div id="tools" class="card tab-pane fade in show" role="tabpanel" aria-labelledby="tools-tab">	
						<div id="tools-collapse" data-parent="#tab-content" role="tabpanel" aria-labelledby="tools-heading">
							<div class="card-body new-card-body">
							{$tools}
							</div>	
						</div>
					</div>
					<div id="styles" class="card tab-pane fade in show" role="tabpanel" aria-labelledby="styles-tab">	
						<div id="styles-collapse" data-parent="#tab-content" role="tabpanel" aria-labelledby="styles-heading">
							<div class="card-body new-card-body">
								<div class="row fontcolor-medium" style="display:block;padding:15px">
									There are three different passage styles available...
									<div style="clear:both"></div>
									<div class="col-md-4" style="float:left">
										<h5 style="margin:10px 0">Traditional</h5>
										<div style="line-height:1.3;font-size:13px;margin-bottom:20px;height:100px">
										The traditional passage style is the most common. This has been the standard layout for Bibles for centuries. The verses are separate and begin with the verse number, and the paragraphs are noted with the ¶ character.
										</div>
										<img src="{$plugin_url}/images/style-traditional.jpg" style="box-shadow:1px 1px 15px rgba(0,0,0,0.2);"/>
									</div>
									<div class="col-md-4" style="float:left">
										<h5 style="margin:10px 0">Paragraph</h5>
										<div style="line-height:1.3;font-size:13px;margin-bottom:20px;height:100px">
										The paragraph passage style is used in many newer translations. The text flows as paragraphs, the verses are marked by a small superscript and the chapter number appear as a large colored number.
										</div>
										<img src="{$plugin_url}/images/style-paragraph.jpg"  style="box-shadow:1px 1px 15px rgba(0,0,0,0.2);"/>
									</div>
									<div class="col-md-4" style="float:left">
										<h5 style="margin:10px 0">Reading</h5>
										<div style="line-height:1.3;font-size:13px;margin-bottom:20px;height:100px">
										The Reader's Bible is a very new creation. The text is laid out like a book, but with no verse markings. The book and chapter headings are displayed, but in the style of a typical book.
										</div>
										<img src="{$plugin_url}/images/style-reading.jpg"  style="box-shadow:1px 1px 15px rgba(0,0,0,0.2);"/>
									</div>
									<div style="clear:both;line-height:1.3;font-size:15px;padding:20px 20px">
									If you are logged in the page will remember your choice as you study, and even when you return. If you are not logged in, it will only remember your choice for the current session.
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="books" class="card tab-pane fade in show" role="tabpanel" aria-labelledby="books-tab">	
						<div id="books-collapse" data-parent="#tab-content" role="tabpanel" aria-labelledby="books-heading">
							<div class="card-body new-card-body">
								<div class="row" style="padding:10px">
									{$book_list}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		EOD;
		return $start_help;
		}

		

		
/********************************************************************************************
      _           _ _     _ _____           _      ____            _             _    __
     | |__  _   _(_) | __| |_   _|__   ___ | |___ / ___|___  _ __ | |_ ___ _ __ | |_ / /
     | '_ \| | | | | |/ _` | | |/ _ \ / _ \| / __| |   / _ \| '_ \| __/ _ \ '_ \| __| | 
     | |_) | |_| | | | (_| | | | (_) | (_) | \__ \ |__| (_) | | | | ||  __/ | | | |_| | 
     |_.__/ \__,_|_|_|\__,_| |_|\___/ \___/|_|___/\____\___/|_| |_|\__\___|_| |_|\__| | 
                                                                                     \_\
                       
*********************************************************************************************/

	
	function buildToolsContent($bid,$part='intro')
		{
		global $wpdb;
		$bookname=$this->getBookTitleFromId($bid);
		$BookData=$this->getBookIdFromVagueTitle($bookname);
		list($abbr)=explode(', ',$BookData['abbr']);
		$Intro=$this->dbFetch('virtual_bible_gty_intro_outline',array('book'=>$bid));
		$text=$Intro[0]['text'];
		$text=str_replace('&ndash;','-',$text);
		$text=str_replace('<strong></strong>','',$text);
		$text=str_replace('<p><strong>','<h5>',$text);
		$text=str_replace('</strong></p>','</h5>',$text);	
		
		$plugin_path=str_replace('includes/','',plugin_dir_path(__FILE__));
		$page_name=$this->getMeta('page_name');
		$page_slug=sanitize_title($page_name);
		$page_url=site_url().'/'.$page_slug.'/';
		$text=preg_replace('/\(([0-9]+):/',"($abbr ".'$1:',$text);
		$text=str_replace('(chap.',"($abbr",$text);
		$regex=file_get_contents($plugin_path.'templates/ref-regex.tpl');
		
		$text=preg_replace($regex,'<a href="'.$page_url.'?keyword=$1+$2">$1 $2</a>',$text);
	
		list($intro,$outline)=$this->explode2list('<h5>Outline</h5>',$text);
		if($part=='outline')
			{
			return '<h4>Outline of '.$bookname.'<small><br>by John MacArthur</small></h4>'.$outline.'<hr /><p><small class="copyright">Outline of '.$bookname.', Copyright &copy; 2007, <a href="https://www.gty.org" target="_blank">Grace To You.</a> All rights reserved. Used by permission.</small></p>';
			}
		else
			{
			return '<h4>Introduction to '.$bookname.'<small><br>by John MacArthur</small></h4>'.$intro.'<p><small class="copyright">Introduction to '.$bookname.', Copyright &copy; 2007, <a href="https://www.gty.org" target="_blank">Grace To You.</a> All rights reserved. Used by permission.</small></p>';
			}
		}
	


		
/********************************************************************************************
      _           _ _     _ ____  _             _   ____                   __
     | |__  _   _(_) | __| / ___|| |_ __ _ _ __| |_|  _ \ __ _  __ _  ___ / /
     | '_ \| | | | | |/ _` \___ \| __/ _` | '__| __| |_) / _` |/ _` |/ _ \ | 
     | |_) | |_| | | | (_| |___) | || (_| | |  | |_|  __/ (_| | (_| |  __/ | 
     |_.__/ \__,_|_|_|\__,_|____/ \__\__,_|_|   \__|_|   \__,_|\__, |\___| | 
                                                               |___/      \_\
                                                                                           
*********************************************************************************************/

	function buildStartPage()
		{
		global $reference,$current_user;
		$form=$this->buildForm($reference);
		$tools=$this->getTools();
		$book_list=$this->getBookList();
		$book_list_modal=$this->buildBookListModal();
		$start_help=$this->buildStartHelp();
		$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));
		$user_id=$current_user->ID;
		$noonce_watcher_url=wp_nonce_url($plugin_url.'ajax/worker.php','vb_watcher');
	
		$virtual_bible_page = <<<EOD
		<div class="row study-bible-cover" style="
			background-color:#fff;
			height:100vh;
			font-family: 'Montserrat', sans-serif;
			color:#fafafa;
			font-size:70px;
			text-align:center;
			font-weight:700;
			display:block;
			width:100%;
			padding-top:100px;line-height:1;">
			Loading
		</div>
		<div id="study-bible" class="start-page">
			<div class="study-bible-form" style="display:none;transition:0.3s;filter: blur(5px)">
				{$form}
			</div>
			<div class="start-help tools" style="display:none">
				{$start_help}
			</div>
		</div>
		{$book_list_modal}
		<script>	
		var vb_strongs=0;
		var strongs_num='';
		var _vb_user_id={$user_id};
		window.addEventListener
			(
			'load',
			function()
				{
				$('div.study-bible-form').fadeIn(600);
				$('div.study-bible-form').css('filter','blur(0)');
				$('div.study-bible-cover').fadeOut(300);
				$('div.study-bible-debug').fadeIn(1000);
				setTimeout
					(
					function()
						{
						$('div.start-help').fadeIn(750);
						},500
					);	
				$(".collapse.show").each
					(
					function()
						{
						$(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
						}
					);
				$(".collapse")
					.on
						(
						'show.bs.collapse',function()
							{
							$(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
							}
						)
					.on
						(
						'hide.bs.collaspe',function()
							{
							$(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
							}
						);
				}
			);

			function saveUserData(key,value)
				{
				var watcher_nonce_url = '{$noonce_watcher_url}';
				$.get
					(
					watcher_nonce_url, 
					{function:'user_data_set',user:_vb_user_id,user_key:key,user_value:value},
					function(data) 
						{
						if(data=='installed')
							{
							$("#settings-submit").prop('disabled',false);
							}
						}
					);
				}
		</script>
		EOD;
		return $virtual_bible_page;
		}


		
/********************************************************************************************
      _           _ _     _ ____                 _ _       ____                   __
     | |__  _   _(_) | __| |  _ \ ___  ___ _   _| | |_ ___|  _ \ __ _  __ _  ___ / /
     | '_ \| | | | | |/ _` | |_) / _ \/ __| | | | | __/ __| |_) / _` |/ _` |/ _ \ | 
     | |_) | |_| | | | (_| |  _ <  __/\__ \ |_| | | |_\__ \  __/ (_| | (_| |  __/ | 
     |_.__/ \__,_|_|_|\__,_|_| \_\___||___/\__,_|_|\__|___/_|   \__,_|\__, |\___| | 
                                                                      |___/      \_\
$virtual_bible_page = $_vb->buildResultsPage($keyword,$scope,$version,$layout);
*********************************************************************************************/

	function buildResultsPage($keyword,$scope,$version,$layout)
		{
		global $reference,$plugin_url,$_vbm;
		$virtual_bible_eastons_installed=$_vbm->is_module_installed('eastons');
		$isRef=$this->isRef($keyword);
		if($isRef['type']=='passage' or $isRef['type']=='verse')
			{
			$virtual_bible_page	= $this->referenceResults($keyword);
			}
		else		
			{
			$virtual_bible_page=$this->wordsearchResults($keyword,$scope);
			}
	
		return $virtual_bible_page;
		}





		
/********************************************************************************************
      _     ____       __  __
     (_)___|  _ \ ___ / _|/ /
     | / __| |_) / _ \ |_| | 
     | \__ \  _ <  __/  _| | 
     |_|___/_| \_\___|_| | | 
                          \_\

$Array = isRef(string $keyword) : $Array['type'] as the type ('passage', 'verse', 'chapter' or 'invalid')
 *********************************************************************************************/
	function isRef($keyword)
		{
		$Return=[];
		$Return['keyword']=$keyword;
		$Ref=$this->getRefByKeyword($keyword);
		$Return['debug']['Ref']=$Ref;
		if(isset($Ref['bid']))
			{
			$bid=$Ref['bid'];
			}
		if(isset($Ref['Verses'][1]))
			{
			$Return['type']='passage';
			}
		elseif(isset($Ref['Verses'][0]))
			{
			$Return['type']='verse';
			}
		elseif(isset($Ref['chapter']))
			{
			$Return['type']='chapter';
			if(isset($Ref['bookname']))
				{
				$Return['bookname']=$Ref['bookname'];
				$Return['chapter']=$Ref['chapter'];
				$chlength=$this->getVersesInChapter($Ref['bookname'],$Ref['chapter']);
				$Return['chapter-length']=$chlength;
				}
			elseif(!isset($Return['type']))
				{
				$Return['type']='invalid';
				}
			}
		elseif(isset($bid))
			{
			$Return['type']='book';
			}
		elseif(!isset($Return['type']))
			{
			$Return['type']='invalid';
			}
		return $Return;
		}


/********************************************************************************************
                 _   ____       __ ____        _  __                                _  __
       __ _  ___| |_|  _ \ ___ / _| __ ) _   _| |/ /___ _   ___      _____  _ __ __| |/ /
      / _` |/ _ \ __| |_) / _ \ |_|  _ \| | | | ' // _ \ | | \ \ /\ / / _ \| '__/ _` | | 
     | (_| |  __/ |_|  _ <  __/  _| |_) | |_| | . \  __/ |_| |\ V  V / (_) | | | (_| | | 
      \__, |\___|\__|_| \_\___|_| |____/ \__, |_|\_\___|\__, | \_/\_/ \___/|_|  \__,_| | 
      |___/                              |___/          |___/                         \_\

$Array = getRefByKeyword(string $k) : return Array of various properties reference ($k) refers to
 *********************************************************************************************/

	function getRefByKeyword($k)
		{
		$vid='';$_V=[];$_V2=[];$vid2='';$bid2=0;$cid2=0;
		$_debug=[];
		$Return['debug']['original key']=$k;

		# clean up reference string
		$_k=urldecode($k);
		$_k=str_ireplace('Song of Solomon','Song',$_k);	//This is the only book with spaces other than after a number
		$_k=str_ireplace('song-of-solomon','Song',$_k);
		$_k = preg_replace("/(\s){2,}/", ' ', $_k);		//remove extra spaces
		$_k=str_replace('.','',$_k);					//remove periods
		$_k=str_replace('1st ', '1 ', $_k);				//make ordinals regular numbers
		$_k=str_replace('2nd ', '2 ', $_k);
		$_k=str_replace('3rd ', '3 ', $_k);		
		$_k=preg_replace('/^i /i', "1 ", $_k); 			//convert Roman numerals (case insensitive)
		$_k=preg_replace('/^ii /i', "2 ", $_k);
		$_k=preg_replace('/^iii /i', "3 ", $_k);

		if(preg_match('/^[1-3][a-zA-Z]/',$_k))			//catch messed up beginning numbers, like 1john
			{
			$_k=preg_replace('/^1/', '1 ',$_k);
			$_k=preg_replace('/^2/', '2 ',$_k);
			$_k=preg_replace('/^3/', '3 ',$_k);
			}
		if(preg_match('/^[1-3]-/',$_k))					//catch messed up beginning numbers, like 1-john
			{
			$_k=preg_replace('/^1-/', '1 ',$_k);
			$_k=preg_replace('/^2-/', '2 ',$_k);
			$_k=preg_replace('/^3-/', '3 ',$_k);
			}
		$_k=ltrim($_k);$_k2='';
		$Return['debug']['cleaned key']=$_k;
		if(strstr($_k,'-'))								//This means it's a series of verses.
			{
			$Keys=explode('-',$_k);
			if(strstr($Keys[1],':'))					//This means it's multiple chapter (Somone could have typed john 1:1-2:3)
				{
				$_k=$Keys[0].'-';
				$_k2=$Keys[1];
				}
			}
		if(strstr($_k,' '))								//must catch even silly errors
			{
			$Ref_keys=explode(' ',$_k);                  //explode by spaces
			if(!$Ref_keys[0])                            //if first element is empty, remove
				{
				$toss=array_shift($Ref_keys);
				}
			if(preg_match('/[1-3]/',$Ref_keys[0]))		//if first element is number...
				{
				$num=$Ref_keys[0];						//grab it
				$toss=array_shift($Ref_keys);			//remove it and shift the array
				$Ref_keys[0]="$num ".$Ref_keys[0];		//combine it with the new first key
				}
			$Return['debug']['Ref_keys']=$Ref_keys;
			$BookData=$this->getBookIdFromVagueTitle($Ref_keys[0]);
			$Return['debug']['BookData']=$BookData;
			if(isset($BookData['book']))
				{
				$Return['bookname']=$BookData['book'];
				$bookname=$BookData['book'];
				$Return['bid']=$BookData['id'];
				$bid=$BookData['id'];
				}
			else
				{
				$bid=0;
				}
			$toss=array_shift($Ref_keys);				//Remove the book from $Ref_keys
			$ref_key=implode($Ref_keys);				//combine to make a new refence minus the book
			$refElements=explode(':',$ref_key);			//separate the chapter from the verse(s)
			$Return['debug']['refElements']=$refElements;
			$cid=(int)$refElements[0];
			$Return['debug']['chapter']=$cid;

			if($cid)
				{
				$Return['chapter']=$cid;
				}
			}
		else
			{
			$Ref_keys=[];$bid=0;$cid=0;
			}
		$_debug['_k2']=$_k2;
		if(strstr($_k2,' '))							//$k2 is the second reference of something like John 1:1-2:5
			{
			$Ref_keys=explode(' ',$_k2);
			if(!$Ref_keys[0])
				{
				$toss=array_shift($Ref_keys);
				}
			if(preg_match('/[1-3]/',$Ref_keys[0]))
				{
				$num=$Ref_keys[0];
				$toss=array_shift($Ref_keys);
				$Ref_keys[0]="$num ".$Ref_keys[0];
				}
			$Return['debug']['Ref_keys2']=$Ref_keys;
			$BookData=getBookIdFromVagueTitle($Ref_keys[0]);
			$Return['debug']['BookData2']=$BookData;
			if(isset($BookData['book']))
				{
				$Return['bookname2']=$BookData['book'];
				$Return['bid']=$BookData['id'];
				$bid=$BookData['id'];
				}			
			$toss=array_shift($Ref_keys);
			$ref_key2=implode($Ref_keys);
			$refElements2=explode(':',$ref_key2);
			$Return['debug']['refElements2']=$refElements2;
			$cid=$refElements2[0];					//technically the chapter number, not id
			$Return['chapter2']=$cid;
			}	
		if(isset($refElements[1]))					//there are specific verses (if it's not set, then it's something like Genesis 1)
			{
			$verses=preg_replace('/[^0-9-,]+/','',$refElements[1]);	//remove anything that's not a number, a dash or a comma
			$Return['Tracking']['verses']=$verses;	//To be honest, I can't remember why I did this.
			if(strstr($verses,','))					//If there's a comma, then we want just the specific verses listed
				{
				$vList=explode(',',$verses);
				for($i=0;$i<count($vList);$i++)
					{
					if(strstr($vList[$i],'-'))
						{
						list($start,$finish)=explode('-', $vList[$i]);
						for($i2=$start;$i2<=$finish;$i2++)
							{
							$_V[]=$i2;
							}
						}
					else
						{
						$_V[]=$vList[$i];
						}      
					}
				}
			elseif(strstr($verses, '-'))		//If it's a dash, then we want the verses between the two surrounding the dash.
				{
				$vList=explode('-',$verses);
				$Return['Tracking']['vList']=$vList;
				$Return['Tracking']['count-vList']=count($vList);
				if(count($vList)==2)			//Yes, we must still catch even silly errors.
					{
					list($start,$finish)=explode('-', $verses);
					if(!$finish and isset($BookData['book']))
						{
						$finish=getVersesInChapter($BookData['book'],$cid);
						$Return['Tracking']['finish']=$finish;
						}
					for($i=$start;$i<=$finish;$i++)
						{
						$_V[]=$i;
						}
					$Return['Tracking']['_V']=$_V;
					}
				}
			else
				{
				$_V[]=$verses;
				$vid=$verses;
				}
			}
		elseif(isset($BookData['book']))		//Remember, we caught requests for specific verses. This is if they want an entire chapter
			{
			$_debug['BookData']=$BookData;
			$finish=$this->getVersesInChapter($BookData['book'],$cid);
			for($i=1;$i<=$finish;$i++)
				{
				$_V[]=$i;
				}
			$_V['ref']="1-$finish";
			$verses="1-$finish";
			}
		if(isset($refElements2[1]))			//This is the second reference from something like John 1:1-2:5
			{	
			$_debug['refElements2']=$refElements2;
			$verses2=preg_replace('/[^0-9-,]+/','',$refElements[1]);
			if(strstr($verses,','))
				{
				$vList=explode(',',$verses2);
				for($i=0;$i<count($vList);$i++)
					{
					if(strstr($vList[$i],'-'))
						{
						list($start,$finish)=explode('-', $vList[$i]);
						for($i2=$start;$i2<=$finish;$i2++)
							{
							$_V2[]=$i2;
							}
						}
					else
						{
						$_V2[]=$vList[$i];
						}      
					}
				}
			elseif(strstr($verses2, '-'))
				{
				$vList=explode('-',$verses2);
				if(count($vList)==2)
					{
					list($start,$finish)=explode('-', $verses);
					for($i=$start;$i<=$finish;$i++)
						{
						$_V2[]=$i;
						}
					}
				}
			else
				{
				$_V2[]=$verses2;
				$vid2=$verses2;
				}
			}
		else
			{
			$_V2=[];$verses2='';
			}
		if($vid)
			{
			$Rid=$this->getVerseIDByRef($bid,$cid,$vid);
			$Return['debug']['getVerseIDByRef']=$Rid;
			if(isset($Rid['text'])){$Return['rid']=$Rid['text'];}
			}
		if($vid2)
			{
			$Rid2=$this->getVerseIDByRef($bid2,$cid2,$vid2);
			$Return['debug']['getVerseIDByRef2']=$Rid2;
			if(isset($Rid2['text'])){$Return['rid2']=$Rid2['text'];}
			}
		$Return['Verses']=$_V;
		$Return['Verses2']=$_V2;
		if(isset($verses)){$Return['Verses']['ref']=$verses;}
		$Return['Verses2']['ref']=$verses2;		
		if(isset($bookname)){$Return['clean-ref']=$bookname.' '.$cid.':'.$verses;}
		return $Return;
		}



/********************************************************************************************

            _   ____              _    ___    _ _____                 __     __                    _____ _ _   _       __
  __ _  ___| |_| __ )  ___   ___ | | _|_ _|__| |  ___| __ ___  _ __ __\ \   / /_ _  __ _ _   _  __|_   _(_) |_| | ___ / /
 / _` |/ _ \ __|  _ \ / _ \ / _ \| |/ /| |/ _` | |_ | '__/ _ \| '_ ` _ \ \ / / _` |/ _` | | | |/ _ \| | | | __| |/ _ \ | 
| (_| |  __/ |_| |_) | (_) | (_) |   < | | (_| |  _|| | | (_) | | | | | \ V / (_| | (_| | |_| |  __/| | | | |_| |  __/ | 
 \__, |\___|\__|____/ \___/ \___/|_|\_\___\__,_|_|  |_|  \___/|_| |_| |_|\_/ \__,_|\__, |\__,_|\___||_| |_|\__|_|\___| | 
 |___/                                                                             |___/                              \_\

$Array = getBookIdFromVagueTitle($title) : return book data (which includes id)
 *********************************************************************************************/
		    
	function getBookIdFromVagueTitle($title)
		{
		global $wpdb;
		trim($title);
		$title=str_replace("\n","",$title);
		$title=str_replace("\r","",$title);
		if(strtolower($title=='eph')){$title="Ephesians";}

		$Results=[];
		$Results['title']=$title;
		
		$table_name = $wpdb->prefix . 'virtual_bible_books';
		$Results['wpdb_get_results'] = $wpdb->get_results
			(
			$wpdb->prepare
				(
				"SELECT * FROM $table_name WHERE `book` LIKE '%s' OR `abbr` LIKE '%%%s%%' LIMIT 1;", array($title,$title)
				),
			ARRAY_A
			);
		if(isset($Results['wpdb_get_results'][0]))
			{
			return $Results['wpdb_get_results'][0];
			}
		else
			{
			return 0;
			}
		}



/********************************************************************************************
            _ __     __                      ___        ____ _                 _             __
  __ _  ___| |\ \   / /__ _ __ ___  ___  ___|_ _|_ __  / ___| |__   __ _ _ __ | |_ ___ _ __ / /
 / _` |/ _ \ __\ \ / / _ \ '__/ __|/ _ \/ __|| || '_ \| |   | '_ \ / _` | '_ \| __/ _ \ '__| | 
| (_| |  __/ |_ \ V /  __/ |  \__ \  __/\__ \| || | | | |___| | | | (_| | |_) | ||  __/ |  | | 
 \__, |\___|\__| \_/ \___|_|  |___/\___||___/___|_| |_|\____|_| |_|\__,_| .__/ \__\___|_|  | | 
 |___/                                                                  |_|                 \_\

 $integer = getVersesInChapter(string $book, integet $chapter, boolean $debug=0)
 *********************************************************************************************/
    
	function getVersesInChapter($book,$chapter,$debug=0)
		{
		global $wpdb;
		$bid=$this->getBookIdFromTitle($book);


		$Results=[];
		$Results['book']=$book;
		$Results['chapter']=$chapter;
		$table_name = $wpdb->prefix . 'virtual_bible_kjvs';
		$query=$wpdb->prepare
				(
				"SELECT id FROM $table_name WHERE `book` = '%s' AND `chapter` = '%s';",array($bid,$chapter)
				);
		$Results['query']=$query;
		$Results['wpdb_get_results'] = $wpdb->get_results($query,ARRAY_A);	
		$Results['num_rows']=$wpdb->num_rows;
		return $wpdb->num_rows;
		}



/********************************************************************************************
            _   ____              _    ___    _ _____                  _____ _ _   _       __
  __ _  ___| |_| __ )  ___   ___ | | _|_ _|__| |  ___| __ ___  _ __ __|_   _(_) |_| | ___ / /
 / _` |/ _ \ __|  _ \ / _ \ / _ \| |/ /| |/ _` | |_ | '__/ _ \| '_ ` _ \| | | | __| |/ _ \ | 
| (_| |  __/ |_| |_) | (_) | (_) |   < | | (_| |  _|| | | (_) | | | | | | | | | |_| |  __/ | 
 \__, |\___|\__|____/ \___/ \___/|_|\_\___\__,_|_|  |_|  \___/|_| |_| |_|_| |_|\__|_|\___| | 
 |___/                                                                                    \_\

 $integer = getBookIdFromTitle(string $booktitle) : 
 *********************************************************************************************/


		
	function getBookIdFromTitle($booktitle)
		{
		global $wpdb;
		if($booktitle=='Psalm'){$booktitle='Psalms';}

		$Results=[];
		$Results['booktitle']=$booktitle;
		$table_name = $wpdb->prefix . 'virtual_bible_books';
		$query=$wpdb->prepare
				(
				"SELECT id FROM $table_name WHERE `book` = '%s' LIMIT 1;", $booktitle
				);
		$Results['query']=$query;
		$Results['wpdb_get_results'] = $wpdb->get_results($query,ARRAY_A);	
		return $Results['wpdb_get_results'][0]['id'];		
		}



/********************************************************************************************
            _ __     __                 ___ ____  ____        ____       __  __
  __ _  ___| |\ \   / /__ _ __ ___  ___|_ _|  _ \| __ ) _   _|  _ \ ___ / _|/ /
 / _` |/ _ \ __\ \ / / _ \ '__/ __|/ _ \| || | | |  _ \| | | | |_) / _ \ |_| | 
| (_| |  __/ |_ \ V /  __/ |  \__ \  __/| || |_| | |_) | |_| |  _ <  __/  _| | 
 \__, |\___|\__| \_/ \___|_|  |___/\___|___|____/|____/ \__, |_| \_\___|_| | | 
 |___/                                                  |___/               \_\
 
$integer = getVerseIDByRef(integer $bid,integer $cn, integer $vn) :
 *********************************************************************************************/


	function getVerseIDByRef($bid,$cn,$vn) # book id, chapter number, verse number
		{
		global $wpdb;
		$table_name = $wpdb->prefix . 'virtual_bible_kjvs';
		$query=$wpdb->prepare
				(
				"SELECT id FROM $table_name WHERE `book` = '%s' and `chapter` = '%s' and `verse` = '%s' LIMIT 1;", array($bid,$cn,$vn)
				);
		$Results['query']=$query;
		$Results['wpdb_get_results'] = $wpdb->get_results($query,ARRAY_A);	

		return $Results['wpdb_get_results'][0]['id'];
		}



/********************************************************************************************
            _ __     __                       __
  __ _  ___| |\ \   / /__ _ __ ___  ___  ___ / /
 / _` |/ _ \ __\ \ / / _ \ '__/ __|/ _ \/ __| | 
| (_| |  __/ |_ \ V /  __/ |  \__ \  __/\__ \ | 
 \__, |\___|\__| \_/ \___|_|  |___/\___||___/ | 
 |___/                                       \_\
 
 $Array = getVerses(integer $bid,integer $chapter,array $Verses) : 
 *********************************************************************************************/
	

	function getVerses($bid,$chapter,$Verses)
		{
		global $wpdb,$_vbm;
		$virtual_bible_holman_installed=$_vbm->is_module_installed('holman');
		$V=[];$table='virtual_bible_kjvs';
		foreach($Verses as $v=>$verse)
			{
			if($v!='ref')
				{
				$V[$verse]=$this->dbFetch1($table,array('book'=>$bid,'chapter'=>$chapter,'verse'=>$verse));
				if($virtual_bible_holman_installed)
					{
					$xref=$this->dbFetch('virtual_bible_xref_holman',array('book'=>$bid,'chapter'=>$chapter,'verse'=>$verse),'ref');
					if($xref)
						{
						$V[$verse]['xref']=$xref;
						}
					}
				}
			}
		return $V;
		}

/********************************************************************************************
            _   ___       _            _ _                        __
  __ _  ___| |_|_ _|_ __ | |_ ___ _ __| (_)_ __   ___  __ _ _ __ / /
 / _` |/ _ \ __|| || '_ \| __/ _ \ '__| | | '_ \ / _ \/ _` | '__| | 
| (_| |  __/ |_ | || | | | ||  __/ |  | | | | | |  __/ (_| | |  | | 
 \__, |\___|\__|___|_| |_|\__\___|_|  |_|_|_| |_|\___|\__,_|_|  | | 
 |___/                                                           \_\
 
 $Array = getInterlinear(integer $bid, integer $chapter, array $Verses)
 *********************************************************************************************/   

	function getInterlinear($bid,$chapter,$Verses)
		{
		global $wpdb;
		$V=[];$table='virtual_bible_interlinear';
		foreach($Verses as $v=>$verse)
			{
			if($v!='ref')
				{
				$V[$verse]=$this->dbFetch1($table,array('book'=>$bid,'chapter'=>$chapter,'verse'=>$verse));
				}
			}
		return $V;
		}


/********************************************************************************************
            _   ____                 _                  ____ _                 _             __
  __ _  ___| |_|  _ \ _ __ _____   _(_) ___  _   _ ___ / ___| |__   __ _ _ __ | |_ ___ _ __ / /
 / _` |/ _ \ __| |_) | '__/ _ \ \ / / |/ _ \| | | / __| |   | '_ \ / _` | '_ \| __/ _ \ '__| | 
| (_| |  __/ |_|  __/| | |  __/\ V /| | (_) | |_| \__ \ |___| | | | (_| | |_) | ||  __/ |  | | 
 \__, |\___|\__|_|   |_|  \___| \_/ |_|\___/ \__,_|___/\____|_| |_|\__,_| .__/ \__\___|_|  | | 
 |___/                                                                  |_|                 \_\
 
 $Array = getPreviousChapter(string $booktitle, integer $chapter) : Return an array of the basic book data for the previous chapter
 *********************************************************************************************/       



	function getPreviousChapter($booktitle,$chapter)
		{
		global $wpdb;
		if($booktitle=='Psalm'){$booktitle='Psalms';}
		if($booktitle)
			{
			if($chapter>1)
				{
				$new_chapter=$chapter-1;
				$new_book=$booktitle;
				$newbookid=$this->getBookIdFromTitle($booktitle);
				$newbooktitle=$this->getBookTitleFromId($newbookid);
				}
			else   
				{
				$newbookid=$this->getBookIdFromTitle($booktitle)-1;
				if($newbookid==0){$newbookid=66;}
				$newbooktitle=$this->getBookTitleFromId($newbookid);
				$new_chapter=$this->getChaptersInBook($newbooktitle);
				}
			$Result['booktitle']=$newbooktitle;
			$Result['chapter']=$new_chapter;
			$Result['bid']=$newbookid;
			return $Result;
			}
		else
			{
			return '';
			}
		}


/********************************************************************************************
            _   _   _           _    ____ _                 _             __
  __ _  ___| |_| \ | | _____  _| |_ / ___| |__   __ _ _ __ | |_ ___ _ __ / /
 / _` |/ _ \ __|  \| |/ _ \ \/ / __| |   | '_ \ / _` | '_ \| __/ _ \ '__| | 
| (_| |  __/ |_| |\  |  __/>  <| |_| |___| | | | (_| | |_) | ||  __/ |  | | 
 \__, |\___|\__|_| \_|\___/_/\_\\__|\____|_| |_|\__,_| .__/ \__\___|_|  | | 
 |___/                                               |_|                 \_\
 
 $Array = getNextChapter(string $booktitle, integer $chapter)
 *********************************************************************************************/   
    
	function getNextChapter($booktitle,$chapter)
		{
		global $_mysql;
		if($booktitle=='Psalm'){$booktitle='Psalms';}
		if($booktitle)
			{
			$last_chapter=$this->getChaptersInBook($booktitle);
			if($chapter<$last_chapter)
				{
				$new_chapter=$chapter+1;
				$new_book=$booktitle;
				$newbookid=$this->getBookIdFromTitle($booktitle);
				$newbooktitle=$this->getBookTitleFromId($newbookid);
				}
			else   
				{
				$newbookid=$this->getBookIdFromTitle($booktitle)+1;
				if($newbookid==67){$newbookid=1;}
				$newbooktitle=$this->getBookTitleFromId($newbookid);
				$new_chapter=1;
				}
			$Result['booktitle']=$newbooktitle;
			$Result['chapter']=$new_chapter;
			$Result['bid']=$newbookid;
			return $Result;
			}
		else
			{
			return '';
			}
		}


/********************************************************************************************
            _   ____              _    _____ _ _   _      _____                    ___    _  __
  __ _  ___| |_| __ )  ___   ___ | | _|_   _(_) |_| | ___|  ___| __ ___  _ __ ___ |_ _|__| |/ /
 / _` |/ _ \ __|  _ \ / _ \ / _ \| |/ / | | | | __| |/ _ \ |_ | '__/ _ \| '_ ` _ \ | |/ _` | | 
| (_| |  __/ |_| |_) | (_) | (_) |   <  | | | | |_| |  __/  _|| | | (_) | | | | | || | (_| | | 
 \__, |\___|\__|____/ \___/ \___/|_|\_\ |_| |_|\__|_|\___|_|  |_|  \___/|_| |_| |_|___\__,_| | 
 |___/                                                                                      \_\
 
 $string = getBookTitleFromId(integer $bookid)
 *********************************************************************************************/  	
		
	function getBookTitleFromId($bookid)
		{
		global $_mysql;
		if($bookid)
			{
			$Row=$this->dbFetch1('virtual_bible_books',array('id'=>$bookid),'book');
			return $Row['book'];
			}
		else
			{
			return '';
			}      
		}



/********************************************************************************************
            _    ____ _                 _                ___       ____              _     __
  __ _  ___| |_ / ___| |__   __ _ _ __ | |_ ___ _ __ ___|_ _|_ __ | __ )  ___   ___ | | __/ /
 / _` |/ _ \ __| |   | '_ \ / _` | '_ \| __/ _ \ '__/ __|| || '_ \|  _ \ / _ \ / _ \| |/ / | 
| (_| |  __/ |_| |___| | | | (_| | |_) | ||  __/ |  \__ \| || | | | |_) | (_) | (_) |   <| | 
 \__, |\___|\__|\____|_| |_|\__,_| .__/ \__\___|_|  |___/___|_| |_|____/ \___/ \___/|_|\_\ | 
 |___/                           |_|                                                      \_\
 
 $integer = getChaptersInBook(string $book)
 *********************************************************************************************/  

    
	function getChaptersInBook($book)
		{
		if($book)
			{
			$Result=$this->dbFetch1('virtual_bible_books',array('book'=>$book));
			return $Result['chapters'];  
			}
		else
			{
			return '';
			}
		}


/********************************************************************************************
                         __     __                _____         _    __
 _ __   __ _ _ __ ___  __\ \   / /__ _ __ ___  __|_   _|____  _| |_ / /
| '_ \ / _` | '__/ __|/ _ \ \ / / _ \ '__/ __|/ _ \| |/ _ \ \/ / __| | 
| |_) | (_| | |  \__ \  __/\ V /  __/ |  \__ \  __/| |  __/>  <| |_| | 
| .__/ \__,_|_|  |___/\___| \_/ \___|_|  |___/\___||_|\___/_/\_\\__| | 
|_|                                                                 \_\
 
$Array = parseVerseText(string $text, array $Verse, boolean $format_first_letter=TRUE)
 *********************************************************************************************/  

		
	function parseVerseText($text,$Verse,$keyword='',$format_first_letter=TRUE)
		{
		$bid=$Verse['book'];
		$v=$Verse['verse'];
		$text=str_replace("\'","&rsquo;",$text);				// ↓ occasionally there will be a lone strong's number not associated with an english word
		$_text=str_replace('}{','} {',$text);					// 		this separates it, so it can be seen as a separate word.
		$_text=preg_replace('/{\(((H|G)[0-9]+)\)}/','',$_text);	// create a new variable without strong's markings.

		# Being phrase extraction...							// I'm not using this, in this version, but hope to in future updates
		preg_match_all('/(.*?)({((H|G)[0-9]+)}[ ,;.])/',$_text,$Matches);	// Find strong's marking (in original text)
		$phrases='';
		$use_second_word=FALSE;$_fl='';
		foreach($Matches[0] as $p=>$toss)
			{
			$show_class='';$p_num='';
			$phrase=ltrim($Matches[1][$p],' ');
			$phrase=rtrim($phrase,' ');
			$punctuation=preg_replace('/{(.*?)}/','',$Matches[2][$p]);
			$punctuation=rtrim($punctuation,' ');
			$phrase.=$punctuation;
			$strongs=$Matches[3][$p];
			if($phrase==' '){$show_class=' h_only';$phrase='';/*$phrase='[אֵת]';*/}
			if($p==0 and $v==1 and $format_first_letter)	// if it's the first word of the first verse...
				{
				$phrase=str_replace('¶','',$phrase);			// remove the paragraph marking
				if($phrase!='')
					{
					$p_num='first-phrase';					// we will mark this word
					$fl=substr($phrase,0,1);					// First Letter
					$row=substr($phrase,1);					// Rest Of Word
					$style='';$additional_class='';
					if($fl=='A')							// of tjhe first letter is an "A"...
						{									// Ajust the rest of the verse to wrap around it.
						$style='style="shape-outside:polygon(50% 0%,0 100%, 100% 100%);margin-right:5px"';
						$additional_class='A';
						}									// ↓ style the first letter
					$_fl="<span class=\"first-letter $additional_class\" $style>$fl</span>";
					$phrase="$row";							// assign the rest of the word to the word.
					
					}
				else
					{
					$use_second_word=TRUE;					// treat the second word as the first
					}
				}
			elseif($p==0)
				{
				$p_num='first-phrase';						// the first word of every other verse
				}
			$phrase=str_replace('¶','<span class="paragraph">¶</span>',$phrase);
			# do if second word
			# do capFilter
			$phrases.="<phrase strongs=\"$strongs\" data-toggle=\"popover\" data-placement=\"bottom\" data-container=\"#study-bible\" class=\"strongs phrase$show_class $p_num\">$phrase</phrase>";
			
			}
		# ...End phrase extraction

		# Begin word extraction...
		$Text=explode(' ',$text);
		$_text='';
		$use_second_word=FALSE;$_fl='';$_fl2='';
		$is_keyword=false;
		foreach($Text as $w=>$word)							// loop through all words
			{
			$strongs='';$_word='';
			$_word=preg_replace('/\{(.*?)\}/','',$word);	// remove strongs markings in alternate variable
			preg_match('/\{(.*?)\}/',$word,$Strongs);		// get strongs number from original word
			$w_num='';
			if(substr_count($keyword,'"')==2)
				{
				$_keyword=str_replace('"','',$keyword);
				$Keywords=explode(' ',$_keyword);
				}
			if(isset($Keywords))
				{
				$__word=$_word;
				if($is_keyword)
					{
					$_word="<strong>$_word</strong>";
					$is_keyword=false;
					}
				if(isset($Text[$w+1]))
					{
					$__nword=$Text[$w+1];
					$__nword=preg_replace('/\{(.*?)\}/','',$__nword);
					$__word=str_replace('&rsquo;',"'",$_word);
					$__nword=str_replace('&rsquo;',"'",$__nword);
					$__word=preg_replace('/[^a-zA-Z \']/','',$__word);
					$__nword=preg_replace('/[^a-zA-Z \']/','',$__nword);
					if(strtolower($__word)==strtolower($Keywords[0]) and strtolower($__nword)==strtolower($Keywords[1]))
						{
						$_word="<strong>$_word</strong>";
						$Text[$w+1]="<strong>{$Text[$w+1]}</strong>";
						$is_keyword=true;
						}
					}
				}
			
			if($w==0 and $v==1 and $format_first_letter)	// if it's the first word of the first verse...
				{
				$_word=str_replace('¶','',$_word);			// remove the paragraph marking
				if($_word)									// Some chapters start with a strong's number alone. Matthew 3
					{
					$w_num='first-word';					// we will mark this word
					$fl=substr($_word,0,1);					// First Letter
					$row=substr($_word,1);					// Rest Of Word
					$style='';$additional_class='';
					if($fl=='A')							// of tjhe first letter is an "A"...
						{									// Ajust the rest of the verse to wrap around it.
						$style='style="shape-outside:polygon(50% 0%,0 100%, 100% 100%);margin-right:5px"';
						$additional_class='A';
						}									// ↓ style the first letter
					$_fl="<span class=\"first-letter $additional_class\" $style>$fl</span>";
					$_fl2="<span class=\"first-letter\"$_fl</span>";
					$_word="$_fl2$row";							// assign the rest of the word to the word.
					}
				else
					{
					$use_second_word=TRUE;					// treat the second word as the first
					}
				}
			elseif($w==0)
				{
				$w_num='first-word';						// the first word of every other verse
				}
			if($use_second_word and $w==1 and $v==1)		// handle the second word if the first word wasn't really a word.
				{
				$w_num='first-word';
				$fl=substr($_word,0,1);
				$row=substr($_word,1);
				$style='';
				if($fl=='A')
					{
					$style='style="shape-outside:polygon(50% -10%,0 100%, 100% 100%);margin-right:5px"';
					}
				$_fl="<span class=\"first-letter\" $style>$fl</span>";
				$_fl2="<span class=\"first-letter\"$_fl</span>";
				$_word="$_fl2$row";
				$use_second_word=FALSE;
				}
			$_word=$this->capFilter($_word);				// correct all cap words
			$smallcaps='';
			if(strstr($_word,'<span class="smallcaps">'))	// the capFilter function surrunds the word with a span
				{											// we don't want that. So we simply make the word as needing it,
				$_word=str_replace('<span class="smallcaps">','',$_word);	// so we can add the class later.
				$_word=str_replace('</span>','',$_word);
				$smallcaps='smallcaps';
				}
			if($bid!=19) 									// No paragraph marks for Psalms 
				{											// (it's all poetry, so literally every verse is marked as a paragraph)
				$_word=str_replace('¶','<b class="paragraph">¶</b>',$_word);
				}
			else											// Otherwise, substitude a classed span to be handled by css
				{
				$_word=str_replace('¶','<span class="paragraph"></span>',$_word);
				}
			if(isset($Strongs[1]))							// Does this word have a strong's number?...
				{
				$strongs=$Strongs[1];						// ↓style it accordingly
				$_text.="<word strongs=\"$strongs\" data-toggle=\"popover\" data-placement=\"bottom\" data-container=\"#study-bible\" class=\"strongs word $w_num $smallcaps\">$_word</word> ";
				}
			else											// words without strong's number get styled differently
				{
				$_text.="<word class=\"word $w_num $smallcaps\">$_word</word> ";
				}
			$_fl2='';
			}
		# ...End word extraction
		$Return['fl']=$_fl;
		$Return['text']=$_text;
		$Return['phrases']=$phrases;
#		$Return['text']=$_text.'<div style="height:50px"></div>'.$phrases;
		return $Return;
		}


/********************************************************************************************

           __                              ____                 _ _        __
 _ __ ___ / _| ___ _ __ ___ _ __   ___ ___|  _ \ ___  ___ _   _| | |_ ___ / /
| '__/ _ \ |_ / _ \ '__/ _ \ '_ \ / __/ _ \ |_) / _ \/ __| | | | | __/ __| | 
| | |  __/  _|  __/ | |  __/ | | | (_|  __/  _ <  __/\__ \ |_| | | |_\__ \ | 
|_|  \___|_|  \___|_|  \___|_| |_|\___\___|_| \_\___||___/\__,_|_|\__|___/ | 
                                                                          \_\

 $string = referenceResults(string $keyword)
 *********************************************************************************************/

	function referenceResults($keyword)
		{
		global $_debug,$page_url,$current_user,$reference,$_vbm;
		$book_list=$this->getBookList();
		$book_list2=$this->getBookList(6);
		$book_list_modal=$this->buildBookListModal();
		$style=$this->getUserMeta('style');
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
		$isRef=$this->isRef($keyword);
		$js='';
		$virtual_bible_holman_installed=$_vbm->is_module_installed('holman');
		if($virtual_bible_holman_installed=='installed')
			{
			$holman_xref_class='holman-xref';
			}
		else
			{
			$holman_xref_class='';
			}
		$virtual_bible_interlinear_installed=$_vbm->is_module_installed('interlinear');
		$Ref=$this->getRefByKeyword($keyword);
		$ref=$Ref['Verses']['ref'];
		unset($Ref['Verses']['ref']);
		$results='';
		$reference=$Ref['clean-ref'];
		$form=$this->buildForm($reference);
		$xref='';
		if($Ref['chapter']>$Ref['debug']['BookData']['chapters'])
			{
			$reference="?$keyword?";
			$results.='There was an error in your keyword reference.';
			}
		$Verses=$this->getVerses($Ref['bid'],$Ref['chapter'],$Ref['Verses']);
		$bid=$Ref['bid'];$chapter=$Ref['chapter'];$clean_ref=$Ref['clean-ref'];
		$hebgreek='';$hebrew='';$greek='';$heb_tag='';$show_hebrew=false;$show_greek=false;$show_languages=false;
		if($virtual_bible_interlinear_installed=='installed')
			{
			$show_interlinear=true;
			$Interlinear=$this->getInterlinear($Ref['bid'],$Ref['chapter'],$Ref['Verses']);
			$iVerse=[];
			$interlinear='<div class="interlinear"><h4>Interlinear Text</h4>';
			foreach($Interlinear as $__Verse)
				{
				if($bid<40)
					{
					if(isset($__Verse['verse']))
						{
						$verse=$__Verse['verse'];
						$text=$__Verse['text'];
						$Words=explode(';',$text);
						$_text='';$hebGreek=[];
						foreach($Words as $w=>$word)
							{
							$wordParts=explode('|',$word);
							$hebrew_word=$wordParts[0];
							$english_word=$wordParts[1];
							$strongs_word=$wordParts[2];
							$parse_word=$wordParts[3];
							$pos_word=$wordParts[4];
							$trans_word=$wordParts[5];
							$hebGreek[]="
							<div class=\"int-heb-word\"  titlex=\"$trans_word\" data-toggle=\"tooltip\" data-html=\"true\" data-container=\"body\"
								data-title=\"
									<div class='hebrew'>$hebrew_word</div>
									<div class='trans'>$trans_word</div>
									<div class='english'>$english_word</div>
									<div class='parse'>$parse_word</div>\"
								onmouseover=\"
									$(this).css({'background-color':'#ffffdd'});
									$(this).css({'border':'1px solid #000'});
									$('verse#verse_{$bid}_{$chapter}_{$verse} word[strongs=H$strongs_word]').css({'background-color':'#ffffdd'})\"
								onmouseout=\"
									$(this).css({'background-color':'transparent'});
									$(this).css({'border':'1px solid transparent'});
									$('verse#verse_{$bid}_{$chapter}_{$verse} word[strongs=H$strongs_word]').css({'background-color':'transparent'})\">
								<span class=\"heb\">$hebrew_word</span>
								<span class=\"eng\">$english_word</span>
							</div>";
							}
						}
					$_text=implode(' ',$hebGreek);
					$interlinear.="\n<div id=\"hebrew_$bid"."_$chapter"."_$verse\" class=\"hebrew hebrew-verse\" 
						onmouseover=\"$('#verse_$bid"."_$chapter"."_$verse').css({'text-decoration':'underline'})\" 
						onmouseout=\"$('#verse_$bid"."_$chapter"."_$verse').css({'text-decoration':'none'})\">
						<b class=\"hebrew-verse-number\">&nbsp; $verse</b>&nbsp; $_text</div>";
					}
				else
					{
					if(isset($__Verse['verse']))
						{
						$verse=$__Verse['verse'];
						$text=rtrim($__Verse['text'],';');
						$text=str_replace('[none]',' ',$text);
						$Words=explode(';',$text);
						$_text='';$hebGreek=[];
						foreach($Words as $w=>$word)
							{
							$wordParts=explode('|',$word);
							$greek_word=$wordParts[0];
							$english_word=$wordParts[1];
							$strongs_word=$wordParts[2];
							$parse_word=$wordParts[3];
							$pos_word=$wordParts[4];
							$trans_word=$wordParts[5];
							if($greek_word!=' ')
								{
								$hebGreek[]="
								<div class=\"int-gre-word\"  titlex=\"$trans_word\" data-toggle=\"tooltip\" data-html=\"true\" data-container=\"body\"
									data-title=\"
										<div class='greek'>$greek_word</div>
										<div class='trans'>$trans_word</div>
										<div class='english'>$english_word</div>
										<div class='parse'>$parse_word</div>\"
									onmouseover=\"
										$(this).css({'background-color':'#ffffdd'});
										$(this).css({'border':'1px solid #000'});
										$('verse#verse_{$bid}_{$chapter}_{$verse} word[strongs=G$strongs_word]').css({'background-color':'#ffffdd'})\"
									onmouseout=\"
										$(this).css({'background-color':'transparent'});
										$(this).css({'border':'1px solid transparent'});
										$('verse#verse_{$bid}_{$chapter}_{$verse} word[strongs=G$strongs_word]').css({'background-color':'transparent'})\">
									<span class=\"gre\">$greek_word</span>
									<span class=\"eng\">$english_word</span>
								</div>";
								}
							}
						}
					$_text=implode(' ',$hebGreek);
					$interlinear.="\n<div id=\"greek_$bid"."_$chapter"."_$verse\" class=\"greek greek-verse\" 
						onmouseover=\"$('#verse_$bid"."_$chapter"."_$verse').css({'text-decoration':'underline'})\" 
						onmouseout=\"$('#verse_$bid"."_$chapter"."_$verse').css({'text-decoration':'none'})\">
						<b class=\"greek-verse-number\">&nbsp; $verse</b>&nbsp; $_text</div>";
					
					}
				}
			$interlinear.='</div>';
			}

		$bookname=$Ref['bookname'];$chapter=$Ref['chapter'];
		$Outline=$this->getOutline($bid,$chapter);
		if(!isset($Outline[0])){$Outline[0]='';}
		$chapter_list_modal=$this->buildChapterListModal($bookname);
		$previousChapter=$this->getPreviousChapter($bookname,$chapter);
		$previous_chapter="{$previousChapter['booktitle']}+{$previousChapter['chapter']}";
		$prev_chap="{$previousChapter['booktitle']} {$previousChapter['chapter']}";
		$nextChapter=$this->getNextChapter($bookname,$chapter);
		$next_chapter="{$nextChapter['booktitle']}+{$nextChapter['chapter']}";
		$next_chap="{$nextChapter['booktitle']} {$nextChapter['chapter']}";
		$gty_intro=$this->buildToolsContent($bid,'intro');
		$gty_outline=$this->buildToolsContent($bid,'outline');
		$_debug.="<b>\$previous_chapter</b>".getPrintR($previous_chapter);
		$results.="
		<div id=\"ref-results\" class=\"row study-bible $style $holman_xref_class\">
			<div id=\"bible\" class=\"row bible\" >
				<div id=\"results-header\" class=\"col-md-12 row\">
					<div class=\"col-xs-1 col-sm-1 \" style=\"padding:10px 0;font-size:19px;\">
						<a href=\"$page_url?keyword=$previous_chapter\" style=\"color:#000;margin-top:-5px\" title=\"$prev_chap\" >
							<i class=\"fas fa-circle-left\" style=\"display:block;margin-top:2px\"></i>
							<!i class=\"fa-solid fa-left-long\"></i>
							<!i class=\"fa-solid fa-circle-chevron-left\"></i>
						</a>
					</div>
					<div class=\"col-xs-10 col-sm-10\" style=\"font-size:13px;padding:10px 0\">
						<span id=\"book-name\" style=\"float:left;text-transform:uppercase;font-family:Ova\">
							<a href=\"#\" style=\"color:#000\">$bookname</a>
						</span>
						<span id=\"chapter-number\" style=\"float:right;text-transform:uppercase;font-family:Ova;cursor:pointer;\" onclick=\"document.getElementById('chapters-modal').style.display='block'\">
							Chapter $chapter
						</span>
					</div>
					<div class=\"col-xs-1 col-sm-1\" style=\"padding:10px 0;text-align:right;font-size:19px;\">
						<a href=\"$page_url?keyword=$next_chapter\" style=\"color:#000\" title=\"$next_chap\">
							<i class=\"fas fa-circle-right\" style=\"display:block;margin-top:2px\"></i>
							<!i class=\"fas fa-arrow-right-long\"></i>
						</a>
					</div>
				</div>
				<div class=\"row\">
					<outline class=\"pos-0\">{$Outline[0]}</outline>
					<verses>\n";
		foreach($Verses as $Verse)
			{
			if(isset($Verse['verse']))
				{
				$v=$Verse['verse'];$par='';
				if(isset($Outline[$v]))
					{
					$ol_text=ucfirst($Outline[$v]);
					$ol_width=strlen($ol_text);
					if($ol_width>145)
						{
						$Words=explode(' ',$ol_text);
						$mid_point = floor(count($Words)/2);
						$Words[$mid_point].="<br />";
						$ol_text=implode(' ',$Words);
						$outline='<outline>'.$ol_text.'</outline>';
						}
					else
						{
						$outline='<outline>'.$ol_text.'</outline>';
						}
					}
				else
					{
					$outline='';
					}
				$text=$Verse['text'];$intro='';
				if($bid==19 and $v==1 and strstr($text,'|'))	// Catch Psalm intros
					{
					list($intro,$text)=explode('|',$text);
					$_intro=preg_replace('/\{(.*?)\}/','',str_replace('¶','',$intro));
					$text="¶$text";
					$Intro=$this->parseVerseText($intro,$Verse,'',FALSE);
					$intro=$Intro['text'];
					$intro_width=strlen($_intro);
					if($intro_width>45)
						{
						$intro=str_replace(',</word> ',',</word><br />',$intro);
						}
					$intro="<intro class=\"intro\" title=\"$intro_width $_intro\">$intro</intro>";
					$_debug.="<br><hr>$intro<hr><br>";
					}
				$height_guess=substr_count($text,' ');		//We are gussing the height of the verse, based on the number of spaces.
				$max_xref=(ceil($height_guess/10)+1);		//We are limiting the number of xrefs shown, based on our guessed height.
				$par_div='';
				if(strstr($text,'¶')){$par=' paragraph';$par_div='<div class="paragraph"></div>';}
				if($virtual_bible_holman_installed=='installed')
					{
					$xref='';$_X=[];
					if(isset($Verse['xref']))
						{
						$Xref=$Verse['xref'];
						for($x=0;$x<$max_xref;$x++)
							{
							if(isset($Xref[$x]['ref']))
								{
								$this_xref=$Xref[$x]['ref'];
								$this_xref_link=str_replace(' ','+',$this_xref);
								$_X[$x]="<xr data-ref=\"$this_xref_link\" class=\"xref_verse\">$this_xref</xr>";
								}
							}
						$xref=implode('; ',$_X);
						if($xref){$xref="<b>$v</b> $xref";}
						}
					}
				if($hebrew)
					{
					$heb_tag="
					onmouseover=\"document.getElementById('heb_$bid"."_$chapter"."_$v').style.backgroundColor='#ffffcc'\" 
					onmouseout=\"document.getElementById('heb_$bid"."_$chapter"."_$v').style.backgroundColor=''\"";
					}

				$Text=$this->parseVerseText($text,$Verse,$keyword);
				$_text=$Text['text'];
				$_fl=$Text['fl'];
				$v_num='';$chnum='';
				if($v==1){$v_num='first-verse';$chnum="<span class=\"chapter-number\">$chapter</span>";}
				$results.="
					$outline
					$intro
					<div id=\"context_$bid"."_$chapter"."_$v\" class=\"context\"></div>$par_div$chnum
					<verse id=\"verse_$bid"."_$chapter"."_$v\" class=\"verse $par $v_num\" $heb_tag>
						<xref>$xref</xref>
						$_fl<b class=\"verse-number\">$v</b>$_text
					</verse>";
				$_fl='';
				}
			}
		$results.="</verses>
			</div>
			<div class=\"col-md-12 row\" style=\"padding:0;margin:0;margin-top:20px;border-top:1px solid #ddd;\">
				<div class=\"col-xs-1 col-md-1\" style=\"padding:10px 0;font-size:19px;\">
					<a href=\"$page_url?keyword=$previous_chapter\" style=\"color:#000;margin-top:-5px\" title=\"$prev_chap\" >
						<i class=\"fas fa-circle-left\" style=\"display:block;margin-top:2px\"></i>
						<!i class=\"fa-solid fa-left-long\"></i>
						<!i class=\"fa-solid fa-circle-chevron-left\"></i>
					</a>
				</div>
				<div class=\"col-xs-10 col-md-10\" style=\"font-size:13px;padding:10px 0\">
				</div>
				<div class=\"col-xs-1 col-md-1\" style=\"padding:10px 0;text-align:right;font-size:19px;\">
					<a href=\"$page_url?keyword=$next_chapter\" style=\"color:#000\" title=\"$next_chap\">
						<i class=\"fas fa-circle-right\" style=\"display:block;margin-top:2px\"></i>
						<!i class=\"fas fa-arrow-right-long\"></i>
					</a>
				</div>
			</div>
			<div class=\"row\" style=\"display:flex;align-self:stretch\"></div>
		</div>";
		$_tools = '
				<ul class="nav nav-tabs tool-nav">
					<li class="active">
						<a data-toggle="tab" href="#tools-overview" style="text-decoration:none">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-wrench" data-title="<i class=\'fas fa-wrench\'></i> &nbsp;Tools" data-toggle="tooltip" data-html="true"></i> 
							</h4>
						</a>
					</li>
					<li>
						<a data-toggle="tab" href="#introduction-results" style="text-decoration:none">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-door-open" data-title="<i class=\'fas fa-door-open\'></i> &nbsp;Introduction" data-toggle="tooltip" data-html="true"></i> 
							</h4>
						</a>
					</li>
					<li>
						<a data-toggle="tab" href="#outline-results" style="text-decoration:none">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-list-ol" data-title="<i class=\'fas fa-list-ol\'></i> &nbsp;Outline" data-toggle="tooltip" data-html="true"></i>
							</h4>
						</a>
					</li>';
		if($show_interlinear)
			{
			$_tools.='
					<li>
						<a data-toggle="tab" href="#hebgreek-results" style="text-decoration:none;" title="Interlinear Bible">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-globe" data-title="<i class=\'fas fa-globe\'></i> &nbsp;Interlinear Bible" data-toggle="tooltip" data-html="true"></i>
							</h4>
						</a>
					</li>';
			}
		$_tools.='
				</ul>
				';
		$results.="<div class=\"tools\" >
				{$_tools}
				<div class=\"tab-content tool-content\" > 
					<div id=\"tools-overview\" class=\"tab-pane fade in active\">	
						<div style=\"font-size:21px;font-family:Poppins, san-serif;text-align:center;color:#833\" class=\"tool-title\">
							<i class=\"fa fa-wrench\"></i> 
							Study Tools
						</div>
						<p style=\"margin-bottom:5px\">Use the tabs above to select the tool you need.</p>
						<p style=\"text-indent:3px;margin-bottom:5px;\">
							<span class=\"tool-title\"><i class=\"fas fa-door-open\"></i> <b>Introduction</b></span> &mdash; Book introductions 
						</p>
						<p style=\"text-indent:3px;margin-bottom:5px;\">
						<span class=\"tool-title\"><i class=\"fas fa-list-ol\"></i> <b>Outline</b></span> &mdash; Chapter and book outline 
						</p>
						<p style=\"text-indent:3px;margin-bottom:5px;\">
							<span class=\"tool-title\">
								<i class=\"fas fa-globe\"></i> 
								<b>Interlinear Bible</b> 
							</span>
							&mdash; the Interlinar Hebrew/Greek text. It can be displayed alongside the English text, and the keyed words will be matched by highlighting the corresponding keyed word.
						</p>
						<small>
							Introductions and Outlines courtesy <a href=\"https://www.gty.org\" target=\"_blank\">Grace to You</a>. Used by permission)
						</small>
						<hr style=\"border:none;border-bottom:1px solid #ccc;background-color:transparent\"/>
						<div class=\"row book-list\">
							<!-- {$book_list2} -->
						</div>
					</div>
					<div id=\"introduction-results\" class=\"tab-pane fade in\">
						{$gty_intro}
					</div>
					<div id=\"outline-results\" class=\"tab-pane fade in\">	
						{$gty_outline}
					</div>
					<div id=\"hebgreek-results\" class=\"tab-pane fade in\">	
						{$interlinear}
					</div>
				</div><!-- tool-content -->
			</div>
		</div>";
		$_debug.='<b>$isRef</b>'.getPrintR($isRef);
		$_debug.='<b>$Ref</b>'.getPrintR($Ref);
		$_debug.='<b>$Verses</b>'.getPrintR($Verses);
		$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));

		$nonce_url_strongs = 	wp_nonce_url($plugin_url.'ajax/fillstrongs.php','strongs_popover');
		$nonce_url_xref = 		wp_nonce_url($plugin_url.'ajax/fillxref.php','xref_popover');
		$noonce_watcher_url=	wp_nonce_url($plugin_url.'ajax/worker.php','vb_watcher');
		$user_id=$current_user->ID;
		$_js = <<<EOD
	
				var vb_strongs=0;
				var strongs_num='';
				var _vb_user_id={$user_id};
			
				function reset_lex()
					{
					$('word.strongs').css({'color':'#000','font-weight':'normal','cursor':'text'});
					$(".strongs").popover('hide');
					$(".strongs").popover('destroy');
					vb_strongs=0;
					}
				function set_lex()
					{
					$('word.strongs').css({'color':'#800','font-weight':'bold','cursor':'pointer'});
					$('phrase.strongs').css({'color':'#800','font-weight':'bold','cursor':'pointer'});
					$(".strongs").click(function()
						{
						strongs_num=$(this).attr("strongs");
						});
					$(".strongs")
						.popover
							(
								{
								placement : "bottom", 
								html: true,
								"content" : function()
									{
									return $.ajax(
											{
											type: "GET",
											url: "{$nonce_url_strongs}",
											data: {strongs:strongs_num,rnd:Math.random()},
											dataType: "html",
											async: false
											}).responseText;
									}
								}
							)
						.click
							(
							function(e)
								{
								$(this).popover('toggle');
								}
							);
					vb_strongs=1;
					}	

				function saveUserData(key,value)
					{
					var watcher_nonce_url = '{$noonce_watcher_url}';
					$.get
						(
						watcher_nonce_url, 
						{function:'user_data_set',user:_vb_user_id,user_key:key,user_value:value},
						function(data) 
							{
							if(data=='installed')
								{
								$("#settings-submit").prop('disabled',false);
								}
							}
						);
					}
		EOD;
	
		$js.=<<<EOD
				var xref="";
				var entry_title=$("h1.entry-title").text();
				$("h1.entry-title").html('<a href="{$page_url}">'+entry_title+'</a>');
				$(
				function()
					{
					$(".strongs").on
						(
						"click", 
						function(e)
							{
							$(".strongs").not(this).popover("hide");
							}
						);
					var hash=window.location.hash;
					var highlight=hash.replace("context","verse");
					$(highlight).css("background-color","#ffffaa");

				
					$(".xref_verse").click
						(
						function()
							{
							xref=$(this).data("ref");
							$('div.popover').popover('hide');
							$('verse').css('background-color','#fff');
							$(this).closest('verse').css('background-color','#ffffee');
							}
						);

					$(".xref_verse")
						.popover
							(
								{
								placement : "top", 
								html: true,
								viewport: ".verse",
								"content" : function()
									{
									return $.ajax(
											{
											type: "GET",
											url: "{$nonce_url_xref}",
											data: {ref:xref,rnd:Math.random()},
											dataType: "html",
											async: false
											}).responseText;
									}
								}
							)
						.click
							(
							function(e)
								{
								$(this).popover("toggle");
								}
							);


					}
				);
				
			EOD;
	
	
		$virtual_bible_page = <<<EOD
		<div class="row study-bible-cover" style="
			background-color:#fff;
			height:100vh;
			font-family: 'Montserrat', sans-serif;
			color:#fafafa;
			font-size:70px;
			text-align:center;
			font-weight:700;
			display:block;
			width:100%;
			padding-top:100px;line-height:1;">
			Loading<small><br>{$reference}...</small>
		</div>
		<div id="study-bible" class="reference-results">
			<div class="study-bible-form" style="display:none;transition:0.6s;filter:blur(15px)">
				{$form}
			</div>
			<div class="row study-bible-results" style="display:none;transition:.9s;filter:blur(15px)">
				{$results}
			</div>
		</div>
		<hr>
		<div class="study-bible-debug" style="display:none">
			{$_debug}
		</div>
		{$book_list_modal}
		{$chapter_list_modal}
		<script>	
		window.addEventListener
			(
			'load',
			function()
				{
				$('div.study-bible-form').fadeIn(600);
				$('div.study-bible-form').css('filter','blur(0)');
				$('div.study-bible-results').fadeIn(900);
				$('div.study-bible-results').css('filter','blur(0)');
				$('div.study-bible-cover').fadeOut(300);
				$('div.study-bible-debug').fadeIn(1000);
				$(function()
					{
					$('[data-toggle="tooltip"]').tooltip(
						{
						'delay':{show:50,hide:1},
						'template': '<div class="tooltip virtual-study-bible"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
						}
					);
					});
				{$js}
				}
			);
		{$_js}
		</script>
		EOD;
		return $virtual_bible_page;
		}

/********************************************************************************************

                       _                         _     ____                 _ _        __
__      _____  _ __ __| |___  ___  __ _ _ __ ___| |__ |  _ \ ___  ___ _   _| | |_ ___ / /
\ \ /\ / / _ \| '__/ _` / __|/ _ \/ _` | '__/ __| '_ \| |_) / _ \/ __| | | | | __/ __| | 
 \ V  V / (_) | | | (_| \__ \  __/ (_| | | | (__| | | |  _ <  __/\__ \ |_| | | |_\__ \ | 
  \_/\_/ \___/|_|  \__,_|___/\___|\__,_|_|  \___|_| |_|_| \_\___||___/\__,_|_|\__|___/ | 
                                                                                      \_\

$string = wordsearchResults(string $keyword, integer $scope)
 *********************************************************************************************/

	function wordsearchResults($keyword,$scope)
		{
		global $version,$_vbm,$current_user;
		$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));
		$user_id=$current_user->ID;
		$noonce_watcher_url=wp_nonce_url($plugin_url.'ajax/worker.php','vb_watcher');
		$search_type='';
		$original_keyword=$keyword;
		$_keyword=str_replace('`','"',$keyword);
		$_keyword=str_replace('\\','',$_keyword);
		if(strstr($keyword,'`'))
			{
			$keyword=str_replace('`','',$keyword);
			$keyword=str_replace('\\','',$keyword);
			$search_type='quotes';
			}
		$virtual_bible_eastons_installed=$_vbm->is_module_installed('eastons');
		$page_name=$this->getMeta('page_name');
		$page_slug=sanitize_title($page_name);
		$page_url=site_url().'/'.$page_slug.'/';
		$isRef=$this->isRef($keyword);
		$results='<div class="row study-bible"><div class="row col-md-6 words">';$chapter_list_modal='';
		$book_list=$this->getBookList();
		$book_list_modal=$this->buildBookListModal();
		$form=$this->buildForm($original_keyword);
		$_debug='';
		$_debug.='<b>$isRef</b>'.getPrintR($isRef);
		if($search_type=='quotes'){$keyword=$_keyword;}
		$verse_count=$this->getVerseCountByKeyword($keyword,$scope);
		$_debug.="<b>Verse Count:</b> ".getPrintR($verse_count);
		if($verse_count)
			{
			$page_count=ceil($verse_count/20);
			if(!isset($_GET['vbpage'])){$current_page=1;}
			else{$current_page=$_GET['vbpage'];}
			$pagination='';
			if($page_count>1)
				{
				$pagination='<ul class="pagination pagination-sm">';$p_count=0;$p_start=1;
				if($current_page>4){$p_start=$current_page-3;}
				if($current_page>($page_count-3)){$p_start=$page_count-6;}
				if($p_start<1){$p_start=1;}
				$pp=$p_start-1;
				if($p_start>1)
					{
					$pagination.="<li class=\"page-item first\">
						<a class=\"page-link\" href=\"$page_url?keyword=$keyword&scope=$scope&vbpage=$pp\" style=\"padding-left:11px;width:37px\">
							<i class=\"fas fa-chevron-circle-left\" style=\"font-size:17px;vertical-align:text-bottom;\"></i>
						</a>
					</li>";
					}
				for($p=1;$p<=$page_count;$p++)
					{
					$p_count++;
					$position='';
					if($p==1){$position='first';}
					if($p==$page_count){$position='last';}
					if($p_count<$p_start+7 and $p_count>=$p_start)
						{
						if($p==$current_page)
							{
							$pagination.="<li class=\"page-item active $position\"><a class=\"page-link\" href=\"/$page_url?keyword=$keyword&scope=$scope&vbpage=$current_page\">$p</a></li>";
							}
						else
							{
							$pagination.="<li class=\"page-item $position\"><a class=\"page-link\" href=\"$page_url?keyword=$keyword&scope=$scope&vbpage=$p\">$p</a></li>";
							}
						$np=$p;
						}
					}
				$np++;
				if($p_start<$page_count-6 and $page_count>7)
					{
					$pagination.="<li class=\"page-item last\">
						<a class=\"page-link\" href=\"$page_url?keyword=$keyword&scope=$scope&vbpage=$np\">
							<i class=\"fas fa-chevron-circle-right\" style=\"font-size:17px;vertical-align:text-bottom\"></i>
						</a>
					</li>";
					}
				$pagination.="</ul>";
				}
			$start=($current_page-1)*20;
			$_start=$start+1;$_end=$_start+19;
			if($verse_count<20){$_end=$verse_count;}
			$keyword=str_replace('"','',$keyword);
			$results.="<div class=\"word-results-title\">$_start-$_end of $verse_count verses containing &ldquo;$keyword&rdquo;</div>$pagination";
			$keyword=$_keyword;
			$Verses=$this->getVersesByKeyword($keyword,$start,$scope);
			$Keywords=[];
			$_debug.="<b>Verses</b> ".getPrintR($Verses);

			foreach($Verses as $v=>$Verse)
				{
				$_debug.="<b>\$Verses[$v] as \$Verse</b>".getPrintR($Verse);
				$bid=$Verse['book'];
				$text=$Verse['text'];
				$bookname=$this->getBookTitleFromId($bid);
				$chapter=$Verse['chapter'];
				$verse=$Verse['verse'];
				$this_in_context="$bookname+$chapter#context_$bid"."_$chapter"."_$verse";
				$Text=$this->parseVerseText($text,$Verse,$keyword,FALSE);
				$text=str_replace('¶','',$Text['text']);
				$text=$this->capFilter($text);		
				$_debug.="<b>\$text</b>".getPrintR($text);
				if(strstr($keyword,'*'))					// wild cards searches
					{
					$_keyword=str_replace('*',')(.*?)(',$keyword);
					$Parts = preg_split('/(<(?:[^"\'>]|"[^"<]*"|\'[^\'<]*\')*>)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
					for ($i=0; $i<count($Parts); $i+=2) 
						{
						$Parts[$i] = preg_replace("/\b($_keyword)\b/", "<strong>$1$2</strong>", $Parts[$i]);
						}
					$text=implode('',$Parts);
					}
				elseif(strstr($keyword,' '))				// multiple words
					{
					$Keywords=explode(' ',$keyword);
					foreach($Keywords as $k)
						{
						$Parts = preg_split('/(<(?:[^"\'>]|"[^"<]*"|\'[^\'<]*\')*>)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
						for ($i=0; $i<count($Parts); $i+=2) 
							{
							$Parts[$i] = preg_replace("/\b($k)\b/", "<strong>$1</strong>", $Parts[$i]);
							}
						$text=implode('',$Parts);
						}
					}
				else										// normal searches
					{
					$Parts = preg_split('/(<(?:[^"\'>]|"[^"<]*"|\'[^\'<]*\')*>)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
					for ($i=0; $i<count($Parts); $i+=2) 
						{
						$Parts[$i] = preg_replace("/\b($keyword)\b/", "<strong>$1</strong>", $Parts[$i]);
						}
					$text=implode('',$Parts);
					}
				
				$results.="
					<div class=\"word-results\">
						<div class=\"word-results-reference\">$bookname $chapter:$verse
							<span class=\"in-context\">
								<a href=\"$page_url?keyword=$this_in_context\">...in context</a>
							</span>
						</div>
						<div class=\"word-results-text\">$text</div>
					</div>";
				}
			$results.=$pagination;
			}
		else
			{
			$results.="
				<div class=\"word-results-title\">
					0 verses containing &ldquo;$keyword&rdquo;
				</div>
				<div class=\"word-results\">
					<div class=\"word-results-text\">Sorry, but no verses could be found containing the word or phrase you submitted.</div>
				</div>";

			}
		$_tools = '
				<ul class="nav nav-tabs tool-nav">
					<li class="active">
						<a data-toggle="tab" href="#tools-overview" style="text-decoration:none" title="Tools">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-wrench"></i> 
							</h4>
						</a>
					</li>
					<li>
						<a id="word-tools-filter" data-toggle="tab" href="#filter-results" style="text-decoration:none" title="Filter">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-filter"></i> 
							</h4>
						</a>
					</li>';
		
		if($virtual_bible_eastons_installed=='installed')
			{
			$_tools.='
					<li>
						<a id="word-tools-eastons" data-toggle="tab" href="#eastons-results" style="text-decoration:none" title="Dictionary">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-book"></i>
							</h4>
						</a>
					</li>';
			}
		$_tools.='
					<li>
						<a id="word-tools-lexicon" data-toggle="tab" href="#lexicon-results"  style="text-decoration:none" title="Lexicons">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-language"></i>
							</h4>
						</a>
					</li>
				</ul>
		';

		$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));
		$results.="
			</div>
			<div class=\"col-md-6 tools\">
			{$_tools}
			<div class=\"tab-content tool-content\" > 
				<div id=\"tools-overview\" class=\"tab-pane fade in active\">	
					<div style=\"font-size:21px;font-family:Poppins, san-serif;text-align:center;margin-top:-35px;color:#833\" class=\"tool-title\">
						<i class=\"fa fa-wrench\"></i> 
						Study Tools <small>(word search)</small></div>
					<p style=\"margin-bottom:5px\">Use the tabs above to select the tool you need.</p>
					<p style=\"text-indent:6px;margin-bottom:5px;\">
						<span class=\"tool-title\">
							<i class=\"fas fa-filter\"></i> 
							<b>Filter</b>
						</span> 
						&mdash; Filer search by book or book type. 
					</p>";
		
		if($virtual_bible_eastons_installed=='installed')
			{
			$results.="
					<p style=\"text-indent:6px;margin-bottom:5px;\">
						<span class=\"tool-title\">
							<i class=\"fas fa-book\"></i> <b>Dictionary</b>
						</span> 
						&mdash; Easton's Bible Dictionary entry for selected words. 
					</p>";
			}
		$results.="
					<p style=\"text-indent:6px;margin-bottom:5px;\">
						<span class=\"tool-title\">
							<i class=\"fas fa-language\"></i> 
							<b>Lexicons</b> 
						</span>
						&mdash; Strong's Lexicons entries matching the selected word. 
					</p>
				</div>
				<div id=\"filter-results\" class=\"tab-pane fade in\">
					<h4>Filter by...</h4>
					<div id=\"word-tools-filter-content\">
						<div style=\"width:100%;text-align:center\">
							<img src=\"$plugin_url/images/gears-spinning.gif\" style=\"width:50%;margin-right:auto;margin-left:auto\"/>
						</div>
					</div>
				</div>";
				
		if($virtual_bible_eastons_installed=='installed')
			{
			$results.="
				<div id=\"eastons-results\" class=\"tab-pane fade in\">	
					<h4>Easton's Bible Dictionary</h4>
					<div id=\"word-tools-eastons-content\">
						<div style=\"width:100%;text-align:center\">
							<img src=\"$plugin_url/images/gears-spinning.gif\" style=\"width:50%;margin-right:auto;margin-left:auto\"/>
						</div>
					</div>
				</div>";
			}
		$results.="
				<div id=\"lexicon-results\" class=\"tab-pane fade in\">	
					<h4>Strong's Lexicon Entries...<small style=\"float:right;font-size:12px;display:none;cursor:pointer\" onclick=\"
					$('.word-results').css('background-color','');
					$('.lexicon-results-item').css('background-color','');
					$(this).css('display','none');\">Clear highlights.</small></small></h4>
					<div id=\"word-tools-lexicon-content\">
						<div style=\"width:100%;text-align:center\">
							<img src=\"$plugin_url/images/gears-spinning.gif\" style=\"width:50%;margin-right:auto;margin-left:auto\"/>
						</div>
					</div>
				</div>
			</div><!-- tool-content -->
		</div>
	</div>";
		$nonce_url_word_filter = wp_nonce_url($plugin_url.'ajax/fillwordfilter.php','word_filter_results');
		$nonce_url_word_eastons = wp_nonce_url($plugin_url.'ajax/filleastons.php','word_eastons_results');
		$nonce_url_word_lexicon = wp_nonce_url($plugin_url.'ajax/filllexicon.php','word_lexicon_results');
		$_keyword=str_replace("'","\'",$original_keyword);
		$js= <<<EOD
		var entry_title=$("h1.entry-title").text();
		$("h1.entry-title").html('<a href="{$page_url}">'+entry_title+'</a>');
		var filter_keyword_results='';
		$("#word-tools-filter").on
			(
			"click", function(e)
				{
				if(!filter_keyword_results)
					{
					$.ajax
						(
							{
							type: "GET",
							url: "{$nonce_url_word_filter}",
							data: {keyword:'{$_keyword}',pageurl:'{$page_url}',rnd:Math.random()},
							success: function(data)
								{
								$("#word-tools-filter-content").html(data);
								filter_keyword_results=data;
								}
							}
						);
					}
				}
			);

		EOD;
		if($virtual_bible_eastons_installed=='installed')
			{
			$js.= <<<EOD

			$("#word-tools-eastons").on
				(
				"click", function(e)
					{
					$.ajax
						(
							{
							type: "GET",
							url: "{$nonce_url_word_eastons}",
							data: {keyword:'{$_keyword}',pageurl:'{$page_url}',rnd:Math.random()},
							success: function(data)
								{
								$("#word-tools-eastons-content").html(data);
								}
							}
						);
					}
				);

			EOD;
			}
		
		$nonce_url_strongs = wp_nonce_url($plugin_url.'ajax/fillstrongs.php','strongs_popover');
		$js.= <<<EOD
		
		$("#word-tools-lexicon").on
			(
			"click", function(e)
				{
				$.ajax
					(
						{
						type: "GET",
						url: "{$nonce_url_word_lexicon}",
						data: {keyword:'{$_keyword}',rnd:Math.random()},
						success: function(data)
							{
							$("#word-tools-lexicon-content").html(data);
							set_lex_in_lex();
							}
						}
					);
				}
			);

		EOD;
		$_js = <<<EOD
		
		
		var vb_strongs=0;
		var strongs_num='';
		var _vb_user_id={$user_id};
	
		function reset_lex()
			{
			$('word.strongs').css({'color':'#000','font-weight':'normal','cursor':'text'});
			$(".strongs").popover('hide');
			$(".strongs").popover('destroy');
			vb_strongs=0;
			}
		function set_lex()
			{
			$('word.strongs').css({'color':'#800','font-weight':'bold','cursor':'pointer'});
			$('phrase.strongs').css({'color':'#800','font-weight':'bold','cursor':'pointer'});
			$(".strongs").click(function()
				{
				strongs_num=$(this).attr("strongs");
				});
			$(".strongs")
				.popover
					(
						{
						placement : "bottom", 
						html: true,
						"content" : function()
							{
							return $.ajax(
									{
									type: "GET",
									url: "{$nonce_url_strongs}",
									data: {strongs:strongs_num,rnd:Math.random()},
									dataType: "html",
									async: false
									}).responseText;
							}
						}
					)
				.click
					(
					function(e)
						{
						$(this).popover('toggle');
						}
					);
			vb_strongs=1;
			}	

		function set_lex_in_lex()
			{
			$('lex.strongs').css({'color':'#800','font-weight':'500','cursor':'pointer'});
			$("lex.strongs").click(function()
				{
				strongs_num=$(this).attr("strongs");
				});

			}	

		function saveUserData(key,value)
			{
			var watcher_nonce_url = '{$noonce_watcher_url}';
			$.get
				(
				watcher_nonce_url, 
				{function:'user_data_set',user:_vb_user_id,user_key:key,user_value:value},
				function(data) 
					{
					if(data=='installed')
						{
						$("#settings-submit").prop('disabled',false);
						}
					}
				);
			}
		EOD;
		$virtual_bible_page = <<<EOD

		<div class="row study-bible-cover" style="
			background-color:#fff;
			height:100vh;
			font-family: 'Montserrat', sans-serif;
			color:#fafafa;
			font-size:70px;
			text-align:center;
			font-weight:700;
			display:block;
			width:100%;
			padding-top:100px;line-height:1;">
			Loading
		</div>
		<div id="study-bible" class="word-search-results">
			<div class="study-bible-form" style="display:none;transition:0.6s;filter:blur(15px)">
				{$form}
			</div>
			<div class="row study-bible-results" style="display:none;transition:.9s;filter:blur(15px)">
				{$results}
			</div>
		</div>

		<hr>
		<div class="study-bible-debug" style="display:none">
			{$_debug}
		</div>
		{$book_list_modal}
		{$chapter_list_modal}
		<script>	
		{$_js}	
		window.addEventListener
			(
			'load',
			function()
				{
				$('div.study-bible-form').fadeIn(600);
				$('div.study-bible-form').css('filter','blur(0)');
				$('div.study-bible-results').fadeIn(900);
				$('div.study-bible-results').css('filter','blur(0)');
				$('div.study-bible-cover').fadeOut(300);
				$('div.study-bible-debug').fadeIn(1000);
				$('#scope').prop('selectedIndex',{$scope});
				$('#version').prop('selectedIndex','{$version}');
				{$js}
				}
			);
		</script>
		EOD;
		return $virtual_bible_page;
		}
		
	

/********************************************************************************************
                 _____ _ _ _             __
  ___ __ _ _ __ |  ___(_) | |_ ___ _ __ / /
 / __/ _` | '_ \| |_  | | | __/ _ \ '__| | 
| (_| (_| | |_) |  _| | | | ||  __/ |  | | 
 \___\__,_| .__/|_|   |_|_|\__\___|_|  | | 
          |_|                           \_\

$string = capFilter(string $text)
 *********************************************************************************************/  

	function capFilter($text)
		{
		$text=str_replace('BRANCH', '<span class="smallcaps">Branch</span>', $text);
		$text=str_replace('LORD', '<span class="smallcaps">Lord</span>', $text);
		$text=str_replace('<span class="smallcaps">Lord</span>\'S', '<span class="smallcaps">Lord\'s</span>', $text);
		$text=str_replace('GOD', '<span class="smallcaps">God</span>', $text);
		$text=str_replace('JEHOVAH', '<span class="smallcaps">Jehovah</span>', $text);
		$text=str_replace('MENE', '<span class="smallcaps">Mene</span>', $text);
		$text=str_replace('TEKEL', '<span class="smallcaps">Tekel</span>', $text);
		$text=str_replace('UPHARSIN', '<span class="smallcaps">Upharsin</span>', $text);
		$text=str_replace('PERES', '<span class="smallcaps">Peres</span>', $text);
		$text=str_replace('HOLINESS', '<span class="smallcaps">Holiness</span>', $text);
		$text=str_replace('UNTO', '<span class="smallcaps">Unto</span>', $text);
		$text=str_replace('THE', '<span class="smallcaps">The</span>', $text);
		$text=str_replace('THIS', '<span class="smallcaps">This</span>', $text);
		$text=str_replace('IS', '<span class="smallcaps">Is</span>', $text);
		$text=str_replace('KING', '<span class="smallcaps">King</span>', $text);
		$text=str_replace('OF', '<span class="smallcaps">Of</span>', $text);
		$text=str_replace('JEWS', '<span class="smallcaps">Jews</span>', $text);
		$text=str_replace('JESUS', '<span class="smallcaps">Jesus</span>', $text);
		$text=str_replace('NAZARETH', '<span class="smallcaps">Nazareth</span>', $text);
		$text=str_replace('THE LORD OUR RIGHTEOUSNESS', '<span class="smallcaps">The Lord Our Righteousness</span>', $text);
		return $text;
		}


/********************************************************************************************
            _ __     __                  ____                  _   ____        _  __                                _  __
  __ _  ___| |\ \   / /__ _ __ ___  ___ / ___|___  _   _ _ __ | |_| __ ) _   _| |/ /___ _   ___      _____  _ __ __| |/ /
 / _` |/ _ \ __\ \ / / _ \ '__/ __|/ _ \ |   / _ \| | | | '_ \| __|  _ \| | | | ' // _ \ | | \ \ /\ / / _ \| '__/ _` | | 
| (_| |  __/ |_ \ V /  __/ |  \__ \  __/ |__| (_) | |_| | | | | |_| |_) | |_| | . \  __/ |_| |\ V  V / (_) | | | (_| | | 
 \__, |\___|\__| \_/ \___|_|  |___/\___|\____\___/ \__,_|_| |_|\__|____/ \__, |_|\_\___|\__, | \_/\_/ \___/|_|  \__,_| | 
 |___/                                                                   |___/          |___/                         \_\

 $integer = getVerseCountByKeyword(string $keyword, integer $scope=0)
 *********************************************************************************************/  

	function getVerseCountByKeyword($keyword,$scope=0)
		{
    	global $wpdb,$_debug,$ScopeKey;
		if(!$scope){$scope=0;}
		$keyword=strtolower($keyword);
		$keyword=str_replace('&quot;','"',$keyword);
		$keyword=str_replace('`','"',$keyword);
		if(strstr($keyword,'"'))
			{
			$Keywords=explode('"',$keyword);
			foreach($Keywords as $kw)
				{
				$kw=str_replace(' ','_',trim($kw));
				$KW[]=$kw;
				}
			$keyword=trim(implode(' ',$KW));
			$KW='';
			}
		if(strstr($keyword,' '))
			{
			$Keywords=explode(' ',$keyword);
			foreach($Keywords as $kw)
				{
				$kw=str_replace('*','[a-zA-Z]*',$kw);
				$kw=str_replace('_','{(.*?)} ',$kw);
				$SearchKey[]="`text` REGEXP \"\\\\b".$kw."\\\\b\"";
				}
			$search_key=implode(' AND ',$SearchKey);
			}
		else
			{
			$keyword=str_replace('*','[a-zA-Z]*',$keyword);
			if(strstr($keyword,'_'))
				{
				$kw1=str_replace('_','{((H|G)[0-9]+)}{\\\((H|G)[0-9]+\\\)} ',$keyword);
				$kw2=str_replace('_','{((H|G)[0-9]+)} ',$keyword);
				$keyword="($kw1)|($kw2)";
				}
			$search_key="`text` REGEXP '\\\\b".$keyword."\\\\b'";
			}
		if($scope)
			{
			if(!isset($ScopeKey[$scope]))
				{
				$bid=$scope-10;
				if($bid)
					{
					$ScopeKey[$scope]="&& `book` = '$bid'";
					}
				}
			$search_key.=' '.$ScopeKey[$scope];
			}

		$table_name = $wpdb->prefix . 'virtual_bible_kjvs';

		$querytext = sprintf("SELECT `id` FROM $table_name WHERE $search_key");
		$_debug['getVerseCountByKeyword querytext']=$querytext;

		$wpdb->get_results($querytext);
		return $wpdb->num_rows;
		}



/********************************************************************************************
            _ __     __                      ____        _  __                                _  __
  __ _  ___| |\ \   / /__ _ __ ___  ___  ___| __ ) _   _| |/ /___ _   ___      _____  _ __ __| |/ /
 / _` |/ _ \ __\ \ / / _ \ '__/ __|/ _ \/ __|  _ \| | | | ' // _ \ | | \ \ /\ / / _ \| '__/ _` | | 
| (_| |  __/ |_ \ V /  __/ |  \__ \  __/\__ \ |_) | |_| | . \  __/ |_| |\ V  V / (_) | | | (_| | | 
 \__, |\___|\__| \_/ \___|_|  |___/\___||___/____/ \__, |_|\_\___|\__, | \_/\_/ \___/|_|  \__,_| | 
 |___/                                             |___/          |___/                         \_\

 $Array = getVersesByKeyword(string $keyword, integer $start=0, integer $scope=0)
 *********************************************************************************************/  
				
	function getVersesByKeyword($keyword,$start=0,$scope=0)
		{
		global $wpdb,$_debug,$ScopeKey;
		$keyword=strtolower($keyword);
		$keyword=str_replace("'","\'",$keyword);
		$keyword=str_replace('`','"',$keyword);
		if(strstr($keyword,'"'))
			{
			$Keywords=explode('"',$keyword);
			foreach($Keywords as $kw)
				{
				$kw=str_replace(' ','_',trim($kw));
				$KW[]=$kw;
				}
			$keyword=trim(implode(' ',$KW));
			$KW='';
			}
		if(strstr($keyword,' '))
			{
			$Keywords=explode(' ',$keyword);
			foreach($Keywords as $kw)
				{
				$kw=str_replace('*','[a-zA-Z]*',$kw);
				$kw=str_replace('_','{(.*?)} ',$kw);
				$SearchKey[]="`text` REGEXP \"\\\\b".$kw."\\\\b\"";
				}
			$search_key=implode(' AND ',$SearchKey);
			}
		else
			{
			$keyword=str_replace('*','[a-zA-Z]*',$keyword);
			if(strstr($keyword,'_'))
				{
				$kw1=str_replace('_','{((H|G)[0-9]+)}{\\\((H|G)[0-9]+\\\)} ',$keyword);
				$kw2=str_replace('_','{((H|G)[0-9]+)} ',$keyword);
				$keyword="($kw1)|($kw2)";
				}
			$search_key="`text` REGEXP '\\\\b".$keyword."\\\\b'";
			}
		if($scope)
			{
			$search_key.=' '.$ScopeKey[$scope];
			}
		$table_name = $wpdb->prefix . 'virtual_bible_kjvs';
    	$querytext = "SELECT `text`,`book`,`chapter`,`verse` FROM $table_name 
        	WHERE $search_key LIMIT $start, 20";
		$Results=$wpdb->get_results($querytext,ARRAY_A);
		return $Results;
		}



/********************************************************************************************
                       _ ____                      _     _____ _ _ _                 __
__      _____  _ __ __| / ___|  ___  __ _ _ __ ___| |__ |  ___(_) | |_ ___ _ __ ___ / /
\ \ /\ / / _ \| '__/ _` \___ \ / _ \/ _` | '__/ __| '_ \| |_  | | | __/ _ \ '__/ __| | 
 \ V  V / (_) | | | (_| |___) |  __/ (_| | | | (__| | | |  _| | | | ||  __/ |  \__ \ | 
  \_/\_/ \___/|_|  \__,_|____/ \___|\__,_|_|  \___|_| |_|_|   |_|_|\__\___|_|  |___/ | 
                                                                                    \_\
$integer = wordSearchFilters(string $keyword, integer $scope=0) : returns number of occurances of $keyword in scope specified
 *********************************************************************************************/  


	function wordSearchFilters($keyword,$scope=0)
		{		
		global $page_url;
		$Filters[0]='The Whole Bible';
		$Filters[1]='Old Testament';
		$Filters[2]='New Testament';
		$Filters[3]='Books of Law';
		$Filters[4]='Books of History';
		$Filters[5]='Books of Poetry';
		$Filters[6]='Major Prophets';
		$Filters[7]='Minor Prophets';
		$Filters[8]='The Gospels';
		$Filters[9]='Pauline Epistles';
		$Filters[10]='General Epistles';
		$Filters[11]='Genesis';
		$Filters[12]='Exodus';
		$Filters[13]='Leviticus';
		$Filters[14]='Numbers';
		$Filters[15]='Deuteronomy';
		$Filters[16]='Joshua';
		$Filters[17]='Judges';
		$Filters[18]='Ruth';
		$Filters[19]='1 Samuel';
		$Filters[20]='2 Samuel';
		$Filters[21]='1 Kings';
		$Filters[22]='2 Kings';
		$Filters[23]='1 Chronicles';
		$Filters[24]='2 Chronicles';
		$Filters[25]='Ezra';
		$Filters[26]='Nehemiah';
		$Filters[27]='Esther';
		$Filters[28]='Job';
		$Filters[29]='Psalms';
		$Filters[30]='Proverbs';
		$Filters[31]='Ecclesiastes';
		$Filters[32]='Song of Solomon';
		$Filters[33]='Isaiah';
		$Filters[34]='Jeremiah';
		$Filters[35]='Lamentations';
		$Filters[36]='Ezekiel';
		$Filters[37]='Daniel';
		$Filters[38]='Hosea';
		$Filters[39]='Joel';
		$Filters[40]='Amos';
		$Filters[41]='Obadiah';
		$Filters[42]='Jonah';
		$Filters[43]='Micah';
		$Filters[44]='Nahum';
		$Filters[45]='Habakkuk';
		$Filters[46]='Zephaniah';
		$Filters[47]='Haggai';
		$Filters[48]='Zechariah';
		$Filters[49]='Malachi';
		$Filters[50]='Matthew';
		$Filters[51]='Mark';
		$Filters[52]='Luke';
		$Filters[53]='John';
		$Filters[54]='Acts';
		$Filters[55]='Romans';
		$Filters[56]='1 Corinthians';
		$Filters[57]='2 Corinthians';
		$Filters[58]='Galatians';
		$Filters[59]='Ephesians';
		$Filters[60]='Philippians';
		$Filters[61]='Colossians';
		$Filters[62]='1 Thessalonians';
		$Filters[63]='2 Thessalonians';
		$Filters[64]='1 Timothy';
		$Filters[65]='2 Timothy';
		$Filters[66]='Titus';
		$Filters[67]='Philemon';
		$Filters[68]='Hebrews';
		$Filters[69]='James';
		$Filters[70]='1 Peter';
		$Filters[71]='2 Peter';
		$Filters[72]='1 John';
		$Filters[73]='2 John';
		$Filters[74]='3 John';
		$Filters[75]='Jude';
		$Filters[76]='Revelation';

		$keyword=strtolower($keyword);
		$total_results='';
		foreach($Filters as $key=>$value)
			{
			$scope=$key;
			$total=$this->getVerseCountByKeyword($keyword,$scope);
			if($total)
				{
				$total_results.="<div class=\"filter-results-item\"><a href=\"{$page_url}?keyword=$keyword&scope=$scope\">$value</a> <span>($total)</span></div>\n";
				}		
			}
		$total_result=str_replace("\n",'',$total_results);
		return $total_results;

		}



/********************************************************************************************
                      _           _      ____  _ _     _    __
       _____  ___ __ | | ___   __| | ___|___ \| (_)___| |_ / /
      / _ \ \/ / '_ \| |/ _ \ / _` |/ _ \ __) | | / __| __| | 
     |  __/>  <| |_) | | (_) | (_| |  __// __/| | \__ \ |_| | 
      \___/_/\_\ .__/|_|\___/ \__,_|\___|_____|_|_|___/\__| | 
               |_|                                         \_\


 *********************************************************************************************/  


	function explode2list($delimiter,$string)
		{
		$Array=array_pad(explode($delimiter,$string),2,null);
		return $Array;	
		}






/********************************************************************************************
          _ _     _____    _       _     _  __
       __| | |__ |  ___|__| |_ ___| |__ / |/ /
      / _` | '_ \| |_ / _ \ __/ __| '_ \| | | 
     | (_| | |_) |  _|  __/ || (__| | | | | | 
      \__,_|_.__/|_|  \___|\__\___|_| |_|_| | 
                                           \_\
 (fetches one item from database)
 *********************************************************************************************/  
	


	function dbFetch1($table,$Where='',$cell='*')
		{
		global $wpdb;
		$table_name = $wpdb->prefix . $table;

		if($Where)
			{
			$W=[];$where='';
			foreach($Where as $column => $criteria)
				{
				$criteria=$wpdb->prepare('%s',$criteria);
				$W[]="`$column`=$criteria";
				}
			$where='WHERE '.implode(' AND ',$W);
			}

		$query="SELECT $cell FROM $table_name $where LIMIT 1;";
		$Results['query']=$query;
		$Results['wpdb_get_results'] = $wpdb->get_results($query,ARRAY_A);	
		if(isset($Results['wpdb_get_results'][0]))
			{
			$cache_out=json_encode($Results);
			return $Results['wpdb_get_results'][0];
			}
		else
			{
			return FALSE;
			}
		}










/********************************************************************************************
          _ _     _____    _       _      __
       __| | |__ |  ___|__| |_ ___| |__  / /
      / _` | '_ \| |_ / _ \ __/ __| '_ \| | 
     | (_| | |_) |  _|  __/ || (__| | | | | 
      \__,_|_.__/|_|  \___|\__\___|_| |_| | 
                                         \_\

 (fetches multiple items from database)
 *********************************************************************************************/  

	function dbFetch($table,$Where='',$cell='*')
		{
		global $wpdb;
		$table_name = $wpdb->prefix . $table;
		$where='';
		if($Where and $Where!='NULL')
			{
			$W=[];
			foreach($Where as $column => $criteria)
				{
				$criteria=$wpdb->prepare('%s',$criteria);
				$W[]="`$column`=$criteria";
				}
			$where='WHERE '.implode(' AND ',$W);
			}

		$query="SELECT $cell FROM $table_name $where;";
		$Results['query']=$query;
		$Results['wpdb_get_results'] = $wpdb->get_results($query,ARRAY_A);
		return $Results['wpdb_get_results'];
		}




	}





/******************************************* 
 *   MISCELLENIOUS FUNCTIONS
*/






function getPrintR($array)
    {
    //hold on to the output
    ob_start();
    print_r($array);
    //store the output in a string
    $out =ob_get_contents();
    //delete the output, because we only wanted it in the string
    ob_clean();

    return "<pre style=\"margin-top:0px\">$out</pre>";
    }


?>