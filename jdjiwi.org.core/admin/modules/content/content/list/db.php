<?php


class content_content_list_db extends driver_db_list_tree {

	public function returnParent() {
		return 'content_content_edit_db';
	}

	protected function getTable() {
		return db_content;
	}

	protected function getFields() {
		return array('id', 'parent', 'level', 'pos', 'name', 'isUri', 'visible');
	}

}

?>