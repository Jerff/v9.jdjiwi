<?php




function cmfJsGetId($n, $o=null) {
	if($o) return '$("#'. $n .'").get(0).'. $o;
	else return '$("#'. $n .'").get(0)';
}


?>
