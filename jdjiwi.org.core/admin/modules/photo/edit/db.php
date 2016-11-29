<?php


class photo_edit_db extends driver_db_edit {

    public function updateController() {
		return 'model_photo';
	}

	protected function getTable() {
		return db_photo;
	}

}

?>