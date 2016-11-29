<?php

if (cAdmin::menu()->sub()->getId()) {
    cAdmin::menu()->sub()->type('photo');
    if (!cLoader::initModul('photo_edit_db')->getDataRecord(cAdmin::menu()->sub()->getId())) {
        $this->command()->noRecord();
    }
    $this->assing('urlView', cLoader::initModul('photo_edit_controller')->siteUrl()->item(cAdmin::menu()->sub()->getId()));
} else {
    $this->command()->noRecord();
}



$edit = $this->load('edit', 'photo_image_edit_controller');
$this->assing('id', $edit->id()->get());
$main_multi = $this->load('photo_image_multi_controller');
$list = $this->load('list', 'photo_image_list_controller');
$this->processing();


$this->assing('isMultiImage', cCommand::get('isMultiUplod'));
$this->assing('main_multi', $main_multi);
?>
