<?php

//кодировки
define('cCharset', 'UTF-8');

// Системная папка
define('cDataPath', cSoursePath . '.data/');

// места для кеша
define('cCacheSitePath', cWWWPath . '.cache/cache/');
define('cCachePagePath', cDataPath . 'cache/page/');
define('cCacheSQLitePath', cDataPath . 'cache/SQLite/mydb.sq3');



define('cAppPathAjax', cSoursePath . 'application/ajax/');
define('cAppPathController', cSoursePath . 'application/controller/');
define('cAppPathTemplates', cSoursePath . 'application/templates/');
define('cAppPathPage', cSoursePath . 'application/templates/.page/');


define('cAdminPath', cSoursePath . 'admin/');
define('cAdminSystemPath', cAdminPath . '.system/');
define('cAdminModulPath', cAdminPath . 'modul/');



// компилированный код
define('cCompilePath', cDataPath . 'compile/');


// папка с upload файлами
define('cFilePath', 'files/');
?>