<?php

cLoader::library('pages/cPagesBase');
cLoader::library('pages/cPagesConfig');
cLoader::library('pages/cPagesParam');
cLoader::library('pages/cPagesTemplate');
cLoader::library('patterns/cPatternsStaticRegistry');

class cPagesCore extends cPatternsStaticRegistry {

    static private $main = null;
    static private $item = null;
    static private $page = null;

    // домены
    static public function base() {
        return self::register('cPagesBase');
    }

    // параметры
    static public function param() {
        return self::register('cPagesParam');
    }

    // шаблоны
    static public function template() {
        return self::register('cPagesTemplate');
    }

//    static public function urlParam() {
//        return self::config('cPagesParam');
//    }
    // установка & возвращение имени главной страницы
    static public function setMain($p) {
        self::$main = $p;
        self::$item = $p;
    }

    static public function getMain() {
        return self::$main;
    }

    static public function isMain($p = null) {
        return self::$main === ($p ? $p : self::$item);
    }

    // установка & возвращение имени текущей страницы
    static public function setItem($p) {
        self::$item = $p;
    }

    static public function getItem() {
        return self::$item;
    }

    // установка & возвращение данных шаблона
    static protected function setPage($p) {
        self::$page = $p;
    }

    static public function getPage($n) {
        if (empty(self::$page[$n]))
            return false;
        return new cPagesConfig(self::$page[$n]);
    }

//    //cPages::getUrl()
//    //cInput::url()->adress()
//    static public function getUrl() {
//        return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//    }
//
//    //cPages::getUrl2()
//    //cInput::url()->host()
//    static public function getUrl2() {
//        return 'http://' . $_SERVER['HTTP_HOST'];
//    }
//
//    //cPages::getUri()
//    //cInput::url()->uri()
//    static public function getUri() {
//        return get($_SERVER, 'REQUEST_URI');
//    }
//
//    // юзаются для кеша
//    //cPages::getPath()
//    //cInput::url()->path()
//	static public function getPath() {
//        return parse_url(self::getUrl(), PHP_URL_PATH);
//    }
}

?>
