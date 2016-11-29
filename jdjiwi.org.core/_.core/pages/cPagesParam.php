<?php

class cPagesParam {

    private $mParam = null;

    public function set(&$p) {
        $this->mParam = $p;
    }

    public function get($id) {
        return isset($this->mParam[$id - 1]) ? $this->mParam[$id - 1] : null;
    }

    public function all() {
        return $this->mParam;
    }

    public function count() {
        return count($this->mParam);
    }

}

?>
