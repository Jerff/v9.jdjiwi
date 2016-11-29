<?php



$list = $this->load('list', 'param_group_select_controller');
if(!cAdmin::menu()->sub()->getId()) {
	$this->command()->noRecord();
}
$parent = cLoader::initModul('catalog_section_edit_db')->getFeildRecord('parent', cAdmin::menu()->sub()->getId());
if(is_null($parent)) {
	$this->command()->noRecord();
}
if(!$parent) {
	cAdmin::menu()->sub()->type('shop');
}
cAdmin::menu()->sub()->type('group');
$this->processing();



list($name, $param) = $list->parentParam();
$this->assing('name', $name);
$this->assing('param', $param);

?>
