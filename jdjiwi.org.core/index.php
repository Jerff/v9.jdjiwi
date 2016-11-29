<?php

if (!defined('cApplication')) {
    if (isset($_GET['admin'])) {
        define('cApplication', 'admin');
    } elseif (isset($_GET['cron'])) {
        define('cApplication', 'cron');
    } else {
        define('cApplication', 'application');
    }
}

define('cRootPath', realpath(__DIR__ . '/../') . '/');
define('cTimeInit', microtime());

chdir(__DIR__);

// конфигурация
require('_.config/config.php');
require('_.config/setting.project.php');

// системный кеш
if (isComplile) {
    require(cCompilePath . '.compile.include.' . cApplication . '.php');
} else {
    require('.include.' . cApplication . '.php');
}
cLog::log('.include.' . cApplication . '.php');

cDebug::setAjax();
cDebug::setError();
cDebug::setSql();
cmfCache::setPages();
cmfCache::setData();

return cApplication::start();
?>