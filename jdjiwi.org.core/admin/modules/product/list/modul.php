<?php


class product_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('product_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('isOrder',	new cmfFormCheckbox());
		$form->add('count',	    new cmfFormTextInt());
		$form->add('visible',	new cmfFormCheckbox());
	}


    protected function saveStart(&$send) {
        if(isset($send['pos']) and (empty($send['pos']) or $send['pos']>9999)) {
				$send['pos'] = 9999;
		}
		parent::saveStart($send);
		$send['isUpdate'] = 'yes';
	}

}

?>