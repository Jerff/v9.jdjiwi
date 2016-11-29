<?php

class cInputGet {

    private $get;
    private $bak;

    function __construct() {
        $this->get = &$_GET;
        $this->bak = (array)$_GET;
    }

    public function initBackup() {
        $this->bak = (array)$this->get;
    }

    public function reset() {
        $this->get = (array)$this->bak;
    }

    public function is($n) {
        return isset($this->get[$n]);
    }

    public function get($n, $d = null) {
        return get($this->get, $n, $d);
    }

    public function all() {
        return $this->get;
    }

    public function set($n, $v) {
        $this->get[$n] = $v;
    }

    public function delete($n) {
        unset($this->get[$n]);
    }

}

?>
