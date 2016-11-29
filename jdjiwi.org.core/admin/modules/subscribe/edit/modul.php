<?php


class subscribe_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('subscribe_edit_db');

		// формы
		$form = $this->form()->get();
		$form->add('type',		new cmfFormSelect());
		$form->add('dateStart',	new cmfFormTextDateTime());

		$form->add('name',		new cmfFormTextarea(array('max'=>255, '!empty')));

		$form->add('user',		new cmfFormSelect());
        $form->add('email',		new cmfFormTextarea());

		$form->add('header',	new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('content',	new cmfFormTextareaWysiwyng('subscribe', $this->id()->get(), array('max'=>10000)));
		$form->add('visible',	new cmfFormCheckbox());
	}

    public function loadForm() {
        parent::loadForm();

        $form = $this->form()->get();
        $form->addElement('type', 'all', 'Обычная рассылка');
        foreach(model_subscribe::typeList() as $k=>$v) {
            $form->addElement('type', $k, '| ----- | '. $v['name']);
        }
        $form->addElement('type', 'user', 'Произвольная рассылка');
        $form->select('type', $this->getFilter('type'));

        $name = cLoader::initModul('user_list_db')->getNameList(null, array('email'));
		$form->addElement('user', 0, 'Выберите');
		foreach($name as $k=>$v) {
            $form->addElement('user', $v['email'], $v['name'] .' ('. $v['email'] .')');
		}
	}

	protected function saveStart(&$send) {
        unset($send['user']);
		parent::saveStart($send);
		if(!$this->id()->get()) {
			$send['date'] = date('Y-m-d H:i:s');
			$send['status'] = 'неактивна';
		}
	}

}

?>