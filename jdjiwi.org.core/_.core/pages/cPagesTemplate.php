<?php

class cPagesTemplate {

    private $mTemplates = null;

    public function set($t) {
        $this->mTemplates = $t;
    }

    public function get($id) {
        return isset($this->mTemplates[$id]) ? $this->mTemplates[$id] : null;
    }

}

?>
