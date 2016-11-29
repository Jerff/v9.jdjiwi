<?php

cLoader::library('ajax/cControllerAjaxSave');
cLoader::library('contact/cmfContact');
cLoader::library('subscribe/cmfSubscribe');
cLoader::library('user/model/cmfUserModel');
class cmfUserInfo extends cControllerAjaxSave {

	function __construct($formUrl=null, $name=null, $func=null) {

		if(!$name)		$name = 'userInfo';
		if(!$formUrl)	$formUrl = cAjaxUrl .'/user/info/?';
		if(!$func)		$func = 'cmfAjaxSendForm';

		parent::__construct($formUrl, $name, $func, 2);
	}


	public function get($n) {
		return parent::get($n);
	}


	protected function init() {
		$form = $this->form()->get();
    	$form->add('name',		new cFormText(array('name'=>'Имя', '!empty', 'specialchars', 'max'=>250)));
		$form->add('family',	new cFormText(array('name'=>'Фамилия', '!empty', 'specialchars', 'max'=>250)));
    	$form->add('login',		new cFormText(array('!empty', 'name'=>'E-mail', 'email', 'min'=>4, 'max'=>100)));

		$form = $this->form()->get(2);
		$form->add('cod',		new cFormText(array('errorHide1', 'phoneCod', 'min'=>4, 'max'=>4)));
		$form->add('phone',		new cFormText(array('errorHide1', 'name'=>'Телефон', 'phonePostPrefix', 'min'=>7, 'max'=>7)));
        $form->add('gorod',     new cFormText(array('name'=>'Город', 'max'=>100)));
        $form->add('index',     new cFormText(array('name'=>'Индекс', 'max'=>15)));
	}

	public function loadData() {
		$user = cRegister::getUser();

		$this->form()->get(1)->select($user->all);
		$this->form()->get(2)->select($user->all);
	}


	public function run1() {
		list($userData, $userValue) = $this->processing();

		$user = cRegister::getUser();
		if($userData) {
			if(isset($userData['login'])) {
				if(!cmfUserModel::isNew($userData['login'], $user->getId())) {
		            $this->form()->get()->setError('login', 'Такой пользователь уже существует');
		            $this->runEnd(true);
		        }
            	$userData['email'] = $userData['login'];
            }

			cmfUserModel::save($userData, $user->getId());
			cAjax::get()->script('cmf.user.view();');
		}

		if($userValue) {
			cmfUserModel::saveParam($userValue, $user->getId());
		}

		$user->reset();
		$this->loadData();

		if($userData or $userValue) {
			$this->runSaveOk();
		} else {
			$this->runEnd();
		}
	}

	protected function processing() {
        $r = cRegister::request();

		$isError = $isUpdate = false;
		$data = array();
		foreach($this->form()->all() as $form) {
			$form->setRequest($r);
			$send = $form->handler();
			$isUpdate |= count($send);
			$data[] = $send;
		    $isError |= $form->isError();
		}

		if(!$isError and $isUpdate) {
            return $this->form()->count()>1 ? $data : $data[0];
		}
		$this->runEnd(true);
	}

	protected function runEnd($error=false) {
		parent::runEnd($error);
	}

}

?>