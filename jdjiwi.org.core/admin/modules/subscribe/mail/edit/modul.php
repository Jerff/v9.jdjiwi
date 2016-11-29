<?php


class subscribe_mail_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('subscribe_mail_edit_db');

		// формы
		$form = $this->form()->get();
		$form->add('type',		new cmfFormSelect());
		$form->add('email',		new cFormText(array('max'=>255, '!empty', 'email')));
		$form->add('subscribe',	new cmfFormCheckbox());
	}

    public function loadForm() {
        parent::loadForm();

        $form = $this->form()->get();
        $name = model_subscribe::typeList();
        $form->addElement('type', '', 'Все');
        foreach($name as $k=>$v) {
            $form->addElement('type', $k, $v['name']);
        }
        $form->select('type', $this->getFilter('type'));
	}

	protected function updateIsErrorData($data, &$isError) {
		if(!empty($data['email'])) {
			$res = $this->sql()->placeholder("SELECT 1 FROM ?t WHERE `id`!=? AND `type`=? AND `email`=?", db_subscribe_mail, $this->id()->get(), $data['type'], $data['email']);
			if($res->numRows()) {
				$isError = true;
				$this->form()->get()->setError('email', 'Email уже существует в базе');
			}
		}
		return $isError;
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(!$this->id()->get()) {
			$send['created'] = date('Y-m-d H:i:s');
			$send['visible'] = 'yes';
		}
	}

}

?>