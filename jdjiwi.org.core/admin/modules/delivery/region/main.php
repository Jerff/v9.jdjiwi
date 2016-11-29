<?php



$edit = $this->load('edit', 'delivery_region_edit_controller');

if(cAdmin::menu()->sub()->getId()) {
	$edit->id()->set(cAdmin::menu()->sub()->getId());
}
if($parent = $edit->id()->get()) {
	$edit->setFilter('list', $parent);
	$edit->setFilter('parent', $parent);
}
if($new = $edit->getFilter('add')) {
	$edit->setFilter('add', null);
	$edit->setFilter('id', null);
}


if(!$new) {
	$list = $this->load('list', 'delivery_region_list_controller');
}
$this->processing();



if(!$new) {
	$this->assing('child', $list->getChild());
	
}


if($edit->id()->get()) {
	cAdmin::menu()->sub()->type('group');
	cAdmin::menu()->sub()->setId($edit->id()->get());
} elseif(!$new and cPages::isMain('/admin/delivery/country/edit/')) {
	$this->ajax()->redirect(cUrl::admin()->get('/admin/delivery/country/'));
}

$this->assing('id', $edit->id()->get());
$this->assing('isList', $edit->getFilter('isList'));
$this->assing('new', $new);





$this->assing('isEnd', $data->level==3);


?>
