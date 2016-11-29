<?php


class price_import_list_db extends driver_db_list {

	public function returnParent() {
		return 'price_import_edit_db';
	}

	protected function getTable() {
		return db_shop_import;
	}

	protected function getSort() {
		return array('date'=>'DESC');
	}

	public function loadData(&$row) {
		$row['date'] = date("d.m.Y H:i", strtotime($row['date']));
		parent::loadData($row);
	}

	protected function getWhereFilter() {
		$filter = array();
		$filter['shop'] = (int)$this->getFilter('shop');
		return $filter;
	}

}

?>