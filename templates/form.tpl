	<div class="row study-bible-form-row">
		<form action="{$page_url}" method="GET" class="study-bible-form">
			<div class="vb-form-container col-md-12" style="position:relative;margin-right:auto;margin-left:auto;font-family: 'Montserrat';">
				<div class="bible-search-form" style="float:left">
					<legend style="font-weight:bold;display:block;">
						<span style="font-family: 'Poppins', sans-serif">Search</span>
						<small style="font-weight:normal;color:#000;float:right;cursor:pointer;margin-right:10px"
							onclick="document.getElementById('vb-book-list-modal').style.display='block'" 
							onmouseover="this.style.color='#600'" onmouseout="this.style.color='#000'" >
							books
						</small>
					</legend>
					<div style="position:relative;display:flex;flex-wrap:wrap;align-items:stretch;width:100%">
						<input id="search-input-field" tabindex=0
							type="text" 
							class="reference" 
							value="{$reference}" 
							placeholder="Keyword or reference" 
							style="border-bottom-left-radius:0;border-top-left-radius:10px;height:calc(1.5rem + 1rem + 2px);padding:0rem 1rem;font-size:17px;line-height:1.5;flex:1 1 auto;width:1%;margin-bottom:0;font-weight:600;border:1px solid #ced4da" 
							name="keyword" 
							id="keyword"
							onkeyup="if(this.value.length>1)
								{
								var kw=this.value.replace(' ','+');							
								$.ajax
									(
										{
										type: 'GET',
										url: '{$guess_url}',
										data: {q:kw},
										success: function(data)
											{
											if(data)
												{
												$('#my-guesses-div').css('display','block');
												$('#guesses').html(data);
												}
											else
												{
												$('#guesses').html('');
												$('#my-guesses-div').css('display','none');
												}
											}
										}
									);
								}
							else
								{
								$('#my-guesses-div').css('display','none');
								}">
						<div class="input-group-append" style="margin-left:-1px;">
							<button type="submit" value="Search" class="btn btn-primary" style="border-bottom-right-radius:0;border-top-right-radius:10px;padding:0rem 1rem;line-height:1.5;align-self:stretch!important;position:relative;cursor:pointer;">Search</button>
						</div>
					</div>
					<div class="drop-down input-group">
						<select id="scope" name="scope" >
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
						<select id="version" name="version" >
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
					<div id="my-guesses-div">
						<div id="do-you-mean">Do you mean . . . </div>
						<div id="guesses" class="col-sm-8">
						</div>
						<div style="clear:both"></div>
					</div>
				</div>
				<div id="form-fieldsets" class="row col-lg-3 col-sm-12" style="float:left">
					<fieldset id="passage-style" class="col-lg-6 text-left form-group" >
						<legend style="font-weight:bold;display:block;font-family: 'Poppins', sans-serif;font-size:16px;white-space:nowrap">Passage Style</legend>
						{$styles}
					</fieldset>
					<fieldset id="toggle-strongs-fieldset" class="col-lg-6 text-left form-group" >
						<legend style="font-weight:bold;display:block;font-family: 'Poppins', sans-serif;font-size:16px">Link Keyed</legend>
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
					<div style="clear:both"></div>
				</div>
			</div>
		</form>
	</div>