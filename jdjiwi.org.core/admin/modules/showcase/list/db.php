<?php


class showcase_list_db extends driver_db_list {

	public function returnParent() {
		return 'showcase_edit_db';
	}

	protected function getTable() {
		return db_showcase;
	}

}

?>