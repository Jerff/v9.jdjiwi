<?php


class payment_list_db extends driver_db_list {

	public function returnParent() {
		return 'payment_edit_db';
	}

	protected function getTable() {
		return db_payment;
	}

	protected function getSort() {
		return array('pos');
	}

}

?>