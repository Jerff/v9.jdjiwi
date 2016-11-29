<?php


$command = 'Неправильная команда';
$name = 'Рассылка';
cInput::param()->start(cPages::param()->get(1));
switch(cInput::param()->get('command')) {
	case 'subscribeYes':
		switch(cInput::param()->get('action')) {
			case 'error':
                $command = 'Неправильная почта или код подверждения';
				break;

			case 'ok':
				$command = 'Подписка завершена';
				break;

			default:
				cLoader::library('subscribe/cSubscribeYes');
				$cSubscribeYes = new cSubscribeYes();
				$cSubscribeYes->run1ok(cInput::param()->get('email'), cInput::param()->get('cod'));
		}
		break;

	case 'subscribeNo':
		switch(cInput::param()->get('action')) {
			case 'error':
                $command = 'Неправильная почта или код подверждения';
				break;

			case 'ok':
				$command = 'Отписка завершена';
				break;

			default:
				cLoader::library('subscribe/cSubscribeNo');
				$cSubscribeNo = new cSubscribeNo();
				$cSubscribeNo->run1ok(cInput::param()->get('email'), cInput::param()->get('cod'));
		}
		break;

}

cSeo::set('command', $name);

$this->assing('name', $name);
$this->assing('content', $command);


?>