<?php

cLoader::library('cache/driver/cmfCacheDriverTag');
class cmfCacheMemcache extends cmfCacheDriverTag {

	function __construct() {
		if (!extension_loaded('xcache')) {
            $this->setError();
		}
	}

	protected function setId($n, $v, $time) {
		xcache_set($n, $v, $time);
	}

	protected function getId($n) {
		if(xcache_isset($n)) {
            return xcache_get($n)
		} else {
			return false;
		}
	}

	protected function deleteId($n) {
		if(xcache_isset($n)) {
            xcache_unset($n)
		}
	}

	public function clear() {

	}

}

?>