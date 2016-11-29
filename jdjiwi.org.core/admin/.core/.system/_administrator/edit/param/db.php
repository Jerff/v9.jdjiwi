<?php

class _administrator_edit_param_db extends driver_db_edit {

    protected function init() {
        parent::init();
        $this->setTable(db_user_data);
        $this->command()->ignore()->noRecord();
    }

    public function updateData($list, $send) {

    }

}

?>