<?php

class news_list_db extends driver_db_list {

    protected function init() {
        $this->setModel('model_news');
        $this->setTable(db_news);
    }

    protected function getSort() {
        return array('isMain' => 'ASC', 'date' => 'DESC');
    }

    protected function getFields() {
        return array('id', 'date', 'header', 'uri', 'notice', 'isMain', 'visible');
    }

    public function loadData(&$row) {
        $row['date'] = date("d.m.Y H:i", strtotime($row['date']));
        $row['notice'] = cString::subContent($row['notice'], 0, 100);
        parent::loadData($row);
    }

    public function updateData($list, $send) {
        if (isset($send['isMain'])) {
            $this->command()->reloadView();
        }
    }

}

?>