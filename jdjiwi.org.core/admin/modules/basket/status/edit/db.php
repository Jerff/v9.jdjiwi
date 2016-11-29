<?php


class basket_status_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_basket_status;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('order');
	}

}

?>