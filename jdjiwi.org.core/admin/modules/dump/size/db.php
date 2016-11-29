<?php


class dump_size_db extends driver_db_list {

	protected function getTable() {
		return db_product_dump;
	}

	protected function getSort() {
		return array('name');
	}

}

?>