<?php




if(!cRegister::getUser()->is()) {
	exit;
}
cLoader::library('user/cmfUserSubscribe');
$subscribe = new cmfUserSubscribe();
$subscribe->run1();

?>