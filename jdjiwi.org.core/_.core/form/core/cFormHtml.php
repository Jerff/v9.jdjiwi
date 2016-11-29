<?php

class cFormHtml extends cFormRegister {

    public function start() {
        $this->parent()->security()->viewStart();
        $this->parent()->error()->view();
    }

    public function end() {
        $this->parent()->security()->viewEnd();
    }

}

?>