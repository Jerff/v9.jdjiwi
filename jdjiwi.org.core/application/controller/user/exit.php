<?php


cLoader::library('basket/cmfBasket');
$user = cRegister::getUser();
$user->filterIsUser();
$user->logOut();
cRedirect(cUrl::get('/user/enter/'));

?>