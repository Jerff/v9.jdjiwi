<?php

class cSystem {

    static public function microtime($t = null) {
        list($usec, $sec) = explode(" ", empty($t) ? microtime() : $t);
        return ((float) $usec + (float) $sec);
    }

    static public function isExtension($name) {
//        try {
            if (!extension_loaded($name)) {
                throw new cException('расширение не загружено', $name);
            }
//            return true;
//        } catch (cFileException $e) {
//            $e->errorLog();
//        }
    }

    static public function isGzip($file) {
        self::isExtension('gzopen');
    }

}

?>