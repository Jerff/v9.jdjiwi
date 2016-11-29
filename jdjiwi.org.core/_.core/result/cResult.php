<?php

class cResult {

    const noData = '<i>отсутсвуют даные</i>';

    private $mData = array();

    function __construct($v = null) {
        $this->mData = $v;
    }

    public function __get($n) {
        return get($this->mData, $n, self::noData);
    }

    public function __set($n, $v) {
        $this->mData[$n] = $v;
    }

    public function get($n) {
        return get($this->mData, $n);
    }

    public function int($n) {
        return (int) get($this->mData, $n);
    }

    public function html($n) {
        return cString::specialchars($this->$n);
    }

    public function is($n) {
        return isset($this->mData[$n]);
    }

    public function isNoEmpty($n) {
        return !empty($this->mData[$n]);
    }

}

?>