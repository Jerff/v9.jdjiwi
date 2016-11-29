<?php


class dump_log_db extends driver_db_list {

	protected function getTable() {
		return db_product_dump_log;
	}

	protected function getSort() {
		return array('date'=>'DESC');
	}

	public function loadData(&$row) {
		$row['date'] = date("d.m.Y H:i", strtotime($row['date']));
		parent::loadData($row);
	}

}

?>