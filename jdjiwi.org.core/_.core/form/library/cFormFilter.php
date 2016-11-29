<?php

class cFormFilter {
    /* === проверка значений === */

    // значение не пусто
    static public function isNoEmpty($v, $error) {
        if (!empty($v) or $v === '0') {
            return null;
        }
        return $error->isNotEmpty;
    }

    // значение пусто
    static public function isEmpty($v, $error) {
        if (empty($v) and $v !== '0') {
            return null;
        }
        return '&nbsp;';
    }

    // зачение почтовый адрес
    static public function isEmail($v, $error) {
        if (filter_var($v, FILTER_VALIDATE_EMAIL)) {
            return null;
        }
        return $error->isEmail;
    }

    // зачение почтовый адрес
    static public function isUrl($v, $error) {
        if (filter_var($v, FILTER_VALIDATE_URL)) {
            return null;
        }
        return $error->isUrl;
    }

    // зачение число
    static public function isNumber($v, $error) {
        if (preg_match('~^[0-9\-\+e\.]$~S', $v)) {
            return null;
        }
        return $error->isEmail;
    }

    /* === /проверка значений === */



    /* === строки === */

    // строковый диапозон
    static public function numberRange($v, $error, $min, $max) {
        $v = (float) $v;
        if ($v < $min or $v > $max) {
            return $error->numberRange()->replace(array('%min%', '%max%'), array($min, $max));
        }
        return null;
    }

    // строка короче
    static public function numberMin($v, $error, $min) {
        $v = (float) $v;
        if ($v < $min) {
            return $error->numberMin()->replace('%min%', $min);
        }
        return null;
    }

    // строка длиннее
    static public function numberMax($v, $error, $max) {
        $v = (float) $v;
        if ($v > $max) {
            return $error->numberMax()->replace('%max%', $max);
        }
        return null;
    }

    /* === /строки === */



    /* === строки === */

    //  числовой диапозон
    static public function strRange($v, $error, $min, $max) {
        $v = cString::strlen($v);
        if ($v < $min or $v > $max) {
            return $error->strRange()->replace(array('%min%', '%max%'), array($min, $max));
        }
        return null;
    }

// строка короче
    static public function strMin($v, $error, $min) {
        $v = cString::strlen($v);
        if ($v < $min) {
            return $error->strMin()->replace('%min%', $min);
        }
        return null;
    }

    // строка длиннее
    static public function strMax($v, $error, $max) {
        $v = cString::strlen($v);
        if ($v > $max) {
            return $error->strMax()->replace('%max%', $max);
        }
        return null;
    }

    /* === /строки === */



    /* === пароли === */

    static public function confirmName($v, $error, $item) {
        static $mConfirm = array();
        if (isset($mConfirm[$item])) {
            if ($mConfirm[$item] !== $v) {
                return $error->confirmName;
            }
        } else {
            $mConfirm[$item] = $v;
        }
    }

    /* === /пароли === */




    // правильность
//    static public function strRange($v, $error, $min, $max) {
//        $v = cString::strlen($v);
//        pre('strRange', $v);
//        if ($v < $min or $v > $max) {
//            return $error->numberRange()->replace(array('%min%', '%max%'), array($min, $max));
//        } elseif ($v < $min) {
//            return $error->numberMin()->replace('%min%', $min);
//        } elseif ($v > $max) {
//            return $error->numberMax()->replace('%max%', $max);
//        }
//        return null;
//    }
}

?>