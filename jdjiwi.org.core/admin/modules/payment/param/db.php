<?php


class payment_param_db extends driver_db_edit_param_of_record {

	protected function getTable() {
		return db_payment;
	}

	public function updateData($list, $send) {
	}

}

?>