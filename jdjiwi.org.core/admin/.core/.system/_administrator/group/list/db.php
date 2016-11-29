<?php


class _administrator_group_list_db extends driver_db_list {

	public function returnParent() {
		return '_administrator_group_edit_db';
	}

	protected function getTable() {
		return db_user_group_admin;
	}

}

?>