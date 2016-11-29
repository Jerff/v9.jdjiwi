<?php

cLoader::library('user/cmfUserInfo');


$user = cRegister::getUser();
$user->filterIsUser();
$user->reset();


if ($userInfo = cmfCache::get('user.info')) {
    list($userInfo, $content) = $userInfo;
} else {

    $userInfo = new cmfUserInfo();
    $content = cRegister::sql()->placeholder("SELECT content  FROM ?t WHERE name='Личный кабинет: Личные данные'", db_content_static)
            ->fetchRow(0);

    cmfCache::set('user.info', array($userInfo, $content), 'user');
}
cmfMenu::add('Личные кабинет', cUrl::get('/user/'));
cmfMenu::add('Данные');

$userInfo->loadData();
$this->assing('userRegister', $userInfo);
$this->assing('form', $userInfo->form()->get(1));
$this->assing('formAll', $userInfo->form()->get(2));

$this->assing('content', $content);
?>