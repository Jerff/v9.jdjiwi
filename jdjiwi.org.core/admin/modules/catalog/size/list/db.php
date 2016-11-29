<?php


class catalog_size_list_db extends driver_db_list_tree {

	public function returnParent() {
		return 'catalog_size_edit_db';
	}

	protected function getTable() {
		return db_size;
	}

	protected function getFields() {
		return array('id', 'parent', 'level', 'pos', 'name', 'visible');
	}

}

?>