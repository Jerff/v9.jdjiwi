<?php


class user_group_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_user_group_main;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('user');
	}

}

?>