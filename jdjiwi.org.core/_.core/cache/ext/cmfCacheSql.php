<?php

cLoader::library('cache/driver/cmfCacheDriverSql');
class cmfCacheSql extends cmfCacheDriverSql {

	function __construct() {
		$this->setResurse(cRegister::sql());
	}

}

?>