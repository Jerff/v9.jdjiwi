<?php


class main_info_db extends driver_db_edit {

    public function updateController() {
		return 'model_main';
	}

	protected function getTable() {
		return db_main;
	}

	public function updateData($list, $send) {
	}

}

?>