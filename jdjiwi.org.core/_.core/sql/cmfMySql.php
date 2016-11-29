<?php

cLoader::library('sql/cmfSqlDriver');

class cmfMySql extends cmfSqlDriver {

    function __construct() {
        $host = cMysqlHost;
        $db = cMysqDb;
        $dsn = "mysql:dbname={$db};host={$host}";
        $res = new cmfPDO($dsn, cMysqUser, cMysqPassword);
        $this->set($res);
    }

}

?>
