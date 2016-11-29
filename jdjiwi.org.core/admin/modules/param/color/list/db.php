<?php


class param_color_list_db extends driver_db_list {

	public function returnParent() {
		return 'param_color_edit_db';
	}

	protected function getTable() {
		return db_color;
	}

	protected function getSort() {
		return array('name');
	}

}

?>