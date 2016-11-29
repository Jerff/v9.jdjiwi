<?php

cLoader::library('input/cInputGet');
cLoader::library('input/cInputPost');
cLoader::library('input/cInputFiles');
cLoader::library('input/cInputIp');
cLoader::library('input/cInputHeader');
cLoader::library('input/cInputUrl');
cLoader::library('input/cInputParam');
cLoader::library('patterns/cPatternsStaticRegistry');

class cInput extends cPatternsStaticRegistry {

//    private $get;
//    private $post;
//    private $files;
//    private $ip;
//    private $header;
//    private $url;
//    static private $userAgent = null;
//    function __construct() {
//        $this->get = new cInputGet();
//        $this->post = new cInputGet();
//        $this->files = new cInputFiles();
//        $this->ip = new cInputIp();
//        $this->header = new cInputHeader();
//        $this->url = new cInputUrl();
//    }

    static public function get() {
        return self::register('cInputGet');
    }

    static public function post() {
        return self::register('cInputPost');
    }

    static public function files() {
        return self::register('cInputFiles');
    }

    static public function ip() {
        return self::register('cInputIp');
    }

    static public function header() {
        return self::register('cInputHeader');
    }

    static public function url() {
        return self::register('cInputUrl');
    }

    static public function param() {
        return self::register('cInputParam');
    }

    static public function userAgent() {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : false;
    }

}

?>
