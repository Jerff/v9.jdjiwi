<?php

class cFilter {

    //cString::isEmail(
    //cFilter::isEmail(
    static public function isEmail($str) {
        return filter_var($str, FILTER_VALIDATE_EMAIL);
    }

}

?>