<?php

$id = cPages::param()->get(1);
if(empty($id)) {
	$id = 'main';
}


$edit = $this->load('edit', 'main_info_controller', $id);
$this->processing();



$current = $edit->current();
list($form, $data) = $current->config;

$this->assing('configData', $data);


list($form, $data) = $current->main;



foreach(cGlobal::get('$isForm') as $k=>$v) {
	$this->assing($k, $v);
}

?>