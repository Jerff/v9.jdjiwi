<?php


$command = 'Неправильная команда';
$name = 'Личный кабинет';
cInput::param()->start(cPages::param()->get(1));
switch(cInput::param()->get('command')) {
	case 'recoverPassword':
		switch(cInput::param()->get('action')) {
			case 'error':
                $command = 'Неправильная почта или код подверждения';
				break;

			case 'ok':
				$command = 'Пароль сменен';
				break;

			default:
				cLoader::library('user/cmfUserRecoverPassword');
				$cmfUserRecoverPassword = new cmfUserRecoverPassword();
				$cmfUserRecoverPassword->run1ok(cInput::param()->get('email'), cInput::param()->get('cod'));
		}
		break;

	case 'userRegister':
		switch(cInput::param()->get('action')) {
			case 'error':
                $command = 'Неправильный аккурант или код подверждения';
				break;

			case 'ok':
				$command = 'Активация прошла успешна';
				break;

			default:
				cLoader::library('user/cmfUserRegister');
				$cmfUserRegister = new cmfUserRegister();
				$cmfUserRegister->userActivate(cInput::param()->get('user'), cInput::param()->get('cod'));
		}
		break;

}

cmfSeo::set('command', $name);

$this->assing('name', $name);
$this->assing('content', $command);


?>