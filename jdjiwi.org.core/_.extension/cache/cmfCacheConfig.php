<?php

// конфигураци драйверов кеша
class cmfCacheConfig {

    const driver= 'SQLite|Memcache|Xcache|eaccelerator|sql';
	const time = 60;


    // конфигурируем  мемкеш
    static public function Memcache(&$res) {
        $res->connect(cMemcacheHost, cMemcachePort);
    }

    public function __call($name, $arg) {
    }

}

?>