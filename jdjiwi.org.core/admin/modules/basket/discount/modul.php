<?php


class basket_discount_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('basket_discount_db');

		// формы
		$form = $this->form()->get();
		$form->add('price',			new cmfFormTextInt(array('!empty')));
		$form->add('discount',		new cmfFormTextInt(array('!empty', 'max'=>99, 'max'=>30)));
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(isset($send['discount'])) {
			$send['discount'] = 1-$send['discount']/100;
		}
	}

}

?>