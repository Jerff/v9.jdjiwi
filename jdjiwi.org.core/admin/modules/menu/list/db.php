<?php


class menu_list_db extends driver_db_list {

	protected function getTable() {
		return db_menu;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('menu');
	}
}

?>