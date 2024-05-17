<?php

if(!defined('ABSPATH')) 
	{
    exit; // Die, hacker scum, die!!
	}


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


function virtual_bible_getTools()
	{
	$virtual_bible_eastons_installed=virtual_bible_is_module_installed('eastons');
	$virtual_bible_hebrew_installed=virtual_bible_is_module_installed('hebrew');
	$virtual_bible_greek_installed=virtual_bible_is_module_installed('greek');
	$virtual_bible_holman_installed=virtual_bible_is_module_installed('holman');
		
	$tools='';
	$tools.= <<<EOD
		<p class="fontcolor-medium">
			<span class="tools-p-header"><i class="fas fa-language" title="Lexicons"></i> <b>Lexicons:</b></span> When the <b>Link Keyed</b> is set to Strong's, words keyed to Strong's Concordance will be highlighted (<span style="color:#008;">in blue</span>). Clicking the word will make the Strong's lexicon definition appear.
		</p>
		EOD;
	if($virtual_bible_eastons_installed)
		{
		$tools.= <<<EOD
		<p class="fontcolor-medium">
		<span class="tools-p-header"><i class="fas fa-book" title="Dictionary"></i> <b>Dictionary:</b></span> When the <b>Link Keyed</b> is set to Easton's, words available from <em>Easton's Dictionary of the Bible</em> will be highlighted (<span style="color:#800;">in red</span>). Clicking the word will make the definition appear.
		</p>
		EOD;
		}
	if($virtual_bible_hebrew_installed)
		{
		$tools.= <<<EOD
		<p class="fontcolor-medium">
		<span class="tools-p-header"><span style="font-family:'Times New Roman';font-weight:bold" title="Hebrew">&#1488;</span> <b>Hebrew Text:</b></span> The Masoretic (Leningrad Codex) Hebrew text of the Old Testament.
		</p>
		EOD;
		}
	if($virtual_bible_greek_installed)
		{
		$tools.= <<<EOD
		<p class="fontcolor-medium">
		<span class="tools-p-header"><span style="font-family:'Times New Roman';font-weight:bold" title="Greek">&Sigma;</span> <b>Greek Text:</b></span> The Textus Receptus Greek text of the New Testament.
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
	$trad_checked='checked';$par_checked='';$read_checked='';
	$virtual_bible_traditional_select=virtual_bible_getMeta('style_traditional');
	$virtual_bible_paragraph_select=virtual_bible_getMeta('style_paragraph');
	$virtual_bible_reader_select=virtual_bible_getMeta('style_reader');
	$styles = '';
	if($virtual_bible_traditional_select)
		{
		$styles.= <<<EOD
			<div class="form-check">
				<label class="form-check-label">
					<input type="radio" name="layout" value="trad" class="form-check-input" {$trad_checked}> Traditional
				</label>
			</div>
		EOD;
		}
	if($virtual_bible_paragraph_select)
		{
		$styles .= <<<EOD
			<div class="form-check">
				<label class="form-check-label">
					<input type="radio" name="layout" value="par" class="form-check-input" {$par_checked}> Paragraph
				</label>
			</div>
		EOD;
		}
	if($virtual_bible_reader_select)
		{
		$styles.= <<<EOD
			<div class="form-check">
				<label class="form-check-label">
					<input type="radio" name="layout" value="read" class="form-check-input" {$read_checked}> Reading 
				</label>
			</div>
		EOD;
		}
	return $styles;
	}


function virtual_bible_getBookList()
	{
	global $wpdb,$page_url;
	$table_name = $wpdb->prefix . 'virtual_bible_books';
	$Books = $wpdb->get_results("SELECT * from $table_name;", ARRAY_A);
	$book_list='';
	foreach($Books as $n=>$Book)
		{
		if($n==0 or $n==16 or $n==33 or $n==49)
			{
			$book_list.="<div class=\"col-md-3\">";
			}
		if(isset($Book['book']))
			{
			$book=$Book['book'];
			$abbr=sanitize_title($book);
			$book_list.="<a href=\"$page_url?keyword=$abbr+1\">$book</a><br>";
			}
		if($n==15 or $n==32 or $n==48)
			{
			$book_list.="</div>";
			}
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
			$text=$Item['text'];
			$Outline[$verse]=$text;
			}
		}
	return $Outline;
	}


function virtual_bible_buildForm()
	{
	global $reference,$page_url;
	$styles=virtual_bible_getStyles();
	$virtual_bible_eastons_installed=virtual_bible_is_module_installed('eastons');
	if($virtual_bible_eastons_installed=='installed')
		{
		$eastons='<div class="form-check">
		<label class="form-check-label">
			<input type="radio" name="link-keyed" value="2" class="form-check-input" onclick="set_dic()"> Easton&rsquo;s
		</label>
	</div>';
		}else{$eastons='';}
	$form = <<<EOD
	<div class="row study-bible-form-row">
		<form action="{$page_url}" method="GET" class="study-bible-form">
			<div class="col-md-12" style="position:relative;margin-right:auto;margin-left:auto;font-family: 'Montserrat';">
				<div class="bible-search-form col-lg-7" style="float:left">
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
				<fieldset class="col-lg-2 text-left form-group" style="font-size:13px;border:none;float:left;margin-bottom:0">
					<legend style="margin-bottom:-5px;font-weight:bold;display:block;font-family: 'Poppins', sans-serif;font-size:16px">Passage Style</legend>
					{$styles}
				</fieldset>
				<fieldset class="col-lg-2 text-left form-group" style="font-size:13px;border:none;margin-bottom:0">
					<legend style="margin-bottom:-5px;font-weight:bold;display:block;font-family: 'Poppins', sans-serif;font-size:16px">Link Keyed</legend>
					<div class="form-check">
						<label class="form-check-label">
							<input type="radio" name="link-keyed" value="0" class="form-check-input" checked onclick="reset_lex_dic()"> None
						</label>
					</div>
					<div class="form-check">
						<label class="form-check-label">
							<input type="radio" name="link-keyed" value="1" class="form-check-input" onclick="set_lex()"> Strong's
						</label>
					</div>
					{$eastons}

				</fieldset>
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
				Find verses containing an exact phrase by enclosing it in quotation marks.<br>
				<em>Example: <a href="{$page_url}?keyword=&#34;For+God+so+loved+the+world&#34;">&quot;for God so loved the world&quot;</a></em></p>
			<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
				Find verses containing partial words by using the wildcard * (an asterisk).<br>
				<em>Example: <a href="{$page_url}?keyword=love*">love*</a> <small>(returns love, loves, loved, lovely, etc.)</small></em></p>
			<h5 style="margin-bottom:5px;margin-top:15px;color:#000">Using the Scope Option</h4>
			<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
				Use the scope drop-down menu (default is set to the Whole Bible) to limit your word search to a specific section of the Bible<br>
				<em>Example: <a href="{$page_url}?keyword=love&scope=2">love</a> <small>(returns only instances of the word &ldquo;love&rdquo; in the New Testament)</small></em><br>
			<small><em>Scope will be ignored for reference searches (i.e. John 3:16)</em></small></p>
			<p class="fontcolor-medium">
				<em>Warning: do not combine quotes and wild card.</em></p>
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
	$Intro=dbFetch('virtual_bible_gty_intro_outline',array('book'=>$bid));
	$text=$Intro[0]['text'];
	$text=str_replace('<p><strong>','<h5>',$text);
	$text=str_replace('</strong></p>','</h5>',$text);
	list($intro,$outline)=explode('<h5>Outline</h5>',$text);
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
	$form=virtual_bible_buildForm();
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

	

function virtual_bible_buildResultsPage($keyword,$scope)
	{
	global $reference,$page_url,$_vb;
	$reference=$keyword;$out='';$js='';
	$book_list=virtual_bible_getBookList();
	$book_list_modal=virtual_bible_buildBookListModal();
	$isRef=$_vb->isRef($keyword);
	$Ref=$_vb->getRefByKeyword($keyword);
	$ref=$Ref['Verses']['ref'];
	unset($Ref['Verses']['ref']);
	$results='';$_debug='';
	$reference=$Ref['clean-ref'];
	$form=virtual_bible_buildForm();
	$virtual_bible_holman_installed=virtual_bible_is_module_installed('holman');
	$virtual_bible_eastons_installed=virtual_bible_is_module_installed('eastons');
	$virtual_bible_hebrew_installed=virtual_bible_is_module_installed('hebrew');
	$virtual_bible_greek_installed=virtual_bible_is_module_installed('greek');
	if($virtual_bible_eastons_installed=='installed')
		{
		$Easton=$_vb->getEastonList();
		}

/*************************************************************
 * 
 *  REFERENCE SEARCH
 * 
 *  */		
	if($isRef['type']=='passage' or $isRef['type']=='verse')
		{
		$xref='';
		$Ref=$_vb->getRefByKeyword($keyword);
		if($Ref['chapter']>$Ref['debug']['BookData']['chapters'])
			{
			$reference="?$keyword?";
			$results.='There was an error in your keyword reference.';
			}
		$Verses=$_vb->getVerses($Ref['bid'],$Ref['chapter'],$Ref['Verses']);
		$bid=$Ref['bid'];$chapter=$Ref['chapter'];$clean_ref=$Ref['clean-ref'];
		$hebgreek='';$hebrew='';$greek='';$heb_tag='';
		if($bid<40 and $virtual_bible_hebrew_installed=='installed')
			{
			$Hebrew=$_vb->getHebrew($Ref['bid'],$Ref['chapter'],$Ref['Verses']);
			$hVerse=[];$hebrew='<div class="hebrew"><h4>Hebrew Text</h4>';
			foreach($Hebrew as $Pasuk) #Pasuk is the Hebrew word for verse, I think.
				{
				$verse=$Pasuk['verse'];
				$text=$Pasuk['text'];
				$hVerse[$verse]=$text;
				$hebrew.="\n<div id=\"heb_$bid"."_$chapter"."_$verse\" class=\"hebrew-verse\" 
					onmouseover=\"$('#verse_$bid"."_$chapter"."_$verse').css({'background-color':'#ffffdd'})\" 
					onmouseout=\"$('#verse_$bid"."_$chapter"."_$verse').css({'background-color':'#ffffff'})\">
					<b class=\"hebrew-verse-number\">&nbsp; $verse</b>&nbsp; $text</div>";
				}
			$hebrew.='</div>';
			$hebgreek=$hebrew;
			}
		elseif($bid>39 and $virtual_bible_greek_installed=='installed')
			{
			$Greek=$_vb->getGreek($Ref['bid'],$Ref['chapter'],$Ref['Verses']);
			$gVerse=[];$greek='<div class="greek"><h4>Greek Text</h4>';
			foreach($Greek as $Stihos) 
				{
				$verse=$Stihos['verse'];
				$text=$Stihos['text'];
				$hVerse[$verse]=$text;

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
		$bookname=$Ref['bookname'];$chapter=$Ref['chapter'];
		$Outline=virtual_bible_getOutline($bid,$chapter);
		$chapter_list_modal=virtual_bible_buildChapterListModal($bookname);
		$previousChapter=$_vb->getPreviousChapter($bookname,$chapter);
		$previous_chapter="{$previousChapter['booktitle']}+{$previousChapter['chapter']}";
		$prev_chap="{$previousChapter['booktitle']} {$previousChapter['chapter']}";
		$nextChapter=$_vb->getNextChapter($bookname,$chapter);
		$next_chapter="{$nextChapter['booktitle']}+{$nextChapter['chapter']}";
		$next_chap="{$nextChapter['booktitle']} {$nextChapter['chapter']}";
		$gty_intro=virtual_bible_buildToolsContent($bid,'intro');
		$gty_outline=virtual_bible_buildToolsContent($bid,'outline');
		$_debug.="<b>\$previous_chapter</b>".getPrintR($previous_chapter);
		$results.="
		<div class=\"row study-bible\">
			<div class=\"col-lg-7 row bible\" >
				<div class=\"col-lg-12 row\" style=\"padding:00;margin:0;border-bottom:1px solid #ddd;margin-bottom:20px;\">
					<div class=\"col-lg-1 offsetx-lg-2\" style=\"padding:10px 0;font-size:19px;\">
						<a href=\"$page_url?keyword=$previous_chapter\" style=\"color:#000;margin-top:-5px\" title=\"$prev_chap\" >
							<i class=\"fas fa-circle-left\" style=\"display:block;margin-top:2px\"></i>
							<!i class=\"fa-solid fa-left-long\"></i>
							<!i class=\"fa-solid fa-circle-chevron-left\"></i>
						</a>
					</div>
					<div class=\"col-lg-10\" style=\"font-size:13px;padding:10px 0\">
						<span style=\"float:left;text-transform:uppercase;font-family:Ova\">
							<a href=\"#\" style=\"color:#000\">$bookname</a>
						</span>
						<span style=\"float:right;text-transform:uppercase;font-family:Ova;cursor:pointer;\" onclick=\"document.getElementById('chapters-modal').style.display='block'\">
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
					<verses class=\"col-lg-10\">\n";
		$dicList=[];
		foreach($Verses as $Verse)
			{
			if(isset($Verse['verse']))
				{
				$v=$Verse['verse'];$par='';
				if(isset($Outline[$v]))
					{
					$ol_text=ucfirst($Outline[$v]);
					$ol_width=strlen($ol_text);
					if($ol_width>45)
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
				$text=$Verse['text'];
				$height_guess=substr_count($text,' ');		//We are gussing the height of the verse, based on the number of spaces.
				$max_xref=(ceil($height_guess/8)+1);		//We are limiting the number of xrefs shown, based on our guessed height.
				if(strstr($text,'¶')){$par=' paragraph';}
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
								$_X[$x]="<a href=\"{$page_url}?keyword=$this_xref_link\" class=\"xref_verse\">$this_xref</a>";
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
				$Text=explode(' ',$text);
				$_text='';
				$use_second_word=FALSE;$_fl='';
				foreach($Text as $w=>$word)
					{
					$strongs='';$_word='';$easton='';
					$_word=preg_replace('/\{(.*?)\}/','',$word);
					preg_match('/\{(.*?)\}/',$word,$Strongs);
					$dic=FALSE;
					if($virtual_bible_eastons_installed=='installed')
						{
						$dic_word=str_replace('¶','',$_word);
						$dic_word=trim($dic_word);
						$dic_word=preg_replace("/(?![.=$'%-])\p{P}/u","",$dic_word);
						$dic_word=preg_replace('/[^a-zA-Z]/','',$dic_word);
						$dic_word=strtolower($dic_word);
						$dic=FALSE;
						if(in_array($dic_word,$Easton))
							{
							$dic=TRUE;
							}
						}
					if($dic){$dic_class=' easton';}else{$dic_class='';}
					$w_num='';
					if($w==0 and $v==1)
						{
						$_word=str_replace('¶','',$_word);
						if($_word)								//Some chapters start with a strong's number alone. Matthew 3
							{
							$w_num='first-word';
							$fl=substr($_word,0,1);				// First Letter
							$row=substr($_word,1);				// Rest Of Word
							$style='';
							if($fl=='A')
								{
								$style='style="shape-outside:polygon(50% 0%,0 100%, 100% 100%);margin-right:5px"';
								}
							$_fl="<span class=\"first-letter\" $style>$fl</span>";
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
					if($bid!=19) #No paragraph marks for Psalms
						{
						$_word=str_replace('¶','<b class="paragraph">¶</b>',$_word);
						}
					else
						{
						$_word=str_replace('¶','',$_word);
						}
					if(isset($Strongs[1]))
						{
						$strongs=$Strongs[1];
						$_text.="<word strongs=\"$strongs\" class=\"strongs$dic_class word $w_num\">$_word</word> ";
						}
					else
						{
						$_text.="<word class=\"word$dic_class $w_num\">$_word</word> ";
						}
					}
				$v_num='';
				if($v==1){$v_num='first-verse';}
				$results.="$outline<verse id=\"verse_$bid"."_$chapter"."_$v\" class=\"verse $par $v_num\" $heb_tag><xref>$xref</xref>$_fl<b>$v</b>$_text</verse>";
				$_fl='';
				}
			}
		$results.="</verses>
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
					</li>
					<li>
						<a data-toggle="tab" href="#hebgreek-results" style="text-decoration:none;padding:0px 15px 3px 15px">
							<h4 style="margin:0;">
								<span style="font-size:29px;font-family: \'Times New Romans\';margin-right:-5px">&#1488;</span>
								<span style="font-weight:normal">/</span>
								<span style="font-size:22px;font-family: \'Times New Romans\';margin-left:-5px">&Sigma;</span>
							</h4>
						</a>
					</li>
				</ul>
		';
		$results.="<div class=\"col-lg-5 tools\" >
				{$_tools}
				<div class=\"tab-content tool-content\" > 
					<div id=\"tools-overview\" class=\"tab-pane fade in active\">	
						<div style=\"font-size:21px;font-family:Poppins, san-serif;text-align:center;margin-top:-35px;color:#833\" class=\"tool-title\"><i class=\"fa fa-wrench\"></i> Study Tools</div>
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
						<small>Introductions and Outlines courtesy <a href=\"https://www.gty.org\" target=\"_blank\">Grace to You</a>. Used by permission)</small>
					</div>
					<div id=\"introduction-results\" class=\"tab-pane fade in\">
						{$gty_intro}
					</div>
					<div id=\"outline-results\" class=\"tab-pane fade in\">	
						{$gty_outline}
					</div>
					<div id=\"hebgreek-results\" class=\"tab-pane fade in\">	
						{$hebgreek}
					</div>
				</div><!-- tool-content -->
			</div>
		</div>";
		}
/***************************************************************
 * 
 *   KEYWORD SEARCH
 * 
 */
	else
		{
		}
	$_debug.='<b>$isRef</b>'.getPrintR($isRef);
	$_debug.='<b>$Ref</b>'.getPrintR($Ref);
	$_debug.='<b>$Verses</b>'.getPrintR($Verses);

	
	$_js = <<<EOD
		
			function reset_lex_dic()
				{
				$('word.strongs').css({'color':'#000','font-weight':'normal','cursor':'text'});
				$('word.easton').css({'color':'#000','font-weight':'normal','cursor':'text'});
				}
			function set_lex()
				{
				$('word.easton').css({'color':'#000','font-weight':'normal','cursor':'text'});
				$('word.strongs').css({'color':'#008','font-weight':'bold','cursor':'pointer'});
				}
			function set_dic()
				{
				$('word.strongs').css({'color':'#000','font-weight':'normal','cursor':'text'});
				$('word.easton').css({'color':'#800','font-weight':'bold','cursor':'pointer'});
				}
	EOD;


	$virtual_bible_page = <<<EOD
	<div class="row study-bible-results">
		{$form}
		{$results}
	</div>
	<hr>
	{$_debug}
	{$book_list_modal}
	{$chapter_list_modal}
	<script>	
	window.addEventListener
		(
		'load',
		function()
			{
			{$js}
			}
		);
	{$_js}
	</script>
	EOD;
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
		$bid=$Ref['bid'];
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

		# clean up reference string
		$_k=urldecode($k);
		$_k=str_ireplace('Song of Solomon','Song',$_k);	//This is the only book with spaces other than after a number
		$_k = preg_replace("/(\s){2,}/", ' ', $_k);		//remove extra spaces
		$_k=str_replace('.','',$_k);					//remove periods
		$_k=str_replace('1st ', '1 ', $_k);				//make ordinals regular numbers
		$_k=str_replace('2nd ', '2 ', $_k);
		$_k=str_replace('3rd ', '3 ', $_k);		
		$_k=preg_replace('/^i /i', "1 ", $_k); 			//if it starts with "i " (case insensitive)
		$_k=preg_replace('/^ii /i', "2 ", $_k);
		$_k=preg_replace('/^iii /i', "3 ", $_k);

		if(preg_match('/^[1-3][a-zA-Z]/',$_k))			//catch messed up beginning numbers, like 1john
			{
			$_k=preg_replace('/^1/', '1 ',$_k);
			$_k=preg_replace('/^2/', '2 ',$_k);
			$_k=preg_replace('/^3/', '3 ',$_k);
			}
		if(preg_match('/^[1-3]-/',$_k))			//catch messed up beginning numbers, like 1john
			{
			$_k=preg_replace('/^1-/', '1 ',$_k);
			$_k=preg_replace('/^2-/', '2 ',$_k);
			$_k=preg_replace('/^3-/', '3 ',$_k);
			}
		$_k=ltrim($_k);$_k2='';
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
			$toss=array_shift($Ref_keys);
			$ref_key=implode($Ref_keys);
			$refElements=explode(':',$ref_key);
			$Return['debug']['refElements']=$refElements;
			$cid=$refElements[0];
			$Return['chapter']=$cid;
			}
		else
			{
			$Ref_keys=[];$bid=0;$cid=0;
			}
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
			$Rid=getVerseIDByRef($bid,$cid,$vid);
			$Return['debug']['getVerseIDByRef']=$Rid;
			if(isset($Rid['text'])){$Return['rid']=$Rid['text'];}
			}
		if($vid2)
			{
			$Rid2=getVerseIDByRef($bid2,$cid2,$vid2);
			$Return['debug']['getVerseIDByRef2']=$Rid2;
			if(isset($Rid2['text'])){$Return['rid2']=$Rid2['text'];}
			}
		$Return['Verses']=$_V;
		$Return['Verses2']=$_V2;
		if(isset($verses)){$Return['Verses']['ref']=$verses;}
		$Return['Verses2']['ref']=$verses2;		
		$Return['clean-ref']=$bookname.' '.$cid.':'.$verses;
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
    
	function getHebrew($bid,$chapter,$Verses)
		{
		global $wpdb;
		$V=[];$table='virtual_bible_hebrew';
		foreach($Verses as $v=>$verse)
			{
			if($v!='ref')
				{
				$V[$verse]=dbFetch1($table,array('book'=>$bid,'chapter'=>$chapter,'verse'=>$verse));
				}
			}
		return $V;
		}
    
	function getGreek($bid,$chapter,$Verses)
		{
		global $wpdb;
		$V=[];$table='virtual_bible_greek';
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

		


	function getEastonList()
		{
		global $wpdb;
		$Results=dbFetch('virtual_bible_eastons');
		$Easton=[];
		foreach($Results as $Result)
			{
			$id=$Result['id'];
			$ref=strtolower($Result['reference']);
			if(strlen($ref)>1)
				{
				$Easton[$id]=$ref;
				}
			}
		return $Easton;
		}

		
	


	}





/******************************************* 
 *   MISCELLENIOUS FUNCTIONS
*/

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
		return $Results['wpdb_get_results'][0];
		}
	else
		{
		return FALSE;
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
	if($Where)
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
?>