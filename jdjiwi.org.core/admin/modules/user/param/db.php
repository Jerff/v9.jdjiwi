<?php


class user_param_db extends driver_db_edit_param_of_record {

    function __construct() {
		parent::constructOld();
	}

	protected function getTable() {
		return db_user_data;
	}

	public function updateData($list, $send) {
	}

}

?>