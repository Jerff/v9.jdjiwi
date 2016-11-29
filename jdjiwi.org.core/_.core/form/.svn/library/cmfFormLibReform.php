<?php


function cmfReformFloatSelect(&$d, $t=true) {
	if(is_string($d)) {
		$d = preg_replace('#\s#mS', '', $d);
		$d = str_replace(	array(',', '-'),
								'.',
								$d);
		$d = $t1 ? str_replace(',', '.', (float)$d) : str_replace(',', '.', number_format((float)$d, 4));
	} elseif(is_null($d)) {
		$d = 0;
	} elseif(is_array($d)) {
		foreach($d as &$v) cmfReformFloatSelect($v);
	} else {
		$d = number_format((float)$d, 4);
	}
	return $d;
}



function cmfReformInt($d, $i, $u) {
	if($i) {
	    $d = (int)preg_replace('#([^0-9\-])#mS', '', $d);
	} else {
	    if(!empty($d))
	    $d = number_format((int)$d, 0, '', ' ');
	}
	return $d;
}

function cmfReformFloat($d, $i, $u) {
	if($i) {
		$d = preg_replace('#([^0-9\,\.])#mS', '', $d);
		$d = str_replace(	array(',', '-'),
							'.',
							$d);
		$d = str_replace(',', '.', (float)$d);
	}
	return $d;
}



function cmfReformStrLen(&$s, $m) {
	if($l = strlen($s)) {
		$s = cString::subContent($s, 0, $m-1);
	}
	return $s;
}


function cmfReformSerialize($v, $in) {
	if($in==='serialize') return $v ? serialize($v) : '';
	if(empty($v)) return null;
	return unserialize($v);
}


function cmfReformSerializeInt($v, $in) {
	if(empty($v)) return '';
	if($in==='serialize') {
		return сSConvert::serialize(cConvert::toInt($v));
	}
	return unserialize($v);
}

function cmfReformArrayPath($v, $in) {
	if($in==='serialize') return сSConvert::arrayToPath($v);
	if(empty($v)) return null;
	return cConvert::pathToArray($v);
}


function cmfReformUri($value, $opt=false, $opt2=false) {
	if($opt===false) return $value;
	$value = cConvert::translate(trim($value));
	$value = preg_replace(array('#\s#mS', '#[^a-z0-9\-_\.\=]#mSi', '#[_]{2,}#mSi'), '-', $value);
	if($opt) $value = substr($value, 0, $opt-1);
	return $value;
}

function cmfFilterPhoneCod($d, $i, $u) {
	if($i) {
        $d = 7 . str_replace(array('+7(', ')'), '', $d);
	} else {
	    if(!empty($d))
	    $d = '+'. substr($d, 0, 1) .'('. substr($d, 1) .')';
	}
	return $d;
}
function cmfFilterPhonePostPrefix($d, $i, $u) {
	if($i) {
	    $d = preg_replace('#([^0-9])#mS', '', $d);
	} else {
        if(!empty($d))
	    $d = substr($d, 0, 3) .'-'. substr($d, 3, 2) .'-'. substr($d, 5, 2);
	}
	return $d;
}


function cmfReformDate($date, $input_format=false, $output_format=false) {
//$default_iso_format='{Y}-{m}-{d} {H}:{M}:{S}';
//$default_rfc_format='{d}-{m}-{Y} {H}:{M}:{S}';
	if($input_format===false) $input_format='{Н}/{m}/{d} {H}:{M}:{S}';
	if($output_format===false) $output_format='{d}/{m}/{Y} {H}:{M}:{S}';
/*
$ date --iso-8601=ns  # a GNU extension
2004-02-29T16:21:42,692722128-0800
$ date --rfc-2822  # a GNU extension
Sun, 29 Feb 2004 16:21:42 -0800
*/
	$date = str_replace('.', '-', $date);
	if(is_array($date)) {
		foreach($date as $value) $this->reform_data($value, $input_format, $output_format);
		return null;
	}

	$format=array();
	$format['y']=array('format'=>'%y','category'=>'Y'); //год без столетия
	$format['Y']=array('format'=>'%Y','category'=>'Y'); //год, включая столетие

	$format['B']=array('format'=>'%B','category'=>'m'); //полное название месяца
	$format['b']=array('format'=>'%b','category'=>'m'); //сокращенное название месяца
	$format['m']=array('format'=>'%m','category'=>'m'); //номер месяца

	$format['d']=array('format'=>'%d','category'=>'d'); //день месяца с ведущими нулями
	$format['e']=array('format'=>'%e','category'=>'d'); //день месяца с ведущими пробелом

	$format['H']=array('format'=>'%H','category'=>'H'); //час

	$format['M']=array('format'=>'%M','category'=>'M'); //минуты

	$format['S']=array('format'=>'%S','category'=>'S'); //секунды

//	$rus_date=array('января'=>'January','февраля'=>'February','марта'=>'March','апреля'=>'April','мая'=>'May','июня'=>'June','июля'=>'July','августа'=>'August','сентября'=>'September','октября'=>'October','ноября'=>'November','декабря'=>'December',
//						'янв'=>'January','фев'=>'February','мар'=>'March','апр'=>'April','май'=>'May','июн'=>'June','июл'=>'July','авг'=>'August','сен'=>'September','окт'=>'October','ноя'=>'November','дек'=>'December');
//	$rus_date=array('декабря'=>'0','января'=>'1','февраля'=>'2','марта'=>'3','апреля'=>'4','мая'=>'5','июня'=>'6','июля'=>'7','августа'=>'8','сентября'=>'9','октября'=>'10','ноября'=>'11','декабря'=>'12',
//						'дек'=>'0','янв'=>'1','фев'=>'2','мар'=>'3','апр'=>'4','май'=>'5','июн'=>'6','июл'=>'7','авг'=>'8','сен'=>'9','окт'=>'10','ноя'=>'11','дек'=>'12');
	static $local_date=array();
	if(!count($local_date))
		for($m=0; $m<12; $m++)
		{	$time=mktime(0,0,0,$m);
			$local_date[strftime('%B',$time)]=$m;
			$local_date[strftime('%b',$time)]=$m;
		}

	$date_value=array();
	$tmp=array();
	$date_key=array();
	$date_key['in']=array();
	$date_key['out']=array();

	$date_format=array('in'=>$input_format,'out'=>$output_format);
	foreach($date_format as $key=>&$value)
	{	$value=preg_quote($value,'/');
		$value=str_replace(array('\{','\}'),array('{','}'),$value);
		preg_match_all("/\{(.?)\}/",$value,$tmp);
		//preg_match_all("/\{([^}]+?)\}/",$value,$tmp);
		$date_key[$key]=$tmp[1];
	}


	if($date==false)
	{	foreach($date_key['in'] as $key=>$value)
		$input_format=str_replace('{'.$value.'}',$format[$value]['format'],$input_format);
		$date=strftime($input_format);
	}
	$date_format['in']=preg_replace('/\{[^}]+\}/','(.+)',$date_format['in']);
	preg_match("/^".$date_format['in']."$/m",$date,$date_value);
	array_shift($date_value);

	foreach($date_value as $key=>$value)
	{	$value=trim($value);
		if(isset($local_date[$value])) $date_value[$key]=$local_date[$value];
	}
	$format_mktime=array('Y'=>0,'m'=>0,'d'=>0,'H'=>0,'M'=>0,'S'=>0);
	foreach($date_key['in'] as $key=>$value)
	{	if(isset($format[$value]) and isset($date_value[$key]))
			$format_mktime[$format[$value]['category']]=$date_value[$key];
	}
	$time=mktime($format_mktime['H'],
					$format_mktime['M'],
					$format_mktime['S'],
					$format_mktime['m'],
					$format_mktime['d'],
					$format_mktime['Y']);

	foreach($date_key['out'] as $key=>$value)
		$output_format=str_replace('{'.$value.'}',$format[$value]['format'],$output_format);
	return strftime($output_format,$time);
}



//текс с допустимыми тегами and парметрами тегов
function cmfReformSpecialchars($value, $opt=1, $opt2=1) {
	if($opt===false) return html_entity_decode($value);
	if(in_array($opt, array(1, 'all', 'script'))) return htmlspecialchars($value);

	$tag_rules=array();
	$tag_rules['div']['type'] = 'paired';
	$tag_rules['div']['param'] = array('align'=>1);
	$tag_rules['a']['type'] = 'paired';
	$tag_rules['a']['param'] = array('href'=>'cmfIsUrl', 'target'=>1);
	$tag_rules['b']['type'] = 'paired';
	$tag_rules['b']['param'] = array('style'=>1);
	$tag_rules['i']['type'] = 'paired';
	$tag_rules['i']['param'] = array('style'=>1);
	$tag_rules['img']['type'] = 'single';
	$tag_rules['img']['param'] = array('src'=>'cmfIsUrl', 'width'=>1, 'height'=>1, 'alt'=>1, 'border'=>1);
	$tag_rules['br']['type'] = 'single';

	switch($opt) {
		case 'link':
			$tag = array('a', 'img');
			break;

		case 'tag':
			$tag = array_keys($tag_rules);
			$tag = array_unique($tag);
			break;

		default:
			$tag = (array)$opt;
			break;
	}


	foreach($tag as $key2=>$value2) {
		if(!isset($tag_rules[$value2]['type'])) continue;
		if($tag_rules[$value2]['type']==='paired')
		{	$value = preg_replace("'<\s*{$value2}\s*>'si","<{$value2} >", $value);
			$value = preg_replace("'<\s*{$value2}( [^>]*)>(.*)(?<!<\/{$value2}>)<\s*/\s*{$value2}([^>]*)?>'siU", "[{$value2}\$1]\$2[/{$value2}\$3]", $value);
		} else $value = preg_replace("'<\s*{$value2}([^>]*)>'si", "[{$value2}\$1]", $value);
	}
	$value=str_replace(array('<','>'), array('&lt;','&gt;'), $value);




	preg_match_all('"\[\s*([^\s\]]*)\s*([^]]*)\]"si', $value, $tmp);
	foreach($tmp[1] as $key2=>$item_tag) {
		preg_match_all('/([^\s=]*?)\s*=(\s*"[^"]*?"|\s*\'[^\']*?\'|[^\s]*)/si', $tmp[2][$key2], $tmp2);

		$tag_param = array();
		foreach($tmp2[1] as $key3=>$value3) {
			if(isset($tag_rules[$item_tag]) and isset($tag_rules[$item_tag]['param'][$value3])) {
				$func = $tag_rules[$item_tag]['param'][$value3];
				if(is_string($func)) {
					if($func($tmp2[2][$key3])) $tag_param[] = "$value3=". trim($tmp2[2][$key3]);
				} else $tag_param[] = "$value3=". trim($tmp2[2][$key3]);

			}
		}
		if(!sizeof($tag_param)) array_unshift($tag_param, $item_tag .' ');
		else array_unshift($tag_param, $item_tag);
		$new_tag = "[".implode(' ', $tag_param) ."]";
		$value = str_replace($tmp[0][$key2], $new_tag, $value);
	}
	foreach($tag as $key2=>$value2) {
		if(!isset($tag_rules[$value2]['type'])) continue;
		if($tag_rules[$value2]['type']==='paired')
			$value = preg_replace("'\[\s*{$value2}( [^\]]*)\](.*)(?<![\/{$value2}])\[\s*/\s*{$value2}([^\]]*)\]'siU", "<{$value2}\$1>\$2</{$value2}\$3>", $value);
		else $value = preg_replace("'\[\s*{$value2}([^\]]*)\]'si","<{$value2}\$1 />", $value);
	}
	return str_replace(array(' >',' />'),array('>','/>'),$value);
}



function cmfReformTextFormat($value, $len=80) {
	$pos = 0;
	$value_len = strlen($value);
	$new_value = '';

	while($pos+$len<=$value_len) {
		if(false !== ($i=stripos($value, "\n", $pos)) and $i-$pos<=$len) {
			$new_value .= substr($value, $pos, $i-$pos+1);
			$pos = $i+1;
		} else
		for($i=$pos+$len-1; $i>=$pos; --$i) {
			if(false !== stripos("\t -?!.,:;", $value[$i])) {
				$new_value .= substr($value,$pos,$i-$pos+1);
				$pos = $i+1;
				if(isset($value[$pos]) and $value[$pos]!=="\n" and $value[$pos]!=="\r") $new_value .= "\n";
				break;
			} else
				if($i==$pos) {
					$new_value .= substr($value, $pos, $len) ."\n";
					$pos = $pos+$len;
					break;
				}
		}

	}
	return $new_value . substr($value, $pos);
}


?>
