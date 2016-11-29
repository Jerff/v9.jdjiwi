<?php


class basket_status_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('basket_status_edit_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();

		$form->add('name',		new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('stop',		new cmfFormRadioInt());
		$form->add('pay',		new cmfFormSelectCheckbox());
		$form->add('changePay', new cmfFormCheckbox());
		$form->add('color',		new cmfFormRadio(array('!empty')));
		$form->add('isTime',	new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->form()->get();
        $form->addElement('pay', 'no', 'не оплачен');
        $form->addElement('pay', 'yes', 'оплачен');

  		$form->addElement('stop', '0', 'Заказ отменен');
		$form->addElement('stop', '1', 'Заказ выполняется');
		$form->addElement('stop', '2', 'Заказ закончен');


		$form->addElement('color', '#FF2D2D', 'Красный');
		$form->addElement('color', '#000000', 'Черный');
		$form->addElement('color', '#36C413', 'Зеленый');
	}

}

?>