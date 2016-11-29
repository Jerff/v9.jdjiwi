<?php



//$r = cRegister::request();


cLoader::library('user/cmfUserRecoverPassword');
$userEnter = new cmfUserRecoverPassword();
$userEnter->run();


?>