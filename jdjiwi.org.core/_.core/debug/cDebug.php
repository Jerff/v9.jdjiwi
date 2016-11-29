<?php

class cDebug {

    private static $isError = false;
    private static $isAjax = false;
    private static $isSql = false;
    private static $isExplain = false;
    private static $isSqlLog = true;
    private static $isShutdown = false;

    // прекращение отладки
    static public function disable() {
        self::setError(false);
        self::setAjax(false);
        self::setSql(false);
        self::setExplain(false);
        cLog::destroy();
    }

    /* отладка Error */

    // отладка ошибок php
    // вернуть режим отладки ошибок php
    static public function isError() {
        return self::$isError;
    }

    // установить режим отладки ошибок php
    static public function setError($status = true) {
//        if ($status) {
//            error_reporting(E_ALL);
//        } else {
//            error_reporting(0);
//        }
        self::$isError = (bool) $status;
    }

    /* отладка Error */



    /* отладка Ajax */

    // показывать лог скриптов
    static public function isAjax() {
        return self::$isAjax;
    }

    static public function setAjax($status = true) {
        self::$isAjax = (bool) $status;
    }

    /* отладка Ajax */



    /* отладка Sql */

    // вернуть режим отладки sql запросов к базе
    static public function isSql() {
        return (self::$isSql and self::$isSqlLog) and !self::$isShutdown;
    }

    // установить режим отладки sql запросов к базе
    static public function setSql($status = true) {
        self::$isSql = (bool) $status;
    }

    static public function sqlOn() {
        self::$isSqlLog = true;
    }

    static public function sqlOff() {
        self::$isSqlLog = false;
    }

    /* /отладка Sql */



    /* отладка Sql explain */

    // отладка sql explain
    // вернуть режим отладки оптимизации sql запросов к базе
    static public function isExplain() {
        return self::$isExplain;
    }

    // установить режим отладки оптимизации sql запросов к базе
    static public function setExplain($status = true) {
        self::$isExplain = (bool) $status;
    }

    /* /отладка Sql explain */



    /*  завершение работы */

    // показывать ли лог или нет
    static public function isView() {
        return self::isError() or self::isSql() or self::isExplain();
    }

    static public function shutdown() {
        if (self::isView()) {
            echo cLog::message();
        }
    }

}

?>