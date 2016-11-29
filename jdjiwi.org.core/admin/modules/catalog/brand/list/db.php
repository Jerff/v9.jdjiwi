<?php


class catalog_brand_list_db extends driver_db_list {

	public function returnParent() {
		return 'catalog_brand_edit_db';
	}

	protected function getTable() {
		return db_brand;
	}

	protected function getSort() {
		return array('name');
	}

}

?>