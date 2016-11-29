<?php

cLoader::library('pages/cPagesCore');
cLoader::library('pages/cUrl');

class cPages extends cPagesCore {
    /* Application */

    public static function routerApplication(&$mP, &$mN, &$mPr) {
        self::setPage($mP);
        if (cApplication !== 'application') {
            return;
        }

        $url = parse_url('http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], strlen(cItemHostUrl)), PHP_URL_PATH);
        $url = urldecode($url);

        $page = '/404/';
        $param = null;
        if (isset($mN[cApplication][$url])) {
            $page = $mN[cApplication][$url];
        } else if (isset($mPr[cApplication])) {
            unset($mN);
            while (list($k, $v) = each($mPr[cApplication])) {
                foreach ($v as $p) {
                    if (preg_match($p, $url, $tmp)) {
                        $page = $k;
                        $param = $tmp;
                        break;
                    }
                }
                if (!is_null($param)) {
                    break;
                }
            }
        }
        if ($page === '/404/') {
            header("HTTP/1.0 404 Not Found");
        }
        if ($param) {
            array_shift($param);
        }
        self::setMain($page);
        self::param()->set($param);
    }

    /* Admin */

    public static function roterAdmin(&$mP) {
        if (cApplication !== 'admin') {
            return;
        }
        self::setPage($mP);

//        if (!cAdmin::user()->is()) {
//            self::setMain('/admin/enter/');
//            return;
//        }

        if (cInput::get()->is('url')) {
            $url = cInput::get()->get('url');
        } else if (!cAjax::is()) {
            cPages::setMain('/admin/index/');
            return;
        } else {

            $url = cAjax::getUrl();
            preg_match('~' . preg_quote(cAdminUrl) . '([^#]*)\#?(\&?.*)~', $url, $tmp);
            $url = empty($tmp[1]) ? '/' : $tmp[1];
        }

        if (!empty($_SERVER['HTTP_REFERER'])) {
            if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== cDomen) {
                self::setMain('/admin/index/');
                return;
            }
        }

        if (!empty($tmp[2])) {
            foreach (explode('&', $tmp[2]) as $v)
                if ($v) {
                    $v = explode('=', $v);
                    if (isset($v[1])) {
                        cInput::get()->set($v[0], $v[1]);
                    } else {
                        cInput::get()->set($v[0], 1);
                    }
                }
        }

        $page = false;
        $param = null;
        while (list($k, $v) = each($mP)) {
            if (isset($v['b']))
                continue;
            if (isset($v['preg'])) {
                foreach ($v['preg'] as $p) {
                    if (preg_match($p, $url, $preg)) {
                        $page = $k;
                        $param = $preg;
                        break;
                    }
                }
                if (!is_null($param))
                    break;
            }
            if (isset($v['u']) and $v['u'] === $url) {
                $page = $k;
                break;
            }
        }
        if (!$page) {
            cAjax::get()->alert('Ничего не найдено!');
            exit;
        }

        if ($param) {
            array_shift($param);
        }
        self::setMain($page);
        self::param()->set($param);
    }

}

?>
