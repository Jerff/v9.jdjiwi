<?php


class param_discount_list_db extends driver_db_list {

	public function returnParent() {
		return 'param_discount_edit_db';
	}

	protected function getTable() {
		return db_param_discount;
	}

	protected function getSort() {
		return array('name');
	}

}

?>