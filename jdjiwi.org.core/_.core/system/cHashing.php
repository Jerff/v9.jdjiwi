<?php

class cHashing {

    //cmfCrc32
    //cMatch::crc32
    //cHashing::crc32
    static public function crc32($n) {
        return crc32($n) & 0xffffffff;
    }

    static public function sha1($n) {
        return sha1($n);
    }

    static public function hash($n) {
        return self::sha1(cSalt . $n) . self::crc32(cSalt . $n);
    }

}

?>