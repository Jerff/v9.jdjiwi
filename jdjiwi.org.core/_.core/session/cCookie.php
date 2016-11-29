<?php


class cCookie {

	public static function is($n) {
		return array_key_exists($n, $_COOKIE);
	}

	public static function get($n) {
		return get($_COOKIE, $n);
	}

	public static function set($n, $v='', $d=12) {
		if($v) setcookie($n, $v, time() + $d*60*60*24, '/', cDomen);
		else self::del($n);
	}

	public static function set2($n, $v='', $d=12) {
		if($v) setrawcookie($n, $v, time() + $d*60*60*24, '/', cDomen);
		else self::del($n);
	}

	public static function del($n) {
		setcookie($n, 0, time()-60*60*24, '/', cDomen);
		unset($_COOKIE[$n]);
	}

}

?>