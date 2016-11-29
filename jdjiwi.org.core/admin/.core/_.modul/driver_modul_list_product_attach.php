<?php


abstract class driver_modul_list_product_attach extends driver_modul_list {

	protected $attach = null;

	protected function init() {
		parent::init();

		// формы
		$form = $this->form()->get();
		$form->add('visible', new cmfFormCheckbox());
		$form->select('visible', 'no');
	}


	protected function selectForm($data) {
		if($data==='yes') $this->form()->get()->select('visible', $data);
		else $this->form()->get()->select('visible', 'no');
	}

	public function deleteProduct($id) {
		$this->getDb()->deleteProduct($id);
	}

	public function deleteAttach($id) {
		$this->getDb()->deleteAttach($id);
	}

}

?>