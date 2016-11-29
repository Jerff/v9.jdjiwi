<?php


class product_edit_db extends driver_db_edit {

    public function updateController() {
		return 'model_product';
	}

	protected function getTable() {
		return db_product;
	}

	protected function startSaveWhere() {
		return array('section');
	}

	public function loadData(&$data) {
	    $data['discount'] = (1-$data['discount'])*100;
	}

	protected function saveEnd($id, $send) {
		parent::saveEnd($id, $send);

		if(isset($send['section'])) {
			$this->command()->reloadView();
		}
	}

	public function getSection($id) {
		return $this->sql()->placeholder("SELECT p.section FROM ?t p WHERE ?w:p", db_product, $this->getWhereId($id))
								->fetchRow(0);
	}

}

?>