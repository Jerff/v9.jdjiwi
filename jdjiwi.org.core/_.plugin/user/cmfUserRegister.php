<?php

cLoader::library('contact/cmfContact');
cLoader::library('subscribe/cmfSubscribe');
cLoader::library('ajax/cControllerAjax');
cLoader::library('user/model/cmfUserModel');
class cmfUserRegister extends cControllerAjax {


	function __construct($name=null, $formUrl=null, $func=null) {
		switch($name ? $name : $name=cInput::get()->get('name')) {
			case 'fancyboxUserRegister':
				break;

			default:
				$name = 'userRegister';
				break;
		}
		if(!$formUrl)	$formUrl = cAjaxUrl .'/user/register/?name='. $name;
		if(!$func)		$func = 'cmfAjaxSendForm';

		parent::__construct($formUrl, $name, $func, 2);
	}


	protected function init() {
		$form = $this->form()->get(1);
    	$form->add('name',		        new cFormText(array('name'=>'Имя', '!empty', 'specialchars', 'max'=>250)));
		$form->add('family',		    new cFormText(array('name'=>'Фамилия', '!empty', 'specialchars', 'max'=>250)));
    	$form->add('login',		        new cFormText(array('name'=>'E-mail', '!empty', 'email', 'min'=>6, 'max'=>100)));
		$form->add('password',	        new cmfFormPasswordView(array('!empty', 'name'=>'Пароль', 'confirmName'=>'userPasssword')));
		$form->add('password2',	        new cmfFormPasswordView(array('!empty', 'confirmName'=>'userPasssword')));
		//$form->add('captcha',		    new cmfFormKcaptcha());


		$form = $this->form()->get(2);
        $form->add('cod',		    new cFormText(array('errorHide1', 'phoneCod', 'min'=>4, 'max'=>4)));
		$form->add('phone',		    new cFormText(array('errorHide1', 'name'=>'Телефон', 'phonePostPrefix', 'min'=>7, 'max'=>7)));
	}


	public function run1() {
		$isActivate = cSettings::get('user', 'isActivate');

		list($userData, $userValue) = $this->processing();
		//$this->getForm()->get('captcha')->free();

		$response = cAjax::get();
        if(!cmfUserModel::isNew($userData['login'])) {
            $this->form()->get()->setError('login', 'Такой пользователь уже существует');
            $this->runEnd(true);
        }

		if(!$userId = $this->register($userData, $userValue, $isActivate)) {
			$this->form()->get()->setError('login', 'Пользователь не добавлен');
            $this->runEnd(true);
		}

		if($isActivate) {
			$content = cRegister::sql()->placeholder("SELECT content FROM ?t WHERE name='Личный кабинет: Регистрация (с активацией)'", db_content_static)
												->fetchRow(0);
		} else {
            cRegister::getUser()->select($userData['login'], $userData['password']);
			$response->redirect(cUrl::get('/user/'));
		}

		$idHash = $this->getIdHash();
		$response->hash("userRegisterHash")
		         ->html($this->getIdForm(), $content);
	}

	public function register($user, $userData, $isActivate=false) {
		$login = $user['login'];
        $password = $user['password'];
        $user['email'] = $email = $user['login'];

		$userMail = array();
		$userMail['login'] = $user['login'];
		$userMail['password'] = $user['password'];
		$userMail['name'] = cmfUser::generateName($user);

		if($isActivate) {
			$user['registerCommand'] = 'register';
			$user['registerCod'] = $cod = sha1(rand(1, time()) . cSalt);

			$user['register'] = 'no';
			$user['visible'] = 'no';
		} else {

			$user['register'] = 'yes';
			$user['visible'] = 'yes';
            $user['registerCommand'] = '';
		}

		$id = cmfUserModel::save($user);
		if(!$id) return false;

		if($userData) {
			cmfUserModel::saveParam($userData, $id);
		}

		if(!empty($userData['cod'])) $userData['phone'] = $userData['cod'] .' '. $userData['phone'];
		$userMail['data'] = cConvert::arrayView(array_merge($this->form()->get(1)->processingName($user),
		                                               $this->form()->get(2)->processingName($userData)));
		$userMail['adminUrl'] = cBaseAdminUrl .'#/user/edit/&id='. $id;


		if($isActivate) {
			$userMail['activateUrl'] = cUrl::get('/user/command/', 'userRegister/user/'. $id .'/cod/'. $cod);

			$cmfMail = new cmfMail();
			$cmfMail->sendType('userNew', 'Личный кабинет: Регистрация: Письмо админу (с активацией)', $userMail);
			$cmfMail->sendTemplates('Личный кабинет: Регистрация (c активацией)', $userMail, $email);
		} else {
			$cmfMail = new cmfMail();
			$cmfMail->sendType('userNew', 'Личный кабинет: Регистрация: Письмо админу (без активации)', $userMail);
			$cmfMail->sendTemplates('Личный кабинет: Регистрация (без активации)', $userMail, $email);
		}
		return $id;
	}


	public static function userActivate($id, $cod) {
        if(!$id or !$cod) {
        	self::runExit('error');
        }

		$row = cRegister::sql()->placeholder("SELECT email, name, login, registerCommand, register FROM ?t WHERE id=? AND registerCod=?", db_user, $id, $cod)
										->fetchAssoc();
		if(!$row) {
            self::runExit('error');
		}
		if($row['registerCommand']!=='register' or $row['register']==='yes') {
			self::runExit('error');
		}
		cRegister::sql()->add(db_user, array('registerCommand'=>'', 'register'=>'yes', 'visible'=>'yes'), $id);

		$cmfMail = new cmfMail();
		$cmfMail->sendTemplates('Личный кабинет: Активация', $row, $row['email']);
		self::runExit('ok');
	}


	protected static function runExit($command) {
		$url = cUrl::get('/user/command/', 'userRegister/action/'. $command);
		cRedirect($url);
	}


}

?>