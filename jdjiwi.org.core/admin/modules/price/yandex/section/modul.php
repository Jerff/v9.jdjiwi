<?php


class price_yandex_section_modul extends driver_modul_edit_product_action {

	function __construct() {
		$this->setIdName('main');
		parent::__construct();
	}

	protected function init() {
		parent::init();

		$this->setDb('price_yandex_section_db');
	}

	public function loadForm() {
		// формы
		$form = $this->form()->get();
		foreach($this->getDb()->getAction() as $key=>$value) {
			$form->add($key, new cmfFormCheckbox(array('label'=>$value['name'])));
			$form->select($key, 'yes');
		}
	}

	protected function selectForm($data) {
		$form = $this->form()->get();
		foreach($this->getDb()->getActionKey() as $action)
			if(isset($data[$action])) $form->select($action, 'no');
			else  $form->select($action, 'yes');
	}

}

?>