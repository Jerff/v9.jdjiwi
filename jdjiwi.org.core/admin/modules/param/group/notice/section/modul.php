<?php


class param_group_notice_section_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('param_group_notice_section_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();

		$form->add('basket',	new cmfFormSelectInt());
	}

	public function loadForm() {
		$form = $this->form()->get();
		foreach(model_param::initParamBasketMenu() as $k=>$v) {
            $form->addElement('basket', $k, $v);
		}
	}

}

?>