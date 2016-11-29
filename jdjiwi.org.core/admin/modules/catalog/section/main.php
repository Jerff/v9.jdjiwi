<?php



$edit = $this->load('edit', 'catalog_section_edit_controller');
$config = $this->load('config', 'catalog_section_config_controller', 'catalog');

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
	$list = $this->load('list', 'catalog_section_list_controller');
}
$this->processing();



if(!$new) {
	$this->assing('child', $list->getChild());
	$this->assing('product', $list->getProduct());
	
}


if($edit->id()->get()) {
	cAdmin::menu()->sub()->type('group');
	cAdmin::menu()->sub()->setId($edit->id()->get());
} elseif(!$new and cPages::isMain('/admin/catalog/section/edit/')) {
	$this->ajax()->redirect(cUrl::admin()->get('/admin/catalog/section/'));
}

$this->assing('id', $edit->id()->get());
$this->assing('isList', $edit->getFilter('isList'));
$this->assing('new', $new);





$this->assing('isEnd', $data->level==3);

if(!$data->parent) {
	cAdmin::menu()->sub()->type('shop');
}





?>
