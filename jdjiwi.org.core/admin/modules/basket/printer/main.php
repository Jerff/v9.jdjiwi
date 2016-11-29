<?php


$edit = $this->load('edit', 'basket_edit_controller');
if($edit->id()->isEmpty()) $this->command()->noRecord();
$this->processing();


$this->assing('listUser', $edit->listUser());
$this->assing('listStatus', $edit->listStatus());







?>