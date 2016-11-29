<?php


$edit = $this->load('edit', 'payment_param_controller');
if (cAdmin::menu()->sub()->getId()) {
    $edit->id()->set(cAdmin::menu()->sub()->getId());
}
$this->processing();
if (!cLoader::initModul('payment_edit_db')->getDataRecord($edit->id()->get())) {

}
cAdmin::menu()->sub()->type('param');




$this->assing('modul', cGlobal::get('modul'));
?>