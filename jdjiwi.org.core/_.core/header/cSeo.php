<?php

class cSeo {

    static private $mSeo = array();

    static public function set($n, $v) {
        self::$mSeo[$n] = strip_tags($v);
    }

    static private function getData() {
        if ($r = cmfCache::getRequest('cSeo::getTitleMeta')) {
            return $r;
        }
        unset($r);

        $dt = $dk = $dd = $t = $k = $k = '';
        $res = cRegister::sql()->query("SELECT `uri`, `title`, `keywords`, `description` FROM " . db_seo_title . " WHERE `uri` IN('default', " . cRegister::sql()->quote(cPages::getMain()) . ")");
        while ($row = $res->fetchAssoc()) {
            if ($row['uri'] === 'default') {
                $dt = $row['title'];
                $dk = $row['keywords'];
                $dd = $row['description'];
                if (!isset($t) and !empty($row['title']))
                    $t = $row['title'];
                if (!isset($k) and !empty($row['keywords']))
                    $k = $row['keywords'];
                if (!isset($d) and !empty($row['description']))
                    $d = $row['description'];
            } else {
                if (!empty($row['title']))
                    $t = $row['title'];
                if (!empty($row['keywords']))
                    $k = $row['keywords'];
                if (!empty($row['description']))
                    $d = $row['description'];
            }
        }
        unset($res, $row);

        if (!isset($t))
            $t = '';
        if (!isset($k))
            $k = '';
        if (!isset($d))
            $d = '';

        while (list($n, $v) = each(self::$mSeo)) {
            $n = '{' . $n . '}';
            list($t, $k, $d, $dt, $dk, $dd) = str_replace($n, $v, array($t, $k, $d, $dt, $dk, $dd));
        }
        self::$mSeo = array();

        if (empty($t))
            $t = $dt;
        if (empty($k))
            $k = $dk;
        if (empty($d))
            $d = $dd;

        $r = array($t, $k, $d);
        cmfCache::setRequest('cSeo::getTitleMeta', $r, 'seoTitle');
        return $r;
    }

    /* view s */

    static public function getTitleMeta() {
        return cBuffer::add('cSeo::_getTitleMeta');
    }

    static public function _getTitleMeta() {
        list($t, $k, $d) = self::getData();

        $head = '
    <title>' . $t . '</title>
    <meta name="keywords" content="' . $k . '"/>
    <meta name="description" content="' . $d . '"/>';

        if (cPages::getPageConfig(cPages::getMain())->noCache) {
            $head .= '
    <meta http-equiv="pragma" content="no-cache"/>
    <meta http-equiv="cache-control" content="no-cache"/>';
            header('Pragma: no-cache');
            header('Cache-Control: no-cache');
        } else {
            $days = 1;
            $day = 24 * 60 * 60;
            $add = ((rand(9, 19)) * 60 + rand(1, 59)) * 60 + rand(1, 59);
            $time = gmdate('D, d M Y H:i:s', (floor(time() / $day) - $days) * $day + $add) . ' GMT';
            $head .= '
    <meta http-equiv="last-modified" content="' . $time . '"/>';
            header('Last-Modified: ' . $time);
        }
        return $head;
    }

}

?>
