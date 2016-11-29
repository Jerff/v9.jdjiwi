<?php

$edit = $this->load('edit', 'user_edit_controller');
if (cAdmin::menu()->sub()->getId()) {
    $edit->id()->set(cAdmin::menu()->sub()->getId());
}
if ($edit->id()->get()) {
    cmfUserModel::accesIs($edit->id()->get());
}
$this->processing();


cAdmin::menu()->sub()->setId($edit->id()->get());
$this->assing('userStat', $edit->getUserStat());
if ($edit->id()->get()) {
    cAdmin::menu()->sub()->type('param');
}
?>