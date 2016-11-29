<?php


abstract class driver_modul_edit_product_action extends driver_modul_edit {

	protected $action = null;


	public function loadForm() {
		// формы
		$form = $this->form()->get();
		foreach($this->getDb()->getAction() as $key=>$value) {
			$form->add($key, new cmfFormCheckbox(array('label'=>$value['name'])));
			$form->select($key, 'no');
		}
	}


	protected function selectForm($data) {
		$form = $this->form()->get();
		foreach($this->getDb()->getActionKey() as $action)
			if(isset($data[$action])) $form->select($action, 'yes');
			else  $form->select($action, 'no');
	}


	public function deleteProduct($id) {
		$this->getDb()->deleteProduct($id);
	}

	public function deleteAction($id) {
		$this->getDb()->deleteAction($id);
	}

}

?>