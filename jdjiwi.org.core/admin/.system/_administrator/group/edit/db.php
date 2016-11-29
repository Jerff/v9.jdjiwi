<?php


class _administrator_group_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_user_group_admin;
	}

	public function updateData($list, $send) {
	}

}

?>