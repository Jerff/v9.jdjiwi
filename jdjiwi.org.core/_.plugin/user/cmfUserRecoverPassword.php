<?php

cLoader::library('ajax/cControllerAjax');
cLoader::library('user/model/cmfUserModel');
class cmfUserRecoverPassword extends cControllerAjax {

	function __construct() {
		$name = 'recoverPassword';
		$formUrl = cAjaxUrl .'/user/recoverPassword/?';
		$func = 'cmfAjaxSendForm';

		parent::__construct($formUrl, $name, $func);
	}


	protected function init() {
        $form = $this->form()->get();

		$form->add('login',		    new cFormText(array('!empty', 'email', 'specialchars')));
		$form->add('captcha',		new cmfFormKcaptcha());
	}


	public function run() {
		$data = $this->processing();
		$this->form()->get()->get('captcha')->free();
		$email = $data['login'];

		if($row = cmfUserModel::getLogin($email)) {
			$data = array();
			$data['registerCommand'] = 'changePassword';
			$data['registerCod'] = $cod = sha1(rand(1, time()) . cSalt);

			if(cmfUserModel::save($data, $row['id'])) {
				$data['name'] = $row['name'];
				$data['login'] = $email;
				$data['userRecoverPasswordUrl'] = cUrl::get('/user/command/', "recoverPassword/email/". urlencode($email) ."/cod/$cod");

				$cmfMail = new cmfMail();
				$cmfMail->sendTemplates('Личный кабинет: Запрос на восстановление пароля', $data, $email);

				$response = cAjax::get();
	            $idForm = $this->getIdForm();
				$idHash = $this->getIdHash();
				$js = "
				$('#{$idForm}FormDiv').html('<b>Запрос на восстановление пароля отправлен</b>');
				document.location.hash = '$idHash';";

				$response->script($js);
				die();

			} else {
				$this->runEnd('Запрос не отправлен');
			}
		} else {
			cAjax::get()->script($this->form()->get()->get('captcha')->jsReloadImage());
			$this->form()->get()->setError('login', 'Пользователь не существует');
			$this->runEnd(true);
		}
	}


	public function run1ok($email, $cod) {
		if(!$email or !$cod) {
			$this->runExit('error');
		}
		$user = new cmfUser();
		if($row = cmfUserModel::changePassword($email, $cod)) {
			$cmfMail = new cmfMail();
			$cmfMail->sendTemplates('Личный кабинет: Восстановление пароля', $row, $email);
			$this->runExit('ok');
		} else {
			$this->runExit('error');
		}
	}


	protected function runExit($command) {
		$url = cUrl::get('/user/command/', "recoverPassword/action/{$command}");
		cRedirect($url);
	}

}

?>