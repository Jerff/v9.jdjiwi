<?php


class basket_discount_db extends driver_db_list {

	protected function getTable() {
		return db_discount;
	}

	protected function getSort() {
		return array('price');
	}

	public function loadData(&$row) {
		if(!empty($row['discount'])) {
    		$row['discount'] = 100 - 100 * $row['discount'];
		}
		parent::loadData($row);
	}

	public function updateData($list, $send) {
		cmfDiscount::update();
		cmfUpdateCache::update('discount');
	}

}

?>