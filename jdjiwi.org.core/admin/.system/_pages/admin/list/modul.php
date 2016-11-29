<?php


class _pages_admin_list_modul extends driver_modul_list_one {

	protected function init() {
		parent::init();

		$this->setDb('_pages_admin_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
        $form = $this->form()->get();

		$type = cmfGlobal::get('pageType');
		if($type==='list') {
			$form->add('sub_menu',	new cmfFormCheckbox());
		}
	}

}

?>