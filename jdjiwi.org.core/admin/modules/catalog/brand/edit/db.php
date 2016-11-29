<?php


class catalog_brand_edit_db extends driver_db_edit {

    public function updateController() {
		return 'model_catalog_brand';
	}

	protected function getTable() {
		return db_brand;
	}

	public function updateData($list, $send) {
	}

}

?>