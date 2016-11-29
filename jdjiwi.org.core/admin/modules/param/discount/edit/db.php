<?php


class param_discount_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_param_discount;
	}


	public function updateData($list, $send) {
		cmfUpdateCache::update('discount', $this->id()->get());
	}

}

?>