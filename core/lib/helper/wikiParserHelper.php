<?php
/**
*qdPM
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@qdPM.net so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade qdPM to newer
* versions in the future. If you wish to customize qdPM for your
* needs please refer to http://www.qdPM.net for more information.
*
* @copyright  Copyright (c) 2009  Sergey Kharchishin and Kym Romanets (http://www.qdpm.net)
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php  
  function wikiParser($html, $projectId)
  {
    wiki_callback_data_store($projectId);
             
    $html = "\n" . $html . "\n";
    
    $html = wiki_convert_tables($html, $projectId);
            
  	$html = str_replace('&ndash;','-',$html);
  	$html = str_replace('&quot;','"',$html);
  	$html = preg_replace('/\&amp;(nbsp);/','&${1};',$html);
  	
  	$html = preg_replace_callback('/<nowiki>([^`]*?)<\/nowiki>/', 'wiki_helper_nowiki_parser', $html);
    
  	//formatting
  	// bold
  	$html = preg_replace('/\'\'\'([^\n\']+)\'\'\'/','<strong>${1}</strong>',$html);
  	
    // emphasized
  	$html = preg_replace('/\'\'([^\'\n]+)\'\'?/','<em>${1}</em>',$html);
  	
    //interwiki links  	  	
    $html = preg_replace_callback('/\[\[([^\|\n\]]+)([\:]([^\]]+))?\]\]/','wiki_helper_internal_links',$html);
                   	  	
  	//links with text  
  	$html = preg_replace('/\[([^\[\]\|\n\' ]+)[\| ]([^\]\']+)\]/','<a href="${1}" target="_blank">${2}</a>',$html);
  	$html = preg_replace_callback('/\[([^\|\n\]]+)([\:]([^\]]+))?\]/','wiki_helper_external_links',$html);
  	
  	
  	// allowed tags
  	$html = preg_replace('/&lt;(\/?)(small|sup|sub|u)&gt;/','<${1}${2}>',$html);
  	
  	$html = preg_replace('/\n*&lt;br *\/?&gt;\n*/',"\n",$html);
  	$html = preg_replace('/&lt;(\/?)(math|pre|code|nowiki)&gt;/','<${1}pre>',$html);
  	$html = preg_replace('/&lt;!--/','<!--',$html);
  	$html = preg_replace('/--&gt;/',' -->',$html);
     	
  	//lists
  	
    /*  
  	$html = preg_replace(
  		'/(\n[ ]*[^#* ][^\n]*)\n(([ ]*[*]([^\n]*)\n)+)/',
  		'${1}<ul>'."\n".'${2}'.'</ul>'."\n",
  		$html
  	);
  	$html = preg_replace(
  		'/(\n[ ]*[^#* ][^\n]*)\n(([ ]*[#]([^\n]*)\n)+)/',
  		'${1}<ol>'."\n".'${2}'.'</ol>'."\n",
  		$html
  	);
  	*/
  	
  	
  	
  	$html = wiki_helper_list_prsers($html);
  	
  	$html = preg_replace('/\n[ ]*[\*#]+([^\n]*)/','<li>${1}</li>',$html);
  	
  	$html = preg_replace('/----/','<hr />',$html);
  	
  	$html = wiki_helper_lines_prsers($html); 
  
  	$html = '<div id="page_contents"></div>' . $html;
  	  	       
  	return $html;
  }
  
  function wikiParserSimple($html, $projectId)
  {
    wiki_callback_data_store($projectId);
             
    $html = "\n" . $html . "\n";
        
            
  	$html = str_replace('&ndash;','-',$html);
  	$html = str_replace('&quot;','"',$html);
  	$html = preg_replace('/\&amp;(nbsp);/','&${1};',$html);
    
    $html = preg_replace_callback('/<nowiki>([^`]*?)<\/nowiki>/', 'wiki_helper_nowiki_parser', $html);
    
  	//formatting
  	// bold
  	$html = preg_replace('/\'\'\'([^\n\']+)\'\'\'/','<strong>${1}</strong>',$html);
  	
    // emphasized
  	$html = preg_replace('/\'\'([^\'\n]+)\'\'?/','<em>${1}</em>',$html);
  	
    //interwiki links  	  	
    $html = preg_replace_callback('/\[\[([^\|\n\]]+)([\:]([^\]]+))?\]\]/','wiki_helper_internal_links',$html);
                   	  	
  	//links with text  
  	$html = preg_replace('/\[([^\[\]\|\n\' ]+)[\| ]([^\]\']+)\]/','<a href="${1}" target="_blank">${2}</a>',$html);
  	$html = preg_replace_callback('/\[([^\|\n\]]+)([\:]([^\]]+))?\]/','wiki_helper_external_links',$html);
  	
  	
  	// allowed tags
  	$html = preg_replace('/&lt;(\/?)(small|sup|sub|u)&gt;/','<${1}${2}>',$html);
  	
  	$html = preg_replace('/\n*&lt;br *\/?&gt;\n*/',"\n",$html);
  	$html = preg_replace('/&lt;(\/?)(math|pre|code|nowiki)&gt;/','<${1}pre>',$html);
  	$html = preg_replace('/&lt;!--/','<!--',$html);
  	$html = preg_replace('/--&gt;/',' -->',$html);
      	  	       
  	return $html;
  }

  function wiki_helper_nowiki_parser($matches)
  {
    $r = array('#'=>'&#35;',
               '\''=>'&#39;',
               '"'=>'&quot;',
               '='=>'&#61;',
               '*'=>'&#42;',
               '{'=>'&#123;',
               '}'=>'&#125;',
               '['=>'&#91;',
               ']'=>'&#93;',
               '-'=>'&#45;');
               
    foreach($r as $k=>$v)
    {
      $matches[1] = str_replace($k,$v,$matches[1]);
    }
    
    return $matches[1];
  }
  
  function wiki_helper_list_prsers($html)
  {
    $params = array();
    $output = "";
    $lines = explode("\n",$html);
    $list_level = 0;
    $level_length = 0;
    
    for($i=0; $i<sizeof($lines);$i++)
    {
      if (preg_match("/^([\*\#]+)(.*?)$/i",$lines[$i],$matches)) 
      {
				 //print_r($matches);
				 $level_length = strlen($matches[1]);
				 $list_type = $matches[1][strlen($matches[1])-1];				 
				 $list_type = ($list_type=='#' ? 'ol':'ul');
			}
			else
			{        
        $level_length =0;        
      }
			
			if($list_level!=$level_length)
			{
        
        
        if($level_length>$list_level)
        {
          $output .= str_repeat('<' . $list_type . '>',$level_length-$list_level);
        }
        
        if($level_length<$list_level)
        {
          $output .= str_repeat('</' . $list_type . '>',$list_level-$level_length);
        }
                
        $list_level = $level_length;
      }
      
      if($list_level>0)
      {  
        $output .= '<li>' . substr($lines[$i],$list_level) . "</li>";
      }
      else
      {
        $output .= $lines[$i] . "\n";
      } 
    }
    
    return $output;
  }
  
  function wiki_callback_data_store($params = null) 
  { 
    static $data; 
    if ($params) { 
        $data = $params; 
    } 
    return $data; 
  } 
  
  function wiki_helper_lines_prsers($text)
  {
    $params = array();
    $output = "";
    $lines = explode("\n",$text);
    
    foreach ($lines as $line) {
			$v = wiki_helper_parse_line($line, $params);
			$output .= str_replace(array("\n\r","\n","\r"),'',$v['line']) . "<br>";
			$params =$v['params']; 
		}
    
    return $output;
  }
  
  function wiki_helper_parse_line($line, $params)
  {
    if(isset($line{0}))
    if($line{0}==' ') 
    {
      $line = htmlspecialchars($line);
      
      if(!isset($params['pre']))
      {
        $params['pre'] = true;
        
        $line = "<pre>" . $line;
      }      
            
    }
    elseif(isset($params['pre']))
    {
      $line =  "</pre>" . $line ;
      unset($params['pre']);
    }
    
    
    // headings
    $line = "\n" . $line . "\n";
  	for($i=7;$i>0;$i--){
  		$line = preg_replace(
  			'/\n+[=]{'.$i.'}([^=]+)[=]{'.$i.'}\n*/',
  			'<a name="' . rand(11111111,99999999) . '" class="contents_anchor" id="${1}" rel="' . $i . '"></a><h'.$i.'>${1}</h'.$i.'>',
  			$line
  		);
  	}
  	  
    return array('line'=>$line,'params'=>$params);
  }
  
  function wiki_helper_internal_links($matches)
  { 
    
    $projectId = wiki_callback_data_store();
         
  	$target = $matches[1];
  	$text = empty($matches[2])?$matches[1]:$matches[2];
  	  	
    return '<a href="' . url_for('wiki/view?name=' . htmlentities($target) . ($projectId>0 ? '&projectId=' . $projectId:'')) . '">'.$text.'</a>';    
  }
  
  
  function wiki_helper_external_links($matches)
  {
  	$target = $matches[1];
  	$text = empty($matches[2])?$matches[1]:$matches[2];
  	
    return '<a href="'.$target.'" target="_blank">'.$text.'</a>';
  }  
  
  function wiki_search_keywords_in_description($keywords, $description)
  {
    $keywords_list = explode(' ', $keywords);
    $description_parts = explode("\n",$description);
    $text = '';
    
    foreach($description_parts as $part)
    {
      $is_keyword = false;
      foreach($keywords_list as $keyword)
      {
        if(preg_match('/([[:punct:]]|[[:space:]])' . $keyword . '([[:punct:]]|[[:space:]])/i',' ' . $part . ' ')>0)
        {          
          $is_keyword = true;
          $part = str_ireplace($keyword,'<b>' . $keyword . '</b>', $part); 
        }
      }
      
      if($is_keyword)
      {
        $text .= '<div class="wiki_description_part">' . $part . '</div>';
      }
    }
    
    return $text;
  }
  
  
function wiki_convert_tables($text, $projectId){
	$lines = explode("\n",$text);
	$innertable = 0;
	$innertabledata = array();	
	foreach($lines as $line){
		//echo "<pre>".++$i.": ".htmlspecialchars($line)."</pre>";
		$line = str_replace("position:relative","",$line);
		$line = str_replace("position:absolute","",$line);
		if(substr($line,0,2) == '{|'){
			// inner table
			//echo "<p>beginning inner table #$innertable</p>";
			$innertable++;
		}
		
		if(!isset($innertabledata[$innertable]))
		{
      $innertabledata[$innertable]  = '';
    }
		
		$innertabledata[$innertable] .= $line . "\n";
		if($innertable){
			// we're inside
			if(substr($line,0,2) == '|}'){
				$innertableconverted = wiki_convert_table($innertabledata[$innertable], $projectId);
				$innertabledata[$innertable] = "";
				$innertable--;
				$innertabledata[$innertable] .= $innertableconverted."\n";
			}
		}
	}
	return $innertabledata[0];
}

function wiki_convert_table($intext, $projectId){
	$text = $intext;
	$lines = explode("\n",$text);
	$intable = false;
	
	//var_dump($lines);
	foreach($lines as $line)
  {
    if(!isset($tdopen)) $tdopen = false;
    if(!isset($thopen)) $thopen = false;
    if(!isset($rowopen)) $rowopen = false;
  
		$line = trim($line);
		if(substr($line,0,1) == '{'){
			//begin of the table
			$stuff = explode('| ',substr($line,1),2);
			$tableopen = true;
			$table = "<table class=\"wiki_content_table\">\n";
		} else if(substr($line,0,1) == '|'){
			// table related
			$line = substr($line,1);
			if(substr($line,0,1) == '-'){
				// row break
				if($thopen)
					$table .="</th>\n";
				if($tdopen)
					$table .="</td>\n";
				if($rowopen)
					$table .="\t</tr>\n";
				$table .= "\t<tr>\n";
				$rowopen = true;
				$tdopen = false;
				$thopen = false;
			}else if(substr($line,0,1) == '}'){
				// table end
				break;
			}else{
				// td
				$stuff = explode('| ',$line,2);
				
				
				
				if($tdopen)
					$table .="</td>\n";
				if(count($stuff)==1)
					$table .= "\t\t<td>".wikiParserSimple($stuff[0], $projectId);
				else
					$table .= "\t\t<td ".$stuff[0].">".
						wikiParserSimple($stuff[1], $projectId);
				$tdopen = true;
			}
		} else if(substr($line,0,1) == '!'){
			// th
			$stuff = explode('| ',substr($line,1),2);
			if($thopen)
				$table .="</th>\n";
			if(count($stuff)==1)
				$table .= "\t\t<th>".wikiParserSimple($stuff[0], $projectId);
			else
				$table .= "\t\t<th ".$stuff[0].">".
					wikiParserSimple($stuff[1], $projectId);
			$thopen = true;
		}else{
			// plain text
			$table .= wikiParserSimple($line, $projectId) ."\n";
		}
		//echo "<pre>".++$i.": ".htmlspecialchars($line)."</pre>";
		//echo "<p>Table so far: <pre>".htmlspecialchars($table)."</pre></p>";
	}
	if($thopen)
		$table .="</th>\n";
	if($tdopen)
		$table .="</td>\n";
	if($rowopen)
		$table .="\t</tr>\n";
	if($tableopen)
		$table .="</table>\n";
	//echo "<hr />";
	//echo "<p>Table at the end: <pre>".htmlspecialchars($table)."</pre></p>";
	//echo $table;
  
  $table = str_replace(array("\n","\t"),'',$table);
  	
	return $table;
}  
