<?php

// компиляция
// 0 - режим отладки
// при режиме > 0 уже юзается общие скомпилированные файлы для морды и админа
// 1 - юзается общие скомпилированные файлы для морды и админа
// 2 - режим компиляции php файлов - проверяются измененные файлы
// 3 - режим компиляции php файлов - файлы не проверятся на изменения
define('isComplile', 0);

// определяем домен
define('cDomen', 'v9.jdjiwi.ru');
define('cHostUrl', '');


// драйвер для кеша
define('cCacheTypeDriver', 'sql');
// драйвер визуального редактора
define('cWysiwyngTypeDriver', 'KCKeditor');
// конфигурация мемкеша
define('cMemcacheHost', 'localhost');
define('cMemcachePort', 11211);
// Sphinx
define('cSphinxHost', 'localhost');
define('cSphinxPort', 3312);


// префикс к таблицам
define('cDbPefix', 'core_');


// концигурации базы данных
define('cMysqlHost', 'localhost');
define('cMysqUser', 'v9-jdjiwi-ru');
define('cMysqPassword', 'v9-jdjiwi-ru');
define('cMysqDb', 'v9-jdjiwi-ru');


// концигурации базы данных
define('cSoursePath', cRootPath . 'jdjiwi.org.core/');
define('cWWWPath', cRootPath . 'jdjiwi.org/');

// Соль
define('cSalt', '6z3WBO4GN8');

// права на сайты
define('cFileMode', 0666);
define('cDirMode', 0777);


//ImageMagick
define('isImageMagick', 1);
switch (1) {
    case 1:
        define('cImageMagickProg', '"');
        define('cImageMagickPath', 'N:\\Program Files\\ImageMagick-6.6.8-Q16\\');
        setlocale(LC_ALL, "Russian_Russia.65001");
        break;

    case 2:
        define('cImageMagickProg', '');
        define('cImageMagickPath', '/usr/local/bin/');
        setlocale(LC_ALL, array('ru_RU.utf-8', 'rus_RUS.utf-8'));
        break;
}

date_default_timezone_set('Europe/Moscow')
?>