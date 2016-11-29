<?php


class _mail_var_db extends driver_db_list {

	protected function getTable() {
		return db_mail_var;
	}

	protected function getSort() {
		return array('name');
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('mail');
	}

}

?>