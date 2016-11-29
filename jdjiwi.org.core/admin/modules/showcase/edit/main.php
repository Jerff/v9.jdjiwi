<?php



$edit = $this->load('edit', 'showcase_edit_controller');
$main_image = $this->load('showcase_edit_image_controller');
$this->processing();



$this->assing('main_image', $main_image);



$this->assing('showcaseForm', $form);
$this->assing('showcaseData', $data);


?>