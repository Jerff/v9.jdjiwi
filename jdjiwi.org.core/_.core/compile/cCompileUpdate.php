<?php

class cCompileUpdate {

    public static function start() {
        cConfig::ignoreUserAbort();
        cConfig::timeLimit();


        cCompile::php()->update();
        cCompile::fileJsCss()->update();
        cConfig::ignoreUserAbort(false);
    }

}

?>
