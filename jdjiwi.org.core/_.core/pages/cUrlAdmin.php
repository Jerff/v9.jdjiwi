<?php

class cUrlAdmin {

    static public function requestUri($opt = null) {
        $get = cInput::get()->all();
        if (!is_null($opt))
            $get = array_merge($get, (array) $opt);

//        $uri = '';
        foreach ($get as $k => $v) {
            if (is_null($v)) {
                unset($get[$k]);
            }
        }
//        reset($get);
//        while (list($k, $v) = each($get))
//            if (!is_null($v))
//                $uri .= '&' . urlencode($k) . '=' . urlencode($v);
        return http_build_query($get);
    }

    //cUrl::admin()->get
    public function get($page, $param = null) {
        $param = func_get_args();
        $uri = cPages::getPage(array_shift($param))->uri;
        return cPages::base()->admin . cUrl::reform($uri, $param) . '#';
    }

    //cUrl::admin()->command
    public function command($page, $param = null) {
        $param = func_get_args();
        $uri = cPages::getPage(array_shift($param))->uri;
        return cPages::base()->admin . '/?url=' . cUrl::reform($uri, $param);
    }

}

?>