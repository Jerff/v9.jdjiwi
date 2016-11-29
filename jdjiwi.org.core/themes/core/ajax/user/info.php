<?php




if(!cRegister::getUser()->is()) {
	exit;
}
cLoader::library('user/cmfUserInfo');
$userRegister = new cmfUserInfo();
$userRegister->run1();

?>