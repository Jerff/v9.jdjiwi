<?php


class content_info_list_db extends driver_db_list_tree {

	public function returnParent() {
		return 'content_info_edit_db';
	}

	protected function getTable() {
		return db_content_info;
	}

	protected function getFields() {
		return array('id', 'parent', 'level', 'pos', 'name', 'isUri', 'visible');
	}

}

?>