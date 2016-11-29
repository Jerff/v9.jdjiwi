<?php


class payment_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_payment;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('order');
	}

}

?>