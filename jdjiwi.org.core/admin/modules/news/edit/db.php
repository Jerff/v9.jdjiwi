<?php

class news_edit_db extends driver_db_edit {

    protected function init() {
        $this->setModel('model_news');
        $this->setTable(db_news);
    }

}

?>