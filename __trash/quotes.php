<?php

elseif($search_type=='quotes')		// quotes
{
$__keyword='/('.str_ireplace(' ',')(<\/word> <.*?>)(',$keyword).')/i';
$text=preg_replace($__keyword,'<strong>$1</strong>$2<strong>$3</strong>',$text);
write_log("$bookname $chapter:$verse");
write_log($__keyword);
preg_match($__keyword,$text,$Matches);
write_log($Matches);
#					$Keywords=explode(' ',$keyword);
#					$replace_from_string=$Matches[0];
#					$replace_to_string=$replace_from_string;
#					foreach($Keywords as $k)
#						{
#						$replace_to_string=preg_replace("/\b($k)\b/i","<strong>$1</strong>",$replace_to_string);
#						}
#					write_log($replace_from_string.' | '.$replace_to_string);
#					$text=str_replace($replace_from_string,$replace_to_string,$text);
}


?>


<p class="fontcolor-medium" style="margin-top:0;margin-left:20px">
				Find verses containing specific sets of words<br>
				<em>Example: <a href="{$page_url}?keyword=&quot;Jesus answered&quot;">&quot;Jesus answered&quot;</a> <small><br>(PLEASE NOTE: Due to the complexity of the database table containing the scriptures, the plugin currently can only hand a two-word combination. I hope to improve this in later updates.)</small></em></p>
