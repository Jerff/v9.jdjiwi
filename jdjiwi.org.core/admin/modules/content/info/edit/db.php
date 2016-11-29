<?php


class content_info_edit_db extends driver_db_edit_tree {

    public function updateController() {
		return 'model_content_info';
	}

	protected function getTable() {
		return db_content_info;
	}


	public function updateData($list, $send) {
	}

}

?>