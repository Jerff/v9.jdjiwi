<?php

cLoader::library('cache/cmfCacheDelegation');
cLoader::library('cache/cmfCacheConfig');
cLoader::library('cache/ext/cmfCacheSql');
cLoader::library('cache/ext/cmfCacheSQLite');
cLoader::library('cache/ext/cmfCacheMemcache');
class cmfCache extends cmfCacheDelegation {


	private static $page = false;
	private static $data = false;
	private static $driver = null;

	static public function driver() {
        if(self::$driver) {
        	return self::$driver;
        }

        $cache = self::getDriver(cCacheTypeDriver);
        if(!$cache->isRun()) {
	        foreach(explode('|', cmfCacheConfig::driver) as $d) {
	        	if($d!==$driver) {
			        $cache = self::getDriver($d);
			        if($cache->isRun()) break;
				}
			}
		}

        return self::$driver = $cache;
	}

	// выборка драйвера кеша
	static private function &getDriver($driver) {
        switch($driver) {
            case 'sql':
            	$cache = new cmfCacheSql();
            	break;

            case 'SQLite':
            	$cache = new cmfCacheSQLite();
            	break;

            case 'Memcache':
            	$cache = new cmfCacheMemcache();
            	break;

            case 'Xcache':
            	$cache = new cmfCacheXcache();
            	break;

            case 'eaccelerator':
            	$cache = new cmfCacheEaccelerator();
            	break;

            case 'apc':
            	$cache = new cmfCacheApc();
            	break;
        }
		return $cache;
	}


	// управление кеширование
	// вернуть режим кеширование
	static public function isNoPages() {
		return self::$page;
	}
	static public function isNoData() {
		return self::$data;
	}

	// установить режим кеширование
	static public function setPages($s=true) {
		self::$page = (bool)$s;
	}
	static public function setData($s=true) {
		self::$data = (bool)$s;
	}

}

?>