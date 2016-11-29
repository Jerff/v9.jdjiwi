<?php


class user_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('user_edit_db');

		// формы
		$form = $this->form()->get();
		$form->add('name',		new cFormText(array('!empty', 'min'=>2, 'max'=>40, 'specialchars')));
		$form->add('family',	new cFormText(array('1!empty', 'min'=>2, 'max'=>40, 'specialchars')));
		$form->add('login',		new cFormText(array('!empty', 'specialchars')));
		$form->add('password',	new cmfFormPassword(array('confirmName'=>'userPasssword')));
		$form->add('password2',	new cmfFormPassword(array('confirmName'=>'userPasssword')));

		$form->add('isIp',		new cmfFormCheckbox());
		$form->add('visible',	new cmfFormCheckbox());
	}


	protected function updateIsErrorData($data, &$isError) {
		if(!empty($data['login'])) {
			if(!cmfUserModel::isNew($data['login'], $this->id()->get())) {
				$isError = true;
				$this->form()->get()->setError('login', 'Такой пользователь уже существует');
			}
		}
		return $isError;
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(!empty($send['login'])) {
			 $send['email'] = $send['login'];
		}
	}

}

?>