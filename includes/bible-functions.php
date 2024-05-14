<?php


$query_text='set character_set_client=utf8';
$query=mysqli_query($_mysql, $query_text);
$query_text='set character_set_connection=utf8';
$query=mysqli_query($_mysql, $query_text);
$query_text='set character_set_results=utf8';
$query=mysqli_query($_mysql, $query_text);
$query_text='set character_set_server=utf8';
$query=mysqli_query($_mysql, $query_text);




	function getVerses($bid,$chapter,$Verses)
		{
		global $_mysql,$_debug;
		foreach($Verses as $verse)
			{
			if($verse!='ref')
				{
				/********************************
				 * 
				 * needs to return an array(vid,bid,chapter,verse,text,coding,coding_json,Coding)
				 *   get ref for verse
				 *   use vid... 
				 *     get text
				 *     get coding
				 *     get json coding
				 *     decode json
				 * 
				 */
				$vRef=dbFetch('kjv_ref',array('book'=>$bid,'chapter'=>$chapter,'verse'=>$verse));
				$Text=dbFetch('text_kjv',array('id'=>$vRef[0]['text']));
				$Json=dbFetch('av_coding_json',array('id'=>$vRef[0]['text']));
				$Verses[$verse]=dbFetch('av',array('book'=>$bid,'chapter'=>$chapter,'verse'=>$verse));
				$Verses[$verse]['ref']=$vRef;
				$Verses[$verse]['vid']=$vRef[0]['text'];
				$Verses[$verse]['bid']=$bid;
				$Verses[$verse]['chapter']=$chapter;
				$Verses[$verse]['verse']=$verse;
				$Verses[$verse]['text']=$Text[0]['text'];
				$Verses[$verse]['json']=$Json[0]['json'];
				$Verses[$verse]['Coding']=json_decode($Json[0]['json'],true);
				$vid=$Verses[$verse][0]['row'];
				$Coding=dbFetch1('av_coding_json',array('id'=>$vid));
				$Verses[$verse][0]['coding_json']=$Coding['json'];
				$c_json=$Coding['json'];
				$Cjson=json_decode($c_json,TRUE);
				$Verses[$verse][0]['Coding']=$Cjson;
				$text=$Verses[$verse][0]['text'];
				$Text=explode(' ',$text);
				foreach($Text as $w=>$word)
					{
					if(isset($Cjson[$w]['hidden']))
						{
						$word="* $word";
						}
					$Words[]=$word;
					}
				$text=implode(' ',$Words);
				$Verses[$verse][0]['text']=$text;
				$Words=[];
				}
			}
		#$_debug['Verses']=$Verses;
		return $Verses;
		}



	
	function getRefByKeyword($k)
		{
		global $_mysql;
		$vid='';$_V=[];$_V2=[];$vid2='';$bid2=0;$cid2=0;
		$_k=urldecode($k);
		$_k=str_ireplace('Song of Solomon','Song',$_k);
		$_k = preg_replace("/(\s){2,}/", ' ', $_k);   //remove extra spaces
		$_k=str_replace('.','',$_k);                  //remove periods
		$_k=str_replace('1st ', '1 ', $_k);           //make ordinals regular numbers
		$_k=str_replace('2nd ', '2 ', $_k);
		$_k=str_replace('3rd ', '3 ', $_k);		
		$_k=preg_replace('/^i /i', "1 ", $_k); 
		$_k=preg_replace('/^ii /i', "2 ", $_k);
		$_k=preg_replace('/^iii /i', "3 ", $_k);

		if(preg_match('/^[1-3][a-zA-Z]/',$_k))        //catch messed up beginning numbers
			{
			$_k=preg_replace('/^1/', '1 ',$_k);
			$_k=preg_replace('/^2/', '2 ',$_k);
			$_k=preg_replace('/^3/', '3 ',$_k);
			}
		$_k=ltrim($_k);$_k2='';
		if(strstr($_k,'-'))
			{
			$Keys=explode('-',$_k);
			if(strstr($Keys[1],':'))
				{
				$_k=$Keys[0].'-';
				$_k2=$Keys[1];
				}
			}
		if(strstr($_k,' '))
			{
			$Ref_keys=explode(' ',$_k);                  //explode by spaces
			if(!$Ref_keys[0])                            //if first element is empty, remove
				{
				$toss=array_shift($Ref_keys);
				}
			if(preg_match('/[1-3]/',$Ref_keys[0]))
				{
				$num=$Ref_keys[0];
				$toss=array_shift($Ref_keys);
				$Ref_keys[0]="$num ".$Ref_keys[0];
				}
			$Return['debug']['Ref_keys']=$Ref_keys;
			$BookData=getBookIdFromVagueTitle($Ref_keys[0]);
			$Return['debug']['BookData']=$BookData;
			if(isset($BookData['book']))
				{
				$Return['bookname']=$BookData['book'];
				$bid=$BookData['number'];
				$Return['bid']=$bid;
				}
			else
				{
				$bid=0;
				}
			$toss=array_shift($Ref_keys);
			$ref_key=implode($Ref_keys);
			$ref_elements=explode(':',$ref_key);
			$Return['debug']['ref_elements']=$ref_elements;
			$cid=$ref_elements[0];
			$Return['chapter']=$cid;
			}
		else
			{
			$Ref_keys=[];$bid=0;$cid=0;
			}
		if(strstr($_k2,' '))
			{
			$Ref_keys=explode(' ',$_k2);                  //explode by spaces
			if(!$Ref_keys[0])                            //if first element is empty, remove
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
				$bid=$BookData['id'];
				$Return['bid']=$bid;
				}
			else
				{
				$bid=0;
				}
			$toss=array_shift($Ref_keys);
			$ref_key2=implode($Ref_keys);
			$ref_elements2=explode(':',$ref_key2);
			$Return['debug']['ref_elements2']=$ref_elements2;
			$cid=$ref_elements2[0];
			$Return['chapter2']=$cid;
			}
		else
			{
#			$Ref_keys=[];$bid=0;$cid=0;
			}		
		if(isset($ref_elements[1]))
			{
			$verses=preg_replace('/[^0-9-,]+/','',$ref_elements[1]);
			$Return['tracking']['verses']=$verses;
			if(strstr($verses,','))
				{
				$vlist=explode(',',$verses);
				for($i=0;$i<count($vlist);$i++)
					{
					if(strstr($vlist[$i],'-'))
						{
						list($start,$finish)=explode('-', $vlist[$i]);
						for($i2=$start;$i2<=$finish;$i2++)
							{
							$_V[]=$i2;
							}
						}
					else
						{
						$_V[]=$vlist[$i];
						}      
					}
				}
			elseif(strstr($verses, '-'))
				{
				$vlist=explode('-',$verses);
				$Return['tracking']['vlist']=$vlist;
				$Return['tracking']['count-vlist']=count($vlist);
				if(count($vlist)==2)
					{
					list($start,$finish)=explode('-', $verses);
					if(!$finish and isset($BookData['book']))
						{
						$finish=getVersesInChapter($BookData['book'],$cid);
						$Return['tracking']['finish']=$finish;
						}
					for($i=$start;$i<=$finish;$i++)
						{
						$_V[]=$i;
						}
					$Return['tracking']['_V']=$_V;
					}
				}
			else
				{
				$_V[]=$verses;
				$vid=$verses;
				}
			}
		elseif(isset($BookData['book']))
			{
			$finish=getVersesInChapter($BookData['book'],$cid);
			for($i=1;$i<=$finish;$i++)
				{
				$_V[]=$i;
				}
			$_V['ref']="1-$finish";
			$verses="1-$finish";
			}
		if(isset($ref_elements2[1]))
			{	
			$verses2=preg_replace('/[^0-9-,]+/','',$ref_elements[1]);
			if(strstr($verses,','))
				{
				$vlist=explode(',',$verses2);
				for($i=0;$i<count($vlist);$i++)
					{
					if(strstr($vlist[$i],'-'))
						{
						list($start,$finish)=explode('-', $vlist[$i]);
						for($i2=$start;$i2<=$finish;$i2++)
							{
							$_V2[]=$i2;
							}
						}
					else
						{
						$_V2[]=$vlist[$i];
						}      
					}
				}
			elseif(strstr($verses2, '-'))
				{
				$vlist=explode('-',$verses2);
				if(count($vlist)==2)
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
		#$Return['debug']['rid']="$bid : $cid : $vid";
		if($vid)
			{
			$Rid=getVerseIDByRef($bid,$cid,$vid);
			if(isset($Rid['text'])){$Return['rid']=$Rid['text'];}
			}
		if($vid2)
			{
			$Rid2=getVerseIDByRef($bid2,$cid2,$vid2);
			if(isset($Rid2['text'])){$Return['rid2']=$Rid2['text'];}
			}
		$Return['verses']=$_V;
		$Return['verses2']=$_V2;
		if(isset($verses)){$Return['verses']['ref']=$verses;}
		$Return['verses2']['ref']=$verses2;		
		return $Return;
		}


	function getBookByKeyword($k)
		{
		global $_mysql;
	
		$_k=urldecode($k);
		$_k=preg_replace("/(\s){2,}/", ' ', $_k);   //remove extra spaces
		$_k=str_replace('.','',$_k);                  //remove periods
		$_k=str_replace('1st ', '1 ', $_k);           //make ordinals regular numbers
		$_k=str_replace('2nd ', '2 ', $_k);
		$_k=str_replace('3rd ', '3 ', $_k);
		$_k=preg_replace('/^i /i', "1 ", $_k); 
		$_k=preg_replace('/^ii /i', "2 ", $_k);
		$_k=preg_replace('/^iii /i', "3 ", $_k);
	
		if(preg_match('/^[1-3][a-zA-Z]/',$_k))        //catch messed up beginning numbers
			{
			$_k=preg_replace('/^1/', '1 ',$_k);
			$_k=preg_replace('/^2/', '2 ',$_k);
			$_k=preg_replace('/^3/', '3 ',$_k);
			}
		$Ref_keys=explode(' ',$_k);                  //explode by spaces
		if(!$Ref_keys[0])                            //if first element is epmty, remove
			{
			$toss=array_shift($Ref_keys);
			}
		if(preg_match('/^[1-9]/',$Ref_keys[0]))      //if first element is a number, comine with second to make the book key
			{
			$book_key=$Ref_keys[0].' '.$Ref_keys[1];
			}
		else
			{
			$book_key=$Ref_keys[0];
			}
				
				
			if(strtolower($book_key)=='jud'){$book_key='Judges';}
			if(strtolower($book_key)=='eph'){$book_key='Ephesians';}
			
		$_c=0;
		$queryText = sprintf("SELECT * FROM `books` WHERE `abbr` LIKE '%%%s%%' || `book` LIKE '%s' || `book` SOUNDS LIKE '%s'
								ORDER BY
								case when `abbr` LIKE '%s' then 4 else 0 end
							+ case when `book` LIKE '%s' then 3 else 0 end
							+ case when `book` SOUNDS LIKE '%s' then 1 else 0 end
								DESC LIMIT 1",
						mysql_real_escape_string($book_key),
						mysql_real_escape_string($book_key),
						mysql_real_escape_string($book_key),
						mysql_real_escape_string($book_key),
						mysql_real_escape_string($book_key),
						mysql_real_escape_string($book_key));
		$query=mysql_query($queryText, $_mysql);
		if(mysql_errno($_mysql)){echo ": " . mysql_error($_mysql) . "<br>$queryText\n<hr>";}
		if(mysql_num_rows($query))
			{
			$c=0;
			while ($dbRow = mysql_fetch_assoc($query)) 
			{
			$c++;
			if($dbRow['book']=='Psalms'){$dbRow['book']='Psalm';}
			$Return['id']=$dbRow['number'];
			$Return['book']=$dbRow['book'];
			$Return['chapters']=$dbRow['chapters'];
			}
			}
	
			
		$Return['queryText']=$queryText;
		return $Return;
		}


		
    
	function getVersesInChapter($book,$chapter,$debug=0)
		{
		global $_mysql;
		$bid=getBookIdFromTitle($book);
		$result=dbFetch('av',array('book'=>$bid,'chapter'=>$chapter));
		if($debug)
		  {
		  $result['book']=$book;
		  $result['chapter']=$chapter;
		  return $result;
		  }
		else
		  {
		  return count($result);
		  }
		}



		
    
    
    
	function getBookIdFromTitle($booktitle)
		{
		global $_mysql;
		if($booktitle=='Psalm'){$booktitle='Psalms';}
		$row=dbFetch1('books',array('book'=>$booktitle),'number');
		if(isset($row['number']))
			{
			return $row['number'];
			}
		else
			{
			return '';
			}
		  
		}


		
    
    function getBookIdFromVagueTitle($title)
		{
		global $_mysql;
		trim($title);
		$title=str_replace("\n","",$title);
		$title=str_replace("\r","",$title);
			if(strtolower($title=='eph')){$title="Ephesians";}
		$queryText = sprintf("SELECT * FROM `books` WHERE `book` LIKE '%s' OR `abbr` LIKE '%%%s%%' LIMIT 1;",
					 mysql_real_escape_string($title),
					 mysql_real_escape_string($title),
					 mysql_real_escape_string($title));
		$query=mysql_query($queryText, $_mysql);
		if(mysql_errno($_mysql)){echo ": " . mysql_error($_mysql) . "<br>\n$queryText<hr>";}
		$result = mysql_fetch_assoc($query);
		$result['querytext']=$queryText;
		return $result;
		}




    
    
	function getPrintR($array,$raw=0)
		{
		//hold on to the output
		ob_start();
		print_r($array);
		//store the output in a string
		$out =ob_get_contents();
		//delete the output, because we only wanted it in the string
		ob_clean();
		if($raw)
			{
			return $out;
			}
		else
			{
			return "<pre style=\"margin-top:0px\">$out</pre>";
			}
		}



		
	
	if($phpver>6.9)
		{
		function connect2db()
			{
			global $_server,$_debug;
			$_mysql = mysqli_connect('localhost','myopenbible_user','gTa(H.tnvP)m','myopenbible_data');
			if(mysqli_errno($_mysql)){$_debug['mysql_error'][]= mysqli_error($_mysql);}
			mysqli_set_charset( $_mysql,'utf8mb4');
			return $_mysql;
			}

		function mysql_query($querytext,$db)
			{
			global $_mysql;
			return mysqli_query($_mysql,$querytext);
			}


		function mysql_real_escape_string($string)
			{
			global $_mysql;
			return mysqli_real_escape_string($_mysql,$string);
			}

		function mysql_errno($_mysql)
			{
			global $_mysql;
			return mysqli_errno($_mysql);
			}

		function mysql_error($_mysql)
			{
			global $_mysql;
			return mysqli_error($_mysql);
			}

		function mysql_num_rows($query)
			{
			global $_mysql;
			return mysqli_num_rows($query);
			}

		function mysql_fetch_assoc($query)
			{
			global $_mysql;
			return mysqli_fetch_assoc($query);
			}

		function mysql_result($res,$row=0,$col=0)
			{
			global $_mysql;
			$numrows = mysqli_num_rows($res); 
			if ($numrows && $row <= ($numrows-1) && $row >=0)
				{
				mysqli_data_seek($res,$row);
				$resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
				if (isset($resrow[$col]))
					{
					return $resrow[$col];
					}
				}
			return false;
			}

		function mysql_select_db($db_name)
			{
			global $_mysql;
			return mysqli_select_db($_mysql,$db_name);
			}
		}
	else
		{
		function connect2db()
			{
			$_mysql=mysql_connect('localhost', 'myopenbible_user', 'gTa(H.tnvP)m');
			mysql_select_db('myopenbible_data');
			if(mysql_errno($_mysql)){$_debug['mysql_error'][]= mysql_error($_mysql);}
			return $_mysql;
			}
		}


	function getVerseIDByRef($bid,$cid,$vid)
		{
		$tid=dbFetch1('av',array('book'=>$bid,'chapter'=>$cid,'verse'=>$vid),'row');
		return $tid;
		}



		


		
	function dbFetch1($table,$Where='',$cell='*')
		{
		global $_mysql,$memcached_installed,$memCache;
		$result=[];
		if(!$cell){$cell='*';}
		if($Where)
		  {
		  foreach($Where as $column => $criteria)
			{
			$criteria=str_replace("'","\'",$criteria);
			$WHere[]="`$column`='$criteria'";
			}
		  $where='WHERE '.implode(' AND ',$WHere);
		  }
		$queryText = sprintf("SELECT $cell FROM `$table` $where LIMIT 1");
	
		if($memcached_installed)
		  {
		  $result=$memCache->get($queryText);
		  }
		if(!$result)
		  {
		  $query=mysql_query($queryText, $_mysql);
		  if(mysql_errno($_mysql)){echo ": " . mysql_error($_mysql) . "\n<hr>$queryText";}
		  $result = mysql_fetch_assoc($query);
		  if($memcached_installed)
			{
			$memCache->set($queryText, $result, 0, 30);
			}
		  }
		$result['queryText']=$queryText;
		mysqli_free_result($query);
		return $result;
		}


		

	function dbFetch($table,$Where='',$cell='*',$order='',$debug=0)
		{
		global $_mysql,$memcached_installed,$memCache;
		$result=[];
		if(!$cell){$cell='*';}
		if(isset($Where) and is_array($Where))
			{
			foreach($Where as $column => $criteria)
				{
				$criteria=str_replace("'","\'",$criteria);
				$WHere[]="`$column`='$criteria'";
				}
			$where='WHERE '.implode(' AND ',$WHere);
			}
		else
			{
			$where='';
			}
		$queryText = sprintf("SELECT $cell FROM `$table` $where $order");
	
		if($memcached_installed)
			{
			$result=$memCache->get($queryText);
			}
		if(!$result)
			{
			$query=mysql_query($queryText, $_mysql);
			if(mysql_errno($_mysql)){echo ": " . mysql_error($_mysql) . "\n<hr>$queryText";}
			if(mysql_num_rows($query))
					{
					while ($dbRow = mysql_fetch_assoc($query)) 
						{
						$result[]=$dbRow;
						}
					}
			if($memcached_installed)
					{
					$memCache->set($queryText, $result, 0, 30);
					}
			}
		if(isset($result[0]) and !is_array($result[0])){$T=$result;$result=[];$result[0]=$T;}
		if($debug){$result['queryText']=$queryText;}
		mysqli_free_result($query);
		return $result;
		}    



		

	function capFilter($text,$p=0)
		{
		if($p==1)
			{
			$text=str_replace('THE LORD OUR RIGHTEOUSNESS', '!*The Lord Our Righteousness*!', $text);
			}
		else
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
			}
		return $text;
		}


	$ScopeKey[0]='';
	$ScopeKey[1]='&& `av`.`book` < "40" ';
	$ScopeKey[2]='&& `av`.`book` > "39" ';
	$ScopeKey[3]='&& `av`.`book` < "6"';
	$ScopeKey[4]='&& `av`.`book` > "5" && `av`.`book` < "18" ';
	$ScopeKey[5]='&& `av`.`book` > "17" && `av`.`book` < "23" ';
	$ScopeKey[6]='&& `av`.`book` > "22" && `av`.`book` < "28" ';
	$ScopeKey[7]='&& `av`.`book` > "27" && `av`.`book` < "40" ';
	$ScopeKey[8]='&& `av`.`book` > "39" && `av`.`book` < "44" ';
	$ScopeKey[9]='&& `av`.`book` > "44" && `av`.`book` < "59" ';
	$ScopeKey[10]='&& `av`.`book` > "58" && `av`.`book` < "66" ';

	

	function getLexVerseCountByKeyword($keyword,$scope=0)
		{
		global $_mysql,$_debug,$ScopeKey;
		$search_key='';
		if(!$scope){$scope=0;}
		$kw=$keyword;
		$kw=str_replace('#','',$kw);
		$kw=str_replace('~','',$kw);
		$search_key="`lex_words`.`strong_num` = '".$kw."'";
		if($scope)
			{
			if(!isset($ScopeKey[$scope]))
				{
				#$book=str_replace('-',' ',$scope);
				#$bid=getBookIdFromTitle($book);
				$bid=$scope-10;
				if($bid)
					{
					$ScopeKey[$scope]="&& `lex_words`.`book` = '$bid'";
					}
				}
			$search_key.=' '.$ScopeKey[$scope];
			}
		$queryText = sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM `lex_words` WHERE $search_key");
		$query=mysql_query($queryText, $_mysql);
		$_debug['getLexVerseCountByKeyword - querytext']=$queryText;
		
		$total_records = mysql_result(mysql_query("SELECT FOUND_ROWS()",$_mysql),0,0);
		if(mysql_errno($_mysql)){echo ": " . mysql_error($_mysql) . "\n<br>$queryText<hr>";}
		return $total_records;
		}
		
		
	function getLexVersesByKeyword($keyword,$start,$scope=0)
		{
		global $_mysql,$_debug,$ScopeKey;
		$search_key='';$Verses=[];
		if(!$scope){$scope=0;}
		$kw=$keyword;
		$kw=str_replace('#','',$kw);
		$kw=str_replace('~','',$kw);
		$search_key="`lex_words`.`strong_num` = '".$kw."'";
		if($scope)
			{
			if(!$ScopeKey[$scope])
				{
				$book=str_replace('-',' ',$scope);
				$bid=getBookIdFromTitle($book);
				if($bid)
					{
					$ScopeKey[$scope]="&& `kjv_ref`.`book` = '$bid'";
					}
				}
			$search_key.=' '.$ScopeKey[$scope];
			}
		$queryText = sprintf("SELECT `lex_words`.`book`,`lex_words`.`chapter`,`lex_words`.`verse`,`lex_words`.`word_num`,`lex_words`.`word` FROM `lex_words` 
		WHERE $search_key LIMIT $start, 20");
		$query=mysql_query($queryText, $_mysql);
		if(mysql_errno($_mysql)){echo ": " . mysql_error($_mysql) . "\n<br>$queryText<hr>";}
		if(mysql_num_rows($query))
			{
			while ($dbRow = mysql_fetch_assoc($query)) 
				{
				$bid=$dbRow['book'];
				$verse=$dbRow['verse'];
				$chapter=$dbRow['chapter'];
				$Vid=dbFetch1('kjv_ref',array('book'=>$bid,'chapter'=>$chapter,'verse'=>$verse));
				$id=$Vid['id'];
				$text=dbFetch1('kjv_text',array('id'=>$id));
				$dbRow['text']=$text['text'];
				$Result[]=$dbRow;
				}
			}
		if(!isset($Result)){$Result='';}
		$_debug['lexVerses']=$Result;
		return $Result;
		}



	function getVerseCountByKeyword($keyword,$scope=0)
		{
    	global $_mysql,$_debug,$ScopeKey;
		if(!$scope){$scope=0;}
		$keyword=strtolower($keyword);
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
				#$SearchKey[]="`av`.`text` REGEXP '[[:<:]]".$kw."[[:>:]]'";
				$SearchKey[]="`av`.`text` REGEXP '\\\\b".$kw."\\\\b'";
				}
			$search_key=implode(' AND ',$SearchKey);
			}
		else
			{
			$keyword=str_replace('_',' ',$keyword);
			$keyword=str_replace('*','[a-zA-Z]*',$keyword);
			#$search_key="`av`.`text` REGEXP '[[:<:]]".$keyword."[[:>:]]'";
			$search_key="`av`.`text` REGEXP \"\\\\b".$keyword."\\\\b\"";
			}
		if($scope)
			{
			if(!isset($ScopeKey[$scope]))
				{
				#$book=str_replace('-',' ',$scope);
				#$bid=getBookIdFromTitle($book);
				$bid=$scope-10;
				if($bid)
					{
					$ScopeKey[$scope]="&& `av`.`book` = '$bid'";
					}
				}
			$search_key.=' '.$ScopeKey[$scope];
			}
		#$queryText = sprintf("SELECT SQL_CALC_FOUND_ROWS * FROM `av` WHERE $search_key");
		$queryText = sprintf("SELECT * FROM `av` WHERE $search_key");
		$_debug['getVerseCountByKeyword querytext']=$queryText;
		$query=mysql_query($queryText, $_mysql);
		
		$total_records = mysql_result(mysql_query("SELECT FOUND_ROWS()",$_mysql),0,0);
		$_debug['getVerseCountByKeyword found_rows']=$total_records;
		if(mysql_errno($_mysql)){echo ": " . mysql_error($_mysql) . "\n<br>$queryText<hr>";}
		return $total_records;
		}
		
		
	function getVersesByKeyword($keyword,$start,$scope=0)
		{
    	global $_mysql,$_debug,$ScopeKey;
		$keyword=strtolower($keyword);
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
				#$SearchKey[]="`av`.`text` REGEXP '[[:<:]]".$kw."[[:>:]]'";
				$SearchKey[]="`av`.`text` REGEXP \"\\\\b".$kw."\\\\b\"";
				}
			$search_key=implode(' AND ',$SearchKey);
			}
		else
			{
			$keyword=str_replace('_',' ',$keyword);
			$keyword=str_replace('*','[a-zA-Z]*',$keyword);
			#$search_key="`av`.`text` REGEXP '[[:<:]]".$keyword."[[:>:]]'";
			$search_key="`av`.`text` REGEXP '\\\\b".$keyword."\\\\b'";
			}
		if($scope)
			{
			if(!$ScopeKey[$scope])
				{
				$book=str_replace('-',' ',$scope);
				$bid=getBookIdFromTitle($book);
				if($bid)
					{
					$ScopeKey[$scope]="&& `kjv_ref`.`book` = '$bid'";
					}
				}
			$search_key.=' '.$ScopeKey[$scope];
			}
    	$queryText = sprintf("SELECT `av`.`text`,`av`.`row`,`av`.`book`,`av`.`chapter`,
								 `av`.`verse` FROM `av` 
        WHERE $search_key LIMIT $start, 20");
    	$query=mysql_query($queryText, $_mysql);
    	if(mysql_errno($_mysql)){echo ": " . mysql_error($_mysql) . "\n<br>$queryText<hr>";}
		if(mysql_num_rows($query))
			{
			while ($dbRow = mysql_fetch_assoc($query)) 
				{
				$Result[]=$dbRow;
				}
			}
		if(!isset($Result)){$Result='';}
		return $Result;
		}
		
		
	function getVersesByKeyword2($keyword,$start,$scope=0)
		{
		global $_mysql,$_debug,$ScopeKey;
		$keyword=strtolower($keyword);
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
				$SearchKey[]="`text_kjv`.`text` REGEXP '[[:<:]]".$kw."[[:>:]]'";
				}
			$search_key=implode(' AND ',$SearchKey);
			}
		else
			{
			$keyword=str_replace('_',' ',$keyword);
			$keyword=str_replace('*','[a-zA-Z]*',$keyword);
			$search_key="`text_kjv`.`text` REGEXP '[[:<:]]".$keyword."[[:>:]]'";
			}
		if($scope)
			{
			if(!$ScopeKey[$scope])
				{
				$book=str_replace('-',' ',$scope);
				$bid=getBookIdFromTitle($book);
				if($bid)
					{
					$ScopeKey[$scope]="&& `kjv_ref`.`book` = '$bid'";
					}
				}
			$search_key.=' '.$ScopeKey[$scope];
			}
		$query_text = sprintf("SELECT `text_kjv`.`text`,`kjv_ref`.`id`,`kjv_ref`.`book`,`kjv_ref`.`chapter`,`kjv_ref`.`verse` FROM `text_kjv` 
        JOIN `kjv_ref` ON `kjv_ref`.`id`=`text_kjv`.`id` 
		WHERE $search_key");
		$query=mysqli_query($_mysql, $query_text);
		if(mysqli_errno($_mysql)){echo ": " . mysqli_error($_mysql) . "\n<br>$query_text<hr>";}
		if(mysqli_num_rows($query))
			{
			while ($dbRow = mysql_fetch_assoc($query)) 
				{
				$Result[]=$dbRow;
				}
			}
		if(!isset($Result)){$Result='';}
		return $Result;
		}


		
	function getBookTitleFromId($bookid)
		{
		global $_mysql;
		if($bookid)
			{
			$row=dbFetch1('books',array('number'=>$bookid),'book');
			return $row['book'];
			}
		else
			{
			return '';
			}      
		}


		  
    
    
	function getXref($bid,$chapter)
		{
		global $_mysql;
		$Result=dbFetch('xref_holman', array('book'=>$bid,'chapter'=>$chapter),'*');
		return $Result;    
		}
    
	function getChaptersInBook($book)
		{
		global $_mysql;
		if($book)
			{
			$result=dbFetch1('kjv_books',array('book'=>$book));
			return $result['chapters'];  
			}
		else
			{
			return '';
			}
		}


	
	function getPreviousChapter($booktitle,$chapter)
		{
		global $_mysql;
		if($booktitle=='Psalm'){$booktitle='Psalms';}
		if($booktitle)
			{
			if($chapter>1)
				{
				$new_chapter=$chapter-1;
				$new_book=$booktitle;
				$newbookid=getBookIdFromTitle($booktitle);
				$newbooktitle=getBookTitleFromId($newbookid);
				}
			else   
				{
				$newbookid=getBookIdFromTitle($booktitle)-1;
				if($newbookid==0){$newbookid=66;}
				$newbooktitle=getBookTitleFromId($newbookid);
				$new_chapter=getChaptersInBook($newbooktitle);
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
			$last_chapter=getChaptersInBook($booktitle);
			if($chapter<$last_chapter)
				{
				$new_chapter=$chapter+1;
				$new_book=$booktitle;
				$newbookid=getBookIdFromTitle($booktitle);
				$newbooktitle=getBookTitleFromId($newbookid);
				}
			else   
				{
				$newbookid=getBookIdFromTitle($booktitle)+1;
				if($newbookid==67){$newbookid=1;}
				$newbooktitle=getBookTitleFromId($newbookid);
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


	function getAbbrFromBookID($bid)
		{
		global $_mysql;
		$Return=dbFetch1('kjv_books',Array('id'=>$bid),'`kjav_abr`');
		return $Return['kjav_abr'];
		}
	

		
	function isRef($keyword)
		{
		global $_mysql;
		$Return['keyword']=$keyword;
		if(substr($keyword,0,1)=='#' or substr($keyword,0,1)=='~')
			{
			$_kw=str_replace('#','',$keyword);
			$_kw=str_replace('~','',$_kw);
			if(ctype_digit($_kw))
				{
				$Return['type']='strongs';				
				}
			}
		$Ref=getRefByKeyword($keyword);
		$bData=getBookIdFromVagueTitle($keyword);
		if(isset($Ref['verses'][1]))
			{
			$Return['type']='passage';
			}
		elseif(isset($Ref['verses'][0]))
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
				$chlength=getVersesInChapter($Ref['bookname'],$Ref['chapter']);
				$Return['chapter-length']=$chlength;
				}
			elseif(!isset($Return['type']))
				{
				$Return['type']='invalid';
				}
			}
		elseif(isset($bData['id']))
			{
			$Return['type']='book';
			}
		elseif(!isset($Return['type']))
			{
			$Return['type']='invalid';
			}
		$Return['getRefByKeyword']=$Ref;
		$Return['bData']=$bData;
		return $Return;
		}



	function isInDic($word)
		{
		global $_mysql,$_debug;
		$word=preg_replace("/(?![.=$'%-])\p{P}/u","",$word);
		$result=dbFetch1('easton_dic', array('reference'=>$word));
		if(isset($result['id']))
			{
			return TRUE;
			}
		else
			{
			return FALSE;
			}
		}





	function lexInfo($vid,$wid)
		{
		$Lex=dbFetch1('av_coding_json',array('id'=>$vid));
		$json=$Lex['json'];
		$Data=json_decode($json,TRUE);
		if(isset($Data[$wid]['strongs']))
			{
			return $Data[$wid]['strongs'];
			}
		else
			{
			return 0;
			}
		}


	function writeFile($path,$name,$data)
		{		
		$fp = fopen("$path/$name", "w");
		fwrite ($fp, $data);
		fclose ($fp);
		if(file_exists("$path/$name"))
			{
			return TRUE;
			}
		else 
			{
			return FALSE;
			}
		}

?>