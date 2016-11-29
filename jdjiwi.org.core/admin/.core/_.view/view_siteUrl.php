<?php

class view_siteUrl {

    const len = 50;

    protected static function replaceUrl($url) {
        $url = str_replace(cAppUrl, '', $url);
        $len = cString::strlen($url);
        if ($len < self::len)
            return $url;
        $new = '';
        for ($i = 0; $i <= $len; $i+=self::len) {
            if ($i)
                $new .= ' ';
            $new .= mb_substr($url, $i, self::len);
        }
        return $new;
    }

    public static function html($hash, $type, $url) {
        return '<span id="view_siteUrl' . $hash . '">' . self::link($type, $url) . '</span>';
    }

    public static function jsUpdate($ajax, $hash, $type, $url) {
        $ajax->html('#view_siteUrl' . $hash, self::link($type, $url));
    }

    protected static function link($type, $url) {
        if (empty($url)) {
            return '&nbsp;';
        }
        switch ($type) {
            case 'edit':
                return 'Постоянная ссылка: <a href="' . $url . '" target="site" class="viewSiteUrl">' . self::replaceUrl($url) . '</a>';
                break;

            case 'tree':
            case 'list':
                return '<span class="button"><a href="' . $url . '" target="site">' . self::replaceUrl($url) . '</a></span>';
                break;

            default:
                break;
        }
    }

}

?>