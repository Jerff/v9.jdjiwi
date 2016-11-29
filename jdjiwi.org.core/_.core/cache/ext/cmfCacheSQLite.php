<?php

cLoader::library('cache/driver/cmfCacheDriverSql');
class cmfCacheSQLite extends cmfCacheDriverSql {

	function __construct() {

        $dns = 'sqlite:'. cCacheSQLitePath;
		//$dns = 'sqlite::memory:';
        try {
        	$sql = new cmfPDO($dns);
        } catch (PDOException $e) {
            $this->setError();
    		return;
		}
		$sql->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('cmfPDOStatement'));

		$sql->query('
		CREATE TABLE IF NOT EXISTS `'. db_cache_data .'` (
		`id` int(11) NOT NULL,
		`name` varchar(40) NOT NULL,
		`time` int(10) unsigned NOT NULL,
		`tag` varchar(250) NOT NULL,
		`data` text NOT NULL,
		PRIMARY KEY  (`id`),
		KEY `id_time` (`id`,`time`),
		KEY `tag` (`tag`)
		) DEFAULT CHARSET=utf8;');
		$this->setResurse($sql);
	}

}

?>