<?php


add_shortcode('The-Virtual-Study-Bible','buildStudyBiblePage');

function buildStudyBiblePage()
	{
	global $wpdb;
	$plugin_url=str_replace('includes/','',plugin_dir_url(__FILE__));

	wp_register_style('vb-bootstrap4-css', plugins_url().'/the-virtual-study-bible/css/bootstrap4b.css');
	wp_enqueue_style('vb-bootstrap4-css');
	wp_register_style('vb-fonts-css', plugins_url().'/the-virtual-study-bible/css/fontawesome.css');
	wp_enqueue_style('vb-fonts-css');
	wp_register_style('vb-logofont-css', plugins_url().'/the-virtual-study-bible/css/logofont.css');
	wp_enqueue_style('vb-logofont-css');
	wp_register_style('vb-styles-css', plugins_url().'/the-virtual-study-bible/css/vb-styles.css');
	wp_enqueue_style('vb-styles-css');
	wp_register_style('vb-study-bible-css', plugins_url().'/the-virtual-study-bible/css/study-bible.css');
	wp_enqueue_style('vb-study-bible-css');
	
	wp_register_script('vb-jquery-js', plugins_url().'/the-virtual-study-bible/js/jquery-1.11.1.js');
	wp_enqueue_script('vb-jquery-js');
	wp_register_script('vb-bootstrap-js', plugins_url().'/the-virtual-study-bible/js/bootstrap.js');
	wp_enqueue_script('vb-bootstrap-js');

	$table_name = $wpdb->prefix . 'virtual_bible_books';
	$Books = $wpdb->get_results("SELECT * from $table_name;", ARRAY_A);
	$reference='';$trad_checked='checked';$par_checked='';$read_checked='';
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
			$book_list.="<a href=\"/study-bible?keyword=$abbr+1\">$book</a><br>";
			}
		if($n==15 or $n==32 or $n==48)
			{
			$book_list.="</div>";
			}
		}
	$book_list.="</div>";

	$virtual_bible_page = <<<EOD
	<form action="/study-bible" method="POST">
		<div class="col-md-12" style="position:relative;margin-right:auto;margin-left:auto;font-family: 'Montserrat';">

			<div class="bible-search-form col-md-9" style="float:left">
				<legend style="font-weight:bold;display:block">
					Search
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
				<div class="drop-down">
					<select id="scope" name="scope" style="border-bottom-left-radius:10px;border-bottom-right-radius:10px;width:100%;height:calc(1.5rem + 0.75rem + 2px);padding:0.375rem 0.75rem;font-size:15px;line-height:1.5;border:1px solid #ced4da;">
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
				</div>
			</div>
			<fieldset class="col-md-3 text-left form-group" style="flex-grow:1;font-size:13px;border:none">
				<legend style="margin-bottom:0;font-weight:bold;display:block">Passage Display Style</legend>
				<div class="form-check">
					<label class="form-check-label">
						<input type="radio" name="layout" value="trad" class="form-check-input" {$trad_checked}> Traditional
					</label>
				</div>
				<div class="form-check">
					<label class="form-check-label">
						<input type="radio" name="layout" value="par" class="form-check-input" {$par_checked}> Paragraph
					</label>
				</div>
				<div class="form-check">
					<label class="form-check-label">
						<input type="radio" name="layout" value="read" class="form-check-input" {$read_checked}> Reading 
					</label>
				</div>
			</fieldset>
		</div>
	</form>

	<ul class="nav nav-tabs" style="margin-top:40px;font-family: 'Montserrat';">
		<li class="active"><a data-toggle="tab" href="#how-to-search" style="text-decoration:none"><h4 style="margin:0">How to Search</h4></a></li>
		<li><a data-toggle="tab" href="#tools" style="text-decoration:none"><h4 style="margin:0">Tools</h4></a></li>
		<li><a data-toggle="tab" href="#books" style="text-decoration:none"><h4 style="margin:0">Books</h4></a></li>
	</ul>
	<div class="tab-content" style=";font-family: 'Montserrat';font-size:15px"> 
		<div id="how-to-search" class="tab-pane fadex in active">			
			<p class="fontcolor-medium">
				Find exact chapters and/or verses by typing the book, chapter, and/or verse in the search box.<br>
				<em>Examples: <a href="?keyword=John+3:16">John 3:16</a> <small>(for a single verse)</small> or <a href="?keyword=John+3">John 3</a> <small>(for the entire chapter)</small> or <a href="?keyword=John+3:14-18">John 3:14-18</a> <small>(for a select passage within a chapter)</small></em></p>
			<p class="fontcolor-medium">
				Find verses containing a specific word.<br>
				<em>Example: <a href="?keyword=love">love</a></em></p>
			<p class="fontcolor-medium">
				Find verses containing two or more words you want by typing them in, separated by a space.<br>
				<em>Example: <a href="?keyword=christ+mercy">Christ mercy</a></em></p>
			<p class="fontcolor-medium">
				Find verses containing an exact phrase by enclosing it in quotation marks.<br>
				<em>Example: <a href="?keyword=&#34;For+God+so+loved+the+world&#34;">&quot;for God so loved the world&quot;</a></em></p>
			<p class="fontcolor-medium">
				Find verses containing partial words by using the wildcard *.<br>
				<em>Example: <a href="?keyword=love*">love*</a> <small>(returns love, loves, loved, lovely, etc.)</small></em></p>
			<p class="fontcolor-medium">
				Use the scope option to limit your word search to a specific section of the Bible<br>
				<em>Example: <a href="?keyword=love&scope=2">love</a> <small>(returns only instances of the word &ldquo;love&rdquo; in the New Testament)</small></em><br>
			<small><em>Scope will be ignored for reference searches (i.e. John 3:16)</em></small></p>
			<p class="fontcolor-medium">
				<em>Warning: do not combine quotes and wild card.</em></p>
		</div>
		
		<div id="tools" class="tab-pane fade">
			<p class="fontcolor-medium">
				<i class="fas fa-list-ol" title="outline"></i> <b>Outline</b> A chapter outline is provided as well as a book outline excerpted from John MacArthur's introduction to the books.   </p>
			<p class="fontcolor-medium">
				<i class="fas fa-comment-alt" title="Commentaries"></i> <b>Commentaries</b> Commentaries for the chapter from 10 well-known theological sources are provided. </p>
			<p class="fontcolor-medium">
				<i class="fas fa-book" title="Dictionary"></i> <b>Dictionary</b> When the Dictionary tool is selected, words available from Easton's Dictionary of the Bible will be highlighted. Clicking the word will make the definition appear in the tools section.</p>
			<p class="fontcolor-medium">
				<i class="fas fa-language" title="Lexicons"></i> <b>Lexicons</b> When the Lexicon tool is selected, words keyed to Strong's Concordance will be highlighted. Clicking the word will make either the Strong's or the IPD lexicon definition appear in the tools section.</p>
			<p class="fontcolor-medium">
				<i class="fas fa-door-open" title="Introduction"></i> <b>Introduction</b> Book introductions to the selected book by eight, well-known theological sources are provided.</p>
			<p class="fontcolor-medium">
				<span style="font-family:'Times New Roman';font-weight:bold" title="Hebrew/Greek">&#1488;/&pi;</span> <b>Hebrew/Greek</b> The Greek or Hebrew version of the selected text.</p>
			<p class="fontcolor-medium">
			<i class="fas fa-search-plus" title="Filter"></i> <b>Filter</b> <i>(keyword search)</i> The filter provides a list of Bible sections to filter the word search and shows the results produced by each filter.</p>
		</div>
		<div id="books" class="tab-pane fade">
			<div class="row">
				{$book_list}
			</div>
		</div>
	</div>



	<div id="modal" class="modal" style="display:none;padding-top:50px;z-index:1000;position:fixed;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,0.4);font-family: 'Montserrat';">
		<div class="modal-content" style="border-radius:10px;border:1px solid:#555;width:600px;margin:auto;background-color:#fff;position:relative;padding:20px;outline:0;;font-family: 'Montserrat';box-shadow:1px 1px 30px rgba(0,0,0,0.5)">
			<span onclick="document.getElementById('modal').style.display='none'" style="cursor:pointer;position:absolute;top:0;right:10px;font-size:19px">&times;</span>
			<h4 style="clear:both;font-family: 'Montserrat';;margin-top:0;font-weight:bold">Books of the Bible</h4>
			<div class="row" style="font-size:13px;font-family: 'Montserrat';'">
				{$book_list}
			</div class="row">
		</div>
	</div>
	EOD;
	return $virtual_bible_page;
	}


?>