<?php

class cCommand {

    private static $v = array();

    public static function is($n) {
        return array_key_exists($n, self::$v);
    }

    public static function get($n) {
        return get(self::$v, $n);
    }

    public static function set($n, $v = 1) {
        self::$v[$n] = $v;
    }

    public static function del($n) {
        unset(self::$v[$n]);
    }

    public static function all() {
        return self::$v;
    }

}

?>