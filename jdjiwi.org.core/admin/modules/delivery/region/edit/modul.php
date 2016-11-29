<?php


class delivery_region_edit_modul extends driver_modul_edit_tree {

	protected function init() {
		parent::init();

		$this->setDb('delivery_region_edit_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();

		$form->add('parent',		new cmfFormSelectInt());
		$form->add('name',	        new cmfFormTextarea(array('!empty', 'max'=>150)));
		$form->add('visible',	    new cmfFormCheckbox());
	}

	protected function saveStart(&$send) {
		if(isset($send['pos']) and (empty($send['pos']) or $send['pos']>99)) {
				$send['pos'] = 99;
		}
		parent::saveStart($send);
	}

}

?>