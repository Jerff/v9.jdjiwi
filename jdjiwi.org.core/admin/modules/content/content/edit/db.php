<?php


class content_content_edit_db extends driver_db_edit_tree {

    public function updateController() {
		return 'model_content';
	}

	protected function getTable() {
		return db_content;
	}

}

?>