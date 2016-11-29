<?php

$command = 'Неправильная команда';
$name = 'Личный кабинет';


cInput::param()->start(cPages::param()->get(1));
switch(cInput::param()->get('action')) {
	case 'recoverPassword':

		$name = 'Восстановление пароля';
		switch(cInput::param()->get('command')) {
			case 1:
                $command = 'Пароль сменен';
				break;

			case 2:
                $command = 'Неправильная почта или код подверждения';
				break;
		}
		break;

	case 'userRegister':

		$name = 'Регистрация пользователя';
		switch(cInput::param()->get('command')) {
			case 1:
                $command = 'Неправильная код подверждения';
				break;

			case 2:
                $command = 'Пользователь уже зарегистрирован';
				break;

			case 3:
                $command = 'Регистрация завершена';
				break;
		}
		break;

}

cSeo::set('command', $name);

$this->assing('name', $name);
$this->assing('content', $command);

?>
