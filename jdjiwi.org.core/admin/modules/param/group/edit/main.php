<?php



$edit = $this->load('edit', 'param_group_edit_controller');
if(cAdmin::menu()->sub()->getId()) {
	$edit->id()->set(cAdmin::menu()->sub()->getId());
}
$this->processing();









if($edit->id()->get()) {
	cAdmin::menu()->sub()->type('group');
}
cAdmin::menu()->sub()->setId($edit->id()->get());

?>