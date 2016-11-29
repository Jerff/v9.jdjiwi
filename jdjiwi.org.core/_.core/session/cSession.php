<?php

cLoader::library('session/cCookie');
cLoader::library('session/cGlobal');
cLoader::library('session/cCommand');

class cSession {

    private static function start() {
        static $is = false;
        if ($is)
            return;
        $is = true;
        session_start();
    }

    public static function is($n) {
        self::start();
        return isset($_SESSION[$n]);
    }

    public static function get($n, $i = null) {
        self::start();
        if (is_null($i)) {
            return get($_SESSION, $n);
        } else {
            return get2($_SESSION, $n, $i);
        }
    }

    public static function set() {
        self::start();
        switch (func_num_args()) {
            case 2:
                $_SESSION[func_get_arg(0)] = func_get_arg(1);
                break;

            case 3:
                $_SESSION[func_get_arg(0)][func_get_arg(1)] = func_get_arg(2);
                break;

            case 4:
                $_SESSION[func_get_arg(0)][func_get_arg(1)][func_get_arg(2)] = func_get_arg(3);
                break;
        }
    }

    public static function del($n, $i = null) {
        self::start();
        if (is_null($i)) {
            unset($_SESSION[$n]);
        } else {
            unset($_SESSION[$n][$i]);
        }
    }

}

?>