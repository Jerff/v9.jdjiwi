<?php



$edit = $this->load('edit', '_profil_edit_controller');
$edit->id()->set(cAdmin::user()->getId());
$this->processing();








?>