<?php


$user = cRegister::getUser();
$user->filterIsUser();
$user->reset();

cLoader::library('user/cmfUserSubscribe');
if($subscribe = cmfCache::get('subscribe')) {
    list($subscribe, $content) = $subscribe;
} else {

    $subscribe = new cmfUserSubscribe();
    $content = cRegister::sql()->placeholder("SELECT content  FROM ?t WHERE name='Личный кабинет: рассылка'", db_content_static)
    									->fetchRow(0);

	cmfCache::set('subscribe', array($subscribe, $content), 'user');
}

cmfMenu::add('Личный кабинет', cUrl::get('/user/'));
cmfMenu::add('Рассылка');

$subscribe->loadData();
$this->assing('subscribe', $subscribe);
$this->assing('form', $subscribe->form()->get());

$this->assing('content', $content);

?>