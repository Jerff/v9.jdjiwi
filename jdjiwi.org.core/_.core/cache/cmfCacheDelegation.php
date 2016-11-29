<?php

// делегатор для cmfCacheDriver
class cmfCacheDelegation {


	// кеширование данных
	static public function set($n, $v, $tags=null, $time=cmfCacheConfig::time) {
		if(cmfCache::isNoData()) return false;
		cLog::log('cache.set '. $n);
		cmfCache::driver()->set($n, $v, $tags, $time*60);
	}
	static public function get($n) {
		if(cmfCache::isNoData()) return false;
		cLog::log('cache.get '. $n);
		return cmfCache::driver()->get($n);
	}
	static public function delete($n) {
		if(cmfCache::isNoData()) return false;
		cLog::log('cache.delete '. $n);
		cmfCache::driver()->delete($n);
	}



	// кеширование данных  + добавляется url
	static private function getPath() {
		static $url = null;
		if(!$url) $url = cInput::url()->path();
		return $url;
	}

	static public function setRequest($n, $v, $tags=null, $time=cmfCacheConfig::time) {
		self::set($n . self::getPath(), $v, $tags, $time);
	}
	static public function getRequest($n) {
		return self::get($n . self::getPath());
	}
	static public function delRequest($n) {
		self::delete($n . self::getPath());
	}




	// кеш данные по имени и параметрам
	static public function setParam($n, $p, $v, $tags=null, $time=cmfCacheConfig::time) {
		self::set($n . serialize($p), $v, $tags, $time);
	}
	static public function getParam($n, $p) {
		return self::get($n . serialize($p));
	}
	static public function delParam($n, $p) {
		self::delete($n . serialize($p));
	}


	static public function deleteTime() {
		cmfCache::driver()->deleteTime();
	}
	static public function deleteTag($tags) {
		cmfCache::driver()->deleteTag($tags);
	}
	static public function clear() {
		cmfCache::driver()->clear();
	}

}

?>