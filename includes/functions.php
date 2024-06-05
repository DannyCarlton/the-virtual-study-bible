<?php

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}


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

function virtual_bible_putMeta($key,$value)
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


function virtual_bible_getUserMeta($key)
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


function virtual_bible_putUserMeta($key,$value)
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


function virtual_bible_getTools()
	{
	$virtual_bible_interlinear_installed=virtual_bible_is_module_installed('interlinear');
	$virtual_bible_holman_installed=virtual_bible_is_module_installed('holman');
		
	$tools='';
	$tools.= <<<EOD
		<p class="fontcolor-medium">
			<span class="tools-p-header"><i class="fas fa-language" title="Lexicons"></i> <b>Lexicons:</b></span> When the <b>Link Keyed</b> is set to Strong's, words keyed to Strong's Concordance will be highlighted (<span style="color:#008;">in blue</span>). Clicking the word will make the Strong's lexicon definition appear.
		</p>
		EOD;
	if($virtual_bible_interlinear_installed)
		{
		$tools.= <<<EOD
		<p class="fontcolor-medium">
			<span class="tools-p-header">
				<span style="font-family:'Times New Roman';font-weight:bold;font-size:29px" title="Interlinearl">&#1488;</span> 
					<b>Interlinear Bible:</b>
				</span> 
			the Interlinar Hebrew/Greek text. It can be displayed alongside the English text, and the keyed words will be matched by highlighting the corresponding keyed word.
		</p>
		EOD;
		}
	if($virtual_bible_holman_installed)
		{
		$tools.= <<<EOD
		<p class="fontcolor-medium">
		<span class="tools-p-header"><i class="fas fa-arrows-turn-to-dots" title="Cross Reference"></i> <b>Holman Cross-Reference:</b></span> Linking 3,992 verses to 57,812 related verses. (Will be in the margin next to the text in passage searches)
		</p>
		EOD;
		}
	return $tools;
	}


function virtual_bible_getStyles()
	{
	$virtual_bible_traditional_select=virtual_bible_getMeta('style_traditional');
	$virtual_bible_paragraph_select=virtual_bible_getMeta('style_paragraph');
	$virtual_bible_reader_select=virtual_bible_getMeta('style_reader');
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
	$styles = '';
	if($virtual_bible_traditional_select)
		{
		$styles.= <<<EOD
			<div class="form-check">
				<label class="form-check-label">
					<input type="radio" name="layout" value="trad" class="form-check-input" {$trad_checked} 
						onclick="
							$('#study-bible').removeClass('paragraph');
							$('#study-bible').removeClass('reading');
							$('#study-bible').addClass('traditional');
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
						$('#study-bible').removeClass('reading');
						$('#study-bible').removeClass('traditional');
						$('#study-bible').addClass('paragraph');
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
						$('#study-bible').removeClass('paragraph');
						$('#study-bible').removeClass('traditional');
						$('#study-bible').addClass('reading');
						saveUserData('style','reading');
						"> Reading 
				</label>
			</div>
		EOD;
		}
	return $styles;
	}


function virtual_bible_getBookList($column_width=3)
	{
	global $wpdb,$page_url;
	$table_name = $wpdb->prefix . 'virtual_bible_books';
	$Books = $wpdb->get_results("SELECT * from $table_name;", ARRAY_A);
	$book_list='';$r=0;
	$cols=12/$column_width;
	$rows_count=ceil(66/$cols);
#	$book_list.="<b>$cols $rows_count</b><br>";
	$book_list.="<div class=\"col-md-$column_width\" style=\"white-space:nowrap\" title=\"$rows_count\">";
	foreach($Books as $n=>$Book)
		{
#		if($n==0 or $n==16 or $n==33 or $n==49)
		if($r==$rows_count)
			{
			$book_list.="</div><div class=\"col-md-$column_width\" style=\"white-space:nowrap\">";
			$r=0;
			}
		if(isset($Book['book']))
			{
			$book=$Book['book'];
			$abbr=sanitize_title($book);
			$book_list.="<a href=\"$page_url?keyword=$abbr+1\">$book</a><br>";
			}
		if($n==15 or $n==32 or $n==48)
			{
#			$book_list.="</div>";
			}
		$r++;
		}
	$book_list.="</div>";
	return $book_list;
	}




function virtual_bible_getOutline($bid,$chapter)
	{
	global $wpdb,$_vb;
	$status=virtual_bible_is_module_installed('outline');
	$Outline=[];
	if($status=='installed')
		{
		$bookname=$_vb->getBookTitleFromId($bid);
		$where="$bookname $chapter";
		$Response=dbFetch('virtual_bible_outline',array('chapter'=>$where));
		foreach($Response as $Item)
			{
			$verse=$Item['verse'];
			$text=str_replace("\'","'",$Item['text']);
			$Outline[$verse]=$text;
			}
		}
	return $Outline;
	}


function virtual_bible_buildForm($reference)
	{
	global $page_url,$scope;
	$styles=virtual_bible_getStyles();
	$form = <<<EOD
	<div class="row study-bible-form-row">
		<form action="{$page_url}" method="GET" class="study-bible-form">
			<div class="col-md-12" style="position:relative;margin-right:auto;margin-left:auto;font-family: 'Montserrat';">
				<div class="bible-search-form" style="float:left">
					<legend style="font-weight:bold;display:block;">
						<span style="font-family: 'Poppins', sans-serif">Search</span>
						<small style="font-weight:normal;color:#000;float:right;cursor:pointer;margin-right:10px" onclick="document.getElementById('modal').style.display='block'" onmouseover="this.style.color='#600'" onmouseout="this.style.color='#000'" >
							books
						</small>
					</legend>
					<div style="position:relative;display:flex;flex-wrap:wrap;align-items:stretch;width:100%">
						<input type="text" class="reference" value="{$reference}" placeholder="Keyword or reference" style="border-bottom-left-radius:0;border-top-left-radius:10px;height:calc(1.5rem + 1rem + 2px);padding:0.5rem 1rem;font-size:17px;line-height:1.5;flex:1 1 auto;width:1%;margin-bottom:0;font-weight:bold" name="keyword" id="keyword">
						<div class="input-group-append" style="margin-left:-1px;">
							<button type="submit" value="Search" class="btn btn-primary" style="border-bottom-right-radius:0;border-top-right-radius:10px;padding:0.5rem 1rem;line-height:1.5;align-self:stretch!important;position:relative;cursor:pointer;height:calc(1.5rem + 1rem + 2px)">Search</button>
						</div>
					</div>
					<div class="drop-down input-group">
						<select id="scope" name="scope" 
							style="border-bottom-left-radius:10px;
									width:50%;
									height:calc(1.5rem + 0.75rem + 2px);
									padding:0.375rem 0.75rem;
									font-size:15px;
									line-height:1.5;
									border:1px solid #ced4da;">
							<optgroup label="Default">
								<option value="0" selected>&nbsp;Whole Bible</option>
							</optgroup>
							<optgroup label="Testaments">
								<option value="1">Old Testament</option>
								<option value="2">New Testament</option>
							</optgroup>
							<optgroup label="By Type">
								<option value="3">Books of Law</option>
								<option value="4">Books of History</option>
								<option value="5">Books of Poetry</option>
								<option value="6">Major Prophets</option>
								<option value="7">Minor Prophets</option>
								<option value="8">The Gospels</option>
								<option value="9">Pauline Epistles</option>
								<option value="10">General Epistles</option>
							</optgroup>
							<optgroup label="Books">
								<option value="11">Genesis</option>
								<option value="12">Exodus</option>
								<option value="13">Leviticus</option>
								<option value="14">Numbers</option>
								<option value="15">Deuteronomy</option>
								<option value="16">Joshua</option>
								<option value="17">Judges</option>
								<option value="18">Ruth</option>
								<option value="19">1 Samuel</option>
								<option value="20">2 Samuel</option>
								<option value="21">1 Kings</option>
								<option value="22">2 Kings</option>
								<option value="23">1 Chronicles</option>
								<option value="24">2 Chronicles</option>
								<option value="25">Ezra</option>
								<option value="26">Nehemiah</option>
								<option value="27">Esther</option>
								<option value="28">Job</option>
								<option value="29">Psalms</option>
								<option value="30">Proverbs</option>
								<option value="31">Ecclesiastes</option>
								<option value="32">Song of Solomon</option>
								<option value="33">Isaiah</option>
								<option value="34">Jeremiah</option>
								<option value="35">Lamentations</option>
								<option value="36">Ezekiel</option>
								<option value="37">Daniel</option>
								<option value="38">Hosea</option>
								<option value="39">Joel</option>
								<option value="40">Amos</option>
								<option value="41">Obadiah</option>
								<option value="42">Jonah</option>
								<option value="43">Micah</option>
								<option value="44">Nahum</option>
								<option value="45">Habakkuk</option>
								<option value="46">Zephaniah</option>
								<option value="47">Haggai</option>
								<option value="48">Zechariah</option>
								<option value="49">Malachi</option>
								<option value="50">Matthew</option>
								<option value="51">Mark</option>
								<option value="52">Luke</option>
								<option value="53">John</option>
								<option value="54">Acts</option>
								<option value="55">Romans</option>
								<option value="56">1 Corinthians</option>
								<option value="57">2 Corinthians</option>
								<option value="58">Galatians</option>
								<option value="59">Ephesians</option>
								<option value="60">Philippians</option>
								<option value="61">Colossians</option>
								<option value="62">1 Thessalonians</option>
								<option value="63">2 Thessalonians</option>
								<option value="64">1 Timothy</option>
								<option value="65">2 Timothy</option>
								<option value="66">Titus</option>
								<option value="67">Philemon</option>
								<option value="68">Hebrews</option>
								<option value="69">James</option>
								<option value="70">1 Peter</option>
								<option value="71">2 Peter</option>
								<option value="72">1 John</option>
								<option value="73">2 John</option>
								<option value="74">3 John</option>
								<option value="75">Jude</option>
								<option value="76">Revelation</option>
						</select>
						<select id="version" name="version" 
							style="border-bottom-right-radius:10px;
									width:50%;
									height:calc(1.5rem + 0.75rem + 2px);
									padding:0.375rem 0.75rem;
									font-size:15px;
									line-height:1.5;
									border:1px solid #ced4da;
									margin-left:-1px">
							<optgroup label="Available Versions">
								<option value="kjvs">KJV keyed to Strong's</option>
							</optgroup>
							<optgroup label="Coming Soon!" style="font-size:13px;display:none">
								<option value="akjvs" disabled>American King James Version</option>
								<option value="twb" disabled>Websters</option>
								<option value="ylt" disabled>Young's Literal Translation</option>
							</optgroup>
						</select>
					</div>
				</div>
				<div id="form-fieldsets" class="row col-lg-3" style="float:left">
					<fieldset class="col-lg-6 text-left form-group" style="max-width:47%;font-size:13px;border:none;float:left;margin-bottom:0">
						<legend style="margin-bottom:-5px;font-weight:bold;display:block;font-family: 'Poppins', sans-serif;font-size:16px;white-space:nowrap">Passage Style</legend>
						{$styles}
					</fieldset>
					<fieldset id="toggle-strongs-fieldset" class="col-lg-6 text-left form-group" style="max-width:47%;font-size:13px;border:none;margin-bottom:0">
						<legend style="margin-bottom:-5px;font-weight:bold;display:block;font-family: 'Poppins', sans-serif;font-size:16px">Link Keyed</legend>
						<input id="toggle-strongs"
							type="checkbox" 
							data-toggle="toggle" 
							data-on="Strong&rsquo;s" 
							data-off="None" 
							data-onstyle="strongs" 
							data-offstyle="none" 
							data-width="110"
							/>
					</fieldset>
				</div>
			</div>
		</form>
	</div>
	EOD;
	return $form;
	}



function virtual_bible_buildBookListModal()
	{
	$book_list=virtual_bible_getBookList();
	$book_list_modal = <<<EOD
	<div id="modal" class="modal" style="display:none;padding-top:50px;z-index:1000;position:fixed;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,0.4);font-family: 'Montserrat';">
		<div class="modal-content" style="border-radius:10px;border:1px solid:#555;width:600px;margin:auto;background-color:#fff;position:relative;padding:20px;outline:0;;font-family: 'Montserrat';box-shadow:1px 1px 30px rgba(0,0,0,0.5)">
			<span onclick="document.getElementById('modal').style.display='none'" style="cursor:pointer;position:absolute;top:0;right:10px;font-size:19px">&times;</span>
			<h4 style="clear:both;font-family: 'Poppins', sans-serif;margin-top:0;margin-bottom:10px;font-weight:bold">Books of the Bible</h4>
			<div class="row" style="font-size:13px;font-family: 'Montserrat';'">
				{$book_list}
			</div class="row">
		</div>
	</div>
	EOD;
	return $book_list_modal;
	}



function virtual_bible_buildChapterListModal($bookname)
	{
	global $_vb,$page_url;
	$chapnum=$_vb->getChaptersInBook($bookname);
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


function virtual_bible_buildStartHelp()
	{
	global $page_url;
	$tools=virtual_bible_getTools();
	$book_list=virtual_bible_getBookList();
	$start_help = <<<EOD
	<ul class="nav nav-tabs" style="margin-top:40px;font-family: 'Montserrat';">
		<li class="active">
			<a data-toggle="tab" href="#how-to-search" style="text-decoration:none">
				<h4 style="margin:0;font-family: 'Poppins', sans-serif">
					<i class="fas fa-search fa-flip-horizontal"></i> 
					&nbsp; How to Search
				</h4>
			</a>
		</li>
		<li>
			<a data-toggle="tab" href="#tools" style="text-decoration:none">
				<h4 style="margin:0;font-family: 'Poppins', sans-serif">
					<i class="fas fa-wrench"></i>
					&nbsp; Tools
				</h4>
			</a>
		</li>
		<li>
			<a data-toggle="tab" href="#books" style="text-decoration:none">
				<h4 style="margin:0;font-family: 'Poppins', sans-serif">
					<i class="fas fa-book"></i>
					&nbsp; Books
				</h4>
			</a>
		</li>
	</ul>
	<div class="tab-content" 
		style="height:630px;font-family: 'Montserrat';font-size:15px;border:1px solid #ddd;margin-top:-2px;background-color:#fff;position:relative;z-index:100"> 
		<div id="how-to-search" class="tab-pane fade in active" style="padding:0 10px">	
			<p class="fontcolor-medium" style="margin-top:0;margin-left:10px;font-size:13px">
				(Note: The example links have all parameter set to show the correct results.)
			</p>
			<h5 style="margin-bottom:5px;margin-top:15px;color:#000">The Basic Passage Search</h5>
			<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
				Find exact chapters and/or verses by typing the book, chapter, and/or verse in the search box.<br>
				<em>Examples: <a href="{$page_url}?keyword=John+3:16">John 3:16</a> <small>(for a single verse)</small> or <a href="?keyword=John+3">John 3</a> <small>(for the entire chapter)</small> or <a href="?keyword=John+3:14-18">John 3:14-18</a> <small>(for a select passage within a chapter)</small></em><br />
				<small>Note: The verse list will be included automatically when you select just the chapter, so <em><a href="{$page_url}?keyword=John+3">John 3</a></em> will become <em><a href-="{$page_url}?John+3:1-36">John 3:1-36</a></em></small></p>
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
			<h5 style="margin-bottom:5px;margin-top:15px;color:#000">Using the Scope Option</h4>
			<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
				Use the scope drop-down menu (default is set to the Whole Bible) to limit your word search to a specific section of the Bible<br>
				<em>Example: <a href="{$page_url}?keyword=love&scope=2">love</a> <small>(returns only instances of the word &ldquo;love&rdquo; in the New Testament)</small></em><br>
			<small><em>Scope will be ignored for reference searches (i.e. John 3:16)</em></small></p>
		</div>		
		<div id="tools" class="tab-pane fade" style="padding: 0 10px">
			{$tools}
		</div>
		<div id="books" class="tab-pane fade">
			<div class="row" style="padding:10px">
				{$book_list}
			</div>
		</div>
	</div>
	EOD;
	return $start_help;
	}


	
function virtual_bible_buildToolsContent($bid,$part='intro')
	{
	global $wpdb,$_vb;
	$bookname=$_vb->getBookTitleFromId($bid);
	$BookData=$_vb->getBookIdFromVagueTitle($bookname);
	list($abbr)=explode(', ',$BookData['abbr']);
	$Intro=dbFetch('virtual_bible_gty_intro_outline',array('book'=>$bid));
	$text=$Intro[0]['text'];
	$text=str_replace('&ndash;','-',$text);
	$text=str_replace('<strong></strong>','',$text);
	$text=str_replace('<p><strong>','<h5>',$text);
	$text=str_replace('</strong></p>','</h5>',$text);	
	
	$plugin_path=str_replace('includes/','',plugin_dir_path(__FILE__));
	$page_name=virtual_bible_getMeta('page_name');
	$page_slug=sanitize_title($page_name);
	$page_url=site_url().'/'.$page_slug.'/';
	$text=preg_replace('/\(([0-9]+):/',"($abbr ".'$1:',$text);
	$text=str_replace('(chap.',"($abbr",$text);
	$regex=file_get_contents($plugin_path.'templates/ref-regex.tpl');
	
	$text=preg_replace($regex,'<a href="'.$page_url.'?keyword=$1+$2">$1 $2</a>',$text);

	list($intro,$outline)=explode2list('<h5>Outline</h5>',$text);
	if($part=='outline')
		{
		return '<h4>Outline of '.$bookname.'<small><br>by John MacArthur</small></h4>'.$outline.'<hr /><p><small class="copyright">Outline of '.$bookname.', Copyright &copy; 2007, <a href="https://www.gty.org" target="_blank">Grace To You.</a> All rights reserved. Used by permission.</small></p>';
		}
	else
		{
		return '<h4>Introduction to '.$bookname.'<small><br>by John MacArthur</small></h4>'.$intro.'<p><small class="copyright">Introduction to '.$bookname.', Copyright &copy; 2007, <a href="https://www.gty.org" target="_blank">Grace To You.</a> All rights reserved. Used by permission.</small></p>';
		}
	}


function virtual_bible_buildStartPage()
	{
	global $reference,$page_url;
	$form=virtual_bible_buildForm($reference);
	$tools=virtual_bible_getTools();
	$book_list=virtual_bible_getBookList();
	$book_list_modal=virtual_bible_buildBookListModal();
	$start_help=virtual_bible_buildStartHelp();

	$virtual_bible_page = <<<EOD
	{$form}
	{$start_help}
	{$book_list_modal}
	EOD;
	return $virtual_bible_page;
	}

	

function virtual_bible_buildResultsPage($keyword,$scope,$version,$layout)
	{
	global $reference,$page_url,$_vb,$plugin_url;
	$virtual_bible_eastons_installed=virtual_bible_is_module_installed('eastons');
	$isRef=$_vb->isRef($keyword);
	if($isRef['type']=='passage' or $isRef['type']=='verse')
		{
		$virtual_bible_page	= $_vb->referenceResults($keyword);
		}
	else		
		{
		$virtual_bible_page=$_vb->wordsearchResults($keyword);
		}

	return $virtual_bible_page;
	}






/******************************
 * 	BIBLE FUNCTIONS
 */

class virtual_bible 
	{
		
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


	function getRefByKeyword($k)
		{
		global $something;
		$vid='';$_V=[];$_V2=[];$vid2='';$bid2=0;$cid2=0;
		$_debug=[];$reference=FALSE;
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



	

	function getVerses($bid,$chapter,$Verses)
		{
		global $wpdb;
		$virtual_bible_holman_installed=virtual_bible_is_module_installed('holman');
		$V=[];$table='virtual_bible_kjvs';
		foreach($Verses as $v=>$verse)
			{
			if($v!='ref')
				{
				$V[$verse]=dbFetch1($table,array('book'=>$bid,'chapter'=>$chapter,'verse'=>$verse));
				if($virtual_bible_holman_installed)
					{
					$xref=dbFetch('virtual_bible_xref_holman',array('book'=>$bid,'chapter'=>$chapter,'verse'=>$verse),'ref');
					if($xref)
						{
						$V[$verse]['xref']=$xref;
						}
					}
				}
			}
		return $V;
		}

    
	function getInterlinear($bid,$chapter,$Verses)
		{
		global $wpdb;
		$V=[];$table='virtual_bible_interlinear';
		foreach($Verses as $v=>$verse)
			{
			if($v!='ref')
				{
				$V[$verse]=dbFetch1($table,array('book'=>$bid,'chapter'=>$chapter,'verse'=>$verse));
				}
			}
		return $V;
		}

    



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

	
		
	function getBookTitleFromId($bookid)
		{
		global $_mysql;
		if($bookid)
			{
			$Row=dbFetch1('virtual_bible_books',array('id'=>$bookid),'book');
			return $Row['book'];
			}
		else
			{
			return '';
			}      
		}




    
	function getChaptersInBook($book)
		{
		if($book)
			{
			$Result=dbFetch1('virtual_bible_books',array('book'=>$book));
			return $Result['chapters'];  
			}
		else
			{
			return '';
			}
		}

		
	function parseVerseText($text,$Verse,$format_first_letter=TRUE)
		{
		$bid=$Verse['book'];
		$v=$Verse['verse'];
		$text=str_replace("\'","&rsquo;",$text);

		$Text=explode(' ',$text);
		$_text='';
		$use_second_word=FALSE;$_fl='';
		foreach($Text as $w=>$word)
			{
			$strongs='';$_word='';
			$_word=preg_replace('/\{(.*?)\}/','',$word);
			preg_match('/\{(.*?)\}/',$word,$Strongs);
			$w_num='';
			if($w==0 and $v==1 and $format_first_letter)
				{
				$_word=str_replace('¶','',$_word);
				if($_word)								//Some chapters start with a strong's number alone. Matthew 3
					{
					$w_num='first-word';
					$fl=substr($_word,0,1);				// First Letter
					$row=substr($_word,1);				// Rest Of Word
					$style='';$additional_class='';
					if($fl=='A')
						{
						$style='style="shape-outside:polygon(50% 0%,0 100%, 100% 100%);margin-right:5px"';
						$additional_class='A';
						}
					$_fl="<span class=\"first-letter $additional_class\" $style>$fl</span>";
					$_word="$row";
					}
				else
					{
					$use_second_word=TRUE;
					}
				}
			elseif($w==0)
				{
				$w_num='first-word';
				}
			if($use_second_word and $w==1 and $v==1)
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
				$_word="$row";
				$use_second_word=FALSE;
				}
			$_word=$this->capFilter($_word);
			$smallcaps='';
			if(strstr($_word,'<span class="smallcaps">'))
				{
				$_word=str_replace('<span class="smallcaps">','',$_word);
				$_word=str_replace('</span>','',$_word);
				$smallcaps='smallcaps';
				}
			if($bid!=19) #No paragraph marks for Psalms
				{
				$_word=str_replace('¶','<b class="paragraph">¶</b>',$_word);
				}
			else
				{
				$_word=str_replace('¶','<span class="paragraph"></span>',$_word);
				}
			if(isset($Strongs[1]))
				{
				$strongs=$Strongs[1];
				$_text.="<word strongs=\"$strongs\" data-toggle=\"popover\" data-placement=\"bottom\" class=\"strongs word $w_num $smallcaps\">$_word</word> ";
				}
			else
				{
				$_text.="<word class=\"word $w_num $smallcaps\">$_word</word> ";
				}
			}
		$Return['fl']=$_fl;
		$Return['text']=$_text;
		return $Return;
		}



	function referenceResults($keyword)
		{
		global $_debug,$page_url,$current_user;
		$book_list=virtual_bible_getBookList();
		$book_list2=virtual_bible_getBookList(6);
		$book_list_modal=virtual_bible_buildBookListModal();
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
		$isRef=$this->isRef($keyword);
		$js='';
		$virtual_bible_holman_installed=virtual_bible_is_module_installed('holman');
		if($virtual_bible_holman_installed=='installed')
			{
			$holman_xref_class='holman-xref';
			}
		else
			{
			$holman_xref_class='';
			}
		$virtual_bible_interlinear_installed=virtual_bible_is_module_installed('interlinear');
		$Ref=$this->getRefByKeyword($keyword);
		$ref=$Ref['Verses']['ref'];
		unset($Ref['Verses']['ref']);
		$results='';
		$reference=$Ref['clean-ref'];
		$form=virtual_bible_buildForm($reference);
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
					#$iVerse[$verse]=$_text;
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
/*
		elseif($bid>39 and $virtual_bible_greek_installed=='installed')
			{
			$show_greek=true;$show_languages=true;
			$Greek=$this->getGreek($Ref['bid'],$Ref['chapter'],$Ref['Verses']);
			$gVerse=[];$greek='<div class="greek"><h4>Greek Text</h4>';
			foreach($Greek as $Stihos) 
				{
				if(isset($Stihos['verse']))
					{
					$verse=$Stihos['verse'];
					$text=$Stihos['text'];
					$hVerse[$verse]=$text;
					}

				$greek.="\n<div id=\"gre_$bid"."_$chapter"."_$verse\" data-connect=\"$bid"."_$chapter"."_$verse\" class=\"greek-verse\" >
					<b class=\"greek-verse-number\">$verse</b>$text</div>";
				}
			$greek.='</div>';
			$js.='$(".greek-verse span").mouseover(function(e)
					{
					var strongs=$(this).data("lex");
					var Strongs=strongs.split(" ");
					Strongs.forEach(st =>
						{
						$("word[strongs="+st+"]").css({"background-color":"#ffffdd"});
						});
					});
				$(".greek-verse span").mouseout(function(e)
					{
					var strongs=$(this).data("lex");
					var Strongs=strongs.split(" ");
					Strongs.forEach(st =>
						{
						$("word[strongs="+st+"]").css({"background-color":""});
						});
					});
				$("verse word").mouseover(function()
					{
					var strongs=$(this).attr("strongs");
					if(strongs !== undefined)
						{
						$("span[data-lex="+strongs+"]").css({"background-color":"#ffffdd"});
						}
					});
				$("verse word").mouseout(function()
					{
					var strongs=$(this).attr("strongs");
					if(strongs !== undefined)
						{
						$("span[data-lex="+strongs+"]").css({"background-color":""});
						}
					});
				';
			$hebgreek=$greek;
			}
*/
		$bookname=$Ref['bookname'];$chapter=$Ref['chapter'];
		$Outline=virtual_bible_getOutline($bid,$chapter);
		if(!isset($Outline[0])){$Outline[0]='';}
		$chapter_list_modal=virtual_bible_buildChapterListModal($bookname);
		$previousChapter=$this->getPreviousChapter($bookname,$chapter);
		$previous_chapter="{$previousChapter['booktitle']}+{$previousChapter['chapter']}";
		$prev_chap="{$previousChapter['booktitle']} {$previousChapter['chapter']}";
		$nextChapter=$this->getNextChapter($bookname,$chapter);
		$next_chapter="{$nextChapter['booktitle']}+{$nextChapter['chapter']}";
		$next_chap="{$nextChapter['booktitle']} {$nextChapter['chapter']}";
		$gty_intro=virtual_bible_buildToolsContent($bid,'intro');
		$gty_outline=virtual_bible_buildToolsContent($bid,'outline');
		$_debug.="<b>\$previous_chapter</b>".getPrintR($previous_chapter);
		$results.="
		<div id=\"study-bible\" class=\"row study-bible $style $holman_xref_class\">
			<div id=\"bible\" class=\"row bible\" >
				<div class=\"col-lg-12 row\" style=\"padding:00;margin:0;border-bottom:1px solid #ddd;margin-bottom:20px;\">
					<div class=\"col-lg-1 offsetx-lg-2\" style=\"padding:10px 0;font-size:19px;\">
						<a href=\"$page_url?keyword=$previous_chapter\" style=\"color:#000;margin-top:-5px\" title=\"$prev_chap\" >
							<i class=\"fas fa-circle-left\" style=\"display:block;margin-top:2px\"></i>
							<!i class=\"fa-solid fa-left-long\"></i>
							<!i class=\"fa-solid fa-circle-chevron-left\"></i>
						</a>
					</div>
					<div class=\"col-lg-10\" style=\"font-size:13px;padding:10px 0\">
						<span id=\"book-name\" style=\"float:left;text-transform:uppercase;font-family:Ova\">
							<a href=\"#\" style=\"color:#000\">$bookname</a>
						</span>
						<span id=\"chapter-number\" style=\"float:right;text-transform:uppercase;font-family:Ova;cursor:pointer;\" onclick=\"document.getElementById('chapters-modal').style.display='block'\">
							Chapter $chapter
						</span>
					</div>
					<div class=\"col-lg-1\" style=\"padding:10px 0;text-align:right;font-size:19px;\">
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
					$Intro=$this->parseVerseText($intro,$Verse,FALSE);
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

				$Text=$this->parseVerseText($text,$Verse);
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
			<div class=\"col-lg-12 row\" style=\"padding:0;margin:0;margin-top:20px;border-top:1px solid #ddd;\">
				<div class=\"col-lg-1 offsetx-lg-2\" style=\"padding:10px 0;font-size:19px;\">
					<a href=\"$page_url?keyword=$previous_chapter\" style=\"color:#000;margin-top:-5px\" title=\"$prev_chap\" >
						<i class=\"fas fa-circle-left\" style=\"display:block;margin-top:2px\"></i>
						<!i class=\"fa-solid fa-left-long\"></i>
						<!i class=\"fa-solid fa-circle-chevron-left\"></i>
					</a>
				</div>
				<div class=\"col-lg-10\" style=\"font-size:13px;padding:10px 0\">
				</div>
				<div class=\"col-lg-1\" style=\"padding:10px 0;text-align:right;font-size:19px;\">
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
						<a data-toggle="tab" href="#tools-overview" style="text-decoration:none" title="introduction">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-wrench"></i> 
							</h4>
						</a>
					</li>
					<li>
						<a data-toggle="tab" href="#introduction-results" style="text-decoration:none" title="introduction">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-door-open"></i> 
							</h4>
						</a>
					</li>
					<li>
						<a data-toggle="tab" href="#outline-results" style="text-decoration:none" title="Outline">
							<h4 style="margin:0;font-family: Poppins, sans-serif">
								<i class="fas fa-list-ol"></i>
							</h4>
						</a>
					</li>';
		if($show_interlinear)
			{
			$_tools.='
					<li>
						<a data-toggle="tab" href="#hebgreek-results" style="text-decoration:none;padding:0px 15px 3px 15px">
							<h4 style="margin:6px 0 0 0;">
								<span style="font-size:29px;font-family: \'Times New Romans\';display:block;margin-top:-6px">&#1488;</span>
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
						<p style=\"text-indent:3px\">
						<span class=\"tool-title\"><i class=\"fas fa-list-ol\"></i> <b>Outline</b></span> &mdash; Chapter and book outline 
						</p>
						<p style=\"text-indent:5px;\">
							<span class=\"tool-title\">
								<span style=\"font-size:19px;font-family: 'Times New Romans';font-weight:bold;margin-right:-2px;\">&#1488;</span>
								<span style=\"font-weight:normal\">/</span>
								<span style=\"font-size:14px;font-family: 'Times New Romans';font-weight:bold;margin-left:-3px\">&Sigma;</span> 
								<b>Hebrew/Greek</b> 
							</span>
							&mdash; The Hebrew or Greek versions of the selected passages. 
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
		<div class="row study-bible-cover style="backgroundColor:#fff;height:100vh">Loading...</div>
		<div class="row study-bible-results" style="display:none">
			{$form}
			{$results}
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
				$('div.study-bible-results').fadeIn(500);
				$('div.study-bible-cover').fadeOut(500);
				$('div.study-bible-debug').fadeIn(1000);
				$(function()
					{
					$('[data-toggle="tooltip"]').tooltip(
						{
						'delay':{show:50,hide:1}
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



	function wordsearchResults($keyword)
		{
		global $scope,$version;
		$virtual_bible_eastons_installed=virtual_bible_is_module_installed('eastons');
		$page_name=virtual_bible_getMeta('page_name');
		$page_slug=sanitize_title($page_name);
		$page_url=site_url().'/'.$page_slug.'/';
		$isRef=$this->isRef($keyword);
		$results='<div class="row study-bible"><div class="row col-lg-6 words">';$chapter_list_modal='';
		$book_list=virtual_bible_getBookList();
		$book_list_modal=virtual_bible_buildBookListModal();
		$keyword=str_replace('\\','',$keyword);
		$form=virtual_bible_buildForm($keyword);
		$_debug='';
		$_debug.='<b>$isRef</b>'.getPrintR($isRef);
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
			$results.="<div class=\"word-results-title\">$_start-$_end of $verse_count verses containing &ldquo;$keyword&rdquo;</div>$pagination";

			$Verses=$this->getVersesByKeyword($keyword,$start,$scope);
			$Keyword=[];
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
				$Text=$this->parseVerseText($text,$Verse,FALSE);
				$text=str_replace('¶','',$Text['text']);
				$text=$this->capFilter($text);
				$_debug.="<b>\$text</b>".getPrintR($text);
				if(strstr($keyword,'*'))
					{
					$_keyword=str_replace('*',')(.*?)(',$keyword);
					$pattern='/\b('.$_keyword.')\b/i';
					$text=preg_replace($pattern,"<strong>$1$2</strong>",$text);
					}
				elseif(substr_count($keyword,'"')==2)
					{
					$K=explode('"',$keyword);
					$_keyword=$K[1];
					$pattern='/\b('.$_keyword.')\b/i';
					$text=preg_replace($pattern,"<strong>$1</strong>",$text);
					$keyword="&quot;$_keyword&quot;";
					}
				elseif(strstr($keyword,' '))
					{
					$Keywords=explode(' ',$keyword);
					foreach($Keywords as $k)
						{
						$pattern='/\b('.$k.')\b/i';
						$text=preg_replace($pattern,"<strong>$1</strong>",$text);
						}
					}
				else
					{
					$pattern='/\b('.$keyword.')\b/i';
					$text=preg_replace($pattern,"<strong class=\"strongs_000\">$1</strong>",$text);
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
			<div class=\"col-lg-6 tools\">
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
					<h4>Strong's Lexicon entries...<small style=\"float:right;font-size:12px;display:none;cursor:pointer\" onclick=\"
					$('.word-results').css('display','block');
					$('.lexicon-results-item').css('background-color','');
					$(this).css('display','none');\">Show all.</small></small></h4>
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
		$_keyword=str_replace("'","\'",$keyword);
		$js= <<<EOD
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
		EOD;
		$virtual_bible_page = <<<EOD
		<div class="row study-bible-results" style="display:none">
			{$form}
			{$results}
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
				$('div.study-bible-results').fadeIn(500);
				$('#scope').prop('selectedIndex',{$scope});
				$('#version').prop('selectedIndex','{$version}');
				{$js}
				}
			);
		</script>
		EOD;
		return $virtual_bible_page;
		}
		
	

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



	function getVerseCountByKeyword($keyword,$scope=0)
		{
    	global $wpdb,$_debug,$ScopeKey;
		if(!$scope){$scope=0;}
		$keyword=strtolower($keyword);
		$keyword=str_replace('&quot;','"',$keyword);
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
				$kw=str_replace('_',' ',$kw);
				$kw=str_replace('*','[a-zA-Z]*',$kw);
				$SearchKey[]="`text` REGEXP '\\\\b".$kw."\\\\b'";
				}
			$search_key=implode(' AND ',$SearchKey);
			}
		else
			{
			$keyword=str_replace('_',' ',$keyword);
			$keyword=str_replace('*','[a-zA-Z]*',$keyword);
			$search_key="`text` REGEXP \"\\\\b".$keyword."\\\\b\"";
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
#		write_log($querytext);

		$cache_file=base64_encode($querytext);
		$cache_file=str_replace('==','',$cache_file);
		$cache_file='gvcbk-'.substr($cache_file,-30);
		$plugin_path=str_replace('includes/','',plugin_dir_path(__FILE__));
		if(file_exists("$plugin_path/cache/$cache_file.dat"))
			{
			$Results=json_decode(file_get_contents("$plugin_path/cache/$cache_file.dat"));	
			return $Results;	
			}
		else
			{
			$wpdb->get_results($querytext);
			$cache_out=json_encode($Results);
			write_cache($wpdb->num_rows,$cache_file);
			return $wpdb->num_rows;
			}
		}



		
		
	function getVersesByKeyword($keyword,$start=0,$scope=0)
		{
		global $wpdb,$_debug,$ScopeKey;
		$keyword=strtolower($keyword);
		$keyword=str_replace("'","\'",$keyword);
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
				$kw=str_replace('_',' ',$kw);
				$kw=str_replace('*','[a-zA-Z]*',$kw);
				$SearchKey[]="`text` REGEXP \"\\\\b".$kw."\\\\b\"";
				}
			$search_key=implode(' AND ',$SearchKey);
			}
		else
			{
			$keyword=str_replace('_',' ',$keyword);
			$keyword=str_replace('*','[a-zA-Z]*',$keyword);
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



	}





/******************************************* 
 *   MISCELLENIOUS FUNCTIONS
*/




function explode2list($delimiter,$string)
	{
	$Array=array_pad(explode($delimiter,$string),2,null);
	return $Array;	
	}


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
	$cache_file=base64_encode($query);
	$cache_file=str_replace('==','',$cache_file);
	$cache_file='dbf1-'.substr($cache_file,-30);
	$plugin_path=str_replace('includes/','',plugin_dir_path(__FILE__));
	if(file_exists("$plugin_path/cache/$cache_file.dat"))
		{
		$Results=json_decode(file_get_contents("$plugin_path/cache/$cache_file.dat"),true);	
		return $Results['wpdb_get_results'][0];	
		}
	else
		{
		write_log("$query\n$cache_file");
		$Results['query']=$query;
		$Results['wpdb_get_results'] = $wpdb->get_results($query,ARRAY_A);	
		if(isset($Results['wpdb_get_results'][0]))
			{
			$cache_out=json_encode($Results);
			write_cache($cache_out,$cache_file);
			return $Results['wpdb_get_results'][0];
			}
		else
			{
			return FALSE;
			}
		}
	}




function dbFetch0($table,$Where='',$cell='*')
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

	$query="SELECT $cell FROM $table_name $where;";
	$Results['query']=$query;
	$Results['wpdb_get_results'] = $wpdb->get_results($query,ARRAY_A);	

#	return $Results['wpdb_get_results'][0];
	return $Results;
	}



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
	$cache_file=base64_encode($query);
	$cache_file=str_replace('==','',$cache_file);
	$cache_file='dbf-'.substr($cache_file,-30);
	$plugin_path=str_replace('includes/','',plugin_dir_path(__FILE__));
	if(file_exists("$plugin_path/cache/$cache_file.dat"))
		{
		$Results=json_decode(file_get_contents("$plugin_path/cache/$cache_file.dat"),true);		
		}
	else
		{
		$Results['query']=$query;
		$Results['wpdb_get_results'] = $wpdb->get_results($query,ARRAY_A);
		$cache_out=json_encode($Results);
		write_cache($cache_out,$cache_file);
		write_log("$query\n$cache_file");
		}

	return $Results['wpdb_get_results'];
#	return $Results;
	}


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


function write_cache($data,$file_name)
	{	
	$plugin_path=str_replace('includes/','',plugin_dir_path(__FILE__));
	$file_name.='.dat';
	$fp = fopen("$plugin_path/cache/$file_name", "w");
	fwrite ($fp, $data);
	fclose ($fp);
	}
?>