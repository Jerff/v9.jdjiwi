<?php



$edit = $this->load('edit', '_cache_data_controller');

if(cAjax::is()) {
	if(cAjax::isCommand()) {
        $edit->runCommand();
	}
}
if($edit->getFilter('command')) {
	$edit->runCommand($edit->getFilter('command'));
	exit;
}
$this->processing();




?>