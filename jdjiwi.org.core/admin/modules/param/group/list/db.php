<?php


class param_group_list_db extends driver_db_list {

	public function returnParent() {
		return 'param_group_edit_db';
	}

	protected function getTable() {
		return db_param_group;
	}


	protected function getSort() {
		return array('name');
	}

}

?>