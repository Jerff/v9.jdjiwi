<?php

cRegister::getUser()->filterNoUser();

cLoader::library('user/cUserEnter');
$this->assing2('userEnter', new cUserEnter());
$this->assing2('passwordUrl', cUrl::get('/user/password/'));
?>