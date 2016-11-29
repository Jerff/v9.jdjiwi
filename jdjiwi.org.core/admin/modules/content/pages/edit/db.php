<?php

class content_pages_edit_db extends driver_db_edit {

    protected function init() {
        $this->setTable(db_content_pages);
    }

}

?>