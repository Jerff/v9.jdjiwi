<?php


cRegister::getUser()->filterNoUser();
if(isset($_GET['fancybox'])) {
    return '/user/register/fancybox/';
}


cLoader::library('user/cmfUserRegister');
$userRegister = new cmfUserRegister();
$this->assing('userRegister',	$userRegister);
$this->assing('form',			$userRegister->form()->get(1));
$this->assing('formAll',		$userRegister->form()->get(2));


$content = cRegister::sql()->placeholder("SELECT content  FROM ?t WHERE name='Личный кабинет: Регистрация'", db_content_static)
							->fetchRow(0);
$this->assing('content', $content);

?>