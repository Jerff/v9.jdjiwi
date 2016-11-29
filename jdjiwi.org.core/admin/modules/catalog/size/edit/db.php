<?php


class catalog_size_edit_db extends driver_db_edit_tree {


	protected function getTable() {
		return db_size;
	}

	public function updateData($list, $send) {
	}

}

?>