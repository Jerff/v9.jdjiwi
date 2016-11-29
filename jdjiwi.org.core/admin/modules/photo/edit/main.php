<?php



$edit = $this->load('edit', 'photo_edit_controller');
if($edit->id()->isEmpty() and cAdmin::menu()->sub()->getId()) {
	$edit->id()->set(cAdmin::menu()->sub()->getId());
}
$this->processing();









if($edit->id()->get()) {
	cAdmin::menu()->sub()->setId($edit->id()->get());
	cAdmin::menu()->sub()->type('photo');
}

?>