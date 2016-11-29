<?php

// ------------- фильтры - отсеивают неподходящие данные -------------
// *** если возвращают true - все хорошо
// *** если возвращают false - не валидные данные


function cmfIsEmpty($v) {
	$r = empty($v) and $v!=='0';
    if(!$r) {
		cmfFormError::set("&nbsp;");
		//cmfFormError::set("поле <b>`{element}`</b> должно быть пустым");
    }
	return $r;
}


function cmfNotEmpty($v) {
	$r = !empty($v) or $v==='0';
	if(!$r) {
		cmfFormError::set("поле не должно быть пустым");
		//cmfFormError::set("поле <b>`{element}`</b> не должно быть пустым");
	}
    return $r;
}


function cmfIsEmail($v) {
	if(!$v) return true;
	$r = filter_var($v, FILTER_VALIDATE_EMAIL);
	if(!$r) {
		cmfFormError::set("в полe не почтовый адресс");
		//cmfFormError::set("в полe <b>`{element}`</b> не почтовый адресс");
	}
    return $r;
}


function cmfIsPhone($v) {
	return true;

	if(!$v) return true;
	$r = filter_var($v, FILTER_VALIDATE_EMAIL);
	if(1 or !$r) {
		//cmfFormError::set("в полe не телефон");
		//cmfFormError::set("в полe <b>`{element}`</b> не почтовый адресс");
	}
    return $r;
}


function cmfIsUrl($v) {
	if(!$v) return true;
	$r = filter_var($v, FILTER_VALIDATE_URL);
	if(!$r) {
		cmfFormError::set("в полe не ссылка");
		//cmfFormError::set("в полe <b>`{element}`</b> не ссылка");
	}
    return $r;
}


function cmfIsHttpUrl($v) {
	if(!$v) return true;
	$r = filter_var('http://'. $v, FILTER_VALIDATE_URL);
	if(!$r) {
		cmfFormError::set("в полe не ссылка");
		//cmfFormError::set("в полe <b>`{element}`</b> не ссылка");
	}
    return $r;
}


function cmfFilterTextLengt($v, $o) {
	$min = get($o, 0);
	$max = get($o, 1);
	$len = strlen((string)$v);
	$r = ($len>=$min and $len<=$max);
	if(!$r) {
		cmfFormError::set("неправильная длина поля");
		//cmfFormError::set("неправильная длина поля <b>`{element}`</b>");
	}
    return $r;
}


function cmfFilterLenMin($v, $m) {
	$len = mb_strlen((string)$v, cCharset);
	$r = (!$len or $len>=$m);
	if(!$r) {
		cmfFormError::set("поле не должно быть меньше ($m) символов");
	}
    return $r;
}

function cmfFilterLenMax($v, $m) {
	$len = mb_strlen((string)$v, cCharset);
	$r = (!$len or $len<=$m);
	if(!$r) {
		cmfFormError::set("поле не должно быть больше ($m) символов");
	}
    return $r;
}


function cmfFilterIntMin($v, $m) {
	$r = ((int)$v >= (int)$m);
	if(!$r) {
		cmfFormError::set("поле меньше ($m)");
	}
    return $r;
}

function cmfFilterIntMax($v, $m) {
	$r = ($v <= $m);
	if(!$r) {
		cmfFormError::set("поле больше ($m)");
	}
    return $r;
}


function cmfIsNotDefault(&$v, $d) {
	$r = ($v!==$d);
	if(!$r) {
		$v = '';
		//cmfFormError::set("поле незаполнено");
	}
    return $r;
}

?>