<?php


class content_static_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_content_static;
	}

	public function updateData($list, $send) {
	}

}

?>