<?php


class payment_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('payment_edit_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();

		$form->add('name',		new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('commission',	new cmfFormTextInt(array('max'=>50)));
		$form->add('notice',	new cmfFormTextarea(array('max'=>10000)));
		$form->add('modul',		new cmfFormSelect());
		$form->add('image',		new cmfFormFile(array('path'=>path_Payment)));
		$form->add('visible',	new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->form()->get();
        foreach(cmfPaymentConfig::getList() as $k=>$v) {
            $form->addElement('modul', $k, $v);
        }
	}

}

?>