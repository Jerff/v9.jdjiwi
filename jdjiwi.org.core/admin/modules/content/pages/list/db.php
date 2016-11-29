<?php


class content_pages_list_db extends driver_db_list {

	public function returnParent() {
		return 'content_pages_edit_db';
	}

	protected function getSort() {
		return array('adress', 'type');
	}

	protected function getTable() {
		return db_content_pages;
	}

}

?>