<?php



$edit = $this->load('edit', 'subscribe_edit_controller');
if(cAdmin::menu()->sub()->getId()) {
	$edit->id()->set(cAdmin::menu()->sub()->getId());
}
$this->processing();









cAdmin::menu()->sub()->setId($edit->id()->get());
if($edit->id()->get()) {
    if($data->type=='user') cAdmin::menu()->sub()->type('history');
}

?>