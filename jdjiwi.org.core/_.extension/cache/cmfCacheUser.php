<?php


class cmfCacheUser {

    const name = '$cacheId';
	static private $config = array();

	static public function getCacheId() {
	    return cCookie::get(self::name);
	}
	static private function init() {
		$d = self::get('discount');
	    if($d) {
	    	$cacheId = $d;
	    } else {
	    	$cacheId = '';
	    }
	    if($cacheId) {
    	    cCookie::set(self::name, $cacheId);
	    } else {
	        cCookie::del(self::name);
	    }
	}
	static private function parse() {
		$cacheId = self::getCacheId();
	    if($cacheId) {
	    	$d = $cacheId;
	    } else {
	    	$d = 1;
	    }
	    self::set('discount', $d);
	}


	static private function set($n, $v) {
		self::$config[$n] = $v;
	}
	static private function get($n, $default=null) {
		if(!isset(self::$config[$n])) {
    		self::parse();
        }
        return get(self::$config, $n, $default);
	}
	static private function is($n) {
		return isset(self::$config[$n]);
	}


	static public function getDiscount() {
		return self::get('discount', 1);
	}
	static public function setDiscount($d) {
		self::set('discount', $d);
		self::init();
	}


	static public function getPay() {
		return self::get('pay');
	}
	static public function setPay($p) {
		self::set('pay', $p);
	}

	static public function updateUserPay($p) {
		self::setDiscount(cmfDiscount::searchPrice($p));
	}

}

?>