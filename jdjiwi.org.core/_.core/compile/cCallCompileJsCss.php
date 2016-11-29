<?php

class cCallCompileJsCss {

    static public function start() {
        cCompile::fileJsCss()->compile(cInput::get()->get('query'));
    }

}

?>
