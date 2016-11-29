<?php

class cInputPost {

    private $post;

    function __construct() {
        $this->post = &$_POST;
    }

    public function is($n) {
        return isset($this->post[$n]);
    }

    public function get($n, $d = null) {
        return get($this->post, $n, $d);
    }

    public function all() {
        return $this->post;
    }

    public function set($n, $v) {
        $this->post[$n] = $v;
    }

    public function delete($n) {
        unset($this->post[$n]);
    }

}

?>
