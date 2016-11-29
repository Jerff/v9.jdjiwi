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



$edit = $this->load('edit', 'product_image_edit_controller');
$this->assing('id', $edit->id()->get());
$main_multi = $this->load('product_image_multi_controller');
$list = $this->load('list', 'product_image_list_controller');
$this->processing();


$this->assing('isMultiImage', cCommand::get('isMultiUplod'));
$this->assing('main_multi', $main_multi);










?>
