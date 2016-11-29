<?php


class catalog_section_config_modul extends driver_modul_edit_param_of_record {


	protected function init() {
		parent::init();

		$this->setDb('catalog_section_config_db');

		// формы
		$form = $this->form()->get();
		$form->add('productLimit',	new cmfFormSelectInt());
		$form->add('productList',	new cFormText());
		$form->add('productPage',	new cmfFormSelectInt());
		$form->add('novelty',	    new cmfFormSelectInt());
	}

    public function loadForm() {
        $form = $this->form()->get();

        $list = array();
        for($id=9; $id<=36; $id+=3) {
            $list[] = $id;
            $form->addElement('productLimit', $id, $id);
        }
        $form->select('productList', implode('-', $list));

        for($id=5; $id<=15; $id++) {
            $form->addElement('productPage', $id, $id);
        }

        for($id=1; $id<=30; $id++) {
            $form->addElement('novelty', $id, $id);
        }
	}

}

?>