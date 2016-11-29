<?php


class showcase_edit_db extends driver_db_edit {

    public function updateController() {
		return 'model_showcase';
	}

	protected function getTable() {
		return db_showcase;
	}

}

?>