<?php


class photo_list_config_controller extends driver_controller_edit_param_of_record {


	protected function init() {
		parent::init();
		$this->initModul('main',	'photo_list_config_modul');

		// url
		$this->url()->setSubmit(cPages::getMain());
	}

}

class photo_list_config_modul extends driver_modul_edit_param_of_record {


    protected function init() {
        parent::init();

        $this->setDb('photo_list_config_db');

        // формы
        $form = $this->form()->get();
        $form->add('photoMain',        new cmfFormSelectInt());
//        $form->add('photoSection',    new cmfFormSelectInt());
        $form->add('photoLimit',        new cmfFormSelectInt());
        $form->add('photoPage',        new cmfFormSelectInt());
    }

    public function loadForm() {
        $form = $this->form()->get();

         foreach(array(5, 10, 15, 20) as $id) {
            $form->addElement('photoLimit', $id, $id);
        }

        foreach(array(4, 8, 12) as $id) {
            $form->addElement('photoMain', $id, $id);
        }

//        for($id=2; $id<=15; $id++) {
//            $form->addElement('photoSection', $id, $id);
//        }

        for($id=3; $id<=15; $id++) {
            $form->addElement('photoPage', $id, $id);
        }
	}

}


class photo_list_config_db extends driver_db_edit_param_of_record {

}

?>