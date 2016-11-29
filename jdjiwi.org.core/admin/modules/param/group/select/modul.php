<?php


class param_group_select_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('param_group_select_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('param',		new cmfFormSelect(array('max'=>20)));
		$form->add('visible',	new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->form()->get();
		foreach(model_param::initParamSelect() as $k=>$v) {
            $form->addElement('param', $k, $v);
		}
	}

	protected function saveStart(&$send) {
		if(!$this->id()->get()) {
			 $send['group'] = cAdmin::menu()->sub()->getId();
		}
		parent::saveStart($send);
	}

}

?>