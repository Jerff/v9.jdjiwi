<?php


class delivery_region_edit_db extends driver_db_edit_tree {

    public function updateController() {
		return 'model_delivery_region';
	}

	protected function getTable() {
		return db_delivery_region;
	}

	protected function loadFormFilterParent() {
		return array('level<3');
	}

}

?>