<?php




if(!cRegister::getUser()->is()) {
	exit;
}
cLoader::library('user/cmfUserChangePassword');
$userEnter = new cmfUserChangePassword();
$userEnter->run1();



?>