<?php




$edit = $this->load('edit', 'price_yandex_config_controller', 'yandex.market');
$this->processing();






list($form, $data) = $edit->current()->section;
$this->assing('formSection', $form);
$this->assing('dataSection', $data);

?>