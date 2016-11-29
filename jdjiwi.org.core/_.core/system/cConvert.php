<?php

class cConvert {
    /* === match === */

    //cmfToFloat(
    //cConvert::toFloat(
    public static function toFloat($value) {
        if (is_array($value)) {
            return array_map('self::toFloat', $value);
        } else {

            $value = preg_replace('#\s#mS', '', $value);
            $value = str_replace(array(',', '-'), array('.', '.'), $value);
            return str_replace(',', '.', (float) $value);
        }
    }

    //cmfToArrayInt(
    //cConvert::toInt(
    public static function toInt($value) {
        if (is_array($value)) {
            return array_map('self::toInt', $value);
        } else {
            return (int) $value;
        }
    }

    //cString::objectToArray(
    //cConvert::objectToArray(
    static public function objectToArray($value) {
        if (is_object($value)) {
            $value = array_map('self::objectToArray', (array) $value);
        }
        return $value;
    }

    /* === /match === */



    /* === array === */

    //cString::arrayToPath(
    //cSConvert::arrayToPath(
    static public function arrayToPath($arg) {
        return empty($arg) ? '' : '[' . implode('][', $arg) . ']';
    }

    //cString::pathToArray(
    //cSConvert::pathToArray(
    static public function pathToArray($str) {
        if (empty($str)) {
            return array();
        } else {
            $str = $str ? explode('][', substr($str, 1, -1)) : array();
            return array_combine($str, $str);
        }
    }

    //cString::unserialize(
    //cSConvert::unserialize(
    static public function unserialize($arg) {
        return empty($arg) ? '' : unserialize($arg);
    }

    //cString::serialize(
    //cSConvert::serialize(
    static public function serialize($arg) {
        return empty($arg) ? '' : serialize($arg);
    }

    //cmfFormtaArray(
    //cConvert::formtArray(
    static public function arrayView($d, $sep = ' ', $br = "\n") {
        $str = '';
        $max = 0;
        foreach ($d as $k => $v) {
            $len = cString::strlen($k);
            if ($len > $max)
                $max = $len;
        }
        $max += 5;

        foreach ($d as $k => $v) {
            $len = $max - cString::strlen($k);
            $str .= $br . $k . ': ';
            for ($i = 0; $i < $len; $i++)
                $str .= $sep;
            $str .= $v;
        }
        return $str;
    }

    static public function arrayToUri($n) {
        $uri = '';
        foreach ($n as $k => $v) {
            if (!is_null($v))
                $uri .= '&' . ($k) . '=' . ($v);
        }
        return $uri;
    }

    /* === /array === */



    /* === translate === */

    //cString::translate(
    //cSConvert::translate(
    static public function translate($str) {
        static $t = array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'gh', 'з' => 'z', 'и' => 'i',
    'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f',
    'х' => 'x', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'th', 'ъ' => '', 'ь' => '', 'ы' => 'y', 'э' => 'eh', 'ю' => 'ju', 'я' => 'ja');

        $new = '';
        $str = cString::strtolower(trim($str));
        $str = preg_replace('~\s~S', '_', $str);
        for ($i = 0, $c = cString::strlen($str); $i < $c; $i++) {
            $s = cString::substr($str, $i, 1);
            if (isset($t[$s]))
                $new .= $t[$s];
            else if ((ord($s) > 126) or (ord($s) == 20))
                $new .= '_';
            else
                $new .= $s;
        }
        return $new;
    }

    /* === /translate === */



    /* === file === */

    //cString::toFileName(
    //cSConvert::toFileName(
    static public function toFileName($str) {
        return preg_replace('([^a-z0-9\-\=\+\.])', '_', self::translate($str));
    }

    /* === /file === */
}

?>