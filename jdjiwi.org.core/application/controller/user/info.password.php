<?php


$user = cRegister::getUser();
$user->filterIsUser();
$user->reset();

cLoader::library('user/cmfUserChangePassword');
if($changePassword = cmfCache::get('register')) {
    list($changePassword, $content) = $changePassword;
} else {

    $changePassword = new cmfUserChangePassword();
    $content = cRegister::sql()->placeholder("SELECT content  FROM ?t WHERE name='Личный кабинет: смена пароля'", db_content_static)
    									->fetchRow(0);

	cmfCache::set('register', array($changePassword, $content), 'user');
}

cmfMenu::add('Личный кабинет', cUrl::get('/user/'));
cmfMenu::add('Изменить пароль');

$this->assing('password', $changePassword);
$this->assing('form', $changePassword->form()->get());

$this->assing('content', $content);

?>