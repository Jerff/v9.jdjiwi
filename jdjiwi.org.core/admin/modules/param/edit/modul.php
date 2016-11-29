<?php


class param_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('param_edit_db');

		// формы
		$form = $this->form()->get();

		$form->add('name',	    new cFormText(array('max'=>50, '!empty')));
		$form->add('header',	new cmfFormTextarea(array('max'=>50, '1!empty')));
		$form->add('style',	    new cFormText(array('max'=>25)));
		$form->add('prefix',	new cFormText(array('max'=>25)));
		$form->add('notice',	new cmfFormTextareaWysiwyng('param', $this->id()->get()));

		$form->add('type',	new cmfFormSelect(array('!empty')));
		$form->add('sort',	new cmfFormSelect());
		$form->add('visible',	new cmfFormCheckbox());
	}

	protected function updateIsErrorData($data, &$isError) {
		if(!empty($data['name'])) {
			$is = cRegister::sql()->placeholder("SELECT 1 FROM ?t WHERE `name`=? AND id!=?", db_param, $data['name'], $this->id()->get())
										->numRows();
			if($is) {
				$this->form()->get()->setError('name', 'Параметр "'. $data['name'] .'" уже существует!');
				$isError = true;
			}
		}
	}

	public function loadForm() {

		$form = $this->form()->get();
		$form->addElement('type', '', 'Не выбрано');
		$form->addElement('type', 'text', 'text');
		$form->addElement('type', 'textarea', 'textarea');
		$form->addElement('type', 'select', 'select');
		$form->addElement('type', 'radio', 'radio');
		$form->addElement('type', 'checkbox', 'checkbox');
		$form->addElement('type', 'basket', 'Параметр для корзины (checkbox)');

		$form->addElement('sort', '', 'Обычная сортировка');
		$form->addElement('sort', 'size', 'Сортировка размеров');
	}

}

?>