<?php

// загрузчик
set_include_path(get_include_path() .
        PATH_SEPARATOR . cRootPath .
        PATH_SEPARATOR . cWWWPath .
        PATH_SEPARATOR . cSoursePath .
        PATH_SEPARATOR . cSoursePath . '_.config/' .
        PATH_SEPARATOR . cSoursePath . '_.core/' .
        PATH_SEPARATOR . cSoursePath . '_.extension/' .
        PATH_SEPARATOR . cSoursePath . '_.plugin/' .
        PATH_SEPARATOR . cSoursePath . '_library/' .
        PATH_SEPARATOR . cSoursePath . '_library/PEAR/');

require('patterns/cPatternsStaticRegistry.php');
require('loader/cLoaderTemplate.php');

class cLoader extends cPatternsStaticRegistry {
    /* === загрузчики === */

    static public function library($file) {
        $file = self::template()->path($file);
        if (!class_exists(basename($file), false)) {
            self::file($file . '.php');
        }
    }

    //cLoader::modul
    static public function isFile($n) {
        return file_exists($n . '.php');
    }

    static public function file($file) {
        require_once($file);
    }

    /* === загрузчики === */



    /* === шаблоны === */

    static public function template() {
        return self::register('cLoaderTemplate');
    }

    /* === шаблоны === */



    /* === загрузка библиотек === */

    static public function form() {
        self::library('form/cForm');
    }

    static public function ajax() {
        self::library('ajax/cAjax');
    }

    static public function input() {
        self::library('input/cInput');
    }

    static public function mail() {
        self::library('mail/cmfMail');
    }

    /* === /загрузка библиотек === */



    /* === загрузка модулей админки === */

    //cLoader::modul
    static public function modul($n) {
        if (!class_exists($n)) {
            if ($n{0} === '_') {
                $n = '_' . str_replace('_', '/', substr($n, 1));
            } else {
                $n = str_replace('_', '/', $n);
            }
            self::file($n . '.php');
        }
    }

    //cLoader::getModul
    static public function &initModul($n) {
        self::modul($n);
        $n = new $n();
        return $n;
    }

    /* === /загрузка модулей админки === */
}

?>