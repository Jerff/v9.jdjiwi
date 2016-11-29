<?php


class param_group_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_param_group;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('param', $this->id()->get());
	}

}

?>