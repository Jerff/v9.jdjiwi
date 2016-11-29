<?php

class cInputFiles {

    private $files;

    function __construct() {
        $this->files = &$_FILES;
    }

    public function is($n) {
        return isset($this->files[$n]);
    }

    public function get($n) {
        return get($this->files, $n);
    }

    public function all() {
        return $this->files;
    }

}

?>
