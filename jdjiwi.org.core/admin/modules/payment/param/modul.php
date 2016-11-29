<?php


class payment_param_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('payment_param_db');

		// формы
		$form = $this->form()->get();
	}

	public function loadForm() {
		parent::loadForm();

		$modul = cLoader::initModul('payment_edit_db')->getFeildRecord('modul', $this->id()->get());
		cGlobal::set('modul', $modul);

		$form = $this->form()->get();
        switch($modul) {
            case 'Sberbank':
                $form->add('name',		new cFormText(array('max'=>255, '!empty')));
                $form->add('fax',		new cFormText(array('max'=>255, '!empty')));
                $form->add('email',		new cFormText(array('max'=>255, '!empty')));
                $form->add('notice',	new cmfFormTextarea(array('max'=>255, '!empty')));
                $form->add('currentAccount', new cFormText(array('max'=>255, '!empty')));
                $form->add('bank',		new cFormText(array('max'=>255, '!empty')));
                $form->add('bik',		new cFormText(array('max'=>255, '!empty')));
                $form->add('ks',		new cFormText(array('max'=>255, '!empty')));
                $form->add('inn',		new cFormText(array('max'=>255, '!empty')));
                break;

            case 'robokassa':
                break;
        }
	}

}

?>