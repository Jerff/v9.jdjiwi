<?php


$edit = $this->load('edit', 'user_param_controller');
if (cAdmin::menu()->sub()->getId()) {
    $edit->id()->set(cAdmin::menu()->sub()->getId());
}
cmfUserModel::accesIs($edit->id()->get());
$this->processing();




$this->assing('userStat', $edit->getUserStat());
$this->assing('userName', cGlobal::get('$userName'));
$this->assing('indexUrl', cGlobal::get('$indexUrl'));

cAdmin::menu()->sub()->type('param');
?>