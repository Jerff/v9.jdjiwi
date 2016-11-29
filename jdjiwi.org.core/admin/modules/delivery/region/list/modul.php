<?php


class delivery_region_list_modul extends driver_modul_list_one {

	protected function init() {
		parent::init();

		$this->setDb('delivery_region_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

	protected function saveStart(&$send) {
		if(isset($send['pos']) and (empty($send['pos']) or $send['pos']>99)) {
				$send['pos'] = 99;
		}
		parent::saveStart($send);
	}

}

?>