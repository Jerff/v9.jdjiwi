<?php

class cInputParam {

    static private $r = null;

    static public function start($param) {
        $_param = explode('/', $param);
        $count = count($_param);
        self::$r = array();
        for ($i = 0; $i <= $count; $i+=2)
            if (isset($_param[$i + 1])) {
                self::$r[$_param[$i]] = $_param[$i + 1];
            }
    }

    static public function get($k) {
        return get(self::$r, $k);
    }

    static public function all() {
        return self::$r;
    }

    static public function toUrl($p) {
        return http_build_query($p);
    }

    static public function toUrlPath($p) {
        if (!$p)
            return '';
        $u = '';
        reset($p);
        while (list($k, $v) = each($p)) {
            $u .= $k ? "{$k}/" . urlencode($v) . '/' : urlencode($v) . '/';
        }
        return $u;
    }

}

?>