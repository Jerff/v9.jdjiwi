<?php


class user_config_modul extends driver_modul_edit_param_of_record {


    protected function init() {
        parent::init();

        $this->setDb('user_config_db');

		// формы
		$form = $this->form()->get();
		$form->add('isActivate',	new cmfFormRadio());
		$form->add('callback',	    new cmfFormTextarea());
		$form->add('basket',	    new cmfFormTextarea());
		$form->add('orderLimit',	new cmfFormSelectInt());
		$form->add('orderPage',	    new cmfFormSelectInt());
		$form->add('mainLimit',	    new cmfFormSelectInt());
	}

    public function loadForm() {
        $form = $this->form()->get();

        $form->addElement('isActivate', 1, 'Требуется активация акурантов');
		$form->addElement('isActivate', 0, 'Не требуется активация акурантов');

        for($id=5; $id<=25; $id++) {
            $form->addElement('orderLimit', $id, $id);
        }

        for($id=5; $id<=15; $id++) {
            $form->addElement('orderPage', $id, $id);
        }
        for($id=5; $id<=10; $id++) {
            $form->addElement('mainLimit', $id, $id);
        }
	}

}
class user_config_controller extends driver_controller_edit_param_of_record {


	protected function init() {
		parent::init();
		$this->initModul('main',	'user_config_modul');

		// url
		$this->url()->setSubmit(cPages::getMain());
	}

}
class user_config_db extends driver_db_edit_param_of_record {

}

?>