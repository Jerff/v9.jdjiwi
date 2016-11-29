<?php

$_group = cLoader::initModul('_administrator_group_list_db')->getNameList();


//$r = cRegister::request();
$group = cInput::get()->get('group');
if(!isset($_group[$group])) $group = key($_group);
$_group[$group]['sel'] = true;
cInput::get()->set('group', $group);

$this->assing('url', $url = cUrl::admin()->get('/admin/pages/access/') .'&'. cInput::param()->toUrl(cInput::get()->all()));
foreach($_group as $key=>$value) {
	$_group[$key]['url'] = $url .'&group='. $key;
}
$this->assing('_group', $_group);




$list = $this->load('list', '_pages_access_controller');
$this->processing();



?>