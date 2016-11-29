<?php


class _administrator_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_administrator_edit_db');

		// формы
		$form = $this->form()->get();
		$form->add('admin',		new cmfFormSelectCheckbox());

		$form->add('name',		new cmfFormTextarea(array('!empty', 'min'=>2, 'max'=>40, 'specialchars')));
		$form->add('login',		new cmfFormTextarea(array('!empty', 'specialchars')));
		$form->add('password',	new cmfFormPassword(array('confirmName'=>'userPasssword')));
		$form->add('password2',	new cmfFormPassword(array('confirmName'=>'userPasssword')));
		$form->add('email',		new cFormText(array('!empty', 'email')));
		$form->add('isIp',		new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
		parent::loadForm();

		$name = cLoader::initModul('_administrator_group_list_db')->getNameList();
		$form = $this->form()->get();
		foreach($name as $k=>$v) {
            $form->addElement('admin', $k, $v['name']);
		}
		$form->select('admin', $this->getFilter('admin'));
	}

	protected function updateIsErrorData($data, &$isError) {
		if(!empty($data['login'])) {
			if(!cmfAdminModel::isNew($data['login'], $this->id()->get())) {
				$isError = true;
				$this->form()->get()->setError('login', 'Такой пользователь уже существует');
			}
		}
		return $isError;
	}

}

?>