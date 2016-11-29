<?php

$edit = $this->load('edit', '_administrator_edit_controller');
if ($edit->id()->get() and !cmfAdminModel::isAdmin($edit->id()->get())) {
    $this->command()->noRecord();
}
$this->processing();
?>