<?php

class data_core {

    private $mData = array();

    public function is($k) {
        return isset($this->mData[$k]);
    }

    public function get($k, $d = null) {
        return get($this->mData, $k, $d);
    }

    public function set($k, $v) {
        $this->mData[$k] = $v;
    }

}

?>