<?php

class cFormReform {

    // int
    static public function int($value, $arg, $isView) {
        if ($isView) {
            return number_format((int) $value, 0, '', ' ');
        } else {
            return (int) preg_replace('#([^0-9\-\,\.])#mS', '', $value);
        }
    }

    // float
    static public function float($value, $arg, $isView) {
        if ($isView or $arg != 1) {
            $arg = (string) ($arg == 1 ? $value : $arg);
            if ($count = strpos($arg, '.')) {
                $count = strlen(substr($arg, $count + 1));
            } else {
                $count = 0;
            }
        }

        if ($isView) {
            return number_format($value, $count, '.', '');
        } else {
            $value = preg_replace('#([^0-9\,\.])#mS', '', $value);
            $value = str_replace(array(',', '-'), '.', $value);
            if ($arg != 1) {
                return number_format($value, $count, '.', '');
            } else {
                return str_replace(',', '.', (float) $value);
            }
        }
    }

    // range
    static public function number($value, $arg, $isView) {
        $arg = (string) $arg;
        if ($count = strpos($arg, '.')) {
            $count = strlen(substr($arg, $count + 1));
        } else {
            $count = 0;
        }
        if ($isView) {
            return number_format($value, $count, '.', '');
        } else {
            $value = preg_replace('#([^0-9\,\.])#mS', '', $value);
            $value = str_replace(array(',', '-'), '.', $value);
            $value = number_format($value, $count, '.', '');
            return str_replace(',', '.', (float) $value);
        }
    }

    // specialchars
    static public function specialchars($value, $arg, $isView) {
        if ($isView) {
            return $value;
        } else {
            return cString::specialchars($value);
        }
    }

}

?>