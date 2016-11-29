<?php


class param_color_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_color;
	}


	public function updateData($list, $send) {
		cmfUpdateCache::update('color', $this->id()->get());
	}

}

?>