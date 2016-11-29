<?php



$edit = $this->load('edit', '_pages_admin_edit_controller');
if($parent = $edit->id()->get()) {
	cInput::get()->set('list', $parent);
	cInput::get()->set('parent', $parent);
}
if($new = cInput::get()->is('add')) {
	cInput::get()->set('add', null);
	cInput::get()->set('id', null);
}


if(!$new) {
	$list = $this->load('list', '_pages_admin_list_controller');
}
$this->processing();


if(!$new) {
	$this->assing('count', $list->getCount());
	
}
$this->assing('path', $edit->path());
unset($page);


$this->assing('id', $edit->id()->get());
$this->assing('new', $new);
$this->assing('type', cGlobal::get('pageType'));








?>
