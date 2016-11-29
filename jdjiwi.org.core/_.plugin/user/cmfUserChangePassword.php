<?php

cLoader::library('ajax/cControllerAjaxSave');
cLoader::library('user/model/cmfUserModel');
class cmfUserChangePassword extends cControllerAjaxSave {

	function __construct($formUrl=null, $name=null, $func=null) {

		if(!$name)		$name = 'changePassword';
		if(!$formUrl)	$formUrl = cAjaxUrl .'/user/changePassword/?';
		if(!$func)		$func = 'cmfAjaxSendForm';

		parent::__construct($formUrl, $name, $func);
	}



	protected function init() {
		$form = $this->form()->get();
		$form->add('passwordMain',	new cmfFormPassword(array('name'=>'Пароль', '!empty', 'confirmName'=>'userPasssword1')));

		$form->add('password',		new cmfFormPassword(array('name'=>'Пароль', '!empty', 'confirmName'=>'userPasssword')));
		$form->add('password2',		new cmfFormPassword(array('!empty', 'confirmName'=>'userPasssword')));
	}

	public function run1() {
		$userData = $this->processing();

        $user = cRegister::getUser();
		$id = $user->getId();
		$num = cRegister::sql()->placeholder("SELECT 1 FROM ?t WHERE id=? AND password=?", db_user, $id, cmfAuth::hash($userData['passwordMain']))
										->numRows();
		if(!$num) {
			$this->form()->get()->setError('passwordMain', 'Неправильный пароль');
			$this->runEnd(true);
		}
		cmfUserModel::save(array('password'=>$userData['password']), $id);


		$email = array();
		$email['name'] = $user->name;
		$email['login'] = $user->login;
		$email['password'] = $userData['password'];

		$cmfMail = new cmfMail();
		$cmfMail->sendTemplates('Личный кабинет: Смена пароля пользователем', $email, $user->email);


		cAjax::get()->script($this->form()->get()->jsUpdate());

		$this->runSaveOk();
		die();
	}

}

?>