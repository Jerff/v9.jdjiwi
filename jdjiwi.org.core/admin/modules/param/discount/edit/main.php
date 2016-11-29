<?php



$edit = $this->load('edit', 'param_discount_edit_controller');
if(cAdmin::menu()->sub()->getId()) {
	$edit->id()->set(cAdmin::menu()->sub()->getId());
}
$this->processing();









cAdmin::menu()->sub()->setId($edit->id()->get());

?>