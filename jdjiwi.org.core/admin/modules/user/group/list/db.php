<?php


class user_group_list_db extends driver_db_list {

	public function returnParent() {
		return 'user_group_edit_db';
	}

	protected function getTable() {
		return db_user_group_main;
	}

}

?>