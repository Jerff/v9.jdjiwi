<?php


if(cAdmin::menu()->sub()->getId()) {
    cAdmin::menu()->sub()->type('product');
    if(!cLoader::initModul('product_edit_db')->getDataRecord(cAdmin::menu()->sub()->getId())) {
        $this->command()->noRecord();
    }
    $this->assing('urlView', cLoader::initModul('product_edit_controller')->siteUrl()->item(cAdmin::menu()->sub()->getId()));
} else {
    $this->command()->noRecord();
}


$edit = $this->load('edit', 'product_param_controller');
$edit->id()->set(cAdmin::menu()->sub()->getId());
$this->processing();









?>