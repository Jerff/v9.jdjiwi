<?php

$infoUri = cGlobal::get('$infoUri');;
if(!$infoUri) return 404;
$info = cRegister::sql()->placeholder("SELECT id, parent, path, name, content, title, keywords, description FROM ?t WHERE isUri=? AND isVisible='yes'", db_content, $infoUri)
								->fetchAssoc();
if(!$info) return 404;
$infoId = $info['id'];
$this->assing('info', $info);


if($info['parent']) {
	$parent = cRegister::sql()->placeholder("SELECT id, name, isUri FROM ?t WHERE id=? AND visible='yes'", db_content, $info['parent'])
									->fetchAssoc();
	if(!$parent) return 404;
    cmfMenu::add($parent['name'], cUrl::get('/content/', $parent['isUri']));
    cmfMenu::add($info['name']);
}


cmfMenu::setSelect('$menuId', $infoId .'menu');
cmfSeo::set('title', $info['title']);
cmfSeo::set('keywords', $info['keywords']);
cmfSeo::set('description', $info['description']);

?>