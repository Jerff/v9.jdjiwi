<?php

class cInputUrl {

    public function adress() {
        return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function host() {
        return 'http://' . $_SERVER['HTTP_HOST'];
    }

    public function uri() {
        return get($_SERVER, 'REQUEST_URI');
    }

    // юзаются для кеша
    public function path() {
        return parse_url(self::adress(), PHP_URL_PATH);
    }

}

?>
