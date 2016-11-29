<?php

class cCompileUpdate {

    public static function start() {
        $_cahce = cCompile::config()->fileList();
        cConfig::ignoreUserAbort();
        cConfig::timeLimit();

        foreach (cCompile::config()->fileList() as $name) {
            if (is_file($name)) {
                file_put_contents(
                        cCompilePath . $name, cmfCompile::php()->compile($name)
                );
            }
        }

        cConfig::ignoreUserAbort(false);
    }

}

?>
