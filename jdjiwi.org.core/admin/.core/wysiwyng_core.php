<?php

class wysiwyng_core extends admin_core {


    // путь к папке с файлами визуального редактора
    public function path() {
        return cWysiwyng::recordPath(get_class($this));
    }


}

?>