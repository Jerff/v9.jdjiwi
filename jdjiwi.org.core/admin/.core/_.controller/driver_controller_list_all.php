<?php


abstract class driver_controller_list_all extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->access()->writeAdd('newLine');
	}

	protected function getLimit() {
		return 1000;
	}

	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>
