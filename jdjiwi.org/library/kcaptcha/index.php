<?php

error_reporting (E_ALL);

include('kcaptcha.php');

if(empty($_GET['id'])) {
	exit;
}

session_start();
$captcha = new KCAPTCHA();
if(isset($_GET['id'])) {
	$_SESSION['kcaptcha'][sha1($_GET['id'])] = $captcha->getKeyString();
}
var_dump($_REQUEST, $_SESSION, session_name());


?>