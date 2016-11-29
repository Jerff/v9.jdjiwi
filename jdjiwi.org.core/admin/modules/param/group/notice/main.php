<?php



$list = $this->load('list', 'param_group_notice_controller');
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

$edit = $this->load('edit', 'param_group_notice_section_controller');
$this->processing();


list($name, $filter, $param) = $list->parentParam();
$this->assing('name', $name);
$this->assing('filter', $filter);
$this->assing('param', $param);






?>
