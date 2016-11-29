<?php

cLoader::library('header/cSeo');
cLoader::library('compile/cCallCompileJsCss');

class cHeader {

    private static $mData = array();

    private static function addType($type, $value) {
        self::$mData[] = [$type, $value];
    }

    static public function addString($string) {
        self::addType('string', $string);
    }

    static public function addSeporator() {
        self::addString('');
    }

    static public function addJs($js) {
        self::addType('js', $js);
    }

    static public function addJsSousre($js) {
        self::addType('jsSousre', $js);
    }

    static public function addCss($css) {
        self::addType('css', $css);
    }

    static public function addCssSousre($css) {
        self::addType('cssSousre', $css);
    }

    static public function addMeta() {
        return self::addType('meta', func_get_args());
    }

    /* view s */

    static public function getHead($isBase = true) {
        return cBuffer::add('cHeader::_getHead', $isBase);
    }

    static public function _getHead($isBase = true) {
        $head = '';
        if ($isBase) {
            $head .= '
        <base href="' . cAppUrl . '/"/>';
        }
        $head .= '
        <meta content="text/html; charset=' . cCharset . '" http-equiv="Content-Type"/>';

        cCompile::fileJsCss()->initHeader(self::$mData);
        foreach (self::$mData as $key => list($type, $value)) {
            switch ($type) {
                case 'string':
                    $head .= $value;
                    break;

                case 'js':
                case 'jsSousre':
                    $head .= '
        <script type="text/javascript" src="' . $value . '"></script>';
                    break;

                case 'css':
                case 'cssSousre':
                    $head .= '
        <link href="' . $value . '" rel="stylesheet" type="text/css" />';
                    break;

                case 'meta':
                    $head .= "
        <meta ";
                    foreach ($value as list($k, $v)) {
                        $head .= '"' . $k . '"="' . $v . '" ';
                    }
                    $head .= '/>';
                    break;
            }
        }
        self::$mData = null;
        return $head;
    }

}

?>
