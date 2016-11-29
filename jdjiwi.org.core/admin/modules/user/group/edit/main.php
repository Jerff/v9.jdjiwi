<?php



$edit = $this->load('edit', 'user_group_edit_controller');
if($edit->id()->isEmpty()) $this->command()->noRecord();
$this->processing();









?>